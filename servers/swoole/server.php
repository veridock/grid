<?php
/**
 * Swoole Server for PHP-SVG Processing
 * Enterprise-grade C++ implementation with built-in HTTP/2 support
 */

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

// Server configuration
$host = '0.0.0.0';
$port = 8097;
$documentRoot = realpath('../php');

// Create HTTP server
$server = new Server($host, $port);

// Server configuration
$server->set([
    'worker_num' => swoole_cpu_num() * 2,      // Number of worker processes
    'max_request' => 10000,                    // Max requests per worker
    'document_root' => $documentRoot,          // Document root for static files
    'enable_static_handler' => true,          // Enable static file handling
    'static_handler_locations' => ['/css', '/js', '/images'], // Static file locations
    'open_http2_protocol' => true,            // Enable HTTP/2
    'open_http_compression' => true,          // Enable gzip compression
    'compression_level' => 6,                 // Compression level (1-9)
    'log_file' => __DIR__ . '/logs/swoole.log',
    'log_level' => SWOOLE_LOG_INFO,
    'daemonize' => false,                     // Run in foreground for development
    'max_conn' => 10000,                      // Maximum connections
    'reactor_num' => swoole_cpu_num(),        // Number of reactor threads
    'open_tcp_keepalive' => true,             // Enable TCP keepalive
    'heartbeat_check_interval' => 60,         // Heartbeat check interval
    'heartbeat_idle_time' => 600,             // Heartbeat idle time
]);

// Include router functions for variable resolution
require_once $documentRoot . '/router.php';

// Request handler
$server->on('request', function (Request $request, Response $response) use ($documentRoot) {
    $uri = $request->server['request_uri'];
    $method = $request->server['request_method'];
    $startTime = microtime(true);
    
    // Log request
    echo "[" . date('Y-m-d H:i:s') . "] $method $uri from {$request->server['remote_addr']}\n";
    
    try {
        // Handle SVG files as PHP
        if (preg_match('/\.svg$/', $uri)) {
            // Remove query string from URI for file path
            $filePath = $documentRoot . parse_url($uri, PHP_URL_PATH);
            
            if (!file_exists($filePath)) {
                $response->status(404);
                $response->header('Content-Type', 'text/plain');
                $response->end("SVG file not found: $uri");
                return;
            }
            
            // Set up environment for SVG processing
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SERVER_NAME'] = $request->header['host'] ?? 'localhost';
            $_SERVER['SERVER_PORT'] = 8097;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['REQUEST_TIME_FLOAT'] = $startTime;
            $_SERVER['HTTP_HOST'] = $request->header['host'] ?? 'localhost:8097';
            $_SERVER['HTTP_USER_AGENT'] = $request->header['user-agent'] ?? 'Swoole';
            
            // Parse query parameters
            if (isset($request->get)) {
                $_GET = $request->get;
            } else {
                $_GET = [];
            }
            
            // Parse POST data
            if (isset($request->post)) {
                $_POST = $request->post;
            } else {
                $_POST = [];
            }
            
            // Get standardized template variables
            $templateVariables = getTemplateVariables();
            
            // Start output buffering
            ob_start();
            
            try {
                // Read and process SVG content
                $svgContent = file_get_contents($filePath);
                
                // Replace placeholders with resolved variables
                $processedContent = str_replace(
                    array_keys($templateVariables),
                    array_values($templateVariables),
                    $svgContent
                );
                
                // Execute PHP code within the SVG
                eval('?>' . $processedContent);
                
            } catch (ParseError $e) {
                // If eval fails, try direct include
                include $filePath;
            } catch (Throwable $e) {
                echo "<!-- Error processing SVG: " . htmlspecialchars($e->getMessage()) . " -->";
                include $filePath;
            }
            
            $content = ob_get_clean();
            
            // Set response headers
            $response->status(200);
            $response->header('Content-Type', 'image/svg+xml; charset=utf-8');
            $response->header('Cache-Control', 'no-cache, must-revalidate');
            $response->header('X-Powered-By', 'Swoole + PHP-SVG');
            $response->header('X-Processing-Time', number_format((microtime(true) - $startTime) * 1000, 2) . 'ms');
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Server', 'Swoole/' . swoole_version());
            
            $response->end($content);
            return;
        }
        
        // Handle regular PHP files
        if (preg_match('/\.php$/', $uri)) {
            $filePath = $documentRoot . parse_url($uri, PHP_URL_PATH);
            
            if (!file_exists($filePath)) {
                $response->status(404);
                $response->header('Content-Type', 'text/plain');
                $response->end("PHP file not found: $uri");
                return;
            }
            
            // Set up PHP environment
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            
            if (isset($request->get)) {
                $_GET = $request->get;
            }
            
            if (isset($request->post)) {
                $_POST = $request->post;
            }
            
            ob_start();
            include $filePath;
            $content = ob_get_clean();
            
            $response->status(200);
            $response->header('Content-Type', 'text/html; charset=utf-8');
            $response->header('X-Powered-By', 'Swoole + PHP');
            $response->end($content);
            return;
        }
        
        // Handle static files (delegated to Swoole's static handler when enabled)
        $staticPath = $documentRoot . parse_url($uri, PHP_URL_PATH);
        if (file_exists($staticPath) && is_file($staticPath)) {
            // Let Swoole handle static files automatically
            return;
        }
        
        // Default 404 response
        $response->status(404);
        $response->header('Content-Type', 'text/html');
        $response->end('<h1>404 Not Found</h1><p>The requested resource was not found.</p>');
        
    } catch (Throwable $e) {
        // Error handling
        error_log("[Swoole Error] " . $e->getMessage() . "\n" . $e->getTraceAsString());
        
        $response->status(500);
        $response->header('Content-Type', 'text/plain');
        $response->end("Internal Server Error: " . $e->getMessage());
    }
});

