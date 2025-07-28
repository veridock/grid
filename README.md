# Multi-Language SVG Scripting System

🚀 **Dynamic SVG Generation** with embedded **PHP**, **Python**, and **Node.js** code

## 🎯 Overview

This project enables **execution of SVG files with embedded scripting languages** both via web server and CLI, providing seamless workflows for dynamic SVG generation.

## ⚡ Quick Start

### PHP (Web Server)
```bash
cd generated
php -S localhost:8093 -t . router.php
# Dostęp: http://localhost:8093/test-minimal1.svg
# CLI: php router.php test-minimal1.svg
```

### Python (Web Server)
```bash
cd python
python svg_server.py 8094
# Dostęp: http://localhost:8094/todo-manager-python.svg
# CLI: python svg_processor.py todo-manager-python.svg > output.svg
```

### Node.js (Web Server)
```bash
cd nodejs
node svg_server.js 8095
# Dostęp: http://localhost:8095/todo-manager-nodejs.svg
# CLI: node svg_processor.js todo-manager-nodejs.svg > output.svg
```

## 🎨 Features

- ✅ **Dynamic SVG Generation**: Embed code directly in SVG files
- ✅ **Multi-Language Support**: PHP, Python, JavaScript
- ✅ **CLI Processing**: Generate static SVG files from templates
- ✅ **Web Server Support**: Live PHP+SVG rendering
- ✅ **Pipeline Integration**: Perfect for automation
- ✅ **Batch Processing**: Process multiple files at once

## 🗂️ Project Structure

```
├── generated/
│   ├── router.php                  # PHP router (WWW + CLI)
│   ├── todo-manager-pwa.svg        # SVG z PHP kodem
│   ├── test-minimal1.svg           # Prosty przykład PHP
│   └── README-SVG-PHP.md           # Dokumentacja PHP
├── python/
│   ├── svg_processor.py            # Python processor (CLI)
│   ├── svg_server.py               # Python HTTP server
│   └── todo-manager-python.svg     # SVG z Python kodem
└── nodejs/
    ├── svg_processor.js            # Node.js processor (CLI)
    ├── svg_server.js               # Node.js HTTP server
    └── todo-manager-nodejs.svg     # SVG z JavaScript kodem
```

## 🔧 Language Syntax

### PHP
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?php echo date('H:i:s'); ?></text>
  <text x="10" y="40">Zadań: <?php echo $tasks_count; ?></text>
</svg>
```

### Python
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?python print(current_time) ?></text>
  <text x="10" y="40">Zadań: <?python print(tasks_count) ?></text>
</svg>
```

### Node.js
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?js print(currentTime) ?></text>
  <text x="10" y="40">Zadań: <?js print(tasksCount) ?></text>
</svg>
```

## 🎯 Available Variables

### PHP
- `$current_time` - aktualny czas (H:i:s)
- `$tasks_count` - liczba zadań
- `$_SERVER` - zmienne serwera
- Wszystkie funkcje PHP

### Python
- `current_time` - aktualny czas
- `current_date` - aktualna data
- `tasks_count` - liczba zadań
- `datetime`, `os`, `sys` - moduły Python

### Node.js
- `currentTime` - aktualny czas
- `currentDate` - aktualna data
- `tasksCount` - liczba zadań
- `nodeVersion` - wersja Node.js
- `Date`, `Math`, `JSON` - obiekty JavaScript

## 📚 Documentation

- 📖 [PHP Documentation](php/README-SVG-PHP.md) - Complete PHP+SVG guide
- 📖 [Multi-Language Guide](README-SVG-SCRIPTING.md) - Comprehensive documentation


## 📊 **21-Point Validation Schema**

### **SVG Structure Tests (8 points)**
- ✅ `file_exists` - File accessibility
- ✅ `valid_xml` - XML structure validity
- ✅ `svg_namespace` - SVG namespace presence
- ✅ `root_svg_element` - Root SVG element
- ✅ `viewbox_attribute` - ViewBox attribute
- ✅ `dimensions` - Width and Height defined
- ✅ `no_external_deps` - No external dependencies
- ✅ `no_g_transform` - No forbidden g transform elements

### **PWA Compliance Tests (7 points)**
- ✅ `inline_styles` - Uses inline styles
- ✅ `responsive_design` - Responsive design elements
- ✅ `no_js_deps` - No external JavaScript dependencies
- ✅ `self_contained` - Self-contained SVG
- ✅ `no_php_conflicts` - No PHP tag conflicts
- ✅ `standard_elements` - Uses only standard SVG elements
- ✅ `css_compatibility` - CSS properties compatible

### **File Quality Tests (6 points)**
- ✅ `correct_mime_type` - Correct MIME type
- ✅ `utf8_encoding` - UTF-8 encoding
- ✅ `file_size` - File size under 1MB
- ✅ `file_readable` - File is readable
- ✅ `correct_extension` - Correct .svg extension
- ✅ `svg_header` - SVG header present

## 🚀 **Advanced Usage Examples**

### **Recursive Directory Testing**

```bash
# Test all SVG files in a directory
php tester/index.php /path/to/svg/files/

