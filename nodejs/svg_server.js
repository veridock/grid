#!/usr/bin/env node
/**
 * Node.js HTTP Server for SVG+JavaScript Templates
 * Usage: node svg_server.js [port]
 * Default port: 8095
 */

const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

class SVGJavaScriptHandler {
    constructor() {
        this.port = process.argv[2] || 8095;
        this.startTime = new Date();
    }

    handleRequest(req, res) {
        const parsedUrl = url.parse(req.url, true);
        const filePath = parsedUrl.pathname.substring(1); // Remove leading slash

        // Handle root request
        if (!filePath) {
            this.sendDirectoryListing(res);
            return;
        }

        // Check if file exists
        if (!fs.existsSync(filePath)) {
            this.sendError(res, 404, `File not found: ${filePath}`);
            return;
        }

        // Handle SVG files with JavaScript processing
        if (filePath.endsWith('.svg')) {
            this.processSVGFile(filePath, res);
        } else {
            // Serve other files normally
            this.serveStaticFile(filePath, res);
        }
    }

    processSVGFile(filePath, res) {
        try {
            const content = fs.readFileSync(filePath, 'utf-8');
            
            // Prepare JavaScript variables
            const currentTime = new Date().toLocaleTimeString('pl-PL');
            const currentDate = new Date().toLocaleDateString('pl-PL');
            const tasksCount = 8; // Example value
            const nodeVersion = process.version;

            // Process JavaScript code blocks
            const processedContent = content.replace(/<\?js\s+(.*?)\s*\?>/gs, (match, jsCode) => {
                try {
                    let output = '';
                    const print = (text) => { output += text; };
                    
                    // Execute JavaScript code with available variables
                    eval(jsCode);
                    
                    return output;
                } catch (error) {
                    return `<!-- JavaScript Error: ${error.message} -->`;
                }
            });

            // Send response
            res.writeHead(200, {
                'Content-Type': 'image/svg+xml',
                'Content-Length': Buffer.byteLength(processedContent, 'utf-8')
            });
            res.end(processedContent);

        } catch (error) {
            this.sendError(res, 500, `Error processing SVG: ${error.message}`);
        }
    }

    serveStaticFile(filePath, res) {
        try {
            const content = fs.readFileSync(filePath);
            const ext = path.extname(filePath);
            const contentType = this.getContentType(ext);
            
            res.writeHead(200, {
                'Content-Type': contentType,
                'Content-Length': content.length
            });
            res.end(content);
        } catch (error) {
            this.sendError(res, 500, `Error serving file: ${error.message}`);
        }
    }

    sendDirectoryListing(res) {
        try {
            const files = fs.readdirSync('.').filter(file => file.endsWith('.svg'));
            
            const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Node.js SVG Server</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 40px; }
                    h1 { color: #68a063; }
                    .file-list { list-style: none; padding: 0; }
                    .file-list li { margin: 10px 0; }
                    .file-list a { 
                        display: block; 
                        padding: 10px; 
                        background: #f0f0f0; 
                        text-decoration: none; 
                        border-radius: 5px; 
                    }
                    .file-list a:hover { background: #e0e0e0; }
                    .info { color: #666; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h1>🟢 Node.js SVG Server</h1>
                <p>Available SVG files with JavaScript processing:</p>
                <ul class="file-list">
                    ${files.map(file => `<li><a href="${file}">📄 ${file}</a></li>`).join('')}
                </ul>
                <div class="info">
                    <p><strong>Server:</strong> Node.js SVG Processor</p>
                    <p><strong>Version:</strong> ${process.version}</p>
                    <p><strong>Time:</strong> ${new Date().toLocaleString('pl-PL')}</p>
                    <p><strong>Directory:</strong> ${process.cwd()}</p>
                    <p><strong>Started:</strong> ${this.startTime.toLocaleString('pl-PL')}</p>
                </div>
            </body>
            </html>
            `;

            res.writeHead(200, {
                'Content-Type': 'text/html',
                'Content-Length': Buffer.byteLength(html, 'utf-8')
            });
            res.end(html);
        } catch (error) {
            this.sendError(res, 500, `Error listing directory: ${error.message}`);
        }
    }

    sendError(res, code, message) {
        res.writeHead(code, { 'Content-Type': 'text/plain' });
        res.end(`Error ${code}: ${message}`);
    }

    getContentType(ext) {
        const types = {
            '.svg': 'image/svg+xml',
            '.html': 'text/html',
            '.css': 'text/css',
            '.js': 'text/javascript',
            '.json': 'application/json'
        };
        return types[ext] || 'text/plain';
    }

    start() {
        const server = http.createServer((req, res) => {
            this.handleRequest(req, res);
        });

        server.listen(this.port, () => {
            console.log('🟢 Node.js SVG Server');
            console.log(`📍 Serving at: http://localhost:${this.port}`);
            console.log(`📂 Directory: ${process.cwd()}`);
            console.log(`⏰ Started: ${new Date().toLocaleString('pl-PL')}`);
            console.log('🛑 Press Ctrl+C to stop');
        });

        // Handle graceful shutdown
        process.on('SIGINT', () => {
            console.log('\n🛑 Server stopped');
            server.close(() => {
                process.exit(0);
            });
        });
    }
}

// Start server
const handler = new SVGJavaScriptHandler();
handler.start();
