<?php
/**
 * VeriDock Grid - SVG PWA Backend Server
 * Serves and manages SVG Progressive Web Applications
 * 
 * @version 2.0.0
 */

header('Content-Type: text/html; charset=UTF-8');

class SVGPWAServer {
    
    private $appsDir = './apps/';
    private $config = [];
    
    public function __construct() {
        $this->config = [
            'title' => 'VeriDock Grid - SVG PWA Server',
            'version' => '2.0.0',
            'apps_dir' => $this->appsDir
        ];
        
        if (!is_dir($this->appsDir)) {
            mkdir($this->appsDir, 0755, true);
        }
    }
    
    /**
     * Handle HTTP requests
     */
    public function handleRequest() {
        $path = $_GET['path'] ?? '';
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'serve':
                return $this->serveApp($path);
            case 'list':
                return $this->listApps();
            case 'upload':
                return $this->uploadApp();
            case 'delete':
                return $this->deleteApp($path);
            case 'validate':
                return $this->validateApp($path);
            default:
                return $this->showDashboard();
        }
    }
    
    /**
     * Serve SVG PWA application
     */
    private function serveApp($appName) {
        $appPath = $this->appsDir . $appName;
        
        if (!file_exists($appPath) || !str_ends_with($appPath, '.svg')) {
            http_response_code(404);
            echo "App not found: $appName";
            return;
        }
        
        // Set correct headers for SVG PWA
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        
        // Add PWA headers
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        
        readfile($appPath);
    }
    
    /**
     * List all available apps
     */
    private function listApps() {
        $apps = [];
        $files = glob($this->appsDir . '*.svg');
        
        foreach ($files as $file) {
            $name = basename($file);
            $apps[] = [
                'name' => $name,
                'size' => filesize($file),
                'modified' => filemtime($file),
                'url' => "?action=serve&path=" . urlencode($name)
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($apps);
    }
    
    /**
     * Upload new app
     */
    private function uploadApp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }
        
        if (!isset($_FILES['app']) || $_FILES['app']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo "Upload error";
            return;
        }
        
        $uploadedFile = $_FILES['app'];
        $fileName = $uploadedFile['name'];
        
        // Validate SVG file
        if (!str_ends_with($fileName, '.svg')) {
            http_response_code(400);
            echo "Only SVG files are allowed";
            return;
        }
        
        $targetPath = $this->appsDir . $fileName;
        
        if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            // Validate using tester
            $isValid = $this->validateSVGFile($targetPath);
            
            echo json_encode([
                'success' => true,
                'filename' => $fileName,
                'valid' => $isValid,
                'url' => "?action=serve&path=" . urlencode($fileName)
            ]);
        } else {
            http_response_code(500);
            echo "Failed to save file";
        }
    }
    
    /**
     * Validate specific app
     */
    private function validateApp($appName) {
        $appPath = $this->appsDir . $appName;
        
        if (!file_exists($appPath) || !str_ends_with($appPath, '.svg')) {
            http_response_code(404);
            echo "App not found: $appName";
            return;
        }
        
        $testerPath = __DIR__ . '/../tester/index.php';
        $command = "php " . escapeshellarg($testerPath) . " " . escapeshellarg($appPath) . " 2>&1";
        $output = shell_exec($command);
        
        header('Content-Type: text/plain; charset=utf-8');
        echo $output;
    }
    
    /**
     * Validate SVG file using tester
     */
    private function validateSVGFile($filePath) {
        $command = "php ../tester/index.php " . escapeshellarg($filePath) . " 2>&1";
        $output = shell_exec($command);
        return strpos($output, '21/21') !== false && strpos($output, '100%') !== false;
    }
    
    /**
     * Show admin dashboard
     */
    private function showDashboard() {
        $apps = glob($this->appsDir . '*.svg');
        $appCount = count($apps);
        
        echo $this->renderDashboard($appCount, $apps);
    }
    
    /**
     * Render HTML dashboard
     */
    private function renderDashboard($appCount, $apps) {
        return '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $this->config['title'] . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .header { 
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white; 
            padding: 30px; 
            text-align: center;
        }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; 
            padding: 30px;
        }
        .stat-card { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px;
            text-align: center;
            border: 2px solid #e9ecef;
        }
        .stat-value { font-size: 2em; font-weight: bold; color: #667eea; }
        .apps-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px; 
            padding: 0 30px 30px;
        }
        .app-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .app-name { font-weight: bold; margin-bottom: 10px; }
        .app-meta { color: #666; font-size: 0.9em; margin-bottom: 15px; }
        .app-actions { display: flex; gap: 10px; }
        .btn { 
            padding: 8px 16px; 
            border: none; 
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }
        .btn-primary { background: #667eea; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .upload-section { 
            background: #e9ecef; 
            padding: 30px; 
            text-align: center;
        }
        .upload-form { display: inline-block; }
        .file-input { margin: 10px; padding: 10px; }
        .no-apps { 
            text-align: center; 
            padding: 50px; 
            color: #666;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            position: relative;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover { color: black; }
        .embed-code {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
            margin: 15px 0;
        }
        .iframe-preview {
            border: 2px solid #ddd;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 VeriDock Grid Server</h1>
            <p>SVG Progressive Web Applications Management</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value">' . $appCount . '</div>
                <div>Deployed Apps</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">' . $this->config['version'] . '</div>
                <div>Server Version</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">21</div>
                <div>Validation Tests</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">100%</div>
                <div>Required Score</div>
            </div>
        </div>
        
        <div class="upload-section">
            <h3>📤 Deploy New SVG PWA</h3>
            <form class="upload-form" action="?action=upload" method="post" enctype="multipart/form-data">
                <input type="file" name="app" accept=".svg" class="file-input" required>
                <button type="submit" class="btn btn-primary">Upload & Validate</button>
            </form>
        </div>
        
        <div class="apps-grid">' . 
        ($appCount > 0 ? $this->renderApps($apps) : '<div class="no-apps">No apps deployed yet. Upload your first SVG PWA above.</div>') .
        '</div>
    </div>
    
    <!-- Iframe Embed Modal -->
    <div id="embedModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEmbedModal()">&times;</span>
            <h2>📱 Embed SVG PWA Application</h2>
            <p>Copy the code below to embed this SVG PWA in your website:</p>
            
            <div class="embed-code" id="embedCode"></div>
            
            <button class="btn btn-primary" onclick="copyEmbedCode()">📋 Copy Code</button>
            
            <h3>Live Preview:</h3>
            <div class="iframe-preview">
                <iframe id="iframePreview" width="100%" height="400" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    
    <script>
        function showIframeEmbed(appName) {
            const modal = document.getElementById("embedModal");
            const embedCode = document.getElementById("embedCode");
            const iframePreview = document.getElementById("iframePreview");
            
            const appUrl = window.location.origin + window.location.pathname + "?action=serve&path=" + appName;
            const embedHtml = `<iframe src="${appUrl}" width="800" height="600" frameborder="0" allowfullscreen></iframe>`;
            
            embedCode.textContent = embedHtml;
            iframePreview.src = appUrl;
            modal.style.display = "block";
        }
        
        function closeEmbedModal() {
            document.getElementById("embedModal").style.display = "none";
        }
        
        function copyEmbedCode() {
            const embedCode = document.getElementById("embedCode");
            navigator.clipboard.writeText(embedCode.textContent).then(() => {
                alert("Embed code copied to clipboard!");
            });
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById("embedModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>';
    }
    
    /**
     * Render apps list
     */
    private function renderApps($apps) {
        $html = '';
        foreach ($apps as $appPath) {
            $name = basename($appPath);
            $size = round(filesize($appPath) / 1024, 2);
            $modified = date('Y-m-d H:i', filemtime($appPath));
            
            $html .= '
            <div class="app-card">
                <div class="app-name">📱 ' . htmlspecialchars($name) . '</div>
                <div class="app-meta">
                    Size: ' . $size . ' KB<br>
                    Modified: ' . $modified . '
                </div>
                <div class="app-actions">
                    <a href="?action=serve&path=' . urlencode($name) . '" class="btn btn-primary" target="_blank">🚀 Launch</a>
                    <a href="javascript:void(0)" class="btn btn-success" onclick="showIframeEmbed(\'' . urlencode($name) . '\')">📱 Embed</a>
                    <a href="?action=validate&path=' . urlencode($name) . '" class="btn btn-warning">✅ Validate</a>
                    <a href="?action=delete&path=' . urlencode($name) . '" class="btn btn-danger" onclick="return confirm(\'Delete this app?\')">🗑️ Delete</a>
                </div>
            </div>';
        }
        return $html;
    }
}

// Initialize and handle request
$server = new SVGPWAServer();
$server->handleRequest();
?>
