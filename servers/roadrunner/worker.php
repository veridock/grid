<?php
/**
 * RoadRunner Worker for PHP-SVG Processing
 * High-performance persistent worker with PSR-7 compliance
 */

use Spiral\RoadRunner;
use Nyholm\Psr7;

include "vendor/autoload.php";

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();

$worker = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

// Path to SVG files
$documentRoot = realpath('../php');

while ($req = $worker->waitRequest()) {
    try {
        $uri = $req->getUri()->getPath();
        $method = $req->getMethod();
        
        // Log request
        error_log("[RoadRunner] Processing: $method $uri");
        
        // Handle SVG files
        if (preg_match('/\.svg$/', $uri)) {
            $filePath = $documentRoot . $uri;
            
            if (!file_exists($filePath)) {
                $response = $psrFactory->createResponse(404)
                    ->withHeader('Content-Type', 'text/plain');
                $response->getBody()->write("SVG file not found: $uri");
                $worker->respond($response);
                continue;
            }
            
            // Set up environment for SVG processing
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['SERVER_NAME'] = $req->getUri()->getHost();
            $_SERVER['SERVER_PORT'] = $req->getUri()->getPort() ?: 8097;
            
            // Parse query parameters
            parse_str($req->getUri()->getQuery(), $_GET);
            
            // Parse POST data if applicable
            if ($method === 'POST') {
                $body = $req->getBody()->getContents();
                parse_str($body, $_POST);
            }
            
            // Include the standardized variable resolution functions
            include_once $documentRoot . '/router.php';
            
            // Get template variables with all priority sources
            $templateVariables = getTemplateVariables();
            
            // Process the SVG file
            ob_start();
            
            try {
                // Read SVG content
                $svgContent = file_get_contents($filePath);
                
                // Replace placeholders with resolved variables
                $processedContent = str_replace(
                    array_keys($templateVariables), 
                    array_values($templateVariables), 
                    $svgContent
                );
                
                // Execute PHP code within the processed SVG
                eval('?>' . $processedContent);
                
            } catch (ParseError $e) {
                // If eval fails, try direct include
                include $filePath;
            }
            
            $content = ob_get_clean();
            
            // Create response with proper headers
            $response = $psrFactory->createResponse(200)
                ->withHeader('Content-Type', 'image/svg+xml; charset=utf-8')
                ->withHeader('Cache-Control', 'no-cache, must-revalidate')
                ->withHeader('X-Powered-By', 'RoadRunner + PHP-SVG')
                ->withHeader('X-Processing-Time', microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);
            
            $response->getBody()->write($content);
            $worker->respond($response);
            
        } 
        // Handle regular PHP files
        elseif (preg_match('/\.php$/', $uri)) {
            $filePath = $documentRoot . $uri;
            
            if (!file_exists($filePath)) {
                $response = $psrFactory->createResponse(404)
                    ->withHeader('Content-Type', 'text/plain');
                $response->getBody()->write("PHP file not found: $uri");
                $worker->respond($response);
                continue;
            }
            
            // Set up PHP environment
            $_SERVER['REQUEST_URI'] = $uri;
            $_SERVER['SCRIPT_FILENAME'] = $filePath;
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            $_SERVER['REQUEST_METHOD'] = $method;
            
            parse_str($req->getUri()->getQuery(), $_GET);
            
            if ($method === 'POST') {
                $body = $req->getBody()->getContents();
                parse_str($body, $_POST);
            }
            
            ob_start();
            include $filePath;
            $content = ob_get_clean();
            
            $response = $psrFactory->createResponse(200)
                ->withHeader('Content-Type', 'text/html; charset=utf-8')
                ->withHeader('X-Powered-By', 'RoadRunner + PHP');
            
            $response->getBody()->write($content);
            $worker->respond($response);
        }
        // Handle static files (delegate to RoadRunner)
        else {
            $response = $psrFactory->createResponse(404)
                ->withHeader('Content-Type', 'text/plain');
            $response->getBody()->write("File not found or not supported: $uri");
            $worker->respond($response);
        }
        
    } catch (\Throwable $e) {
        // Error handling
        error_log("[RoadRunner Error] " . $e->getMessage() . "\n" . $e->getTraceAsString());
        
        $response = $psrFactory->createResponse(500)
            ->withHeader('Content-Type', 'text/plain');
        $response->getBody()->write("Internal Server Error: " . $e->getMessage());
        $worker->respond($response);
    }
}
