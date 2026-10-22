#!/bin/bash
# Fix permissions for Docker container

echo "Fixing permissions..."

# Fix session directory permissions
docker exec sgn_hospital_web chown -R www-data:www-data /var/www/sessions
docker exec sgn_hospital_web chmod -R 775 /var/www/sessions

# Fix application directory permissions
docker exec sgn_hospital_web chown -R www-data:www-data /var/www/html
docker exec sgn_hospital_web chmod -R 775 /var/www/html

# Create and fix permissions for log files
docker exec sgn_hospital_web touch /var/www/html/debug_login.log
docker exec sgn_hospital_web chown www-data:www-data /var/www/html/debug_login.log
docker exec sgn_hospital_web chmod 664 /var/www/html/debug_login.log

echo "Permissions fixed successfully!"

# Verify
echo ""
echo "Session directory:"
docker exec sgn_hospital_web ls -la /var/www/sessions

echo ""
echo "Application directory (sample):"
docker exec sgn_hospital_web ls -la /var/www/html | head -20