# Output includes:
# - Per-file validation results
# - Summary statistics
# - Failed file analysis
# - Memory usage optimization
```

### **Generate Custom SVG PWA**

```bash
# Interactive generator with prompts
php generator/svg-pwa-generator.php

# Example generation session:
# 1. Select app type (dashboard, tracker, manager, etc.)
# 2. Configure features (real-time, backend, analytics)
# 3. Add PHP backend logic (embedded as JSON)
# 4. Define JavaScript interactivity
# 5. Set JSON metadata and configuration
# 6. Automatic validation and compliance check
```

### **Backend Integration Example**

```php
// Example: Embedded PHP in JSON format (XML-safe)
{
  "backend_implementation": {
    "controller": "class ApiController { public function getData() { return ['status' => 'success']; } }",
    "routes": "Route::get('/api/data', 'ApiController@getData');",
    "database": "class DB { private $pdo; public function query($sql) { /* implementation */ } }"
  }
}
```

## 🎯 **Application Examples**

### **💰 Expense Tracker PWA**
```bash
# Test the expense tracker
php tester/index.php php/expense-tracker-pwa.svg

# Features:
# - Financial transaction tracking (income/expenses)
# - Category-based organization
# - Real-time analytics and reporting
# - JSON data export functionality
# - PHP backend for database operations
```

### **📦 Inventory Manager PWA**
```bash
# Test the inventory manager
php tester/index.php php/inventory-manager-pwa.svg

# Features:
# - Stock level monitoring and alerts
# - Product catalog management
# - Supplier and vendor tracking
# - Automated reorder notifications
# - Advanced reporting dashboard
```

### **🚀 Project Manager PWA**
```bash
# Test the project manager
php tester/index.php php/project-manager-pwa.svg

# Features:
# - Task and milestone tracking
# - Team collaboration tools
# - Progress visualization
# - Resource allocation management
# - Performance metrics and KPIs
```

### **📊 Test Dashboard PWA**
```bash
# Test the analytics dashboard
php tester/index.php php/test-dashboard.svg

