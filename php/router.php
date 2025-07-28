<?php
// Ulepszony router dla bezpośredniej obsługi SVG+PHP
// Obsługuje zarówno serwer WWW jak i CLI
// Ustandaryzowane pobieranie zmiennych z GET, POST, .env, CLI args

/**
 * Standardized variable resolution function
 * Priority: CLI args > GET > POST > ENV > .env file > defaults
 */
function resolveVariable($key, $default = '') {
    global $argc, $argv;
    
    // 1. CLI arguments (highest priority)
    if (php_sapi_name() === 'cli' && $argc > 2) {
        for ($i = 2; $i < $argc; $i++) {
            if (strpos($argv[$i], $key . '=') === 0) {
                return substr($argv[$i], strlen($key) + 1);
            }
        }
    }
    
    // 2. GET parameters
    if (isset($_GET[$key]) && !empty($_GET[$key])) {
        return $_GET[$key];
    }
    
    // 3. POST parameters
    if (isset($_POST[$key]) && !empty($_POST[$key])) {
        return $_POST[$key];
    }
    
    // 4. Environment variables
    $envValue = $_ENV[$key] ?? getenv($key);
    if ($envValue !== false && !empty($envValue)) {
        return $envValue;
    }
    
    // 5. .env file (lowest priority before defaults)
    static $envFile = null;
    if ($envFile === null) {
        $envFile = [];
        $envPath = __DIR__ . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
                    list($envKey, $envVal) = explode('=', $line, 2);
                    $envFile[trim($envKey)] = trim($envVal, '"\' ');
                }
            }
        }
    }
    
    if (isset($envFile[$key]) && !empty($envFile[$key])) {
        return $envFile[$key];
    }
    
    // 6. Default value
    return $default;
}

/**
 * Get all standardized variables for SVG templates
 */
function getTemplateVariables() {
    return [
        '{APP_TITLE}' => resolveVariable('APP_TITLE', 'PHP SVG Calculator'),
        '{APP_DESC}' => resolveVariable('APP_DESC', 'Interactive calculator built with PHP+SVG'),
        '{APP_KEYWORDS}' => resolveVariable('APP_KEYWORDS', 'calculator,php,svg'),
        '{APP_AUTHOR}' => resolveVariable('APP_AUTHOR', 'Developer'),
        '{APP_AUTHOR_URL}' => resolveVariable('APP_AUTHOR_URL', 'https://localhost'),
        '{APP_VERSION}' => resolveVariable('APP_VERSION', '1.0.0'),
        '{APP_BASE_URL}' => resolveVariable('APP_BASE_URL', 'http://localhost:8097'),
        '{CALCULATOR_TITLE}' => resolveVariable('CALCULATOR_TITLE', 'PHP Calculator'),
        '{USER_NAME}' => resolveVariable('USER_NAME', resolveVariable('USER', 'User')),
        '{HOST_NAME}' => resolveVariable('HOSTNAME', 'localhost'),
        '{PHP_VERSION}' => PHP_VERSION,
        '{SERVER_PORT}' => $_SERVER['SERVER_PORT'] ?? '8097',
        '{CURRENT_TIME}' => date('H:i:s'),
        '{CURRENT_DATE}' => date('Y-m-d'),
        '{TIMESTAMP}' => date('Y-m-d H:i:s')
    ];
}

// === CLI MODE ===
if (php_sapi_name() === 'cli') {
    if ($argc < 2) {
        echo "Użycie: php router.php <plik.svg>\n";
        echo "Przykład: php router.php test-minimal1.svg\n";
        exit(1);
    }
    
    $svg_file = $argv[1];
    $svg_path = __DIR__ . '/' . $svg_file;
    
    if (!file_exists($svg_path)) {
        echo "Błąd: Plik '$svg_file' nie istnieje\n";
        exit(1);
    }
    
    if (!preg_match('/\.svg$/', $svg_file)) {
        echo "Błąd: Plik musi mieć rozszerzenie .svg\n";
        exit(1);
    }
    
    // Przygotuj zmienne PHP dostępne w SVG
    $current_time = date('H:i:s');
    $tasks_count = 5;
    
    echo "<!-- SVG+PHP renderowany przez CLI: $svg_file o $current_time -->\n";
    
    // Read and process SVG file with placeholders
    $svg_content = file_get_contents($svg_path);
    
    // Get standardized variables with priority resolution
    $replacements = getTemplateVariables();
    
    $svg_content = str_replace(array_keys($replacements), array_values($replacements), $svg_content);
    
    // Create temporary processed file and include it
    $temp_file = sys_get_temp_dir() . '/processed_' . basename($svg_file);
    file_put_contents($temp_file, $svg_content);
    include $temp_file;
    unlink($temp_file);
    exit(0);
}

// === WEB SERVER MODE ===
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__ . $uri;

// GŁÓWNA FUNKCJA: Przetwarzaj pliki .svg jako PHP
if (preg_match('/\.svg$/', $uri) && file_exists($path)) {
    // Ustaw nagłówki dla SVG
    header('Content-Type: image/svg+xml');
    
    // Przygotuj zmienne PHP dostępne w SVG
    $current_time = date('H:i:s');
    $tasks_count = 5;
    
    // Debug
    error_log("SVG+PHP processed: $uri at $current_time");
    
    // Read and process SVG file with placeholders
    $svg_content = file_get_contents($path);
    
    // Get standardized variables with priority resolution
    $replacements = getTemplateVariables();
    
    $svg_content = str_replace(array_keys($replacements), array_values($replacements), $svg_content);
    
    // Create temporary processed file and include it
    $temp_file = sys_get_temp_dir() . '/processed_' . basename($path);
    file_put_contents($temp_file, $svg_content);
    include $temp_file;
    unlink($temp_file);
    return true;
}

// Standardowe pliki PHP
if (preg_match('/\.php$/', $uri) && file_exists($path)) {
    include $path;
    return true;
}

// Statyczne pliki (obrazy, CSS, JS)
if (file_exists($path) && !is_dir($path)) {
    return false; // Pozwól serwerowi obsłużyć statycznie
}

// 404 dla nieistniejących plików
http_response_code(404);
echo "File not found: $uri";
return true;
?>
