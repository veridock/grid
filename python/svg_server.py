#!/usr/bin/env python3
"""
Python HTTP Server for SVG+Python Templates
Usage: python svg_server.py [port]
Default port: 8094
"""

import http.server
import socketserver
import os
import sys
import urllib.parse
from datetime import datetime
import re

class SVGPythonHandler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        # Parse URL path
        parsed_path = urllib.parse.urlparse(self.path)
        file_path = parsed_path.path.lstrip('/')
        
        # Handle root request
        if not file_path:
            self.send_directory_listing()
            return
        
        # Check if file exists
        if not os.path.exists(file_path):
            self.send_error(404, f"File not found: {file_path}")
            return
        
        # Handle SVG files with Python processing
        if file_path.endswith('.svg'):
            self.process_svg_file(file_path)
        else:
            # Serve other files normally
            super().do_GET()
    
    def process_svg_file(self, file_path):
        """Process SVG file with embedded Python code"""
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Prepare Python variables
            current_time = datetime.now().strftime("%H:%M:%S")
            current_date = datetime.now().strftime("%Y-%m-%d")
            tasks_count = 7  # Example value
            
            # Environment variables for PHP compatibility
            env_vars = {
                'APP_TITLE': os.getenv('APP_TITLE', 'Python SVG Calculator'),
                'APP_DESC': os.getenv('APP_DESC', 'Interactive calculator built with Python+SVG'),
                'CALCULATOR_TITLE': os.getenv('CALCULATOR_TITLE', 'Python Calculator'),
                'USER_NAME': os.getenv('USER_NAME', os.getenv('USER', 'User')),
                'HOST_NAME': os.getenv('HOSTNAME', 'localhost'),
                'PYTHON_VERSION': sys.version.split()[0],
                'SERVER_PORT': '8094'
            }
            
            # Replace environment variable placeholders first
            for key, value in env_vars.items():
                content = content.replace(f'{{{key}}}', str(value))
            
            # Process Python code blocks
            def execute_python_code(match):
                python_code = match.group(1).strip()
                
                # Create execution context
                context = {
                    'current_time': current_time,
                    'current_date': current_date,
                    'tasks_count': tasks_count,
                    'datetime': datetime,
                    'os': os,
                    'sys': sys,
                    # Add env vars to Python context
                    **env_vars
                }
                
                # Capture output
                output = ''
                def print_func(*args, **kwargs):
                    nonlocal output
                    output += ' '.join(str(arg) for arg in args)
                
                context['print'] = print_func
                
                try:
                    # Execute Python code
                    exec(python_code, context)
                    return output
                except Exception as e:
                    return f"<!-- Python Error: {str(e)} -->"
            
            # Replace Python code blocks with their output
            processed_content = re.sub(r'<\?python\s+(.*?)\s*\?>', execute_python_code, content, flags=re.DOTALL)
            
            # Send response
            self.send_response(200)
            self.send_header('Content-Type', 'image/svg+xml')
            self.send_header('Content-Length', str(len(processed_content.encode('utf-8'))))
            self.end_headers()
            
            self.wfile.write(processed_content.encode('utf-8'))
            
        except Exception as e:
            self.send_error(500, f"Error processing SVG: {str(e)}")
    
    def send_directory_listing(self):
        """Send directory listing of SVG files"""
        svg_files = [f for f in os.listdir('.') if f.endswith('.svg')]
        
        html = """
        <!DOCTYPE html>
        <html>
        <head>
            <title>Python SVG Server</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                h1 { color: #3776ab; }
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
            <h1>🐍 Python SVG Server</h1>
            <p>Available SVG files with Python processing:</p>
            <ul class="file-list">
        """
        
        for file in svg_files:
            html += f'<li><a href="{file}">📄 {file}</a></li>'
        
        html += """
            </ul>
            <div class="info">
                <p><strong>Server:</strong> Python SVG Processor</p>
                <p><strong>Time:</strong> {time}</p>
                <p><strong>Directory:</strong> {dir}</p>
            </div>
        </body>
        </html>
        """.format(time=datetime.now().strftime("%Y-%m-%d %H:%M:%S"), dir=os.getcwd())
        
        self.send_response(200)
        self.send_header('Content-Type', 'text/html')
        self.send_header('Content-Length', str(len(html.encode('utf-8'))))
        self.end_headers()
        self.wfile.write(html.encode('utf-8'))

def main():
    # Get port from command line or use default
    port = 8094
    if len(sys.argv) > 1:
        try:
            port = int(sys.argv[1])
        except ValueError:
            print("Invalid port number. Using default port 8094.")
    
    # Create server
    handler = SVGPythonHandler
    httpd = socketserver.TCPServer(("", port), handler)
    
    print(f"🐍 Python SVG Server")
    print(f"📍 Serving at: http://localhost:{port}")
    print(f"📂 Directory: {os.getcwd()}")
    print(f"⏰ Started: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print("🛑 Press Ctrl+C to stop")
    
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\n🛑 Server stopped")
        httpd.shutdown()

if __name__ == "__main__":
    main()
