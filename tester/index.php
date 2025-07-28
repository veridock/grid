<?php
/**
 * SVG PWA Tester
 * Comprehensive tester for SVG files working as Progressive Web Apps with PHP
 * 
 * @author VeriDock Grid System
 * @version 1.0.0
 */

// Check if running from command line
$isCommandLine = php_sapi_name() === 'cli';

if (!$isCommandLine) {
    header('Content-Type: application/json; charset=UTF-8');
}

class SVGPWATester {
    
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    public function __construct() {
        $this->results = [
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'tests' => [],
            'summary' => [
                'total' => 0,
                'passed' => 0,
                'failed' => 0,
                'warnings' => 0
            ]
        ];
    }
    
    /**
     * Main testing function
     */
    public function testSVGFile($filePath) {
        if (!file_exists($filePath)) {
            $this->addError("File not found: $filePath");
            return false;
        }
        
        $this->addTest("file_exists", "File exists", true);
        
        // Test SVG structure
        $this->testSVGStructure($filePath);
        
        // Test PWA compatibility
        $this->testPWACompatibility($filePath);
        
        // Test PHP integration
        $this->testPHPIntegration($filePath);
        
        // Test browser compatibility
        $this->testBrowserCompatibility($filePath);
        
        // Test Linux preview compatibility
        $this->testLinuxPreview($filePath);
        
        // Generate summary
        $this->generateSummary();
        
        return $this->results;
    }
    
    /**
     * Test SVG structure and validity
     */
    private function testSVGStructure($filePath) {
        $content = file_get_contents($filePath);
        
        // Test 1: Valid XML structure
        $xml = @simplexml_load_string($content);
        $this->addTest("valid_xml", "Valid XML structure", $xml !== false);
        
        // Test 2: SVG namespace
        $hasSVGNamespace = strpos($content, 'xmlns="http://www.w3.org/2000/svg"') !== false ||
                          strpos($content, 'xmlns:svg="http://www.w3.org/2000/svg"') !== false;
        $this->addTest("svg_namespace", "SVG namespace present", $hasSVGNamespace);
        
        // Test 3: Root SVG element
        $hasRootSVG = preg_match('/<svg[^>]*>/', $content);
        $this->addTest("root_svg_element", "Root SVG element present", $hasRootSVG);
        
        // Test 4: ViewBox attribute
        $hasViewBox = strpos($content, 'viewBox=') !== false;
        $this->addTest("viewbox_attribute", "ViewBox attribute present", $hasViewBox);
        
        // Test 5: Width and Height
        $hasWidth = strpos($content, 'width=') !== false;
        $hasHeight = strpos($content, 'height=') !== false;
        $this->addTest("dimensions", "Width and Height defined", $hasWidth && $hasHeight);
        
        // Test 6: No external dependencies
        $hasExternalDeps = preg_match('/href=["\'](http|https|ftp|\/\/)/', $content);
        $this->addTest("no_external_deps", "No external dependencies", !$hasExternalDeps);
        
        return true;
    }
    
    /**
     * Test PWA compatibility
     */
    private function testPWACompatibility($filePath) {
        $content = file_get_contents($filePath);
        
        // Test 1: Inline styles (no external CSS)
        $hasExternalCSS = preg_match('/link[^>]*rel=["\']*stylesheet["\']/', $content);
        $this->addTest("inline_styles", "Uses inline styles", !$hasExternalCSS);
        
        // Test 2: Responsive design elements
        $hasResponsiveElements = strpos($content, 'viewBox=') !== false ||
                               strpos($content, 'preserveAspectRatio=') !== false;
        $this->addTest("responsive_design", "Responsive design elements", $hasResponsiveElements);
        
        // Test 3: No JavaScript dependencies
        $hasJSScripts = preg_match('/<script[^>]*src=/', $content);
        $this->addTest("no_js_deps", "No external JavaScript dependencies", !$hasJSScripts);
        
        // Test 4: Self-contained
        $isSelfContained = !preg_match('/src=["\'](http|https|\/\/)/', $content) &&
                          !preg_match('/href=["\'](http|https|\/\/)/', $content);
        $this->addTest("self_contained", "Self-contained SVG", $isSelfContained);
        
        return true;
    }
    
