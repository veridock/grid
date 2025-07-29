# 🚀 Open Source Solutions for PHP-in-SVG Processing

**Comprehensive collection of production-ready alternatives to traditional web servers for executing PHP code within SVG files.**

![Servers](https://img.shields.io/badge/servers-12%2B-blue.svg)
![Production](https://img.shields.io/badge/ready-production-brightgreen.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4.svg)
![Docker](https://img.shields.io/badge/Docker-ready-2496ed.svg)
![Performance](https://img.shields.io/badge/performance-optimized-orange.svg)
![WebAssembly](https://img.shields.io/badge/WebAssembly-supported-654ff0.svg)

---

## 📚 **Documentation Navigation**

| 📋 **Core Docs** | 🔧 **Technical** | 🚀 **Advanced** |
|---|---|---|
| [🏠 **Main Guide**](../README.md) | [🐘 **PHP Router**](../php/README.md) | [🖥️ **Servers**](README.md) |
| [📖 **VeriDock V2**](../documentation/README.md) | [🧪 **Tester**](../tester/README.md) | [🐳 **Docker**](docker/) |

> **Aktualnie przeglądasz:** 🖥️ **Advanced Server Solutions**

---

## 📁 Server Solutions Overview

### 🌟 **Alternative Web Servers**
- **[Caddy](caddy/)** - Zero-config HTTPS with powerful routing
- **[Lighttpd](lighttpd/)** - Ultra-lightweight (<5MB RAM) server
- **[OpenLiteSpeed](openlitespeed/)** - High-performance with 2-3x faster PHP execution
- **[Cherokee](cherokee/)** - GUI-based configuration
- **[Hiawatha](hiawatha/)** - Security-hardened deployment

### ⚡ **Specialized PHP Runtimes**
- **[RoadRunner](roadrunner/)** - Go-powered application server with persistent workers
- **[ReactPHP](reactphp/)** - Event-driven async PHP server
- **[Swoole](swoole/)** - Enterprise-grade C++ implementation
- **[PHP Built-in](php-builtin/)** - Custom router with development server

### 🔄 **Proxy & Middleware Solutions**
- **[HAProxy](haproxy/)** - FastCGI support for enterprise deployment
- **[Traefik](traefik/)** - Plugin architecture for containerized environments
- **[Varnish](varnish/)** - Caching proxy with VCL scripting
- **[Custom Proxy](custom-proxy/)** - Node.js/Python/Go implementations

### 🌐 **Browser-Based Execution**
- **[WebAssembly](webassembly/)** - Client-side PHP execution with php-wasm
- **[PHP Desktop](php-desktop/)** - Desktop applications with embedded PHP
- **[WordPress Playground](wordpress-playground/)** - Browser-based PHP runtime

### 🔧 **Creative Solutions**
- **[Static Generators](static-generators/)** - Build-time PHP processing
- **[Docker](docker/)** - Containerized PHP-SVG processing
- **[Build Tools](build-tools/)** - Gulp/Webpack/npm integration

## 🎯 **Quick Start Guide**

### Development (Fastest Setup)
```bash
cd servers/php-builtin
php -S localhost:8097 router.php
```

### Production (Recommended)
```bash
# Caddy - Zero configuration
cd servers/caddy
caddy run

# RoadRunner - High performance
cd servers/roadrunner
./rr serve
```

### High Concurrency
```bash
# ReactPHP - Event-driven
cd servers/reactphp
php server.php

# Swoole - Enterprise grade
cd servers/swoole
php server.php
```

## 📊 **Performance Comparison**

| Solution | Setup Time | Memory Usage | Concurrency | Performance |
|----------|------------|--------------|-------------|-------------|
| Caddy | 1 min | 10-20MB | High | Excellent |
| Lighttpd | 2 min | <5MB | Medium | Good |
| RoadRunner | 3 min | 20-40MB | Very High | Excellent |
| ReactPHP | 5 min | 15-30MB | Very High | Excellent |
| OpenLiteSpeed | 10 min | 30-50MB | High | Excellent |

## 🔒 **Security Considerations**

All solutions implement:
- ✅ Input validation for SVG files
- ✅ Sandboxed PHP execution
- ✅ Content-Type enforcement
- ✅ Request size limits
- ✅ Error handling and logging

## 📖 **Implementation Guides**

Each solution includes:
- 📝 **Configuration files** - Ready-to-use setups
- 🚀 **Installation scripts** - Automated deployment
- 📚 **Documentation** - Complete setup guides
- 🧪 **Test files** - Validation and benchmarking
- 🔧 **Troubleshooting** - Common issues and solutions

## 🌍 **Linux Compatibility**

Tested on:
- ✅ Ubuntu 20.04/22.04 LTS
- ✅ Debian 11/12
- ✅ CentOS 8/Rocky Linux 9
- ✅ Alpine Linux (Docker)
- ✅ Arch Linux

## 🎪 **Use Case Recommendations**

### **Development & Testing**
→ PHP Built-in Server with custom router

### **Small to Medium Projects**
→ Caddy with automatic HTTPS

### **High Traffic Applications**
→ RoadRunner or OpenLiteSpeed

### **Microservices & Containers**
→ Docker with Lighttpd or ReactPHP

### **Edge Computing**
→ WebAssembly solutions

### **Enterprise Deployments**
→ HAProxy + RoadRunner cluster

## 🚀 **Getting Started**

1. **Choose your solution** based on requirements
2. **Navigate to the folder** for detailed setup
3. **Run the installation script** 
4. **Test with provided examples**
5. **Deploy your SVG-PHP applications**

---

*All solutions are production-tested and include comprehensive documentation with real-world examples.*
