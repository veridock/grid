<?php
/**
 * SVG PWA Generator v2.0
 * Automated creation of SVG Progressive Web Applications
 */

class SVGPWAGenerator {
    
    private $patterns = [];
    private $outputDir = '';
    
    public function __construct($outputDir = '../php') {
        $this->outputDir = $outputDir;
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }
    
    /**
     * Generate SVG PWA application
     */
    public function generate($type, $config) {
        $this->validateConfig($config);
        
        switch ($type) {
            case 'dashboard':
                return $this->generateDashboard($config);
            case 'calculator':
                return $this->generateCalculator($config);
            case 'form':
                return $this->generateForm($config);
            case 'game':
                return $this->generateGame($config);
            default:
                return $this->generateCustom($type, $config);
        }
    }
    
    /**
     * Save generated SVG to file
     */
    public function saveToFile($content, $filename) {
        $filepath = $this->outputDir . '/' . $filename;
        return file_put_contents($filepath, $content);
    }
    
    /**
     * Validate SVG using tester
     */
    public function validate($filepath) {
        $testerPath = __DIR__ . '/../tester/index.php';
        $command = "php " . escapeshellarg($testerPath) . " " . escapeshellarg($filepath);
        $output = shell_exec($command);
        return strpos($output, '21/21') !== false && strpos($output, '100%') !== false;
    }
    
    /**
     * Generate Base Template
     */
    private function getBaseTemplate() {
        return '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="{WIDTH}" height="{HEIGHT}" viewBox="{VIEWBOX}">
  <title>{APP_TITLE}</title>
  <desc>{APP_DESC}</desc>
  <defs>
    <style><![CDATA[{CSS_STYLES}]]></style>
    {GRADIENTS}
  </defs>
  {MAIN_CONTENT}
  <script><![CDATA[{JAVASCRIPT}]]></script>
</svg>';
    }
    
    /**
     * Generate Dashboard Pattern
     */
    private function generateDashboard($config) {
        $template = $this->getBaseTemplate();
        
        $replacements = [
            '{APP_TITLE}' => $config['title'] ?? 'Dashboard PWA',
            '{APP_DESC}' => $config['description'] ?? 'Interactive dashboard',
            '{VIEWBOX}' => '0 0 1200 800',
            '{WIDTH}' => '100%',
            '{HEIGHT}' => '100%',
            '{CSS_STYLES}' => $this->getDashboardCSS($config),
            '{GRADIENTS}' => $this->getGradients('dashboard'),
            '{MAIN_CONTENT}' => $this->getDashboardContent($config),
            '{JAVASCRIPT}' => $this->getDashboardJS($config)
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
    
    private function getDashboardCSS($config) {
        return '
      .header { font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; fill: white; }
      .card { fill: rgba(255,255,255,0.1); stroke: #64ffda; stroke-width: 1; rx: 10; }
      .text { font-family: Arial, sans-serif; font-size: 14px; fill: white; }
      .value { font-family: Arial, sans-serif; font-size: 18px; fill: #64ffda; font-weight: bold; }
      .button { fill: #64ffda; stroke: none; rx: 20; cursor: pointer; }
      .button:hover { fill: #00bcd4; }
      .button-text { font-family: Arial, sans-serif; font-size: 12px; fill: #1a1a2e; text-anchor: middle; }';
    }
    
    private function getDashboardContent($config) {
        return '
  <rect width="100%" height="100%" fill="url(#bgGrad)"/>
  <text x="50" y="50" class="header">Dashboard</text>
  <rect x="50" y="120" width="250" height="120" class="card"/>
  <text x="70" y="150" class="text">System Status</text>
  <text x="70" y="180" class="value">Online</text>
  <rect x="50" y="400" width="120" height="40" class="button" onclick="refresh()"/>
  <text x="110" y="425" class="button-text">Refresh</text>';
    }
    
    private function getDashboardJS($config) {
        return '
    function refresh() {
      console.log("Dashboard refreshed");
      document.querySelector(".value").textContent = "Updated";
    }
    console.log("Dashboard PWA initialized");';
    }
    
    private function getGradients($type) {
        return '
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#1a1a2e;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#0f3460;stop-opacity:1" />
    </linearGradient>';
    }
    
    private function validateConfig($config) {
        if (!is_array($config)) {
            throw new Exception('Config must be an array');
        }
    }
    
    // Additional pattern methods...
    private function generateCalculator($config) { return $this->generateDashboard($config); }
    private function generateForm($config) { return $this->generateDashboard($config); }
    private function generateGame($config) { return $this->generateDashboard($config); }
    private function generateCustom($type, $config) { return $this->generateDashboard($config); }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    if ($argc < 3) {
        echo "Usage: php svg-pwa-generator.php [type] [output-file] [title]\n";
        echo "Types: dashboard, calculator, form, game\n";
        exit(1);
    }
    
    $type = $argv[1];
    $outputFile = $argv[2];
    $title = $argv[3] ?? ucfirst($type) . ' PWA';
    
    $generator = new SVGPWAGenerator('./php');
    $config = ['title' => $title, 'description' => "Generated $type application"];
    
    $svg = $generator->generate($type, $config);
    $generator->saveToFile($svg, $outputFile);
    
    echo "Generated: php/$outputFile\n";
    
    // Validate
    $outputPath = "php/$outputFile";
    $isValid = $generator->validate($outputPath);
        
    if ($isValid) {
        echo "✅ Validation: PASSED\n";
    } else {
        echo "❌ Validation: FAILED\n";
        $command = "php " . __DIR__ . "/../tester/index.php " . escapeshellarg($outputPath);
        echo shell_exec($command);
    }
}
?>
