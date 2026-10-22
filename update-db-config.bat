@echo off
REM update-db-config.bat - Updates database host for Docker environment (Windows)

echo Updating database configuration for Docker environment...

REM Backup original file
copy model\dbconnect.php model\dbconnect.php.backup >nul

REM Update localhost to db using PowerShell
powershell -Command "(Get-Content model\dbconnect.php) -replace \"\\$this->dbHost = 'localhost'\", \"\\$this->dbHost = 'db'\" | Set-Content model\dbconnect.php"

echo [DONE] Database host updated from 'localhost' to 'db'
echo [DONE] Backup saved as model\dbconnect.php.backup
echo.
echo To revert changes, run:
echo   copy model\dbconnect.php.backup model\dbconnect.php
pause
