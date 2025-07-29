#!/bin/bash
# RoadRunner Setup Script for PHP-SVG Processing
# High-performance application server with persistent workers

set -e

echo "🚀 Setting up RoadRunner for PHP-SVG processing..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check PHP version
check_php() {
    if ! command -v php &> /dev/null; then
        echo -e "${RED}PHP is not installed. Please install PHP 8.0 or higher.${NC}"
        exit 1
    fi
    
    PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
    echo -e "${GREEN}Found PHP version: $PHP_VERSION${NC}"
    
    if [[ $(echo "$PHP_VERSION < 8.0" | bc -l) -eq 1 ]]; then
        echo -e "${RED}PHP 8.0 or higher is required. Current version: $PHP_VERSION${NC}"
        exit 1
    fi
}

# Install Composer if not available
install_composer() {
    if ! command -v composer &> /dev/null; then
        echo -e "${YELLOW}Installing Composer...${NC}"
        curl -sS https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
        sudo chmod +x /usr/local/bin/composer
    fi
    echo -e "${GREEN}Composer is available${NC}"
}

# Install PHP dependencies
install_dependencies() {
    echo -e "${YELLOW}Installing PHP dependencies...${NC}"
    composer install --no-dev --optimize-autoloader
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Dependencies installed successfully${NC}"
    else
        echo -e "${RED}Failed to install dependencies${NC}"
        exit 1
    fi
}

# Download RoadRunner binary
download_roadrunner() {
    echo -e "${YELLOW}Downloading RoadRunner binary...${NC}"
    
    # Detect architecture
    ARCH=$(uname -m)
    case $ARCH in
        x86_64)
            ARCH="amd64"
            ;;
        aarch64|arm64)
            ARCH="arm64"
            ;;
        *)
            echo -e "${RED}Unsupported architecture: $ARCH${NC}"
            exit 1
            ;;
    esac
    
    # Detect OS
    OS=$(uname -s | tr '[:upper:]' '[:lower:]')
    
    # Download latest release
    LATEST_VERSION=$(curl -s https://api.github.com/repos/roadrunner-server/roadrunner/releases/latest | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')
    DOWNLOAD_URL="https://github.com/roadrunner-server/roadrunner/releases/download/${LATEST_VERSION}/roadrunner-${LATEST_VERSION#v}-${OS}-${ARCH}.tar.gz"
    
    echo -e "${GREEN}Downloading RoadRunner ${LATEST_VERSION} for ${OS}-${ARCH}...${NC}"
    curl -L $DOWNLOAD_URL | tar -xz
    
    if [ -f "roadrunner" ]; then
        chmod +x roadrunner
        mv roadrunner rr
        echo -e "${GREEN}RoadRunner binary installed as 'rr'${NC}"
    else
        echo -e "${RED}Failed to download RoadRunner binary${NC}"
        exit 1
    fi
}

# Create directories
create_directories() {
    echo -e "${YELLOW}Creating directories...${NC}"
    mkdir -p logs
    mkdir -p src
    touch logs/rr.log
    touch logs/rr_errors.log
}

# Create systemd service
create_service() {
    echo -e "${YELLOW}Creating systemd service...${NC}"
    
    sudo tee /etc/systemd/system/roadrunner-svg-php.service > /dev/null <<EOF
[Unit]
Description=RoadRunner SVG-PHP Server
After=network.target
Wants=network.target

[Service]
Type=simple
User=$USER
Group=$USER
WorkingDirectory=$(pwd)
ExecStart=$(pwd)/rr serve
ExecReload=/bin/kill -USR1 \$MAINPID
Restart=always
RestartSec=5s
StandardOutput=journal
StandardError=journal
SyslogIdentifier=roadrunner-svg-php
KillMode=mixed
KillSignal=SIGTERM

# Security settings
NoNewPrivileges=true
PrivateTmp=true
ProtectSystem=strict
ReadWritePaths=$(pwd)
ProtectHome=true

[Install]
WantedBy=multi-user.target
EOF

    sudo systemctl daemon-reload
    echo -e "${GREEN}Systemd service created${NC}"
}

# Test configuration
test_config() {
    echo -e "${YELLOW}Testing RoadRunner configuration...${NC}"
    
    if ./rr -c .rr.yaml serve &
    then
        RR_PID=$!
        sleep 3
        
        # Test if server is responding
        if curl -s http://localhost:8097 > /dev/null; then
            echo -e "${GREEN}✅ RoadRunner server is responding${NC}"
            kill $RR_PID
        else
            echo -e "${RED}❌ RoadRunner server is not responding${NC}"
            kill $RR_PID 2>/dev/null || true
            exit 1
        fi
    else
        echo -e "${RED}❌ Failed to start RoadRunner server${NC}"
        exit 1
    fi
}

# Create test script
create_test_script() {
    cat > test.php << 'EOF'
<?php
/**
 * Test script for RoadRunner SVG-PHP setup
 */

echo "🧪 Testing RoadRunner SVG-PHP setup...\n";

// Test 1: Check if RoadRunner binary exists
if (file_exists('./rr')) {
    echo "✅ RoadRunner binary found\n";
} else {
    echo "❌ RoadRunner binary not found\n";
    exit(1);
}

// Test 2: Check configuration file
if (file_exists('.rr.yaml')) {
    echo "✅ Configuration file found\n";
} else {
    echo "❌ Configuration file not found\n";
    exit(1);
}

// Test 3: Check worker file
if (file_exists('worker.php')) {
    echo "✅ Worker file found\n";
} else {
    echo "❌ Worker file not found\n";
    exit(1);
}

// Test 4: Check PHP files directory
if (is_dir('../php')) {
    echo "✅ PHP files directory found\n";
} else {
    echo "❌ PHP files directory not found\n";
    exit(1);
}

// Test 5: Check autoloader
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer autoloader found\n";
    require 'vendor/autoload.php';
} else {
    echo "❌ Composer autoloader not found\n";
    exit(1);
}

echo "\n🎉 All tests passed! RoadRunner is ready to serve PHP-SVG files.\n";
echo "Start the server with: ./rr serve\n";
echo "Test URL: http://localhost:8097/calculator.svg\n";
EOF
}

# Main installation
main() {
    echo -e "${GREEN}Starting RoadRunner setup...${NC}"
    
    check_php
    install_composer
    install_dependencies
    download_roadrunner
    create_directories
    create_service
    create_test_script
    
    echo -e "${GREEN}✅ RoadRunner setup complete!${NC}"
    echo
    echo -e "${YELLOW}To test the setup:${NC}"
    echo "  php test.php"
    echo
    echo -e "${YELLOW}To start the server:${NC}"
    echo "  ./rr serve"
    echo
    echo -e "${YELLOW}To start as service:${NC}"
    echo "  sudo systemctl start roadrunner-svg-php"
    echo "  sudo systemctl enable roadrunner-svg-php"
    echo
    echo -e "${YELLOW}To monitor workers:${NC}"
    echo "  ./rr workers -i"
    echo
    echo -e "${YELLOW}Test URLs:${NC}"
    echo "  http://localhost:8097/calculator.svg"
    echo "  http://localhost:8097/todo-manager-pwa.svg"
    echo
    echo -e "${YELLOW}Monitoring:${NC}"
    echo "  Metrics: http://localhost:2112/metrics"
    echo "  Status: http://localhost:2114/health"
}

# Run main function
main "$@"