// Server start event
$server->on('start', function (Server $server) use ($host, $port) {
    echo "🚀 Swoole SVG-PHP Server started successfully!\n";
    echo "📋 Server Information:\n";
    echo "   - Version: Swoole " . swoole_version() . " + PHP " . PHP_VERSION . "\n";
    echo "   - Address: http://$host:$port\n";
    echo "   - Workers: " . $server->setting['worker_num'] . "\n";
    echo "   - HTTP/2: " . ($server->setting['open_http2_protocol'] ? 'Enabled' : 'Disabled') . "\n";
    echo "   - Compression: " . ($server->setting['open_http_compression'] ? 'Enabled' : 'Disabled') . "\n";
    echo "   - PID: " . $server->master_pid . "\n";
    
    echo "\n📋 Features enabled:\n";
    echo "   - Enterprise-grade C++ implementation\n";
    echo "   - Built-in HTTP/2 support\n";
    echo "   - High-performance static file serving\n";
    echo "   - SVG+PHP processing\n";
    echo "   - Standardized variable system\n";
    echo "   - Automatic compression\n";
    
    echo "\n🧪 Test URLs:\n";
    echo "   http://localhost:$port/calculator.svg\n";
    echo "   http://localhost:$port/todo-manager-pwa.svg\n";
    
    echo "\n⏹️  Press Ctrl+C to stop the server\n\n";
});

// Worker start event
$server->on('workerStart', function (Server $server, int $workerId) {
    echo "🔧 Worker #$workerId started (PID: " . posix_getpid() . ")\n";
});

// Worker stop event
$server->on('workerStop', function (Server $server, int $workerId) {
    echo "🔧 Worker #$workerId stopped\n";
});

// Server shutdown event
$server->on('shutdown', function (Server $server) {
    echo "🛑 Swoole server shutting down...\n";
});

// Create logs directory
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}

// Handle signals for graceful shutdown
pcntl_async_signals(true);
pcntl_signal(SIGTERM, function () use ($server) {
    echo "📡 Received SIGTERM, shutting down gracefully...\n";
    $server->shutdown();
});

pcntl_signal(SIGINT, function () use ($server) {
    echo "📡 Received SIGINT, shutting down gracefully...\n";
    $server->shutdown();
});

// Start the server
echo "🔄 Starting Swoole server...\n";
$server->start();
