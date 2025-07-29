# SVG+PHP Router - Dokumentacja

🐘 **Advanced PHP Router** for SVG file processing with **standardized variable system**

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4.svg)
![Router](https://img.shields.io/badge/type-Router-blue.svg)
![CLI](https://img.shields.io/badge/mode-CLI%2BWWW-green.svg)
![Variables](https://img.shields.io/badge/variables-standardized-orange.svg)
![Status](https://img.shields.io/badge/status-production-brightgreen.svg)

---

## 📚 **Documentation Navigation**

| 📋 **Core Docs** | 🔧 **Technical** | 🚀 **Advanced** |
|---|---|---|
| [🏠 **Main Guide**](../README.md) | [🐘 **PHP Router**](README.md) | [🖥️ **Servers**](../servers/README.md) |
| [📖 **VeriDock V2**](../documentation/README.md) | [🧪 **Tester**](../tester/README.md) | [🐳 **Docker**](../servers/docker/) |

> **Aktualnie przeglądasz:** 🐘 **PHP Router Documentation**

---

## Opis
Router umożliwia uruchamianie plików SVG jako skrypty PHP w dwóch trybach:
1. **Serwer WWW** - dostęp przez przeglądarkę
2. **CLI** - bezpośrednie wykonanie w terminalu

## 🌐 Tryb Serwer WWW

### Uruchomienie serwera:
```bash
cd php
php -S localhost:8097 router.php
```

### Dostęp do plików SVG+PHP:
- `http://localhost:8097/calculator.svg`
- `http://localhost:8097/todo-manager-pwa.svg`
- `http://localhost:8097/project-manager.svg`
- `http://localhost:8097/expense-tracker.svg`
- `http://localhost:8097/inventory-manager.svg`
- `http://localhost:8097/files.svg`

### Przykład:
```bash
# Uruchom serwer
cd php
php -S localhost:8097 router.php

# Otwórz w przeglądarce
curl http://localhost:8097/calculator.svg
# lub
firefox http://localhost:8097/calculator.svg
```

## 💻 Tryb CLI

### Uruchomienie pojedynczego pliku:
```bash
cd php
php router.php calculator.svg
php router.php todo-manager-pwa.svg
```

### Składnia:
```bash
php router.php <nazwa_pliku.svg> [VARIABLE=value] [VARIABLE2=value2]
```

### Przykłady:
```bash
# Renderuj SVG do konsoli
php router.php calculator.svg

# Z customowymi zmiennymi
php router.php calculator.svg CALCULATOR_TITLE="My Calculator" APP_VERSION="2.0"

# Zapisz wynik do pliku
php router.php calculator.svg > output.svg

# Wykonaj SVG z PHP kodem
php router.php todo-manager-pwa.svg > rendered-todo.svg
```

## 📁 Struktura Plików

```
php/
├── router.php              # Router obsługujący SVG+PHP z systemem zmiennych
├── calculator.svg          # Kalkulator z placeholders
├── todo-manager-pwa.svg     # Aplikacja TODO z PHP
├── project-manager.svg      # Menedżer projektów
├── expense-tracker.svg      # Śledzenie wydatków
├── inventory-manager.svg    # Zarządzanie inwentarzem
├── files.svg               # Przeglądarka plików
├── .env                    # Zmienne środowiskowe
└── README-SVG-PHP.md        # Ta dokumentacja
```

## 🔧 Funkcje Routera

### Dostępne zmienne PHP w plikach SVG:
- `$current_time` - aktualny czas (H:i:s)
- `$tasks_count` - liczba zadań (przykład: 5)

### Przykład pliku SVG+PHP:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="100">
    <text x="10" y="50">Czas: <?php echo $current_time; ?></text>
    <text x="10" y="70">Zadań: <?php echo $tasks_count; ?></text>
</svg>
```

## ✅ Zalety

### Tryb Serwer WWW:
- ✅ Interaktywność w przeglądarce
- ✅ Automatyczne odświeżanie
- ✅ Poprawny Content-Type: image/svg+xml
- ✅ Obsługa JavaScript w SVG

### Tryb CLI:
- ✅ Batch processing
- ✅ Skryptowanie i automatyzacja
- ✅ Przekierowywanie do plików
- ✅ Integracja z pipeline'ami

## 🚀 Przykłady Użycia

### 1. Development i testowanie:
```bash
# Uruchom serwer deweloperski
php -S localhost:8093 router.php
php -S localhost:8093 -t php/ php/router.php
# Otwórz http://localhost:8093/test-minimal1.svg
```

### 2. Generowanie statycznych plików:
```bash
# Wygeneruj statyczne wersje SVG
php router.php todo-manager-pwa.svg > static/todo-app.svg
php router.php test-minimal1.svg > static/test.svg
```

### 3. Batch processing:
```bash
# Przetwórz wszystkie pliki SVG
for file in *.svg; do
    php router.php "$file" > "rendered/$file"
done
```

## 📝 Błędy i Rozwiązywanie Problemów

### "Plik nie istnieje":
```bash
# Sprawdź czy plik istnieje w katalogu php/
ls -la php/test-minimal1.svg
```

### "XML declaration error":
- Upewnij się, że pliki SVG zaczynają się od `<?xml version="1.0"...`
- Sprawdź czy nie ma białych znaków przed deklaracją XML

### Port zajęty:
```bash
# Użyj innego portu
php -S localhost:8094 -t php/ php/router.php
php -S localhost:8094 router.php
```
