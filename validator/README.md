# SVG PHP+PWA Validator - Dokumentacja Kompletna

🧪 **Advanced SVG PWA Testing Suite** with **21 comprehensive validation tests**

![Tests](https://img.shields.io/badge/tests-21-blue.svg)
![PWA](https://img.shields.io/badge/PWA-compliant-purple.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4.svg)
![CLI](https://img.shields.io/badge/mode-CLI%2BWEB-green.svg)
![Schema](https://img.shields.io/badge/schema-v2.0-orange.svg)
![Status](https://img.shields.io/badge/status-production-brightgreen.svg)

---

## 📚 **Documentation Navigation**

| 📋 **Core Docs** | 🔧 **Technical** | 🚀 **Advanced** |
|---|---|---|
| [🏠 **Main Guide**](../README.md) | [🐘 **PHP Router**](../php/README.md) | [🖥️ **Servers**](../servers/README.md) |
| [📖 **VeriDock V2**](../documentation/README.md) | [🧪 **Tester**](README.md) | [🐳 **Docker**](../servers/docker/) |

> **Aktualnie przeglądasz:** 🧪 **SVG PWA Tester Documentation**

---

## 📋 Spis Treści
1. [Wprowadzenie](#wprowadzenie)
2. [Instalacja i Konfiguracja](#instalacja-i-konfiguracja)
3. [Jak Używać](#jak-używać)
4. [API Reference](#api-reference)
5. [Schema SVG PWA](#schema-svg-pwa)
6. [Testy i Walidacja](#testy-i-walidacja)
7. [Kompatybilność z Przeglądarkami](#kompatybilność-z-przeglądarkami)
8. [Kompatybilność z Linux](#kompatybilność-z-linux)
9. [Najlepsze Praktyki](#najlepsze-praktyki)
10. [Rozwiązywanie Problemów](#rozwiązywanie-problemów)

---

## 🎯 Wprowadzenie

**SVG PWA Tester** to kompleksowe narzędzie do testowania plików SVG pod kątem kompatybilności z Progressive Web Apps (PWA) oraz integracją z PHP. System sprawdza czy plik SVG spełnia wszystkie wymagania techniczne do poprawnego wyświetlania w przeglądarce i podglądzie w systemach Linux.

### Główne Funkcje:
- ✅ **Walidacja struktury SVG** - sprawdzenie poprawności XML i elementów SVG
- ✅ **Testy kompatybilności PWA** - weryfikacja wymagań Progressive Web App
- ✅ **Integracja z PHP** - sprawdzenie kompatybilności z serwerami PHP
- ✅ **Kompatybilność przeglądarek** - testy wsparcia dla głównych przeglądarek
- ✅ **Podgląd Linux** - weryfikacja wyświetlania w systemach Linux
- ✅ **Interfejs webowy** - intuicyjny interfejs użytkownika
- ✅ **API REST** - programatyczny dostęp do testów

---

## 🚀 Instalacja i Konfiguracja

### Wymagania Systemowe:
- **PHP 7.4+** z rozszerzeniami:
  - `simplexml`
  - `fileinfo`
  - `mbstring`
- **Serwer web** (Apache/Nginx)
- **Przeglądarka** z obsługą JavaScript ES6+

### Instalacja:

1. **Skopiuj pliki do katalogu serwera:**
   ```bash
   cp index.php /var/www/html/
   cp index.html /var/www/html/
   cp svg-pwa-schema.json /var/www/html/
   ```

2. **Ustaw odpowiednie uprawnienia:**
   ```bash
   chmod 644 index.php
   chmod 644 index.html
   chmod 644 svg-pwa-schema.json
   ```

3. **Konfiguracja Apache (.htaccess):**
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^api/test$ index.php [L]
   
   # Dodaj obsługę SVG
   AddType image/svg+xml .svg
   AddType image/svg+xml .svgz
   ```

4. **Konfiguracja Nginx:**
   ```nginx
   location ~ \.svg$ {
       add_header Content-Type image/svg+xml;
   }
   
   location /api/test {
       try_files $uri /index.php;
   }
   ```

---

## 🎮 Jak Używać

### 1. **Interfejs Webowy**

Otwórz w przeglądarce: `http://localhost/index.html`

**Kroki:**
1. Kliknij **"Choose SVG File"** i wybierz plik SVG
2. Sprawdź podgląd pliku w sekcji informacji
3. Kliknij **"Run Tests"** aby uruchomić testy
4. Przejrzyj wyniki w sekcji **"Test Results"**

### 2. **API REST - GET Request**
```bash
curl "http://localhost/index.php?file=path/to/your/file.svg"
```

### 3. **API REST - POST Request**
```bash
curl -X POST http://localhost/index.php \
  -H "Content-Type: application/json" \
  -d '{"file": "path/to/your/file.svg"}'
```

### 4. **Integracja z PHP**
```php
<?php
require_once 'index.php';

$tester = new SVGPWATester();
$results = $tester->testSVGFile('./my-icon.svg');

if ($results['summary']['status'] === 'PASSED') {
    echo "SVG jest gotowy do użycia w PWA!";
} else {
    echo "Znaleziono problemy: " . $results['summary']['failed'];
}
?>
```

---

## 🔌 API Reference

### **Endpoint: GET/POST /** 

#### Parametry GET:
| Parametr | Typ | Opis |
|----------|-----|------|
| `file` | string | Ścieżka do pliku SVG |

#### Parametry POST:
```json
{
  "file": "path/to/file.svg"
}
```

#### Odpowiedź:
```json
{
  "timestamp": "2024-01-20 15:30:45",
  "version": "1.0.0",
  "tests": [
    {
      "name": "file_exists",
      "description": "File exists",
      "passed": true,
      "status": "PASS"
    }
  ],
  "summary": {
    "total": 19,
    "passed": 17,
    "failed": 2,
    "warnings": 1,
    "success_rate": 89.47,
    "status": "PASSED"
  },
  "errors": [],
  "warnings": ["File size is 512KB, consider optimization"]
}
```

#### Kody odpowiedzi:
- **200 OK** - Testy zakończone pomyślnie
- **400 Bad Request** - Brak parametru file
- **404 Not Found** - Plik nie istnieje
- **500 Internal Server Error** - Błąd serwera

---

## 📊 Schema SVG PWA

Schema definiuje wymagania dla plików SVG do użycia w PWA:

### **Struktura SVG:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" 
     viewBox="0 0 100 100" 
     width="100" 
     height="100">
  <!-- Zawartość SVG -->
</svg>
```

### **Wymagane atrybuty:**
- ✅ `xmlns="http://www.w3.org/2000/svg"`
- ✅ `viewBox="x y width height"`
- ✅ `width` i `height`

### **Zabronione elementy:**
- ❌ `<foreignObject>`
- ❌ `<switch>`
- ❌ Zewnętrzne zależności (`href="http://..."`)
- ❌ Zewnętrzne skrypty JavaScript

### **Zalecenia:**
- 📏 Rozmiar pliku < 1MB
- 🎨 Inline CSS styles
- 📱 Responsive design (viewBox)
- 🔒 UTF-8 encoding

---

## 🧪 Testy i Walidacja

### **Lista wszystkich testów:**

| Test | Opis | Krytyczny |
|------|------|-----------|
| `file_exists` | Sprawdza czy plik istnieje | ✅ |
| `valid_xml` | Walidacja struktury XML | ✅ |
| `svg_namespace` | Obecność namespace SVG | ✅ |
| `root_svg_element` | Element root `<svg>` | ✅ |
| `viewbox_attribute` | Atrybut viewBox | ❌ |
| `dimensions` | Width i Height | ❌ |
| `no_external_deps` | Brak zewnętrznych zależności | ✅ |
| `inline_styles` | Style inline | ❌ |
| `responsive_design` | Elementy responsive | ❌ |
| `no_js_deps` | Brak zewnętrznych JS | ✅ |
| `self_contained` | Samowystarczalność | ✅ |
| `no_php_conflicts` | Brak konfliktów PHP | ❌ |
| `correct_mime_type` | Poprawny MIME type | ❌ |
| `utf8_encoding` | Kodowanie UTF-8 | ❌ |
| `standard_elements` | Standardowe elementy SVG | ❌ |
| `css_compatibility` | Kompatybilność CSS | ❌ |
| `file_size` | Rozmiar < 1MB | ❌ |
| `file_readable` | Plik czytelny | ✅ |
| `correct_extension` | Rozszerzenie .svg | ❌ |
| `svg_header` | Nagłówek SVG | ✅ |

### **Kryteria oceny:**
- ✅ **PASSED**: Wszystkie krytyczne testy przeszły + ≥85% ogólnej skuteczności
- ❌ **FAILED**: Jeden lub więcej krytycznych testów nie przeszło
- ⚠️ **WARNINGS**: Problemy niekrytyczne wymagające uwagi

---

## 🌐 Kompatybilność z Przeglądarkami

### **Obsługiwane przeglądarki:**
- ✅ **Chrome/Chromium 80+**
- ✅ **Firefox 75+**
- ✅ **Safari 13+**
- ✅ **Edge 80+**
- ✅ **Opera 67+**

### **Problemy kompatybilności:**
- ❌ **Internet Explorer** - Brak obsługi
- ⚠️ **Stare wersje Safari** - Ograniczona obsługa CSS
- ⚠️ **Mobile browsers** - Problemy z niektórymi filtrami

### **Testowanie w przeglądarce:**
```html
<!DOCTYPE html>
<html>
<head>
    <title>Test SVG</title>
</head>
<body>
    <img src="your-file.svg" alt="Test SVG" />
    <object data="your-file.svg" type="image/svg+xml"></object>
    <iframe src="your-file.svg"></iframe>
</body>
</html>
```

---

## 🐧 Kompatybilność z Linux

### **Obsługiwane aplikacje:**

#### **Menedżery plików:**
- ✅ **Nautilus** (GNOME)
- ✅ **Dolphin** (KDE)
- ✅ **Thunar** (XFCE)
- ✅ **PCManFM** (LXDE)

#### **Przeglądarki grafik:**
- ✅ **Eye of GNOME (eog)**
- ✅ **Gwenview**
- ✅ **feh**
- ✅ **sxiv**

#### **Edytory SVG:**
- ✅ **Inkscape**
- ✅ **GIMP** (z obsługą SVG)

### **Konfiguracja MIME:**
```bash
# Dodaj do ~/.config/mimeapps.list
echo "image/svg+xml=org.gnome.eog.desktop" >> ~/.config/mimeapps.list

# Lub globalnie
sudo cp /usr/share/applications/org.gnome.eog.desktop /usr/share/applications/svg-viewer.desktop
```

### **Generowanie miniatur:**
```bash
# Instalacja thumbnailer dla SVG
sudo apt install librsvg2-bin

# Test generowania miniatury
rsvg-convert -w 128 -h 128 files.svg -o thumbnail.png
```

---

## 💡 Najlepsze Praktyki

### **1. Optymalizacja rozmiaru:**
```bash
# Użyj SVGO do optymalizacji
npm install -g svgo
svgo input.svg -o output.svg
```

### **2. Responsive SVG:**
```xml
<svg xmlns="http://www.w3.org/2000/svg" 
     viewBox="0 0 100 100" 
     width="100%" 
     height="100%"
     preserveAspectRatio="xMidYMid meet">
```

### **3. Accessibility:**
```xml
<svg role="img" aria-labelledby="title">
  <title id="title">Opis ikony</title>
  <!-- Zawartość -->
</svg>
```

### **4. CSS w SVG:**
```xml
<svg xmlns="http://www.w3.org/2000/svg">
  <defs>
    <style type="text/css"><![CDATA[
      .primary { fill: #007bff; }
      .secondary { fill: #6c757d; }
    ]]></style>
  </defs>
  <!-- Zawartość -->
</svg>
```

### **5. Animacje:**
```xml
<svg xmlns="http://www.w3.org/2000/svg">
  <circle r="10" cx="50" cy="50">
    <animate attributeName="r" 
             values="10;20;10" 
             dur="2s" 
             repeatCount="indefinite"/>
  </circle>
</svg>
```

---

## 🔧 Rozwiązywanie Problemów

### **Problem: "File not found"**
```bash
# Sprawdź ścieżkę
ls -la /path/to/file.svg

# Sprawdź uprawnienia
chmod 644 /path/to/file.svg
```

### **Problem: "Invalid XML structure"**
```bash
# Walidacja XML
xmllint --noout your-file.svg

# Sprawdź encoding
file -i your-file.svg
```

### **Problem: "MIME type incorrect"**
```bash
# Sprawdź MIME type
file --mime-type your-file.svg

# Dodaj headers w Apache
echo "AddType image/svg+xml .svg" >> .htaccess
```

### **Problem: "File size too large"**
```bash
# Sprawdź rozmiar
du -h your-file.svg

# Optymalizacja
svgo --multipass your-file.svg
```

### **Problem: "External dependencies detected"**
Sprawdź plik pod kątem:
```xml
<!-- Usuń takie linki -->
<image href="http://example.com/image.png"/>
<use href="http://example.com/icons.svg#icon"/>
```

### **Problem: "Browser compatibility issues"**
```html
<!-- Test w różnych przeglądarkach -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var svg = document.querySelector('svg');
    if (!svg) {
        console.error('SVG not supported or loaded');
    }
});
</script>
```

---

## 📈 Przykład Prawidłowego SVG PWA

```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" 
     viewBox="0 0 24 24" 
     width="24" 
     height="24"
     role="img"
     aria-labelledby="icon-title">
  
  <title id="icon-title">Home Icon</title>
  
  <defs>
    <style type="text/css"><![CDATA[
      .icon-path {
        fill: currentColor;
        stroke: none;
      }
      .icon-bg {
        fill: #f8f9fa;
        stroke: #dee2e6;
        stroke-width: 1;
      }
    ]]></style>
  </defs>
  
  <rect class="icon-bg" x="1" y="1" width="22" height="22" rx="2"/>
  <path class="icon-path" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
  <polyline class="icon-path" points="9,22 9,12 15,12 15,22"/>
</svg>
```

---

## 📞 Wsparcie

Jeśli masz problemy z narzędziem:

1. ✅ Sprawdź tę dokumentację
2. ✅ Uruchom testy diagnostyczne
3. ✅ Sprawdź logi serwera PHP
4. ✅ Przetestuj w różnych przeglądarkach

---

## 📝 Changelog

### v1.0.0 (2024-01-20)
- ✅ Pierwsza wersja testera SVG PWA
- ✅ Kompletna walidacja 19 testów
- ✅ Interfejs webowy HTML/CSS/JS
- ✅ API REST w PHP
- ✅ Schema JSON validation
- ✅ Dokumentacja kompletna

---

**© 2024 VeriDock Grid System - SVG PWA Tester**
