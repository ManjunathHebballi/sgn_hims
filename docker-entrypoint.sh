#!/bin/bash
# Docker entrypoint script to fix permissions before starting Apache

echo "Fixing permissions on startup..."

# Fix git dubious ownership issue (run as root)
git config --system --add safe.directory '*' 2>/dev/null || true

# Create directories if they don't exist
mkdir -p /var/www/sessions
mkdir -p /var/www/html/uploads
mkdir -p /var/www/html/reports

# Fix ownership and permissions
chown -R www-data:www-data /var/www/sessions
chmod -R 775 /var/www/sessions

chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html

# Create log file if it doesn't exist
touch /var/www/html/debug_login.log
chown www-data:www-data /var/www/html/debug_login.log
chmod 664 /var/www/html/debug_login.log

echo "Permissions fixed. Starting Apache..."

# Execute the original entrypoint
exec apache2-foreground
