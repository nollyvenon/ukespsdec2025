# Ukesps Installation Guide for Plesk Server

## Table of Contents
1. [Server Requirements](#server-requirements)
2. [Prerequisites](#prerequisites)
3. [Step-by-Step Installation](#step-by-step-installation)
4. [Database Setup](#database-setup)
5. [PHP Configuration](#php-configuration)
6. [File Permissions](#file-permissions)
7. [Environment Configuration](#environment-configuration)
8. [Final Steps](#final-steps)
9. [Troubleshooting](#troubleshooting)

## Server Requirements

Before installing Ukesps, ensure your Plesk server meets the following requirements:

- **Operating System**: Linux (Ubuntu 20.04+, CentOS 7+, Debian 10+)
- **PHP Version**: 8.3 or higher
- **Database**: MySQL 8.0+ or MariaDB 10.4+
- **Web Server**: Apache 2.4+ with mod_rewrite or Nginx
- **Memory**: Minimum 512MB RAM (1GB+ recommended)
- **Storage**: Minimum 500MB free space

## Prerequisites

### 1. Enable Required PHP Extensions
Through Plesk:
1. Go to Domains → yourdomain.com → PHP Settings
2. Enable the following extensions:
   - curl
   - dom
   - fileinfo
   - gd
   - json
   - mbstring
   - openssl
   - pdo
   - tokenizer
   - xml
   - zip
   - intl
   - bcmath

### 2. Command Line Access
Ensure you have SSH access to your Plesk server.

## Step-by-Step Installation

### Step 1: Access Your Domain Directory
```bash
# Connect to your server via SSH
ssh your_username@your_server_ip

# Navigate to your domain's document root
cd /var/www/vhosts/yourdomain.com/httpdocs
# OR if using subdomain structure:
cd /var/www/vhosts/yourdomain.com/subdomains/ukesp/httpdocs
```

### Step 2: Download Ukesps Application
```bash
# Option A: If you have the application files locally, upload via SFTP
# Option B: If the code is in a git repository:
git clone https://github.com/your-repo/ukesps.git .

# If you're setting up in a subdirectory:
mkdir ukesps && cd ukesps
git clone https://github.com/your-repo/ukesps.git .
```

### Step 3: Install PHP Dependencies
```bash
# Navigate to your application directory
cd /var/www/vhosts/yourdomain.com/httpdocs

# Verify PHP version
php -v

# Install Composer if not already available
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# If you get memory limit errors:
php -d memory_limit=-1 /usr/local/bin/composer install --no-dev --optimize-autoloader
```

### Step 4: Install Node.js Dependencies and Build Assets
```bash
# Check if Node.js is available
node -v

# If Node.js is not installed, contact your hosting provider or check if it's available through Plesk
# In Plesk, go to Domains → yourdomain.com → Applications → Node.js Manager (if available)

# If Node.js is available via command line:
npm install
npm run build

# OR for development:
npm install
npm run dev
```

## Database Setup

### Step 1: Create Database via Plesk
1. Go to Plesk → Domains → yourdomain.com → Databases
2. Click "Add New Database"
3. Choose a database name (e.g., `ukesps_db`)
4. Select MySQL or MariaDB
5. Create a database user or use the default
6. Note the database credentials

### Step 2: Configure Database via Command Line
```bash
# Navigate to your application directory
cd /var/www/vhosts/yourdomain.com/httpdocs

# Copy the example environment file
cp .env.example .env
```

## PHP Configuration

### Step 1: Check and Adjust PHP Settings
```bash
# Check current PHP settings
php -i | grep -E "(memory_limit|upload_max_filesize|post_max_size|max_execution_time)"

# Note: Laravel typically requires:
# - memory_limit: 256M or higher
# - upload_max_filesize: 64M or higher
# - post_max_size: 64M or higher
# - max_execution_time: 60 or higher
```

### Step 2: Set Up PHP Configuration (if allowed)
```bash
# Create a custom PHP configuration file in your application root
echo "memory_limit = 512M" > .user.ini
echo "upload_max_filesize = 64M" >> .user.ini
echo "post_max_size = 64M" >> .user.ini
echo "max_execution_time = 300" >> .user.ini
```

## File Permissions

### Set Proper Permissions
```bash
# Navigate to application directory
cd /var/www/vhosts/yourdomain.com/httpdocs

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Set executable permissions for artisan and other scripts
chmod +x artisan
chmod +x composer.phar (if present in root)

# Create and set permissions for storage and bootstrap/cache directories
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions for storage directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (replace 'www-data' with your server's web user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Environment Configuration

### Step 1: Configure Environment Variables
```bash
# Edit the .env file
nano .env
```

Update the following settings in your `.env` file:

```env
APP_NAME="Ukesps"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ukesps_db
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# File storage (use 'public' for local storage)
FILESYSTEM_DISK=public

# Mail settings (configure according to your provider)
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.com
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 2: Generate Application Key
```bash
# Generate the application key
php artisan key:generate --force

# If you get permission errors, run:
sudo -u www-data php artisan key:generate --force
```

### Step 3: Run Database Migrations
```bash
# Run database migrations with seeders
php artisan migrate --force

# If you get errors, try running with specific user:
sudo -u www-data php artisan migrate --force
```

### Step 4: Create Storage Link
```bash
# Create symbolic link for storage
php artisan storage:link

# If running under specific user:
sudo -u www-data php artisan storage:link
```

## Final Steps

### Step 1: Clear Caches
```bash
# Clear various caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 2: Set Up Web Server Configuration

#### For Apache:
Make sure your domain has the proper Document Root pointing to the public directory:
- In Plesk: Domains → yourdomain.com → Document Root
- Set it to: `/var/www/vhosts/yourdomain.com/httpdocs/public`

Create or update `.htaccess` in the public directory (usually already present):
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 3: Configure Plesk Settings

In Plesk:
1. Go to Domains → yourdomain.com
2. Under "Web Hosting Access":
   - Set PHP version to 8.3 or higher
   - Set PHP handler to "PHP-FPM"
3. Under "Directory Privacy":
   - Ensure the `storage` directory is not accessible via web
4. Under "SSL/TLS Certificates":
   - Install SSL certificate for HTTPS

### Step 4: Test Installation
```bash
# Test if artisan works
php artisan list

# Check if your application responds
curl -I https://yourdomain.com

# Check specific pages
curl -I https://yourdomain.com/api/csrf-cookie
```

## Troubleshooting

### Common Issues and Solutions

#### 1. Memory Limit Errors
```bash
# Run commands with increased memory
php -d memory_limit=-1 artisan migrate
```

#### 2. Permission Errors
```bash
# Fix ownership
chown -R www-data:www-data /var/www/vhosts/yourdomain.com/httpdocs

# Or if using different user
chown -R $(whoami):$(whoami) /var/www/vhosts/yourdomain.com/httpdocs
```

#### 3. Database Connection Errors
- Verify database credentials in `.env`
- Check if the database server is accessible
- Confirm the database user has proper permissions

#### 4. 500 Internal Server Error
- Check error logs: `/var/www/vhosts/yourdomain.com/logs/error_log`
- Verify file permissions
- Ensure `storage` and `bootstrap/cache` are writable

#### 5. 404 Errors for Assets
- Ensure the Document Root points to the `public` directory
- Verify `.htaccess` file is present and properly configured

#### 6. PHP Extension Missing
```bash
# Check which extensions are loaded
php -m

# If extensions are missing, contact your hosting provider or install via Plesk:
# Plesk → Domains → yourdomain.com → PHP Settings
```

### Useful Debugging Commands
```bash
# Check PHP configuration
php --ini

# Verify Composer dependencies
composer dump-autoload

# Check environment
php artisan env

# Check configuration cache
php artisan config:cache

# Check if all required extensions are installed
php artisan about
```

### Setting Up Cron Jobs (if needed)
If your application requires scheduled tasks:
```bash
# Edit crontab
crontab -e

# Add Laravel scheduler (replace path with your actual path)
* * * * * cd /var/www/vhosts/yourdomain.com/httpdocs && php artisan schedule:run >> /dev/null 2>&1
```

Your Ukesps application should now be successfully deployed on your Plesk server. Access your application at https://yourdomain.com to verify the installation.