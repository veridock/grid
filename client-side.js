    // Client-side code for browser
    if (typeof window !== 'undefined' && window.document) {
      // PWA Registration
      if ('serviceWorker' in navigator) {
        const swCode = `
          const CACHE_NAME = 'file-monitor-v1';
          
          self.addEventListener('install', (e) => {
            e.waitUntil(
              caches.open(CACHE_NAME).then((cache) => {
                return cache.addAll(['/']);
              })
            );
          });
          
          self.addEventListener('fetch', (e) => {
            e.respondWith(
              caches.match(e.request).then((response) => {
                return response || fetch(e.request);
              })
            );
          });
        `;
        
        const blob = new Blob([swCode], { type: 'application/javascript' });
        const swUrl = URL.createObjectURL(blob);
        navigator.serviceWorker.register(swUrl).catch(console.error);
      }
      
      // App State
      let currentView = 'grid';
      let currentFolder = null;
      let files = [];
      let monitoredFolders = [];
      let searchQuery = '';
      
      // Elements
      const folderList = document.getElementById('folder-list');
      const fileContainer = document.getElementById('file-container');
      const gridView = document.getElementById('grid-view');
      const listView = document.getElementById('list-view');
      const listItems = document.getElementById('list-items');
      const breadcrumb = document.getElementById('breadcrumb');
      const statusText = document.getElementById('status-text');
      const fileCount = document.getElementById('file-count');
      const loadingOverlay = document.getElementById('loading-overlay');
      const dropZone = document.getElementById('drop-zone');
      
      // Initialize
      async function init() {
        await loadMonitoredFolders();
        setupEventListeners();
        updateStatus('Ready to monitor files');
        
        // Auto-refresh every 10 seconds
        setInterval(() => {
          if (currentFolder) {
            loadFiles(false); // Silent refresh
          }
        }, 10000);
      }
      
      // Load monitored folders
      async function loadMonitoredFolders() {
        try {
          const response = await fetch('/api/folders');
          const data = await response.json();
          monitoredFolders = data.folders || [];
          renderFolderList();
        } catch (error) {
          console.error('Error loading folders:', error);
          updateStatus('Failed to load folders', true);
        }
      }
      
      // Render folder list
      function renderFolderList() {
        folderList.innerHTML = '';
        
        if (monitoredFolders.length === 0) {
          const emptyMsg = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          emptyMsg.setAttribute('x', '115');
          emptyMsg.setAttribute('y', '100');
          emptyMsg.setAttribute('text-anchor', 'middle');
          emptyMsg.setAttribute('font-family', 'system-ui');
          emptyMsg.setAttribute('font-size', '13');
          emptyMsg.setAttribute('fill', '#94a3b8');
          emptyMsg.textContent = 'No folders monitored';
          folderList.appendChild(emptyMsg);
          return;
        }
        
        monitoredFolders.forEach((folder, index) => {
          const folderGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
          folderGroup.setAttribute('transform', `translate(0, ${index * 60})`);
          
          const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
          rect.setAttribute('class', currentFolder === folder ? 'folder-item folder-item-active' : 'folder-item');
          rect.setAttribute('width', '230');
          rect.setAttribute('height', '50');
          rect.setAttribute('rx', '6');
          
          const folderIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          folderIcon.setAttribute('x', '15');
          folderIcon.setAttribute('y', '30');
          folderIcon.setAttribute('font-size', '20');
          folderIcon.textContent = '📁';
          
          const folderName = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          folderName.setAttribute('x', '45');
          folderName.setAttribute('y', '20');
          folderName.setAttribute('font-family', 'system-ui');
          folderName.setAttribute('font-size', '13');
          folderName.setAttribute('font-weight', '500');
          folderName.setAttribute('fill', '#1e293b');
          
          const parts = folder.split(/[\\/]/);
          const name = parts[parts.length - 1] || folder;
          folderName.textContent = name.length > 18 ? name.substring(0, 18) + '...' : name;
          
          // Full path (smaller text)
          const fullPath = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          fullPath.setAttribute('x', '45');
          fullPath.setAttribute('y', '35');
          fullPath.setAttribute('font-family', 'monospace');
          fullPath.setAttribute('font-size', '10');
          fullPath.setAttribute('fill', '#94a3b8');
          fullPath.textContent = folder.length > 25 ? '...' + folder.substring(folder.length - 22) : folder;
          
          const removeBtn = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          removeBtn.setAttribute('x', '210');
          removeBtn.setAttribute('y', '30');
          removeBtn.setAttribute('font-size', '20');
          removeBtn.setAttribute('fill', '#ef4444');
          removeBtn.setAttribute('cursor', 'pointer');
          removeBtn.textContent = '×';
          
          // Event listeners
          rect.addEventListener('click', () => selectFolder(folder));
          folderIcon.addEventListener('click', () => selectFolder(folder));
          folderName.addEventListener('click', () => selectFolder(folder));
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            removeFolder(folder);
          });
          
          folderGroup.appendChild(rect);
          folderGroup.appendChild(folderIcon);
          folderGroup.appendChild(folderName);
          folderGroup.appendChild(fullPath);
          folderGroup.appendChild(removeBtn);
          
          folderList.appendChild(folderGroup);
        });
      }
      
      // Select folder
      async function selectFolder(folder) {
        currentFolder = folder;
        breadcrumb.textContent = folder;
        renderFolderList();
        await loadFiles();
      }
      
      // Load files from selected folder
      async function loadFiles(showLoader = true) {
        if (!currentFolder) return;
        
        if (showLoader) showLoading(true);
        
        try {
          const response = await fetch(`/api/files?folder=${encodeURIComponent(currentFolder)}`);
          const data = await response.json();
          files = data.files || [];
          
          // Apply search filter
          if (searchQuery) {
            files = files.filter(file => 
              file.name.toLowerCase().includes(searchQuery.toLowerCase())
            );
          }
          
          renderFiles();
          fileCount.textContent = `${files.length} files`;
          document.getElementById('status-indicator').setAttribute('fill', '#10b981');
        } catch (error) {
          console.error('Error loading files:', error);
          updateStatus('Failed to load files', true);
          document.getElementById('status-indicator').setAttribute('fill', '#ef4444');
        } finally {
          if (showLoader) showLoading(false);
        }
      }
      
      // Render files based on current view
      function renderFiles() {
        if (currentView === 'grid') {
          renderGridView();
        } else {
          renderListView();
        }
      }
      
      // Render grid view
      function renderGridView() {
        gridView.innerHTML = '';
        
        if (files.length === 0) {
          const emptyMsg = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          emptyMsg.setAttribute('x', '550');
          emptyMsg.setAttribute('y', '300');
          emptyMsg.setAttribute('text-anchor', 'middle');
          emptyMsg.setAttribute('font-family', 'system-ui');
          emptyMsg.setAttribute('font-size', '16');
          emptyMsg.setAttribute('fill', '#94a3b8');
          emptyMsg.textContent = searchQuery ? 'No files match your search' : 'No supported files in this folder';
          gridView.appendChild(emptyMsg);
          return;
        }
        
        files.forEach((file, index) => {
          const col = index % 5;
          const row = Math.floor(index / 5);
          const x = col * 220;
          const y = row * 250;
          
          const fileGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
          fileGroup.setAttribute('transform', `translate(${x}, ${y})`);
          
          // Card background
          const card = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
          card.setAttribute('class', 'file-item ' + file.type + '-file');
          card.setAttribute('width', '200');
          card.setAttribute('height', '230');
          card.setAttribute('rx', '8');
          card.setAttribute('filter', 'url(#shadow)');
          
          // Thumbnail container
          const thumbContainer = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
          thumbContainer.setAttribute('x', '10');
          thumbContainer.setAttribute('y', '10');
          thumbContainer.setAttribute('width', '180');
          thumbContainer.setAttribute('height', '160');
          thumbContainer.setAttribute('rx', '4');
          thumbContainer.setAttribute('fill', '#f3f4f6');
          
          // Thumbnail or placeholder
          if (['png', 'jpg', 'jpeg', 'svg', 'pdf'].includes(file.type)) {
            const thumbnail = document.createElementNS('http://www.w3.org/2000/svg', 'image');
            thumbnail.setAttribute('x', '10');
            thumbnail.setAttribute('y', '10');
            thumbnail.setAttribute('width', '180');
            thumbnail.setAttribute('height', '160');
            thumbnail.setAttribute('href', `/api/thumbnail?file=${encodeURIComponent(file.path)}`);
            thumbnail.setAttribute('preserveAspectRatio', 'xMidYMid meet');
            fileGroup.appendChild(thumbnail);
          } else {
            // File type placeholder
            const placeholder = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            placeholder.setAttribute('x', '100');
            placeholder.setAttribute('y', '90');
            placeholder.setAttribute('text-anchor', 'middle');
            placeholder.setAttribute('font-family', 'system-ui');
            placeholder.setAttribute('font-size', '48');
            placeholder.setAttribute('font-weight', 'bold');
            placeholder.setAttribute('class', 'placeholder-icon');
            placeholder.textContent = file.type.toUpperCase();
            fileGroup.appendChild(placeholder);
          }
          
          // File type icon
          const typeIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          typeIcon.setAttribute('x', '20');
          typeIcon.setAttribute('y', '195');
          typeIcon.setAttribute('font-size', '24');
          typeIcon.textContent = getFileIcon(file.type);
          
          // File name
          const fileName = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          fileName.setAttribute('x', '50');
          fileName.setAttribute('y', '190');
          fileName.setAttribute('font-family', 'system-ui');
          fileName.setAttribute('font-size', '13');
          fileName.setAttribute('fill', '#1e293b');
          fileName.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
          
          // File size
          const fileSize = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          fileSize.setAttribute('x', '50');
          fileSize.setAttribute('y', '210');
          fileSize.setAttribute('font-family', 'system-ui');
          fileSize.setAttribute('font-size', '11');
          fileSize.setAttribute('fill', '#64748b');
          fileSize.textContent = formatFileSize(file.size);
          
          // Actions for PDF
          if (file.type === 'pdf') {
            const convertBtn = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            convertBtn.setAttribute('transform', 'translate(160, 190)');
            convertBtn.setAttribute('cursor', 'pointer');
            
            const btnBg = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            btnBg.setAttribute('cx', '0');
            btnBg.setAttribute('cy', '0');
            btnBg.setAttribute('r', '15');
            btnBg.setAttribute('fill', '#3b82f6');
            
            const btnText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            btnText.setAttribute('x', '0');
            btnText.setAttribute('y', '0');
            btnText.setAttribute('text-anchor', 'middle');
            btnText.setAttribute('dominant-baseline', 'middle');
            btnText.setAttribute('fill', 'white');
            btnText.setAttribute('font-size', '10');
            btnText.setAttribute('font-weight', 'bold');
            btnText.textContent = 'SVG';
            
            convertBtn.appendChild(btnBg);
            convertBtn.appendChild(btnText);
            
            convertBtn.addEventListener('click', (e) => {
              e.stopPropagation();
              convertPdfFile(file.path);
            });
            
            fileGroup.appendChild(convertBtn);
          }
          
          // Event listeners
          card.addEventListener('click', () => openFile(file));
          thumbContainer.addEventListener('click', () => openFile(file));
          
          fileGroup.appendChild(card);
          fileGroup.appendChild(thumbContainer);
          fileGroup.appendChild(typeIcon);
          fileGroup.appendChild(fileName);
          fileGroup.appendChild(fileSize);
          
          gridView.appendChild(fileGroup);
        });
      }
      
      // Render list view
      function renderListView() {
        listItems.innerHTML = '';
        
        if (files.length === 0) {
          const emptyMsg = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          emptyMsg.setAttribute('x', '550');
          emptyMsg.setAttribute('y', '100');
          emptyMsg.setAttribute('text-anchor', 'middle');
          emptyMsg.setAttribute('font-family', 'system-ui');
          emptyMsg.setAttribute('font-size', '16');
          emptyMsg.setAttribute('fill', '#94a3b8');
          emptyMsg.textContent = searchQuery ? 'No files match your search' : 'No supported files in this folder';
          listItems.appendChild(emptyMsg);
          return;
        }
        
        files.forEach((file, index) => {
          const y = index * 50;
          
          const itemGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
          itemGroup.setAttribute('transform', `translate(0, ${y})`);
          itemGroup.setAttribute('class', 'list-item');
          
          // Row background
          const rowBg = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
          rowBg.setAttribute('width', '1100');
          rowBg.setAttribute('height', '50');
          rowBg.setAttribute('fill', index % 2 === 0 ? 'white' : '#f9fafb');
          rowBg.setAttribute('cursor', 'pointer');
          
          // File icon
          const icon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          icon.setAttribute('x', '20');
          icon.setAttribute('y', '30');
          icon.setAttribute('font-size', '20');
          icon.textContent = getFileIcon(file.type);
          
          // File name
          const name = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          name.setAttribute('x', '50');
          name.setAttribute('y', '30');
          name.setAttribute('font-family', 'system-ui');
          name.setAttribute('font-size', '14');
          name.setAttribute('fill', '#1e293b');
          name.textContent = file.name.length > 40 ? file.name.substring(0, 37) + '...' : file.name;
          
          // File type
          const type = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          type.setAttribute('x', '400');
          type.setAttribute('y', '30');
          type.setAttribute('font-family', 'system-ui');
          type.setAttribute('font-size', '13');
          type.setAttribute('fill', '#64748b');
          type.textContent = file.type.toUpperCase();
          
          // File size
          const size = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          size.setAttribute('x', '550');
          size.setAttribute('y', '30');
          size.setAttribute('font-family', 'system-ui');
          size.setAttribute('font-size', '13');
          size.setAttribute('fill', '#64748b');
          size.textContent = formatFileSize(file.size);
          
          // Modified date
          const modified = document.createElementNS('http://www.w3.org/2000/svg', 'text');
          modified.setAttribute('x', '700');
          modified.setAttribute('y', '30');
          modified.setAttribute('font-family', 'system-ui');
          modified.setAttribute('font-size', '13');
          modified.setAttribute('fill', '#64748b');
          try {
            const date = new Date(file.modified);
            modified.textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
          } catch {
            modified.textContent = 'Unknown';
          }
          
          // Actions
          const actions = document.createElementNS('http://www.w3.org/2000/svg', 'g');
          actions.setAttribute('transform', 'translate(900, 15)');
          
          // View button
          const viewBtn = createButton('View', 0, () => openFile(file));
          actions.appendChild(viewBtn);
          
          // Convert button (for PDFs)
          if (file.type === 'pdf') {
            const convertBtn = createButton('Convert', 60, () => convertPdfFile(file.path));
            actions.appendChild(convertBtn);
          }
          
          // Event listener
          rowBg.addEventListener('click', () => openFile(file));
          
          itemGroup.appendChild(rowBg);
          itemGroup.appendChild(icon);
          itemGroup.appendChild(name);
          itemGroup.appendChild(type);
          itemGroup.appendChild(size);
          itemGroup.appendChild(modified);
          itemGroup.appendChild(actions);
          
          listItems.appendChild(itemGroup);
        });
      }
      
      // Helper function to create buttons
      function createButton(text, x, onClick) {
        const btnGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        btnGroup.setAttribute('transform', `translate(${x}, 0)`);
        btnGroup.setAttribute('cursor', 'pointer');
        
        const btnBg = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        btnBg.setAttribute('width', '50');
        btnBg.setAttribute('height', '20');
        btnBg.setAttribute('rx', '4');
        btnBg.setAttribute('fill', '#3b82f6');
        
        const btnText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        btnText.setAttribute('x', '25');
        btnText.setAttribute('y', '14');
        btnText.setAttribute('text-anchor', 'middle');
        btnText.setAttribute('fill', 'white');
        btnText.setAttribute('font-family', 'system-ui');
        btnText.setAttribute('font-size', '11');
        btnText.textContent = text;
        
        btnGroup.appendChild(btnBg);
        btnGroup.appendChild(btnText);
        
        btnGroup.addEventListener('click', (e) => {
          e.stopPropagation();
          onClick();
        });
        
        return btnGroup;
      }
      
      // Get file icon based on type
      function getFileIcon(type) {
        const icons = {
          pdf: '📄',
          svg: '🎨',
          png: '🖼️',
          jpg: '🖼️',
          jpeg: '🖼️'
        };
        return icons[type] || '📄';
      }
      
      // Format file size
      function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
      }
      
      // Open file
      function openFile(file) {
        window.open(`/files/${encodeURIComponent(file.path)}`, '_blank');
      }
      
      // Convert PDF to SVG
      async function convertPdfFile(filePath) {
        showLoading(true);
        updateStatus('Converting PDF to SVG...');
        
        try {
          const response = await fetch('/api/convert-pdf', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ filePath })
          });
          
          const result = await response.json();
          
          if (result.success) {
            updateStatus('PDF converted successfully');
            await loadFiles();
          } else {
            updateStatus(result.error || 'Failed to convert PDF', true);
          }
        } catch (error) {
          console.error('Error converting PDF:', error);
          updateStatus('Error converting PDF', true);
        } finally {
          showLoading(false);
        }
      }
      
      // Convert all PDFs in current folder
      async function convertAllPdfs() {
        const pdfFiles = files.filter(f => f.type === 'pdf');
        
        if (pdfFiles.length === 0) {
          updateStatus('No PDF files to convert');
          return;
        }
        
        if (!confirm(`Convert ${pdfFiles.length} PDF files to SVG?`)) {
          return;
        }
        
        showLoading(true);
        updateStatus(`Converting ${pdfFiles.length} PDFs...`);
        
        let converted = 0;
        let failed = 0;
        
        for (const file of pdfFiles) {
          try {
            const response = await fetch('/api/convert-pdf', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ filePath: file.path })
            });
            
            const result = await response.json();
            if (result.success) {
              converted++;
            } else {
              failed++;
            }
            
            updateStatus(`Progress: ${converted + failed}/${pdfFiles.length} (${converted} converted, ${failed} failed)`);
          } catch (error) {
            console.error(`Error converting ${file.name}:`, error);
            failed++;
          }
        }
        
        updateStatus(`Conversion complete: ${converted} successful, ${failed} failed`);
        showLoading(false);
        await loadFiles();
      }
      
      // Add new folder
      async function addFolder() {
        const folderPath = prompt('Enter folder path to monitor:');
        if (!folderPath || !folderPath.trim()) return;
        
        try {
          const response = await fetch('/api/folders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ path: folderPath.trim() })
          });
          
          const result = await response.json();
          
          if (response.ok && result.success) {
            updateStatus('Folder added successfully');
            await loadMonitoredFolders();
          } else {
            updateStatus(result.error || 'Failed to add folder', true);
          }
        } catch (error) {
          console.error('Error adding folder:', error);
          updateStatus('Error adding folder', true);
        }
      }
      
      // Remove folder
      async function removeFolder(folderPath) {
        if (!confirm(`Stop monitoring "${folderPath}"?`)) return;
        
        try {
          const response = await fetch('/api/remove-folder', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ path: folderPath })
          });
          
          if (response.ok) {
            if (currentFolder === folderPath) {
              currentFolder = null;
              files = [];
              renderFiles();
              breadcrumb.textContent = 'Select a folder to monitor';
            }
            updateStatus('Folder removed');
            await loadMonitoredFolders();
          } else {
            updateStatus('Failed to remove folder', true);
          }
        } catch (error) {
          console.error('Error removing folder:', error);
          updateStatus('Error removing folder', true);
        }
      }
      
      // Switch view
      function switchView(view) {
        currentView = view;
        
        // Update button states
        document.getElementById('grid-view-btn').classList.toggle('active-view', view === 'grid');
        document.getElementById('list-view-btn').classList.toggle('active-view', view === 'list');
        
        // Show/hide views
        gridView.style.display = view === 'grid' ? 'block' : 'none';
        listView.style.display = view === 'list' ? 'block' : 'none';
        
        renderFiles();
      }
      
      // Show/hide loading overlay
      function showLoading(show) {
        loadingOverlay.style.display = show ? 'block' : 'none';
      }
      
      // Update status
      function updateStatus(message, isError = false) {
        statusText.textContent = message;
        statusText.setAttribute('fill', isError ? '#ef4444' : '#94a3b8');
        
        if (!isError) {
          setTimeout(() => {
            statusText.textContent = 'Ready';
            statusText.setAttribute('fill', '#94a3b8');
          }, 3000);
        }
      }
      
      // Setup event listeners
      function setupEventListeners() {
        document.getElementById('add-folder-btn').addEventListener('click', addFolder);
        document.getElementById('refresh-btn').addEventListener('click', () => {
          if (currentFolder) loadFiles();
        });
        document.getElementById('grid-view-btn').addEventListener('click', () => switchView('grid'));
        document.getElementById('list-view-btn').addEventListener('click', () => switchView('list'));
        document.getElementById('convert-all-btn').addEventListener('click', convertAllPdfs);
        
        // Search functionality
        const searchBox = document.getElementById('search-placeholder');
        searchBox.addEventListener('click', () => {
          const query = prompt('Search files:', searchQuery);
          if (query !== null) {
            searchQuery = query.trim();
            searchBox.textContent = searchQuery ? `🔍 ${searchQuery}` : '🔍 Search files...';
            if (currentFolder) loadFiles();
          }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
          if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
              case 'f':
                e.preventDefault();
                searchBox.click();
                break;
              case 'r':
                e.preventDefault();
                if (currentFolder) loadFiles();
                break;
            }
          }
        });
      }
      
      // Initialize app
      init();
    }
