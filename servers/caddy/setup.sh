#!/bin/bash
# Caddy Setup Script for PHP-SVG Processing
# Zero-configuration HTTPS with powerful routing

set -e

echo "🚀 Setting up Caddy for PHP-SVG processing..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   echo -e "${RED}This script should not be run as root${NC}"
   exit 1
fi

# Detect OS
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    if [ -f /etc/debian_version ]; then
        OS="debian"
    elif [ -f /etc/redhat-release ]; then
        OS="redhat"
    elif [ -f /etc/arch-release ]; then
        OS="arch"
    else
        OS="unknown"
    fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
    OS="macos"
else
    OS="unknown"
fi

echo -e "${YELLOW}Detected OS: $OS${NC}"

# Install Caddy
install_caddy() {
    case $OS in
        "debian")
            sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https
            curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
            curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
            sudo apt update
            sudo apt install -y caddy
            ;;
        "redhat")
            dnf install 'dnf-command(copr)' -y
            dnf copr enable @caddy/caddy -y
            dnf install caddy -y
            ;;
        "arch")
            sudo pacman -S caddy --noconfirm
            ;;
        "macos")
            if command -v brew &> /dev/null; then
                brew install caddy
            else
                echo -e "${RED}Homebrew not found. Please install Homebrew first.${NC}"
                exit 1
            fi
            ;;
        *)
            echo -e "${YELLOW}Installing Caddy binary directly...${NC}"
            curl -L "https://github.com/caddyserver/caddy/releases/latest/download/caddy_$(uname -s)_$(uname -m).tar.gz" | tar -xz caddy
            sudo mv caddy /usr/local/bin/
            sudo chmod +x /usr/local/bin/caddy
            ;;
    esac
}

# Install PHP and PHP-FPM
install_php() {
    case $OS in
        "debian")
            sudo apt update
            sudo apt install -y php php-fpm php-cli php-xml php-mbstring php-simplexml
            ;;
        "redhat")
            sudo dnf install -y php php-fpm php-cli php-xml php-mbstring
            ;;
        "arch")
            sudo pacman -S php php-fpm --noconfirm
            ;;
        "macos")
            brew install php
            ;;
        *)
            echo -e "${RED}Please install PHP and PHP-FPM manually${NC}"
            exit 1
            ;;
    esac
}

# Configure PHP-FPM
configure_php_fpm() {
    echo -e "${YELLOW}Configuring PHP-FPM...${NC}"
    
    # Find PHP-FPM socket path
    if [ -S "/run/php/php8.2-fpm.sock" ]; then
        PHP_SOCKET="/run/php/php8.2-fpm.sock"
    elif [ -S "/run/php/php8.1-fpm.sock" ]; then
        PHP_SOCKET="/run/php/php8.1-fpm.sock"
    elif [ -S "/run/php/php8.0-fpm.sock" ]; then
        PHP_SOCKET="/run/php/php8.0-fpm.sock"
    elif [ -S "/var/run/php-fpm/php-fpm.sock" ]; then
        PHP_SOCKET="/var/run/php-fpm/php-fpm.sock"
    else
        echo -e "${RED}PHP-FPM socket not found. Please check PHP-FPM installation.${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}Found PHP-FPM socket: $PHP_SOCKET${NC}"
    
    # Update Caddyfile with correct socket path
    sed -i "s|unix//run/php/php8.2-fpm.sock|unix/$PHP_SOCKET|g" Caddyfile
}

# Create systemd service
create_service() {
    echo -e "${YELLOW}Creating systemd service...${NC}"
    
    sudo tee /etc/systemd/system/caddy-svg-php.service > /dev/null <<EOF
[Unit]
Description=Caddy SVG-PHP Server
After=network.target network-online.target
Requires=network-online.target

[Service]
Type=notify
User=caddy
Group=caddy
ExecStart=/usr/bin/caddy run --config $(pwd)/Caddyfile
ExecReload=/usr/bin/caddy reload --config $(pwd)/Caddyfile
TimeoutStopSec=5s
LimitNOFILE=1048576
LimitNPROC=1048576
PrivateTmp=true
ProtectSystem=full
AmbientCapabilities=CAP_NET_BIND_SERVICE

[Install]
WantedBy=multi-user.target
EOF

    sudo useradd --system --home /var/lib/caddy --create-home --shell /bin/false caddy 2>/dev/null || true
    sudo systemctl daemon-reload
}

# Main installation
main() {
    echo -e "${GREEN}Installing Caddy...${NC}"
    install_caddy
    
    echo -e "${GREEN}Installing PHP...${NC}"
    install_php
    
    echo -e "${GREEN}Configuring PHP-FPM...${NC}"
    configure_php_fpm
    
    echo -e "${GREEN}Creating systemd service...${NC}"
    create_service
    
    # Start PHP-FPM
    case $OS in
        "debian"|"redhat")
            sudo systemctl enable php-fpm
            sudo systemctl start php-fpm
            ;;
        "arch")
            sudo systemctl enable php-fpm
            sudo systemctl start php-fpm
            ;;
    esac
    
    # Create log directory
    sudo mkdir -p /var/log/caddy
    sudo chown caddy:caddy /var/log/caddy
    
    echo -e "${GREEN}✅ Caddy setup complete!${NC}"
    echo
    echo -e "${YELLOW}To start the server:${NC}"
    echo "  caddy run"
    echo
    echo -e "${YELLOW}To start as service:${NC}"
    echo "  sudo systemctl start caddy-svg-php"
    echo "  sudo systemctl enable caddy-svg-php"
    echo
    echo -e "${YELLOW}Test URL:${NC}"
    echo "  http://localhost:8097/calculator.svg"
}

# Run main function
main "$@"
