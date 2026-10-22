# Dockerfile for Alva's Hospital Management System
FROM php:7.4-apache

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache port
RUN sed -i 's/80/1111/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Copy PHP configuration
COPY php-custom.ini /usr/local/etc/php/conf.d/

# Copy entrypoint script
COPY docker-entrypoint-simple.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copy application files
COPY . /var/www/html/

# Create session directory
RUN mkdir -p /var/www/sessions && chmod -R 777 /var/www/sessions

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Fix git safe directory
RUN git config --system --add safe.directory '*'

WORKDIR /var/www/html

EXPOSE 1111

ENTRYPOINT ["docker-entrypoint.sh"]
