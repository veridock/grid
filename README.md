# SVG PWA Tester and Generator

🚀 **Advanced SVG Progressive Web Applications with PHP Backend Integration**

A comprehensive system for creating, testing, and validating SVG-based Progressive Web Applications that embed PHP backend logic, JavaScript interactivity, and JSON metadata while maintaining full schema compliance.

![SVG PWA Tester](https://img.shields.io/badge/Tests-21%20Validations-brightgreen) 
![Compliance](https://img.shields.io/badge/Schema-100%25%20Compliant-success) 
![Applications](https://img.shields.io/badge/Generated%20Apps-4%20Ready-blue)

## 🎯 **Core Features**

- 🧪 **Advanced SVG Testing** - 21-point validation system for schema compliance
- 🔄 **Recursive Validation** - Batch testing of entire directory structures
- 🚀 **PWA Generation** - Automated creation of compliant SVG PWA applications
- 📱 **PHP Integration** - Backend logic embedded as JSON strings (XML-safe)
- ⚡ **JavaScript Embedding** - Interactive functionality within SVG constraints
- 📊 **JSON Metadata** - Application configuration and API definitions
- 🛡️ **Security Compliance** - No external dependencies, self-contained apps
- 📐 **iframe Embedding** - Ready-to-embed applications for any platform

## 📋 **Generated SVG PWA Applications (All 100% Compliant)**

### 🎯 **Production-Ready Applications:**

| Application | Status | Tests Passed | Features |
|-------------|---------|--------------|----------|
| 💰 **Expense Tracker PWA** | ✅ Ready | 21/21 (100%) | Financial tracking, categories, analytics, JSON export |
| 📦 **Inventory Manager PWA** | ✅ Ready | 21/21 (100%) | Stock management, alerts, reporting, database integration |
| 🚀 **Project Manager PWA** | ✅ Ready | 21/21 (100%) | Task management, team collaboration, metrics dashboard |
| 📊 **Test Dashboard PWA** | ✅ Ready | 21/21 (100%) | Analytics dashboard with PHP backend, real-time monitoring |

### 🔧 **Technical Implementation:**

- **Backend PHP Logic**: Embedded as JSON strings to avoid XML conflicts
- **JavaScript Interactivity**: Inline SVG-compatible event handling
- **JSON Metadata**: Complete application configuration and API definitions
- **Schema Compliance**: All applications pass 21 rigorous validation tests
- **iframe Ready**: Immediate embedding capability for any platform

## 🚀 **Quick Start**

### **Option 1: Test Existing Applications**

```bash
# Test a single SVG PWA file
php tester/index.php generated/expense-tracker-pwa.svg

# Test all SVG files recursively
php tester/index.php /path/to/svg/directory
```

### **Option 2: Generate New SVG PWA**

```bash
# Interactive PWA generator
php generator/svg-pwa-generator.php

# Follow prompts for app type, features, and backend integration
```

### **Option 3: Serve via PHP Backend**

```bash
# Start the backend server
php -S localhost:8080 -t php-backend/
php -S localhost:8091 -t generated/
php -S localhost:8092 -t generated/ router.php
# Open in browser: http://localhost:8080
```

## 🔧 **Installation & Requirements**

### **Prerequisites**

- **PHP 7.4+** with extensions: `simplexml`, `mbstring`, `fileinfo`
- **CLI Environment** for running tester and generator scripts
- **Modern Browser** supporting SVG, JavaScript, and PWA features
- **Local File System** with write permissions for generated apps

### **Project Structure**

```
svg-pwa-project/
├── tester/
│   ├── index.php              # CLI tester with 21 validation points
│   └── SVGPWATester.php       # Core validation logic
├── generator/
│   ├── svg-pwa-generator.php  # Interactive PWA generator
│   └── templates/             # PWA templates and patterns
├── generated/
│   ├── expense-tracker-pwa.svg    # ✅ 100% compliant
│   ├── inventory-manager-pwa.svg  # ✅ 100% compliant
│   ├── project-manager-pwa.svg    # ✅ 100% compliant
│   └── test-dashboard.svg         # ✅ 100% compliant
├── correct/
│   ├── devmind.svg           # Gold standard reference
│   ├── example.svg           # Schema baseline
│   └── test_svg_calculator.svg # Pattern template
├── php-backend/
│   ├── index.php             # Backend server with dashboard
│   └── api/                  # RESTful API endpoints
└── README.md                 # This documentation
```

### **Installation Steps**

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd svg-pwa-project
   ```

2. **Verify PHP requirements:**
   ```bash
   php --version  # Should be 7.4+
   php -m | grep -E "(simplexml|mbstring|fileinfo)"  # Check extensions
   ```

3. **Test the system:**
   ```bash
   # Run a quick validation
   php tester/index.php generated/expense-tracker-pwa.svg
   ```

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
php tester/index.php generated/expense-tracker-pwa.svg

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
php tester/index.php generated/inventory-manager-pwa.svg

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
php tester/index.php generated/project-manager-pwa.svg

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
php tester/index.php generated/test-dashboard.svg

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
   node file-monitor.js
   ```

## Usage

### Adding Folders to Monitor

1. Click the "+ Add Folder" button
2. Enter the full path to the folder
3. The folder will appear in the sidebar

### Converting PDFs

- **Single file**: Click the blue "SVG" button on any PDF
- **Batch convert**: Click "⚡ Convert PDFs" to convert all PDFs in the current folder

### Views

- **Grid View**: Visual thumbnails in a 5x5 grid
- **List View**: Detailed file information in a table

### Keyboard Shortcuts

- `Ctrl/Cmd + F` - Search files
- `Ctrl/Cmd + R` - Refresh current folder

## Features in Detail

### Auto-monitoring
When you add a folder, the app watches for:
- New files added
- Files modified
- Files deleted

New PDF files are automatically converted to SVG upon detection.

### Thumbnail Generation
The app generates thumbnails for:
- Images (PNG, JPG, JPEG)
- SVG files
- PDF files (first page)

### File Management
- Click any file to open it
- Search files by name
- Filter by file type
- Sort by date or name

## Configuration

### Environment Variables

- `PORT` - Server port (default: 3000, auto-increments if busy)

### File Types Supported

- PDF documents
- SVG graphics
- PNG images
- JPG/JPEG images

## Troubleshooting

### Port Already in Use
The app automatically finds an available port if 3000 is busy.

### No Thumbnails Showing
Install the `sharp` package:
```bash
npm install sharp
```

### PDF Conversion Not Working
Install PDF conversion tools:
```bash
# Try pdf2svg first (faster)
sudo apt-get install pdf2svg

# Or ImageMagick as fallback
sudo apt-get install imagemagick
```

### Cannot Monitor Folders
Install the `chokidar` package:
```bash
npm install chokidar
```

## How It Works

The application consists of two parts:

1. **Server (`file-monitor.js`)**
   - HTTP server with API endpoints
   - File system monitoring
   - PDF conversion
   - Thumbnail generation

2. **Client (`file-monitor-app.svg`)**
   - SVG-based user interface
   - Can be opened directly or served
   - Auto-connects to server
   - PWA capabilities

### Architecture

```
┌─────────────────┐     ┌─────────────────┐
│   Browser       │────▶│   Node.js       │
│   (SVG App)     │◀────│   Server        │
└─────────────────┘     └─────────────────┘
                              │
                              ▼
                        ┌─────────────┐
                        │ File System │
                        └─────────────┘
```

## Development

### Project Structure
```
file-monitor-pwa/
├── file-monitor.js       # Server component
├── file-monitor-app.svg  # Client application
├── package.json          # Dependencies
└── README.md            # This file
```

### API Endpoints

- `GET /` - Serve SVG application
- `GET /api/folders` - List monitored folders
- `POST /api/folders` - Add folder to monitor
- `GET /api/files?folder=path` - List files in folder
- `POST /api/convert-pdf` - Convert PDF to SVG
- `GET /api/thumbnail?file=path` - Get file thumbnail
- `POST /api/remove-folder` - Remove folder from monitoring




### Creating a New PWA.SVG App

1. Create a new directory in `examples/`
2. Create your `.pwa.svg` file with the following structure:
   ```svg
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 600">
     <!-- SVG content -->
     <foreignObject width="100%" height="100%">
       <xhtml:div>
         <!-- Your HTML/CSS/JS here -->
       </xhtml:div>
     </foreignObject>
     <script><![CDATA[
       // Your JavaScript here
     ]]></script>
   </svg>
   ```
   
## License

Apache 2.0 License - Feel free to use and modify!

## Contributing

Pull requests welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## Credits

Built with:
- Node.js
- SVG
- Progressive Web App technologies
- Optional: chokidar, sharp, pdf2svg