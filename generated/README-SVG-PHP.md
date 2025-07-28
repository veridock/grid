# SVG+PHP Router - Dokumentacja

## Opis
Router umożliwia uruchamianie plików SVG jako skrypty PHP w dwóch trybach:
1. **Serwer WWW** - dostęp przez przeglądarkę
2. **CLI** - bezpośrednie wykonanie w terminalu

## 🌐 Tryb Serwer WWW

### Uruchomienie serwera:
```bash
php -S localhost:8093 -t generated/ router.php
```

### Dostęp do plików SVG+PHP:
- `http://localhost:8093/test-minimal1.svg`
- `http://localhost:8093/todo-manager-pwa.svg`
- `http://localhost:8093/todo-manager2.svg`

### Przykład:
```bash
# Uruchom serwer
cd generated
php -S localhost:8093 -t . router.php
php -S localhost:8093 router.php

# Otwórz w przeglądarce
curl http://localhost:8093/test-minimal1.svg
# lub
firefox http://localhost:8093/test-minimal1.svg
```

## 💻 Tryb CLI

### Uruchomienie pojedynczego pliku:
```bash
php router.php test-minimal1.svg
php test-minimal1.svg
```

### Składnia:
```bash
php router.php <nazwa_pliku.svg>
```

### Przykłady:
```bash
# Renderuj SVG do konsoli
php router.php test-minimal1.svg

# Zapisz wynik do pliku
php router.php test-minimal1.svg > output.svg

# Wykonaj SVG z PHP kodem
php router.php todo-manager-pwa.svg > rendered-todo.svg
```

## 📁 Struktura Plików

```
generated/
├── router.php              # Router obsługujący SVG+PHP
├── test-minimal1.svg        # Przykładowy plik testowy
├── todo-manager-pwa.svg     # Aplikacja TODO z PHP
├── todo-manager2.svg        # Druga wersja aplikacji
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
php -S localhost:8093 -t generated/ router.php
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
# Sprawdź czy plik istnieje w katalogu generated/
ls -la generated/test-minimal1.svg
```

### "XML declaration error":
- Upewnij się, że pliki SVG zaczynają się od `<?xml version="1.0"...`
- Sprawdź czy nie ma białych znaków przed deklaracją XML

### Port zajęty:
```bash
# Użyj innego portu
php -S localhost:8094 -t generated/ router.php
```
