<?php
// Ulepszony router dla bezpośredniej obsługi SVG+PHP
// Obsługuje zarówno serwer WWW jak i CLI

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
    
    // Environment variables for template compatibility
    $APP_TITLE = $_ENV['APP_TITLE'] ?? getenv('APP_TITLE') ?: 'PHP SVG Calculator';
    $APP_DESC = $_ENV['APP_DESC'] ?? getenv('APP_DESC') ?: 'Interactive calculator built with PHP+SVG';
    $CALCULATOR_TITLE = $_ENV['CALCULATOR_TITLE'] ?? getenv('CALCULATOR_TITLE') ?: 'PHP Calculator';
    $USER_NAME = $_ENV['USER_NAME'] ?? getenv('USER_NAME') ?: (getenv('USER') ?: 'User');
    $HOST_NAME = $_ENV['HOSTNAME'] ?? getenv('HOSTNAME') ?: 'localhost';
    $PHP_VERSION = PHP_VERSION;
    $SERVER_PORT = '8097';
    
    echo "<!-- SVG+PHP renderowany przez CLI: $svg_file o $current_time -->\n";
    
    // Read and process SVG file with placeholders
    $svg_content = file_get_contents($svg_path);
    
    // Replace environment variable placeholders
    $replacements = [
        '{APP_TITLE}' => $APP_TITLE,
        '{APP_DESC}' => $APP_DESC,
        '{CALCULATOR_TITLE}' => $CALCULATOR_TITLE,
        '{USER_NAME}' => $USER_NAME,
        '{HOST_NAME}' => $HOST_NAME,
        '{PHP_VERSION}' => $PHP_VERSION,
        '{SERVER_PORT}' => $SERVER_PORT
    ];
    
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
    
    // Environment variables for template compatibility
    $APP_TITLE = $_ENV['APP_TITLE'] ?? getenv('APP_TITLE') ?: 'PHP SVG Calculator';
    $APP_DESC = $_ENV['APP_DESC'] ?? getenv('APP_DESC') ?: 'Interactive calculator built with PHP+SVG';
    $CALCULATOR_TITLE = $_ENV['CALCULATOR_TITLE'] ?? getenv('CALCULATOR_TITLE') ?: 'PHP Calculator';
    $USER_NAME = $_ENV['USER_NAME'] ?? getenv('USER_NAME') ?: (getenv('USER') ?: 'User');
    $HOST_NAME = $_ENV['HOSTNAME'] ?? getenv('HOSTNAME') ?: 'localhost';
    $PHP_VERSION = PHP_VERSION;
    $SERVER_PORT = $_SERVER['SERVER_PORT'] ?? '8097';
    
    // Debug
    error_log("SVG+PHP processed: $uri at $current_time");
    
    // Read and process SVG file with placeholders
    $svg_content = file_get_contents($path);
    
    // Replace environment variable placeholders
    $replacements = [
        '{APP_TITLE}' => $APP_TITLE,
        '{APP_DESC}' => $APP_DESC,
        '{CALCULATOR_TITLE}' => $CALCULATOR_TITLE,
        '{USER_NAME}' => $USER_NAME,
        '{HOST_NAME}' => $HOST_NAME,
        '{PHP_VERSION}' => $PHP_VERSION,
        '{SERVER_PORT}' => $SERVER_PORT
    ];
    
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
