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