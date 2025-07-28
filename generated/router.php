<?php
// Router script dla serwera PHP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__ . $uri;

// Jeśli plik .svg istnieje, przetwórz go jako PHP
if (preg_match('/\.svg$/', $uri) && file_exists($path)) {
    header('Content-Type: image/svg+xml');
    include $path;
    return true;
}

// Jeśli plik istnieje i nie jest PHP, serwuj go normalnie
if (file_exists($path) && !is_dir($path)) {
    return false; // Serwuj plik statycznie
}

// Dla innych przypadków, spróbuj znaleźć plik PHP
if (preg_match('/\.php$/', $uri) && file_exists($path)) {
    include $path;
    return true;
}

// 404 dla nieistniejących plików
http_response_code(404);
echo "File not found";
return true;
?>
