#!/bin/bash
# update-db-config.sh - Updates database host for Docker environment

echo "Updating database configuration for Docker environment..."

# Backup original file
cp model/dbconnect.php model/dbconnect.php.backup

# Update localhost to db (Docker service name)
sed -i "s/\$this->dbHost = 'localhost'/\$this->dbHost = 'db'/g" model/dbconnect.php

echo "✓ Database host updated from 'localhost' to 'db'"
echo "✓ Backup saved as model/dbconnect.php.backup"
echo ""
echo "To revert changes, run:"
echo "  cp model/dbconnect.php.backup model/dbconnect.php"
