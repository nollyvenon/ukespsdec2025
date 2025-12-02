# GitHub Actions Deployment

This project uses GitHub Actions to automatically deploy the application to a web server when code is pushed to the main branch.

## Setup Instructions

### 1. Create Secrets in GitHub Repository

Go to your repository settings and add the following secrets:

- `FTP_SERVER` - Your FTP server hostname (e.g., `ftp.yourdomain.com`)
- `FTP_USERNAME` - Your FTP username
- `FTP_PASSWORD` - Your FTP password
- `FTP_PORT` - FTP port (default: 21) 
- `FTP_SERVER_DIR` - Destination directory on server (default: `./`)
- `FTP_PROTOCOL` - Protocol to use: `ftp` or `ftps` (default: `ftp`)

#### For Post-Deployment SSH Commands (Optional):
- `SSH_HOST` - Your server hostname for SSH access
- `SSH_USERNAME` - SSH username
- `SSH_PRIVATE_KEY` - SSH private key for authentication
- `SSH_PORT` - SSH port (default: 22)
- `REMOTE_PROJECT_PATH` - Path to your project on the remote server

### 2. Branch Configuration

The workflow is triggered when code is pushed to the `main` branch. You can change this in `.github/workflows/deploy.yml`:

```yaml
on:
  push:
    branches:
      - main  # Change this to your preferred branch
```

### 3. Optional: Dry Run Mode

To test without actually uploading files, set the `DRY_RUN` secret to `'true'`.

## Deployment Process

When code is pushed to the configured branch:

1. Code is checked out and dependencies are installed
2. Node.js assets are built
3. Files are prepared for deployment (excluding development files)
4. Files are uploaded to the server via FTP
5. Post-deployment commands are executed (SSH access required)
6. Laravel artisan commands for caching and migrations are run

## Post-Deployment Tasks

The workflow automatically runs these commands after deployment:
- Clears and re-caches configuration, routes, and views
- Runs database migrations
- Creates storage symlink
- Sets proper file permissions
- Optimizes Composer autoloader

## Troubleshooting

### Common Issues:
- Make sure all required secrets are properly set
- Verify FTP credentials and server details
- Check that the server directory exists and is writable
- Ensure SSH key format is correct (if using SSH post-deployment)

### FTP Error:
If experiencing FTP errors, verify that:
- Server details are correct
- Firewall allows FTP connections
- Port is accessible
- Credentials are valid

## Security Notes

- Never commit secrets to the repository
- Use strong passwords for FTP accounts
- Consider using SFTP or FTPS for encrypted transfers
- Regularly rotate your deployment credentials

## Modifying the Workflow

You can modify the deployment workflow by editing `.github/workflows/deploy.yml`:
- Change deployment triggers
- Modify build steps
- Adjust file exclusions
- Customize post-deployment commands