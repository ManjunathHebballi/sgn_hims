@echo off
REM Fix permissions for Docker container (Windows)

echo Fixing permissions...

REM Fix session directory permissions
docker exec sgn_hospital_web chown -R www-data:www-data /var/www/sessions
docker exec sgn_hospital_web chmod -R 775 /var/www/sessions

REM Fix application directory permissions
docker exec sgn_hospital_web chown -R www-data:www-data /var/www/html
docker exec sgn_hospital_web chmod -R 775 /var/www/html

REM Create and fix permissions for log files
docker exec sgn_hospital_web touch /var/www/html/debug_login.log
docker exec sgn_hospital_web chown www-data:www-data /var/www/html/debug_login.log
docker exec sgn_hospital_web chmod 664 /var/www/html/debug_login.log

echo Permissions fixed successfully!
echo.
echo Verifying session directory:
docker exec sgn_hospital_web ls -la /var/www/sessions

pause
