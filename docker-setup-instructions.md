# Docker Setup Instructions - Alva's Hospital Management System

## Prerequisites
- Docker Desktop installed on your machine
- Docker Compose installed (comes with Docker Desktop)

## Quick Start

### 1. Build and Start Containers
```bash
docker-compose up -d
```

This will:
- Build the PHP Apache image
- Start MySQL database
- Start phpMyAdmin (optional)
- Create persistent volumes in `./docker-data/` directory

### 2. Access the Application
- **Main Application**: http://localhost:1111
- **phpMyAdmin**: http://localhost:8080 (for database management)

### 3. Database Configuration

The application is configured to connect to the MySQL container. If you need to update the database connection, edit `model/dbconnect.php`:

```php
$this->dbHost = 'db';  // Docker service name (not 'localhost')
$this->dbUser = 'shesayur_alvas_user';
$this->dbName = 'shesayur_alvas_hospital';
$this->dbPass = 'TXDy4iKYMtBTer2j';
```

**Note**: Change `localhost` to `db` in the dbconnect.php file for Docker networking.

## Volume Persistence

All data is stored in physical directories on your machine:

### Application Files
- **Location**: `./` (current directory)
- **Mounted to**: `/var/www/html` in container
- Any changes to PHP files are immediately reflected

### Database Data
- **Location**: `./docker-data/mysql/`
- **Mounted to**: `/var/lib/mysql` in container
- All database data persists even if containers are removed

### Uploads/Reports
- **Location**: `./uploads/` and `./reports/`
- **Purpose**: Store generated files, reports, and uploads

## Common Commands

### Start Containers
```bash
docker-compose up -d
```

### Stop Containers
```bash
docker-compose down
```

### Stop and Remove All Data (⚠️ Deletes database)
```bash
docker-compose down -v
rm -rf docker-data/
```

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f web
docker-compose logs -f db
```

### Rebuild After Changes
```bash
docker-compose up -d --build
```

### Access Container Shell
```bash
# PHP Container
docker exec -it alvas_hospital_web bash

# MySQL Container
docker exec -it alvas_hospital_db bash
```

### Import Database
```bash
# Copy SQL file to container
docker cp your_database.sql alvas_hospital_db:/tmp/

# Import
docker exec -i alvas_hospital_db mysql -uroot -prootpassword shesayur_alvas_hospital < /tmp/your_database.sql
```

Or place SQL files in `./db/` directory before first run - they auto-import.

### Export Database
```bash
docker exec alvas_hospital_db mysqldump -uroot -prootpassword shesayur_alvas_hospital > backup_$(date +%Y%m%d).sql
```

## Troubleshooting

### Port Already in Use
If port 1111 is already in use, modify `docker-compose.yml`:
```yaml
ports:
  - "YOUR_PORT:1111"
```

### Database Connection Issues
1. Ensure `model/dbconnect.php` uses `db` as hostname (not `localhost`)
2. Check database container is running: `docker-compose ps`
3. Verify credentials in docker-compose.yml match dbconnect.php

### Permission Issues
```bash
# Fix file permissions
docker exec -it alvas_hospital_web chown -R www-data:www-data /var/www/html
docker exec -it alvas_hospital_web chmod -R 755 /var/www/html
```

### Clear Docker Cache
```bash
docker-compose down
docker system prune -a
docker-compose up -d --build
```

## Production Considerations

### Security Enhancements
1. Change default MySQL root password
2. Use environment variables for credentials (create `.env` file)
3. Enable HTTPS with SSL certificates
4. Restrict phpMyAdmin access or remove it

### Performance Optimization
1. Add Redis/Memcached for caching
2. Configure PHP-FPM instead of mod_php
3. Use Nginx as reverse proxy
4. Optimize MySQL configuration

### Backup Strategy
```bash
# Automated daily backup script
#!/bin/bash
docker exec alvas_hospital_db mysqldump -uroot -prootpassword shesayur_alvas_hospital | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

## Environment Variables (Advanced)

Create `.env` file:
```env
DB_HOST=db
DB_NAME=shesayur_alvas_hospital
DB_USER=shesayur_alvas_user
DB_PASS=TXDy4iKYMtBTer2j
DB_ROOT_PASS=rootpassword
WEB_PORT=1111
PMA_PORT=8080
TZ=Asia/Kolkata
```

Update `docker-compose.yml` to use these variables with `${VARIABLE_NAME}` syntax.

## Support

For issues with Docker setup, check:
- Docker Desktop is running
- Sufficient disk space for volumes
- No firewall blocking ports 1111, 3306, 8080
- Docker daemon logs for errors

---

**Created**: February 2026  
**System**: Alva's Hospital Management System  
**Docker Version**: Compatible with Docker 20.10+ and Docker Compose 3.8+
