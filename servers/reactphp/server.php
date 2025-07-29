<?php
/**
 * ReactPHP Server for PHP-SVG Processing
 * Event-driven async server with high concurrency support
 */

require __DIR__ . '/vendor/autoload.php';

use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Psr\Http\Message\ServerRequestInterface;

// Server configuration
$port = 8097;
$documentRoot = realpath('../php');

echo "🚀 Starting ReactPHP SVG-PHP Server...\n";
echo "📁 Document root: $documentRoot\n";
echo "🌐 Server address: http://0.0.0.0:$port\n";

// Include router functions for variable resolution
require_once $documentRoot . '/router.php';

$server = new HttpServer(function (ServerRequestInterface $request) use ($documentRoot) {
    $uri = $request->getUri()->getPath();
    $method = $request->getMethod();
    
    // Log request
    $clientIp = $request->getHeaderLine('X-Forwarded-For') ?: 'unknown';
    echo "[" . date('Y-m-d H:i:s') . "] $method $uri from $clientIp\n";
    
    try {
        // Handle SVG files as PHP
        if (preg_match('/\.svg$/', $uri)) {
            $filePath = $documentRoot . $uri;
            
            if (!file_exists($filePath)) {
                return new Response(
                    404,
                    ['Content-Type' => 'text/plain'],
                    "SVG file not found: $uri"
                );
            }
            
            // Set up environment for processing
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SERVER_NAME'] = $request->getUri()->getHost();
            $_SERVER['SERVER_PORT'] = $request->getUri()->getPort() ?: $port;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
            
            // Parse query parameters
            parse_str($request->getUri()->getQuery(), $_GET);
            
            // Parse POST data for POST requests
            if ($method === 'POST') {
                $body = (string) $request->getBody();
                parse_str($body, $_POST);
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
            
            // Return response with proper headers
            return new Response(
                200,
                [
                    'Content-Type' => 'image/svg+xml; charset=utf-8',
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'X-Powered-By' => 'ReactPHP + PHP-SVG',
                    'X-Processing-Time' => number_format((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2) . 'ms',
                    'Access-Control-Allow-Origin' => '*'
                ],
                $content
            );
        }
        
        // Handle regular PHP files
        if (preg_match('/\.php$/', $uri)) {
            $filePath = $documentRoot . $uri;
            
            if (!file_exists($filePath)) {
                return new Response(
                    404,
                    ['Content-Type' => 'text/plain'],
                    "PHP file not found: $uri"
                );
            }
            
            // Set up PHP environment
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            
            parse_str($request->getUri()->getQuery(), $_GET);
            
            if ($method === 'POST') {
                $body = (string) $request->getBody();
                parse_str($body, $_POST);
            }
            
            ob_start();
            include $filePath;
            $content = ob_get_clean();
            
            return new Response(
                200,
                [
                    'Content-Type' => 'text/html; charset=utf-8',
                    'X-Powered-By' => 'ReactPHP + PHP'
                ],
                $content
            );
        }
        
        // Handle static files
        if (file_exists($documentRoot . $uri) && is_file($documentRoot . $uri)) {
            $content = file_get_contents($documentRoot . $uri);
            $mimeType = mime_content_type($documentRoot . $uri) ?: 'application/octet-stream';
            
            return new Response(
                200,
                ['Content-Type' => $mimeType],
                $content
            );
        }
        
        // Default 404 response
        return new Response(
            404,
            ['Content-Type' => 'text/html'],
            '<h1>404 Not Found</h1><p>The requested resource was not found.</p>'
        );
        
    } catch (Throwable $e) {
        // Error handling
        error_log("[ReactPHP Error] " . $e->getMessage() . "\n" . $e->getTraceAsString());
        
        return new Response(
            500,
            ['Content-Type' => 'text/plain'],
            "Internal Server Error: " . $e->getMessage()
        );
    }
});

// Create socket server
$socket = new SocketServer("0.0.0.0:$port");

// Handle server events
$socket->on('connection', function() {
    // Connection established
});

$socket->on('error', function (Exception $e) {
    echo "❌ Server error: " . $e->getMessage() . "\n";
});

// Start the server
$server->listen($socket);

echo "✅ ReactPHP server started successfully!\n";
echo "📋 Features enabled:\n";
echo "   - Event-driven architecture\n";
echo "   - High concurrency support\n";
echo "   - SVG+PHP processing\n";
echo "   - Standardized variable system\n";
echo "   - Real-time request logging\n";
echo "\n🧪 Test URLs:\n";
echo "   http://localhost:$port/calculator.svg\n";
echo "   http://localhost:$port/todo-manager-pwa.svg\n";
echo "\n⏹️  Press Ctrl+C to stop the server\n\n";

// Keep the server running
echo "🔄 Server is running and waiting for requests...\n";