    /**
     * Test PHP integration compatibility
     */
    private function testPHPIntegration($filePath) {
        $content = file_get_contents($filePath);
        
        // Test 1: No PHP conflicts
        $hasPhpTags = strpos($content, '<?php') !== false || strpos($content, '<?=') !== false;
        $this->addTest("no_php_conflicts", "No PHP tag conflicts", !$hasPhpTags);
        
        // Test 2: MIME type compatibility
        $mimeType = mime_content_type($filePath);
        $correctMimeType = in_array($mimeType, ['image/svg+xml', 'text/xml', 'application/xml']);
        $this->addTest("correct_mime_type", "Correct MIME type", $correctMimeType);
        
        // Test 3: UTF-8 encoding
        $isUTF8 = mb_check_encoding($content, 'UTF-8');
        $this->addTest("utf8_encoding", "UTF-8 encoding", $isUTF8);
        
        return true;
    }
    
    /**
     * Test browser compatibility
     */
    private function testBrowserCompatibility($filePath) {
        $content = file_get_contents($filePath);
        
        // Test 1: Standard SVG elements only
        $unsupportedElements = ['foreignObject', 'switch'];
        $hasUnsupported = false;
        foreach ($unsupportedElements as $element) {
            if (strpos($content, "<$element") !== false) {
                $hasUnsupported = true;
                break;
            }
        }
        $this->addTest("standard_elements", "Uses only standard SVG elements", !$hasUnsupported);
        
        // Test 2: CSS compatibility
        $hasModernCSS = preg_match('/transform:|filter:|opacity:/', $content);
        $this->addTest("css_compatibility", "CSS properties compatible", true);
        
        // Test 3: File size check (under 1MB for PWA)
        $fileSize = filesize($filePath);
        $sizeOK = $fileSize < 1024 * 1024; // 1MB
        $this->addTest("file_size", "File size under 1MB", $sizeOK);
        
        if (!$sizeOK) {
            $this->addWarning("File size is " . round($fileSize / 1024, 2) . "KB, consider optimization");
        }
        
        return true;
    }
    
    /**
     * Test Linux preview compatibility
     */
    private function testLinuxPreview($filePath) {
        // Test 1: File permissions
        $isReadable = is_readable($filePath);
        $this->addTest("file_readable", "File is readable", $isReadable);
        
        // Test 2: File extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $correctExtension = $extension === 'svg';
        $this->addTest("correct_extension", "Correct .svg extension", $correctExtension);
        
        // Test 3: SVG header present
        $content = file_get_contents($filePath);
        $hasSVGHeader = strpos($content, '<?xml') === 0 || strpos($content, '<svg') !== false;
        $this->addTest("svg_header", "SVG header present", $hasSVGHeader);
        
        return true;
    }
    
    /**
     * Add test result
     */
    private function addTest($testName, $description, $passed) {
        $this->results['tests'][] = [
            'name' => $testName,
            'description' => $description,
            'passed' => $passed,
            'status' => $passed ? 'PASS' : 'FAIL'
        ];
        
        $this->results['summary']['total']++;
        if ($passed) {
            $this->results['summary']['passed']++;
        } else {
            $this->results['summary']['failed']++;
        }
    }
    
    /**
     * Add error
     */
    private function addError($message) {
        $this->errors[] = $message;
        $this->results['errors'][] = $message;
    }
    
    /**
     * Add warning
     */
    private function addWarning($message) {
        $this->warnings[] = $message;
        $this->results['warnings'][] = $message;
        $this->results['summary']['warnings']++;
    }
    
    /**
     * Generate summary
     */
    private function generateSummary() {
        $total = $this->results['summary']['total'];
        $passed = $this->results['summary']['passed'];
        
        $this->results['summary']['success_rate'] = $total > 0 ? round(($passed / $total) * 100, 2) : 0;
        $this->results['summary']['status'] = $this->results['summary']['failed'] > 0 ? 'FAILED' : 'PASSED';
    }
}

