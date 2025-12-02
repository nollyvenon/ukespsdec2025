# Ukesps - Educational Platform

## Overview
Ukesps is a comprehensive educational platform that provides event management, course management, job listings, and additional features including blogging and support systems. Built with Laravel 12, it offers a modern, scalable solution for managing educational content and resources.

## Features

### Core Features
- **Events Management**: Create, manage, and register for educational events
- **Courses Management**: Create, enroll in, and track course progress
- **Job Listings**: Post and apply for job opportunities
- **Blog System**: Content publishing platform with categories and comments
- **Support System**: Ticket-based support for users

### Advanced Features
- **Advanced Search**: Multi-criteria search for jobs, events, and courses
- **User Authentication**: Secure login and registration
- **Role-based Access**: Different permissions for users, instructors, and administrators
- **Responsive Design**: Works on all device sizes

## Installation

### Prerequisites
- PHP 8.3 or higher
- Composer
- MySQL or SQLite database
- Web server (Apache/Nginx) or PHP Built-in Server

### Quick Setup
1. Clone the repository or download the files
2. Navigate to the project directory
3. Install dependencies:
   ```bash
   composer install
   npm install
   ```
4. Create environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Configure your database in `.env`
7. Run database migrations:
   ```bash
   php artisan migrate --seed
   ```
8. Create storage link:
   ```bash
   php artisan storage:link
   ```
9. Start the server:
   ```bash
   php artisan serve
   ```

## Configuration

### Environment Variables
Key variables to configure in `.env`:
- `APP_NAME` - Your application name
- `APP_ENV` - Environment (local, production)
- `APP_KEY` - Generated automatically
- `DB_*` - Database connection settings
- `MAIL_*` - Email settings
- `AWS_*` - AWS/S3 settings (if using cloud storage)

### Database Setup
The application supports both MySQL and SQLite databases. Update your `.env` file with the appropriate database configuration.

## Usage

### For Users
1. Register and log in to access all features
2. Browse events, courses, and job listings
3. Use advanced search to find specific content
4. Create content (events, courses, etc.) if you have permissions
5. Manage your profile and enrolled content

### For Administrators
1. Access the admin dashboard
2. Manage users and content
3. Configure system settings
4. Generate reports and analytics

## Directory Structure
```
ukesps/
├── app/                    # Application core
│   ├── Http/               # Controllers, Middleware, Requests
│   ├── Models/             # Eloquent models
│   └── Policies/           # Authorization policies
├── bootstrap/              # Framework startup
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── public/                 # Web root and assets
├── resources/              # Views, raw assets
├── routes/                 # Route definitions
├── storage/                # File storage
├── tests/                  # Test files
└── vendor/                 # Composer dependencies
```

## API Endpoints

### Public Endpoints
- `GET /` - Homepage
- `GET /events` - List events
- `GET /courses` - List courses
- `GET /jobs` - List jobs
- `GET /blog` - List blog posts

### Authenticated Endpoints
- `POST /events` - Create event
- `POST /courses` - Create course
- `POST /jobs` - Create job
- `POST /blog` - Create blog post
- `POST /support` - Create support ticket

## Development

### Running Tests
```bash
php artisan test
```

### Code Standards
The project follows PSR-12 coding standards and Laravel best practices.

### Deployment
For production deployment:
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set proper file permissions

## Documentation
Comprehensive documentation is available in the following files:
- `Documentation.md` - Complete technical and user documentation
- `Technical_Documentation.md` - Developer-focused technical details
- `User_Manual.md` - End-user guide

## Support
For technical issues, check the documentation first. For feature requests or bug reports, contact the development team.

## License
[Specify your license here]

## Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

**Note**: This is a Laravel 12 application with built-in support for events, courses, job listings, blogging, and support systems. For the most up-to-date information about the application, refer to the documentation files.