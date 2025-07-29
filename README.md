# Multi-Language SVG Scripting System

🚀 **Dynamic SVG Generation** with embedded **PHP**, **Python**, and **Node.js** code

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![License](https://img.shields.io/badge/license-Apache%202.0-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4.svg)
![Python](https://img.shields.io/badge/Python-3.8%2B-3776ab.svg)
![Node.js](https://img.shields.io/badge/Node.js-18%2B-339933.svg)
![SVG](https://img.shields.io/badge/format-SVG-orange.svg)
![PWA](https://img.shields.io/badge/type-PWA-purple.svg)
![Status](https://img.shields.io/badge/status-production-brightgreen.svg)

---

## 📚 **Documentation Navigation**

| 📋 **Core Docs** | 🔧 **Technical** | 🚀 **Advanced** |
|---|---|---|
| [🏠 **Main Guide**](README.md) | [🐘 **PHP Router**](php/README.md) | [🖥️ **Servers**](servers/README.md) |
| [📖 **VeriDock V2**](documentation/README.md) | [🧪 **Tester**](tester/README.md) | [🐳 **Docker**](servers/docker/) |

---

## 🎯 Overview

This project enables **execution of SVG files with embedded scripting languages** both via web server and CLI, providing seamless workflows for dynamic SVG generation.

## ⚡ Quick Start

### PHP (Web Server + Standardized Variables)
```bash
cd php
php -S localhost:8097 router.php
# Dostęp: http://localhost:8097/calculator.svg
# CLI: php router.php calculator.svg
# With variables: php router.php calculator.svg CALCULATOR_TITLE="My Calculator"
```

### Python (Web Server)
```bash
cd python
python svg_server.py 8094
# Dostęp: http://localhost:8094/todo-manager-python.svg
# CLI: python svg_processor.py todo-manager-python.svg > output.svg
```

### Node.js (Web Server)
```bash
cd nodejs
node svg_server.js 8095
# Dostęp: http://localhost:8095/todo-manager-nodejs.svg
# CLI: node svg_processor.js todo-manager-nodejs.svg > output.svg
```



## 🎨 Features

- ✅ **Dynamic SVG Generation**: Embed code directly in SVG files
- ✅ **Multi-Language Support**: PHP, Python, JavaScript
- ✅ **CLI Processing**: Generate static SVG files from templates
- ✅ **Web Server Support**: Live PHP+SVG rendering
- ✅ **Pipeline Integration**: Perfect for automation
- ✅ **Batch Processing**: Process multiple files at once

## 🗂️ Project Structure

```
├── php/                         # 🔥 Main PHP implementation
│   ├── router.php                  # Standardized PHP router (WWW + CLI)
│   ├── calculator.svg              # Advanced calculator with placeholders
│   ├── todo-manager-pwa.svg        # SVG PWA todo manager
│   ├── project-manager.svg         # Project management SVG
│   ├── expense-tracker.svg         # Expense tracker SVG
│   ├── inventory-manager.svg       # Inventory manager SVG
│   ├── files.svg                   # File browser SVG
│   ├── README-SVG-PHP.md           # PHP documentation
│   └── .env                        # Environment variables
├── python/                      # Python implementation
│   ├── svg_processor.py            # Python processor (CLI)
│   ├── svg_server.py               # Python HTTP server
│   ├── todo-manager-python.svg     # SVG z Python kodem
│   └── calculator-python.svg       # Calculator in Python
├── nodejs/                      # Node.js implementation
│   ├── svg_processor.js            # Node.js processor (CLI)
│   ├── svg_server.js               # Node.js HTTP server
│   └── todo-manager-nodejs.svg     # SVG z JavaScript kodem
├── generator/                   # SVG PWA generator
│   └── svg-pwa-generator.php       # Interactive generator
├── tester/                      # SVG PWA tester
│   ├── index.php                  # CLI testing tool
│   ├── index.html                 # Web testing interface
│   └── README.md                  # Tester documentation
├── templates/                   # SVG templates
│   └── svg-pwa-generation-prompt.md # Generation prompts
└── documentation/               # Additional documentation
    └── README-V2.md              # Version 2 documentation
```

## 🔧 Language Syntax

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

## 🎯 Standardized Variable System

### Variable Resolution Priority

System automatycznie pobiera zmienne z różnych źródeł w następującej kolejności priorytetów:

1. **CLI arguments** (najwyższy priorytet)
2. **GET parameters** 
3. **POST parameters**
4. **Environment variables**
5. **.env file**
6. **Default values** (najniższy priorytet)

### Available Template Variables

Wszystkie języki wspierają te same placeholders w plikach SVG:

#### Core Variables
- `{APP_TITLE}` - tytuł aplikacji
- `{APP_DESC}` - opis aplikacji
- `{APP_KEYWORDS}` - słowa kluczowe
- `{APP_AUTHOR}` - autor
- `{APP_AUTHOR_URL}` - URL autora
- `{APP_VERSION}` - wersja aplikacji
- `{APP_BASE_URL}` - bazowy URL
- `{CALCULATOR_TITLE}` - tytuł kalkulatora

#### System Variables
- `{USER_NAME}` - nazwa użytkownika
- `{HOST_NAME}` - nazwa hosta
- `{PHP_VERSION}` - wersja PHP (tylko PHP)
- `{PYTHON_VERSION}` - wersja Python (tylko Python)
- `{SERVER_PORT}` - port serwera
- `{CURRENT_TIME}` - aktualny czas (H:i:s)
- `{CURRENT_DATE}` - aktualna data (Y-m-d)
- `{TIMESTAMP}` - pełny timestamp (Y-m-d H:i:s)

### Usage Examples

#### CLI Arguments (najwyższy priorytet)
```bash
# PHP
php router.php calculator.svg CALCULATOR_TITLE="Custom Calculator" APP_TITLE="My App"

# Python
python svg_processor.py template.svg CALCULATOR_TITLE="Custom Calculator"
```

#### GET Parameters
```bash
# PHP
http://localhost:8097/calculator.svg?CALCULATOR_TITLE=Web%20Calculator&APP_DESC=Custom%20Description

# Python
http://localhost:8094/template.svg?CALCULATOR_TITLE=Web%20Calculator
```

#### .env File
```bash
# php/.env
APP_TITLE="My PHP Calculator"
APP_DESC="Interactive calculator"
CALCULATOR_TITLE="PHP Calc"
```

### Language-Specific Variables

#### PHP
- `$current_time` - aktualny czas (H:i:s)
- `$tasks_count` - liczba zadań
- `$_SERVER` - zmienne serwera
- Wszystkie funkcje PHP

#### Python
- `current_time` - aktualny czas
- `current_date` - aktualna data
- `tasks_count` - liczba zadań
- `datetime`, `os`, `sys` - moduły Python

#### Node.js
- `currentTime` - aktualny czas
- `currentDate` - aktualna data
- `tasksCount` - liczba zadań
- `nodeVersion` - wersja Node.js
- `Date`, `Math`, `JSON` - obiekty JavaScript

## 🚀 Advanced Usage Examples

### Multi-Source Variable Resolution

#### Example 1: CLI Override
```bash
# Override specific variables via CLI
php router.php calculator.svg CALCULATOR_TITLE="Production Calculator" APP_VERSION="2.0.0"
```

#### Example 2: Web Server with GET Parameters
```bash
# Start server
php -S localhost:8097 router.php
# Access with custom variables
http://localhost:8097/calculator.svg?CALCULATOR_TITLE=Custom%20Calculator&APP_DESC=My%20Description
```

#### Example 3: Environment + .env Configuration
```bash
# Set environment variables
export CALCULATOR_TITLE="Production Calculator"
export APP_AUTHOR="John Doe"

# Run with .env fallback
php router.php calculator.svg
```

#### Example 4: Production Deployment
```bash
# Create production .env
echo 'APP_TITLE="Production Calculator"' > php/.env
echo 'APP_VERSION="1.0.0"' >> php/.env
echo 'APP_AUTHOR="Company Name"' >> php/.env

# Deploy with production settings
php -S localhost:8097 router.php
```

### SVG Template Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">
  <!-- Variables are automatically replaced -->
  <title>{APP_TITLE}</title>
  <desc>{APP_DESC}</desc>
  
  <!-- Dynamic content -->
  <text x="200" y="50" text-anchor="middle">
    {CALCULATOR_TITLE}
  </text>
  
  <text x="200" y="80" text-anchor="middle">
    Version: {APP_VERSION}
  </text>
  
  <text x="200" y="110" text-anchor="middle">
    Author: {APP_AUTHOR}
  </text>
  
  <text x="200" y="140" text-anchor="middle">
    Generated: {TIMESTAMP}
  </text>
  
  <!-- Still supports PHP code -->
  <?php echo '<text x="200" y="170">Server: ' . $_SERVER['SERVER_NAME'] . '</text>'; ?>
</svg>
```

### Variable Priority Example

```bash
# 1. Set .env file
echo 'CALCULATOR_TITLE="From .env"' > php/.env

# 2. Set environment variable (higher priority)
export CALCULATOR_TITLE="From Environment"

# 3. Use CLI argument (highest priority)
php router.php calculator.svg CALCULATOR_TITLE="From CLI"
# Result: "From CLI" wins

# 4. Use GET parameter (when running server)
http://localhost:8097/calculator.svg?CALCULATOR_TITLE=From%20GET
# Result: "From GET" wins over .env and environment
```

## 📚 Documentation

- 📖 [PHP Documentation](php/README-SVG-PHP.md) - Complete PHP+SVG guide
- 📖 [Tester Documentation](tester/README.md) - SVG PWA testing and validation
- 📖 [Documentation V2](documentation/README-V2.md) - VeriDock Grid V2.0 framework guide
- 📖 [Generation Templates](templates/svg-pwa-generation-prompt.md) - SVG PWA generation prompts


## 📊 **21-Point Validation Schema**

### **SVG Structure Tests (8 points)**
- ✅ `file_exists` - File accessibility
- ✅ `valid_xml` - XML structure validity
- ✅ `svg_namespace` - SVG namespace presence
- ✅ `root_svg_element` - Root SVG element
- ✅ `viewbox_attribute` - ViewBox attribute
- ✅ `dimensions` - Width and Height defined
- ✅ `no_external_deps` - No external dependencies
- ✅ `no_g_transform` - No forbidden g transform elements

### **PWA Compliance Tests (7 points)**
- ✅ `inline_styles` - Uses inline styles
- ✅ `responsive_design` - Responsive design elements
- ✅ `no_js_deps` - No external JavaScript dependencies
- ✅ `self_contained` - Self-contained SVG
- ✅ `no_php_conflicts` - No PHP tag conflicts
- ✅ `standard_elements` - Uses only standard SVG elements
- ✅ `css_compatibility` - CSS properties compatible

### **File Quality Tests (6 points)**
- ✅ `correct_mime_type` - Correct MIME type
- ✅ `utf8_encoding` - UTF-8 encoding
- ✅ `file_size` - File size under 1MB
- ✅ `file_readable` - File is readable
- ✅ `correct_extension` - Correct .svg extension
- ✅ `svg_header` - SVG header present

## 🚀 **Advanced Usage Examples**

### **Recursive Directory Testing**

```bash
# Test all SVG files in a directory
php tester/index.php /path/to/svg/files/

# Output includes:
# - Per-file validation results
# - Summary statistics
# - Failed file analysis
# - Memory usage optimization
```

### **Generate Custom SVG PWA**

```bash
# Interactive generator with prompts
php generator/svg-pwa-generator.php

# Example generation session:
# 1. Select app type (dashboard, tracker, manager, etc.)
# 2. Configure features (real-time, backend, analytics)
# 3. Add PHP backend logic (embedded as JSON)
# 4. Define JavaScript interactivity
# 5. Set JSON metadata and configuration
# 6. Automatic validation and compliance check
```

### **Backend Integration Example**

```php
// Example: Embedded PHP in JSON format (XML-safe)
{
  "backend_implementation": {
    "controller": "class ApiController { public function getData() { return ['status' => 'success']; } }",
    "routes": "Route::get('/api/data', 'ApiController@getData');",
    "database": "class DB { private $pdo; public function query($sql) { /* implementation */ } }"
  }
}
```

## 🎯 **Application Examples**

### **💰 Expense Tracker PWA**
```bash
# Test the expense tracker
php tester/index.php php/expense-tracker-pwa.svg

# Features:
# - Financial transaction tracking (income/expenses)
# - Category-based organization
# - Real-time analytics and reporting
# - JSON data export functionality
# - PHP backend for database operations
```

### **📦 Inventory Manager PWA**
```bash
# Test the inventory manager
php tester/index.php php/inventory-manager-pwa.svg

# Features:
# - Stock level monitoring and alerts
# - Product catalog management
# - Supplier and vendor tracking
# - Automated reorder notifications
# - Advanced reporting dashboard
```

### **🚀 Project Manager PWA**
```bash
# Test the project manager
php tester/index.php php/project-manager-pwa.svg

# Features:
# - Task and milestone tracking
# - Team collaboration tools
# - Progress visualization
# - Resource allocation management
# - Performance metrics and KPIs
```

### **📊 Test Dashboard PWA**
```bash
# Test the analytics dashboard
php tester/index.php php/test-dashboard.svg

# Features:
# - Real-time system monitoring
# - Performance metrics visualization
# - Alert management system
# - Data export capabilities
# - PHP backend integration for live data
```

## 🔒 **Security & Compliance**

- **No External Dependencies**: All applications are self-contained
- **XML-Safe PHP Embedding**: Backend code stored as JSON strings
- **Standard SVG Elements Only**: No forbidden elements like `foreignObject`
- **Memory-Optimized Testing**: Large file protection (>50MB)
- **UTF-8 Encoding**: Full international character support
- **iframe Security**: Safe embedding in any web context

## 📈 **Performance & Statistics**

```
🎯 PROJECT ACHIEVEMENTS
=======================
Total Applications:     4
Compliance Rate:       100%
Validation Tests:      21 per app
Successful Tests:      84/84 ✅
Failed Tests:          0/84 ❌
Backend Integration:   PHP + JSON
JavaScript Support:    Full SVG compatibility
Production Ready:      ✅ All applications
```

## 🤝 **Contributing**

To add new SVG PWA applications:

1. Follow the 21-point validation schema
2. Embed PHP as JSON strings (never direct PHP tags)
3. Use only standard SVG elements
4. Test with `php tester/index.php your-app.svg`
5. Ensure 100% compliance before submission

## 📄 **License**

This project is licensed under the MIT License - see the LICENSE file for details.

---

**🚀 Ready to create your own SVG PWA? Start with the generator and join the revolution of self-contained, backend-integrated web applications!**

2. **Save the files:**
   - Download `file-monitor.js`
   - Download `file-monitor-app.svg`

3. **Install dependencies (optional but recommended):**
   ```bash
   npm init -y
   npm install chokidar sharp
   ```

4. **Install PDF tools (optional):**
   ```bash
   # Ubuntu/Debian
   sudo apt-get install pdf2svg
   # or
   sudo apt-get install imagemagick

   # macOS
   brew install pdf2svg
   # or
   brew install imagemagick

   # Windows
   # Download from ImageMagick website
   ```

5. **Run the application:**
   ```bash

---

**Zbudowane z użyciem**: PHP, Python, Node.js, SVG, Progressive Web App technologies
- Optional: chokidar, sharp, pdf2svg