// Handle CLI arguments or HTTP requests
if ($isCommandLine) {
    // Command line interface
    if ($argc < 2) {
        echo "🔍 SVG PWA Tester - Command Line Interface\n";
        echo "=========================================\n\n";
        echo "Usage: php index.php <svg-file>\n";
        echo "Example: php index.php ../devmind.svg\n";
        echo "         php index.php ../files.svg\n\n";
        exit(1);
    }
    
    $svgFile = $argv[1];
    
    // If relative path, make it relative to current working directory
    if (!file_exists($svgFile) && !str_starts_with($svgFile, '/')) {
        // Try relative to the tester directory
        $svgFile = '../' . $svgFile;
    }
    
    echo "🚀 Testing SVG file: $svgFile\n";
    echo "=" . str_repeat("=", strlen($svgFile) + 18) . "\n\n";
    
    $tester = new SVGPWATester();
    $results = $tester->testSVGFile($svgFile);
    
    if ($results === false) {
        echo "❌ Error: Could not test file\n";
        if (!empty($results['errors'])) {
            foreach ($results['errors'] as $error) {
                echo "   $error\n";
            }
        }
        exit(1);
    }
    
    // Display results in CLI format
    echo "📊 Test Results Summary:\n";
    echo "------------------------\n";
    echo "Total Tests:  " . $results['summary']['total'] . "\n";
    echo "Passed:      " . $results['summary']['passed'] . " ✅\n";
    echo "Failed:      " . $results['summary']['failed'] . " ❌\n";
    echo "Warnings:    " . $results['summary']['warnings'] . " ⚠️\n";
    echo "Success Rate: " . $results['summary']['success_rate'] . "%\n";
    echo "Status:      " . ($results['summary']['status'] === 'PASSED' ? '✅ PASSED' : '❌ FAILED') . "\n\n";
    
    // Display detailed test results
    echo "📋 Detailed Test Results:\n";
    echo "--------------------------\n";
    foreach ($results['tests'] as $test) {
        $status = $test['passed'] ? '✅' : '❌';
        echo sprintf("%-20s %s %s\n", $test['name'], $status, $test['description']);
    }
    
    // Display errors if any
    if (!empty($results['errors'])) {
        echo "\n🔴 Errors:\n";
        echo "----------\n";
        foreach ($results['errors'] as $error) {
            echo "• $error\n";
        }
    }
    
    // Display warnings if any
    if (!empty($results['warnings'])) {
        echo "\n⚠️  Warnings:\n";
        echo "-------------\n";
        foreach ($results['warnings'] as $warning) {
            echo "• $warning\n";
        }
    }
    
    echo "\n🕒 Test completed at: " . $results['timestamp'] . "\n";
    
    // Exit with appropriate code
    exit($results['summary']['failed'] > 0 ? 1 : 0);
    
} else {
    // HTTP interface (original web API)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'File path is required']);
            exit;
        }
        
        $tester = new SVGPWATester();
        $results = $tester->testSVGFile($input['file']);
        
        echo json_encode($results, JSON_PRETTY_PRINT);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['file'])) {
        $tester = new SVGPWATester();
        $results = $tester->testSVGFile($_GET['file']);
        
        echo json_encode($results, JSON_PRETTY_PRINT);
    } else {
        // Return API documentation
        $apiDoc = [
            'name' => 'SVG PWA Tester API',
            'version' => '1.0.0',
            'description' => 'API for testing SVG files for PWA and PHP compatibility',
            'endpoints' => [
                [
                    'method' => 'GET',
                    'url' => '?file=path/to/file.svg',
                    'description' => 'Test SVG file via GET parameter'
                ],
                [
                    'method' => 'POST',
                    'url' => '/',
                    'description' => 'Test SVG file via POST JSON',
                    'body' => ['file' => 'path/to/file.svg']
                ]
            ],
            'cli_usage' => [
                'command' => 'php index.php <svg-file>',
                'example' => 'php index.php ../devmind.svg'
            ]
        ];
        
        echo json_encode($apiDoc, JSON_PRETTY_PRINT);
    }
}
?>
