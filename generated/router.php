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
    
    echo "<!-- SVG+PHP renderowany przez CLI: $svg_file o $current_time -->\n";
    
    // Wykonaj plik SVG jako PHP i wyświetl wynik
    include $svg_path;
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
    
    // Przetwórz plik SVG jako PHP
    include $path;
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
