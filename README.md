# File Monitor PWA

A Progressive Web App for monitoring folders, converting PDFs to SVG, and managing files with automatic thumbnails.

![File Monitor PWA](screenshot.png)
http://localhost:8080/concept-minimal/index.svg
http://localhost:8080/concept-dark/index.svg
http://localhost:8080/concept-cards/index.svg
http://localhost:8080/concept-list/index.svg  
http://localhost:8080/concept-modern/index.svg
## Features

- 📁 **Folder Monitoring** - Watch folders for file changes in real-time
- 🔄 **Auto PDF Conversion** - Automatically convert PDFs to SVG format
- 🖼️ **Thumbnail Generation** - Preview images and documents
- 📱 **PWA Support** - Install as a desktop/mobile app
- 🔍 **Search & Filter** - Find files quickly
- 📊 **Grid & List Views** - Choose your preferred layout

## Quick Start

### Option 1: Two-file setup (Recommended)

1. Save both files:
   - `file-monitor.js` - Server component
   - `file-monitor-app.svg` - Client application

2. Run the server:
   ```bash
   node file-monitor.js
   
   ```

3. Open in browser: http://localhost:3000

### Option 2: Automatic installation

```bash
curl -sL https://example.com/install.sh | bash
```

## Manual Installation

### Prerequisites

- Node.js 14+ installed
- (Optional) `pdf2svg` or ImageMagick for PDF conversion

### Steps

1. **Create project directory:**
   ```bash
   mkdir file-monitor-pwa
   cd file-monitor-pwa
   ```

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

## License

MIT License - Feel free to use and modify!

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