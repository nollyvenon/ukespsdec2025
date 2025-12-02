# Ukesps Application Deployment Guide

## Table of Contents
1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [GitHub Secrets Configuration](#github-secrets-configuration)
4. [Workflow Customization](#workflow-customization)
5. [Post-Deployment Tasks](#post-deployment-tasks)
6. [Troubleshooting](#troubleshooting)

## Overview

The Ukesps application includes an automated deployment system using GitHub Actions. The system deploys the application to a web server via FTP when changes are pushed to the main branch.

## Prerequisites

### Server Requirements
- Web server with PHP 8.3+ support
- FTP access to the web server
- (Optional) SSH access for post-deployment tasks
- MySQL or PostgreSQL database

### Repository Setup
- GitHub repository with the Ukesps codebase
- GitHub secret management access

## GitHub Secrets Configuration

### Required FTP Secrets

Set these in your GitHub repository under Settings → Secrets and variables → Actions:

```
FTP_SERVER=ftp.yourdomain.com
FTP_USERNAME=your_ftp_username
FTP_PASSWORD=your_ftp_password
```

### Optional FTP Secrets

```
FTP_PORT=21                    # Default: 21
FTP_SERVER_DIR=./              # Default: ./
FTP_PROTOCOL=ftp               # Options: ftp, ftps. Default: ftp
DRY_RUN=false                  # Options: true, false. Default: false
DANGER_DROP_CONNECTION=false   # Default: false
```

### SSH Post-Deployment Secrets (Optional)

For post-deployment tasks like running Laravel commands:

```
SSH_HOST=yourserver.com
SSH_USERNAME=your_ssh_username
SSH_PRIVATE_KEY=-----BEGIN OPENSSH PRIVATE KEY-----
# your private key here
-----END OPENSSH PRIVATE KEY-----
SSH_PORT=22
REMOTE_PROJECT_PATH=/path/to/your/project
```

## Workflow Customization

### 1. Branch Configuration

Modify `.github/workflows/deploy.yml` to trigger on different branches:

```yaml
on:
  push:
    branches:
      - main      # Your main branch
      - develop   # Optional: development branch
    tags:
      - 'v*'      # Optional: release tags
```

### 2. File Exclusions

The workflow excludes development files. You can modify the exclusion list in the `Prepare deployment files` step:

```bash
rsync -av --progress . deploy/ \
--exclude deploy/ \
--exclude .git/ \
--exclude .github/ \
--exclude .env \
# ... add more exclusions as needed
```

### 3. Build Process

Customize the build process in the workflow:

```yaml
- name: Build assets
  run: |
    npm run build
    # Add additional build commands as needed
```

### 4. Deployment Commands

Post-deployment SSH commands can be customized:

```yaml
script: |
  cd ${{ secrets.REMOTE_PROJECT_PATH }}
  # Add custom commands here
  php artisan config:cache
  php artisan migrate --force
  # etc.
```

## Post-Deployment Tasks

The deployment workflow automatically executes these tasks:

### Laravel Optimization
- Configuration caching
- Route caching
- View caching
- Event caching
- Migration execution
- Storage linking

### Security & Permissions
- File permission setup
- Ownership configuration
- Autoloader optimization

### Example Post-Deployment Script
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan migrate --force
php artisan storage:link
composer dump-autoload --optimize
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## Database Migration Considerations

### Automatic Migrations
The workflow automatically runs `php artisan migrate --force` to apply database changes. This is suitable for development but consider production implications.

### Manual Migration
To disable automatic migrations, comment out the migration command in the workflow.

## Security Best Practices

### Secret Management
- Never hardcode credentials in the workflow file
- Rotate secrets regularly
- Use specific GitHub repository secrets
- Restrict access to the repository

### FTP Security
- Use FTPS (FTP over SSL) when possible
- Limit FTP user permissions
- Monitor FTP access logs
- Use strong passwords

### SSH Security (if applicable)
- Use SSH keys instead of passwords
- Limit SSH user permissions
- Use dedicated deployment user
- Enable SSH access logs

## Troubleshooting

### Common Issues

#### 1. Authentication Failure
```
Error: Cannot connect to FTP server
```
- Verify `FTP_SERVER`, `FTP_USERNAME`, and `FTP_PASSWORD`
- Check network connectivity to the FTP server
- Verify the FTP server is running and accepting connections

#### 2. Permission Denied
```
Error: Permission denied to write to directory
```
- Ensure the FTP user has write permissions to `FTP_SERVER_DIR`
- Check if the target directory exists
- Verify sufficient disk space

#### 3. Deployment Hangs
```
Action hung during file transfer
```
- Check `DANGER_DROP_CONNECTION` setting
- Try setting `DRY_RUN` to `'true'` to test without actual upload
- Verify file sizes and limits

#### 4. Post-Deployment Failures
```
SSH: Command failed
```
- Verify SSH connection details
- Check if the SSH user has necessary permissions
- Validate the `REMOTE_PROJECT_PATH`

### Debugging Steps

1. **Dry Run Test**: Set `DRY_RUN: 'true'` to test without uploading
2. **Check Logs**: Review GitHub Actions logs for detailed error messages
3. **Manual Test**: Try FTP connection manually to verify credentials
4. **File Verification**: Ensure all necessary files are included/excluded correctly

### Debug Mode

Add a debug step to the workflow:

```yaml
- name: Debug information
  run: |
    echo "Running on branch: ${{ github.ref }}"
    echo "Repository: ${{ github.repository }}"
    ls -la
```

## Rollback Procedures

### To implement automatic rollback:
- Set up a backup strategy before deployment
- Create a rollback workflow
- Implement health checks post-deployment

## Environment-Specific Configurations

### Staging vs Production
Use different branches or workflow triggers:

```yaml
on:
  push:
    branches:
      - main      # Production
    tags:
      - 'release-*'  # Alternative for production releases
```

### Multiple Environments
Create separate workflow files:
- `.github/workflows/deploy-staging.yml`
- `.github/workflows/deploy-production.yml`
- Use different secret sets for each environment

This deployment system provides a robust, automated way to deploy your Ukesps application to your web server while maintaining security and reliability.