# Ukesps Application Documentation

## Table of Contents
1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Installation & Setup](#installation--setup)
4. [Features Overview](#features-overview)
5. [User Manual](#user-manual)
6. [Developer Guide](#developer-guide)
7. [API Documentation](#api-documentation)
8. [Database Schema](#database-schema)
9. [Troubleshooting](#troubleshooting)
10. [FAQ](#faq)

## Overview
Ukesps is a comprehensive educational platform that provides event management, course management, and job listings along with additional features such as blogging and support systems. Built with Laravel, it offers a modern, scalable solution for managing educational content and resources.

## System Architecture
- **Framework**: Laravel 12
- **Language**: PHP 8.3
- **Database**: MySQL/SQLite
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Sanctum/Breeze

### Key Components
- Events Management System
- Courses Management System  
- Job Listings System
- Blogging Platform
- Support Ticket System
- User Management
- Admin Dashboard

## Installation & Setup

### Prerequisites
- PHP 8.3 or higher
- Composer
- MySQL or SQLite database
- Web server (Apache/Nginx) or PHP Built-in Server

### Installation Steps
1. Clone or download the application files
2. Navigate to the project directory
3. Install dependencies:
   ```bash
   composer install
   npm install
   ```
4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Configure your database settings in `.env`
7. Run database migrations:
   ```bash
   php artisan migrate
   ```
8. Create storage link:
   ```bash
   php artisan storage:link
   ```
9. Start the development server:
   ```bash
   php artisan serve
   ```

## Features Overview

### 1. Events Management
- Create, edit, and manage events
- Event registration and attendance tracking
- Search and filter events
- Event categories and types

### 2. Courses Management
- Create, edit, and manage courses
- Enroll in courses
- Progress tracking
- Course materials and syllabus

### 3. Job Listings
- Post and manage job listings
- Job applications
- Search and filter jobs
- Application management

### 4. Blog System
- Create blog posts and categories
- Comment system
- Rich text editor
- SEO-friendly URLs

### 5. Support System
- Create support tickets
- Ticket management
- Reply system
- Agent assignment

### 6. Premium Features & Payment System
- **CV/Resume Management**: Upload, parse, and manage CVs with automated skill extraction
- **CV Search Engine**: Advanced search functionality for recruiters to find candidates
- **Job Alerts**: Automated notifications when new jobs match user criteria
- **Premium Job Listings**: Enhanced visibility options for job postings
- **Course Promotion**: Paid options to promote courses to more students
- **Featured Events**: Special placement for events to increase attendance
- **University Admission Assistance**: Services to help students apply to universities
- **Direct Recruitment Platform**: Tools for companies to manage their hiring process
- **Payment Gateway Integration**: Support for multiple payment processors (Paystack, Flutterwave, Stripe, Mpesa, etc.)
- **Subscription Packages**: Tiered packages for different user types with varying features
- **Ad Campaign Management**: Advertising platform with payment options
- **CV Parsing and Scoring**: Automatic extraction of skills, experience, and qualifications from uploaded CVs
- **CV Matching Algorithm**: Intelligent matching of CVs to job requirements
- **Revenue Stream Management**: Support for the four key revenue streams:
  - Job posting fees
  - Course promotion services
  - Direct recruitment services
  - University admission assistance

### 7. Reed.co.uk Style Interface
- **Professional Layouts**: Modern, clean interface similar to Reed.co.uk with enhanced card-based designs
- **Horizontal Job Cards**: Detailed job listings with clear information hierarchy
- **Featured Content Badges**: Star icons, premium badges, and enhanced indicators to highlight featured content
- **Consistent Styling**: Professional dark purple header/footer theme with animations
- **Enhanced Search Results**: Improved search interfaces with clear filtering and sorting
- **Course Detail Pages**: Professional course information pages with comprehensive details
- **Interactive Elements**: Hover effects, transitions, and intuitive navigation
- **Responsive Design**: Fully responsive layout that works on all device sizes

### 8. Advanced Admin Features
- **Payment Gateway Configuration**: Admin controls for managing multiple payment processors
- **Subscription Package Management**: Interfaces for configuring and managing subscription packages
- **Content Moderation**: Enhanced admin tools for managing premium content
- **User Role Management**: Advanced controls for managing different user types and permissions

## User Manual

### For Regular Users

#### Homepage Navigation
- Browse events, courses, and job listings from the homepage
- Use search functionality to find specific content
- Create an account to access all features

#### Searching Content
1. Use the search bar on listings pages
2. Apply filters to narrow down results
3. Save searches for future reference

#### Creating Content
- Register and log in to create events, courses, or job listings
- For blog posts and support tickets, navigate to the appropriate sections

#### Managing Enrollments
- View enrolled courses in your profile
- Track progress and completion
- View registered events

### For Admin Users

#### Dashboard Access
- Access admin features via the admin panel
- Manage users, content, and system settings

#### Managing Content
- Approve, edit, or delete user-generated content
- Monitor system activity
- Generate reports

## Developer Guide

### Project Structure
```
ukesps/
├── app/                    # Application core
│   ├── Console/            # Commands
│   ├── Exceptions/         # Exception handlers
│   ├── Http/               # Controllers, Middleware, Requests
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── bootstrap/              # Framework bootstrapping
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── public/                 # Public assets and entry point
├── resources/              # Views, assets, language
├── routes/                 # Route definitions
├── storage/                # File storage
├── tests/                  # Test files
```

### Controllers
- `EventsController` - Manages events
- `CoursesController` - Manages courses  
- `JobListingsController` - Manages job listings
- `BlogController` - Manages blog posts
- `SupportController` - Manages support tickets

### Models
- `Event` - Represents events
- `Course` - Represents courses
- `JobListing` - Represents job listings
- `BlogPost` - Represents blog posts
- `SupportTicket` - Represents support tickets
- `User` - Represents users

### Views
Organized by feature in the `resources/views/` directory:
- `events/` - Event-related views
- `courses/` - Course-related views
- `jobs/` - Job-related views
- `blog/` - Blog-related views
- `support/` - Support-related views

### Adding New Features
1. Create models using: `php artisan make:model ModelName`
2. Create migrations: `php artisan make:migration create_table_name_table`
3. Create controllers: `php artisan make:controller ControllerName`
4. Add routes in `routes/web.php`
5. Create views in `resources/views/`
6. Update any necessary middleware

### Testing
Run tests using:
```bash
php artisan test
```

### Deployment
1. Set production environment in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set proper file permissions

## API Documentation

### Authentication
Most endpoints require authentication using Laravel Sanctum or standard session-based authentication.

### Available Endpoints

#### Events
- `GET /events` - List events with optional search parameters
- `GET /events/{id}` - Get specific event
- `POST /events` - Create new event (auth required)
- `PUT /events/{id}` - Update event (auth required)
- `DELETE /events/{id}` - Delete event (auth required)

#### Courses
- `GET /courses` - List courses with optional search parameters
- `GET /courses/{id}` - Get specific course
- `POST /courses` - Create new course (auth required)
- `PUT /courses/{id}` - Update course (auth required)
- `DELETE /courses/{id}` - Delete course (auth required)

#### Job Listings
- `GET /jobs` - List jobs with optional search parameters
- `GET /jobs/{id}` - Get specific job
- `POST /jobs` - Create new job (auth required)
- `PUT /jobs/{id}` - Update job (auth required)
- `DELETE /jobs/{id}` - Delete job (auth required)

#### Blog
- `GET /blog` - List blog posts
- `GET /blog/{slug}` - Get specific blog post
- `POST /blog` - Create new blog post (auth required)
- `PUT /blog/{slug}` - Update blog post (auth required)
- `DELETE /blog/{slug}` - Delete blog post (auth required)

#### Support
- `GET /support` - List user's support tickets
- `POST /support` - Create new support ticket (auth required)
- `POST /support/{id}/reply` - Add reply to ticket (auth required)

## Database Schema

### Main Tables
- `users` - User accounts and authentication
- `events` - Event management
- `courses` - Course management
- `job_listings` - Job listings
- `blog_posts` - Blog posts
- `blog_categories` - Blog categories
- `support_tickets` - Support tickets
- `support_replies` - Support ticket replies
- `course_enrollments` - User course enrollments
- `event_registrations` - User event registrations
- `job_applications` - Job applications

### Relations
- Users can create many events, courses, jobs, blog posts, and support tickets
- Courses can have many enrollments from users
- Events can have many registrations from users
- Jobs can have many applications from users
- Blog posts belong to categories
- Support tickets belong to categories and can have many replies

## Security & Permissions

### File & Directory Permissions

For optimal security and functionality, ensure the following permissions are set on your server:

#### Web Server Directories
```
storage/                775 (drwxrwxr-x) for user:group (typically www-data:www-data or apache:apache)
bootstrap/cache/        775 (drwxrwxr-x) for user:group
public/storage/         775 (drwxrwxr-x) for user:group
```

#### Specific Permission Requirements
```
storage/logs/           775 (drwxrwxr-x) - Required for logging
storage/framework/      775 (drwxrwxr-x) - Required for cached views, sessions, etc.
storage/app/public/     775 (drwxrwxr-x) - Required for file uploads (CVs, images, etc.)
storage/app/private/    750 (drwxr-x---) - For private files (higher security)
bootstrap/cache/        775 (drwxrwxr-x) - Required for compiled views and configs
```

#### Linux/Unix Permission Setup
```bash
# Main application directories
sudo chown -R www-data:www-data storage public/storage bootstrap/cache
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 public/storage/

# For production environments, consider restricting private files further
sudo chmod 750 storage/app/private/
```

#### Windows Permission Setup
```cmd
icacls storage /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls bootstrap/cache /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls public/storage /grant "IIS_IUSRS:(OI)(CI)F" /T
```

### Application Security Features

#### User Role Permissions
- **Admin**: Full access to all features and user management
- **Recruiter**: Access to job posting, applicant viewing, CV search
- **University Manager**: Course creation, student management, affiliation management
- **Event Host**: Event creation, attendee management, registration tracking
- **Student/Job Seeker**: Course enrollment, event registration, job application, CV management
- **Regular User**: Basic access to browse public content

#### Content Access Controls
- **Public Content**: Accessible to all users and guests
- **Premium Content**: Requires active subscription or specific payment
- **Private CVs**: Only accessible to uploader and authorized recruiters (based on subscription)
- **Draft Content**: Only accessible to creator and admins
- **Published Content**: Available to general audience

#### File Upload Security
- **CV Uploads**: Stored in `storage/app/cvs/` with restricted access
- **Image Uploads**: Stored in `storage/app/images/` with size/type validation
- **Public Assets**: Linked via symbolic links to `public/storage/` directory
- **File Validation**: MIME type checking, file size limits, and virus scanning (recommended)

#### Payment Security
- **PCI Compliance**: Card data never stored locally; processed through external gateways
- **Encryption**: All sensitive payment data encrypted at rest
- **Webhooks**: Secured with signature validation for payment confirmations
- **Transaction Logs**: Full audit trail of all payment transactions

### Environment Variables Security

#### Critical .env Files
```
APP_KEY=              # Laravel application key - keep secret
DB_PASSWORD=          # Database password - restrict access
MAIL_PASSWORD=        # Mail server password - use app-specific passwords
PUSHER_APP_SECRET=    # Pusher service secret - keep secret
STRIPE_SECRET=        # Stripe API secret - keep secret
PAYSTACK_SECRET_KEY=  # Paystack API secret - keep secret
FLUTTERWAVE_SECRET=   # Flutterwave API secret - keep secret
```

#### Secure .env File Permissions
```bash
chmod 600 .env                    # Read/write for owner only
chown www-data:www-data .env      # Owned by web server user
```

### Payment Gateway Security

#### API Key Management
- Store API keys in `.env` file, never in code
- Use different keys for development/production
- Regularly rotate API keys
- Monitor API usage for unusual patterns

#### Transaction Security
- Validate all webhook signatures
- Implement CSRF protection for all payment forms
- Use HTTPS for all payment-related pages
- Sanitize and validate all payment inputs
- Log all payment-related activities for audit purposes

### Upload Security

#### CV/Resume Upload Protection
- File type validation (PDF, DOC, DOCX)
- File size limits (10MB max)
- Virus scanning for uploaded files
- Restricted access based on user roles
- Automatic parsing with security validation

#### Image Upload Protection
- MIME type validation
- Image dimension verification
- File extension validation
- Malware detection (recommended)
- Stored separately from core application files

## Troubleshooting

### Common Issues

#### Authentication Issues
- Ensure middleware is properly configured
- Check if user is logged in
- Verify route authentication requirements

#### Database Issues
- Check database connection in `.env`
- Run migrations if tables are missing: `php artisan migrate`
- Clear configuration cache: `php artisan config:clear`

#### File Upload Issues
- Ensure `storage:link` has been run
- Check file permissions on storage directory
- Verify file upload size limits in php.ini

### Debugging
Enable debugging by setting `APP_DEBUG=true` in `.env` file.

## FAQ

**Q: How do I reset the application?**
A: Run `php artisan migrate:fresh --seed` to reset database and re-run seeders.

**Q: How do I add a new user role?**
A: Modify the User model and add appropriate middleware checks in the controllers.

**Q: How do I customize the theme?**
A: Modify the Tailwind CSS configuration in `tailwind.config.js` and update CSS in `resources/css/app.css`.

**Q: How do I add new search filters?**
A: Update the controller methods to include new filter parameters and add form fields to the views.

**Q: How do I internationalize the application?**
A: Create language files in `resources/lang/` and use Laravel's localization functions.