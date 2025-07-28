# SVG Scripting - Multi-Language Support

## Przegląd
Obsługa plików SVG z wbudowanym kodem w różnych językach programowania:

- **PHP** - `<?php ... ?>` z router.php
- **Python** - `<?python ... ?>` z svg_processor.py  
- **Node.js** - `<?js ... ?>` z svg_processor.js

## 🚀 Szybki Start

### PHP (php/)
```bash
cd generated
php -S localhost:8093 -t . router.php
# Dostęp: http://localhost:8093/test-minimal1.svg
# CLI: php router.php test-minimal1.svg
```

### Python (python/)
```bash
cd python
python svg_processor.py todo-manager-python.svg
# Lub zapisz: python svg_processor.py todo-manager-python.svg > output.svg
```

### Node.js (nodejs/)
```bash
cd nodejs
node svg_processor.js todo-manager-nodejs.svg
# Lub zapisz: node svg_processor.js todo-manager-nodejs.svg > output.svg
```

## 📁 Struktura Projektu

```
├── php/
│   ├── router.php                  # PHP router (WWW + CLI)
│   ├── todo-manager-pwa.svg        # SVG z PHP kodem
│   ├── test-minimal1.svg           # Prosty przykład PHP
│   └── README-SVG-PHP.md           # Dokumentacja PHP
├── python/
│   ├── svg_processor.py            # Python processor (CLI)
│   └── todo-manager-python.svg     # SVG z Python kodem
└── nodejs/
    ├── svg_processor.js            # Node.js processor (CLI)
    └── todo-manager-nodejs.svg     # SVG z JavaScript kodem
```

## 🔧 Składnia Języków

### PHP
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?php echo date('H:i:s'); ?></text>
  <text x="10" y="40">Zadań: <?php echo $tasks_count; ?></text>
</svg>
```

### Python  
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?python print(current_time) ?></text>
  <text x="10" y="40">Zadań: <?python print(tasks_count) ?></text>
</svg>
```

### Node.js
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg">
  <text x="10" y="20"><?js print(currentTime) ?></text>
  <text x="10" y="40">Zadań: <?js print(tasksCount) ?></text>
</svg>
```

## 🎯 Dostępne Zmienne

### PHP
- `$current_time` - aktualny czas (H:i:s)
- `$tasks_count` - liczba zadań
- `$_SERVER` - zmienne serwera
- Wszystkie funkcje PHP

### Python
- `current_time` - aktualny czas
- `current_date` - aktualna data
- `tasks_count` - liczba zadań
- `datetime`, `os`, `sys` - moduły Python

### Node.js
- `currentTime` - aktualny czas
- `currentDate` - aktualna data
- `tasksCount` - liczba zadań
- `nodeVersion` - wersja Node.js
- `Date`, `Math`, `JSON` - obiekty JavaScript

## 📊 Przykłady Działania

### PHP Output
```xml
<!-- SVG+PHP renderowany przez CLI: test-minimal1.svg o 18:37:51 -->
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="100">
    <text x="10" y="50">Test SVG+PHP 2025-07-28 18:07:51</text>
</svg>
```

### Python Output
```xml
<!-- SVG+Python renderowany przez CLI: todo-manager-python.svg o 21:00:48 -->
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="500" height="300">
  <title>Todo Manager Python - Czas: 21:00:48</title>
  <text x="20" y="55">Wygenerowano: 2025-07-28 21:00:48</text>
  <text x="20" y="80">Zadań w systemie: 7</text>
</svg>
```

### Node.js Output
```xml
<!-- SVG+Node.js renderowany przez CLI: todo-manager-nodejs.svg o 21:07:12 -->
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400">
  <title>Todo Manager Node.js - Czas: 21:07:12</title>
  <text x="20" y="55">Wygenerowano: 28.07.2025 21:07:12</text>
  <text x="20" y="75">Node.js v23.1.0</text>
</svg>
```

## 💡 Zaawansowane Użycie

### Batch Processing
```bash
# PHP
for file in *.svg; do php router.php "$file" > "output/$file"; done

# Python
for file in *.svg; do python svg_processor.py "$file" > "output/$file"; done

# Node.js
for file in *.svg; do node svg_processor.js "$file" > "output/$file"; done
```

### Pipeline z innymi narzędziami
```bash
# Formatuj XML
php router.php todo-manager-pwa.svg | xmllint --format -

# Optymalizuj SVG
python svg_processor.py todo-manager-python.svg | svgo -

# Konwertuj do PNG  
node svg_processor.js todo-manager-nodejs.svg | convert svg:- output.png
```

## 🔍 Porównanie Języków

| Funkcja | PHP | Python | Node.js |
|---------|-----|--------|---------|
| Serwer WWW | ✅ | ❌ | ❌ |
| CLI | ✅ | ✅ | ✅ |
| Moduły/Biblioteki | ✅ | ✅ | ✅ |
| Złożoność składni | Średnia | Prosta | Prosta |
| Performance | Dobra | Dobra | Bardzo dobra |

## 🛠️ Rozwiązywanie Problemów

### "Plik nie istnieje"
```bash
# Sprawdź ścieżkę
pwd
ls -la *.svg
```

### "Błąd składni"
- Sprawdź czy tags są poprawnie zamknięte: `<?php ... ?>`
- Upewnij się że nie ma białych znaków przed `<?xml`

### "Moduł/funkcja niedostępna"
- PHP: Sprawdź czy extension jest zainstalowany
- Python: `pip install <module>`
- Node.js: `npm install <package>`

## 🎉 Podsumowanie

Wszystkie trzy implementacje pozwalają na:
- ✅ Dynamiczne generowanie SVG
- ✅ Dostęp do zmiennych środowiskowych
- ✅ Wykonywanie obliczeń w SVG
- ✅ Integrację z pipeline'ami
- ✅ Batch processing

**Rekomendacje:**
- **PHP** - dla aplikacji webowych
- **Python** - dla data science i analizy
- **Node.js** - dla wysokiej wydajności i JSON

Wybierz język który najlepiej pasuje do twojego projektu! 🚀
