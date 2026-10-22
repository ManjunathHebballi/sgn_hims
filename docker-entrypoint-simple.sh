#!/bin/bash
# Simple entrypoint to fix runtime permissions

# Ensure required writable directories exist
mkdir -p /var/www/sessions /var/www/html/uploads/store_grn_invoices

# Fix permissions for bind-mounted Docker volumes at runtime
chown -R www-data:www-data /var/www/sessions /var/www/html/uploads 2>/dev/null || true
chmod -R 777 /var/www/sessions 2>/dev/null || true
chmod -R 775 /var/www/html/uploads 2>/dev/null || true

# Start Apache
exec apache2-foreground