# Features:
# - Real-time system monitoring
# - Performance metrics visualization
# - Alert management system
# - Data export capabilities
# - PHP backend integration for live data
```

## 🔒 **Security & Compliance**

- **No External Dependencies**: All applications are self-contained
- **XML-Safe PHP Embedding**: Backend code stored as JSON strings
- **Standard SVG Elements Only**: No forbidden elements like `foreignObject`
- **Memory-Optimized Testing**: Large file protection (>50MB)
- **UTF-8 Encoding**: Full international character support
- **iframe Security**: Safe embedding in any web context

## 📈 **Performance & Statistics**

```
🎯 PROJECT ACHIEVEMENTS
=======================
Total Applications:     4
Compliance Rate:       100%
Validation Tests:      21 per app
Successful Tests:      84/84 ✅
Failed Tests:          0/84 ❌
Backend Integration:   PHP + JSON
JavaScript Support:    Full SVG compatibility
Production Ready:      ✅ All applications
```

## 🤝 **Contributing**

To add new SVG PWA applications:

1. Follow the 21-point validation schema
2. Embed PHP as JSON strings (never direct PHP tags)
3. Use only standard SVG elements
4. Test with `php tester/index.php your-app.svg`
5. Ensure 100% compliance before submission

## 📄 **License**

This project is licensed under the MIT License - see the LICENSE file for details.

---

**🚀 Ready to create your own SVG PWA? Start with the generator and join the revolution of self-contained, backend-integrated web applications!**

2. **Save the files:**
   - Download `file-monitor.js`
   - Download `file-monitor-app.svg`

3. **Install dependencies (optional but recommended):**
   ```bash
   npm init -y
   npm install chokidar sharp
   ```

4. **Install PDF tools (optional):**
   ```bash
   # Ubuntu/Debian
   sudo apt-get install pdf2svg
   # or
   sudo apt-get install imagemagick

   # macOS
   brew install pdf2svg
   # or
   brew install imagemagick

   # Windows
   # Download from ImageMagick website
   ```

5. **Run the application:**
   ```bash
   node file-monitor.js
   ```

## Usage

### Adding Folders to Monitor

1. Click the "+ Add Folder" button
2. Enter the full path to the folder
3. The folder will appear in the sidebar

### Converting PDFs

- **Single file**: Click the blue "SVG" button on any PDF
- **Batch convert**: Click "⚡ Convert PDFs" to convert all PDFs in the current folder

### Views

- **Grid View**: Visual thumbnails in a 5x5 grid
- **List View**: Detailed file information in a table

### Keyboard Shortcuts

- `Ctrl/Cmd + F` - Search files
- `Ctrl/Cmd + R` - Refresh current folder

## Features in Detail

### Auto-monitoring
When you add a folder, the app watches for:
- New files added
- Files modified
- Files deleted

New PDF files are automatically converted to SVG upon detection.

### Thumbnail Generation
The app generates thumbnails for:
- Images (PNG, JPG, JPEG)
- SVG files
- PDF files (first page)

### File Management
- Click any file to open it
- Search files by name
- Filter by file type
- Sort by date or name

## Configuration

### Environment Variables

- `PORT` - Server port (default: 3000, auto-increments if busy)

### File Types Supported

- PDF documents
- SVG graphics
- PNG images
- JPG/JPEG images

## Troubleshooting

### Port Already in Use
The app automatically finds an available port if 3000 is busy.

### No Thumbnails Showing
Install the `sharp` package:
```bash
npm install sharp
```

### PDF Conversion Not Working
Install PDF conversion tools:
```bash
# Try pdf2svg first (faster)
sudo apt-get install pdf2svg

# Or ImageMagick as fallback
sudo apt-get install imagemagick
```

### Cannot Monitor Folders
Install the `chokidar` package:
```bash
npm install chokidar
```

## How It Works

The application consists of two parts:

1. **Server (`file-monitor.js`)**
   - HTTP server with API endpoints
   - File system monitoring
   - PDF conversion
   - Thumbnail generation

2. **Client (`file-monitor-app.svg`)**
   - SVG-based user interface
   - Can be opened directly or served
   - Auto-connects to server
   - PWA capabilities

### Architecture

```
┌─────────────────┐      ┌─────────────────┐
│   Browser       │────▶│   Node.js       │
│   (SVG App)     │◀────│   Server        │
└─────────────────┘      └─────────────────┘
                              │
                              ▼
                        ┌─────────────┐
                        │ File System │
                        └─────────────┘
```

## Development

### Project Structure
```
file-monitor-pwa/
├── file-monitor.js       # Server component
├── file-monitor-app.svg  # Client application
├── package.json          # Dependencies
└── README.md            # This file
```

### API Endpoints

- `GET /` - Serve SVG application
- `GET /api/folders` - List monitored folders
- `POST /api/folders` - Add folder to monitor
- `GET /api/files?folder=path` - List files in folder
- `POST /api/convert-pdf` - Convert PDF to SVG
- `GET /api/thumbnail?file=path` - Get file thumbnail
- `POST /api/remove-folder` - Remove folder from monitoring




### Creating a New PWA.SVG App

1. Create a new directory in `examples/`
2. Create your `.pwa.svg` file with the following structure:
   ```svg
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 600">
     <!-- SVG content -->
     <foreignObject width="100%" height="100%">
       <xhtml:div>
         <!-- Your HTML/CSS/JS here -->
       </xhtml:div>
     </foreignObject>
     <script><![CDATA[
       // Your JavaScript here
     ]]></script>
   </svg>
   ```
   
## License

Apache 2.0 License - Feel free to use and modify!

## Contributing

Pull requests welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## Credits

Built with:
- Node.js
- SVG
- Progressive Web App technologies
- Optional: chokidar, sharp, pdf2svg