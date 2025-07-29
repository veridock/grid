# Multi-stage Docker build for PHP-SVG processing
# Optimized for production deployment with minimal attack surface

# Build stage
FROM php:8.2-fpm-alpine AS builder

# Install build dependencies
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    gcc \
    g++ \
    make \
    pkgconfig \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-install \
    opcache \
    pcntl \
    sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Clean up build dependencies
RUN apk del .build-deps

# Production stage
FROM php:8.2-fpm-alpine AS production

LABEL maintainer="VeriDock Grid Team"
LABEL description="Docker container for PHP-SVG processing with security hardening"
LABEL version="1.0.0"

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    tzdata \
    && rm -rf /var/cache/apk/*

# Copy PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Create application user for security
RUN addgroup -g 1001 appgroup && \
    adduser -D -u 1001 -G appgroup appuser

# Create necessary directories
RUN mkdir -p \
    /var/log/nginx \
    /var/log/php-fpm \
    /var/log/supervisor \
    /var/run/php-fpm \
    /var/cache/nginx \
    /app/php \
    /app/logs \
    && chown -R appuser:appgroup /app \
    && chown -R appuser:appgroup /var/log \
    && chown -R appuser:appgroup /var/run \
    && chown -R appuser:appgroup /var/cache/nginx

# Copy application files
COPY --chown=appuser:appgroup ../php/ /app/php/

# Copy configuration files
COPY --chown=root:root docker/nginx.conf /etc/nginx/nginx.conf
COPY --chown=root:root docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=root:root docker/php.ini /usr/local/etc/php/php.ini
COPY --chown=root:root docker/supervisord.conf /etc/supervisord.conf
COPY --chown=appuser:appgroup docker/entrypoint.sh /app/entrypoint.sh

# Make entrypoint executable
RUN chmod +x /app/entrypoint.sh

# Security hardening
RUN chmod 644 /etc/nginx/nginx.conf \
    && chmod 644 /usr/local/etc/php-fpm.d/www.conf \
    && chmod 644 /usr/local/etc/php/php.ini \
    && chmod 644 /etc/supervisord.conf

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8097/health.php || exit 1

# Switch to non-root user
USER appuser

# Expose port
EXPOSE 8097

# Set working directory
WORKDIR /app

# Environment variables
ENV PHP_FPM_USER=appuser
ENV PHP_FPM_GROUP=appgroup
ENV PHP_MEMORY_LIMIT=128M
ENV PHP_MAX_EXECUTION_TIME=30
ENV PHP_UPLOAD_MAX_FILESIZE=10M
ENV PHP_POST_MAX_SIZE=10M
ENV NGINX_WORKER_PROCESSES=auto
ENV NGINX_WORKER_CONNECTIONS=1024

# Volume for logs
VOLUME ["/app/logs"]

# Start services via supervisor
ENTRYPOINT ["/app/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf", "-n"]
