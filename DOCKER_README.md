# 🐳 Docker Setup - Quick Start

## Get Started in 3 Steps

### 1. Update Database Configuration
**Windows:**
```cmd
update-db-config.bat
```

**Linux/Mac:**
```bash
chmod +x update-db-config.sh
./update-db-config.sh
```

This changes the database host from `localhost` to `db` for Docker networking.

### 2. Start Docker Containers
```bash
docker-compose up -d
```

### 3. Access Application
- **Hospital System**: http://localhost:1111
- **phpMyAdmin**: http://localhost:8080

## What's Included

- ✅ PHP 8.1 with Apache
- ✅ MySQL 8.0 Database
- ✅ phpMyAdmin (Database Management)
- ✅ Persistent Storage in `./docker-data/`  
- ✅ Live file updates (no rebuild needed)

## Volume Storage Locations

All data persists on your physical machine:

```
your-project/
├── docker-data/
│   └── mysql/              ← Database files (persistent)
├── uploads/                ← User uploads (persistent)
├── reports/                ← Generated reports (persistent)
└── (all .php files)       ← Live-synced to container
```

## Useful Commands

```bash
# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Rebuild after Dockerfile changes
docker-compose up -d --build

# Access database shell
docker exec -it alvas_hospital_db mysql -uroot -prootpassword

# Backup database
docker exec alvas_hospital_db mysqldump -uroot -prootpassword shesayur_alvas_hospital > backup.sql
```

## Credentials

**Database:**
- Root Password: `rootpassword`
- User: `shesayur_alvas_user`
- Password: `TXDy4iKYMtBTer2j`
- Database: `shesayur_alvas_hospital`

**phpMyAdmin:**
- URL: http://localhost:8080
- Username: `root`
- Password: `rootpassword`

## Troubleshooting

**Port 1111 already in use?**
Edit `docker-compose.yml` and change:
```yaml
ports:
  - "YOUR_NEW_PORT:1111"
```

**Database not connecting?**
Ensure you ran the `update-db-config` script first.

**Need detailed help?**
See [docker-setup-instructions.md](docker-setup-instructions.md) for complete documentation.

---

🚀 **You're all set!** Your hospital management system is now Dockerized with persistent volumes on port 1111.
