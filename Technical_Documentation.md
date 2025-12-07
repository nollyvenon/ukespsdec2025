# Ukesps Technical Documentation

## Architecture Overview

### Framework & Tech Stack
- **Primary Framework**: Laravel 12
- **Language**: PHP 8.3
- **Database**: MySQL/SQLite with Eloquent ORM
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Authentication**: Laravel Breeze/Sanctum
- **File Storage**: Laravel Storage (local/public disk)

### Architecture Pattern
- **MVC Pattern**: Models, Views, Controllers
- **Service Layer**: Business logic separation
- **Repository Pattern**: Data access abstraction
- **Event-Driven**: Laravel Events and Listeners

## Core Components

### 1. Authentication System
```
├── app/Models/User.php
├── app/Http/Controllers/Auth/
├── resources/views/auth/
└── routes/auth.php
```

The application uses Laravel's built-in authentication with Breeze, including:
- Registration and login
- Password reset
- Email verification
- Role-based access control with enhanced permissions for different user types (recruiter, university_manager, event_hoster, student, job_seeker)
- User profile management

### 2. Premium Content & Payment System
```
├── app/Models/PaymentGateway.php
├── app/Models/Transaction.php
├── app/Models/SubscriptionPackage.php
├── app/Models/CvUpload.php
├── app/Models/JobAlert.php
├── app/Http/Controllers/PaymentController.php
├── app/Http/Controllers/CvUploadController.php
├── app/Http/Controllers/JobAlertController.php
├── app/Services/PaymentService.php
├── app/Middleware/RoleMiddleware.php
└── resources/views/payment/
    ├── process.blade.php
    ├── success.blade.php
    └── cancel.blade.php
```

Core payment system includes:
- Multiple payment gateway support (Paystack, Flutterwave, Stripe, Mpesa, etc.)
- Transaction tracking and management
- Subscription packages with different feature sets
- Premium content management for jobs, courses, and events
- CV/Resume management system with parsing and extraction
- Job alert system with automated notifications

### 3. Content Management
```
├── app/Models/JobListing.php
├── app/Models/Course.php
├── app/Models/Event.php
├── app/Models/AffiliatedCourse.php
├── app/Http/Controllers/JobListingsController.php
├── app/Http/Controllers/CoursesController.php
├── app/Http/Controllers/EventsController.php
├── app/Http/Controllers/AffiliatedCoursesController.php
└── app/Services/CvParsingService.php
```

Enhanced content management with:
- Premium content flagging (is_premium, premium_expires_at)
- Sorting algorithms to prioritize premium content
- Featured placement for enhanced visibility
- Revenue stream tracking for different content types
- Advanced search with filtering capabilities
- CV/Resume parsing and extraction with automated skills detection
- Professional Reed.co.uk-style layouts with enhanced UI/UX

### 4. Frontend & UI Components
```
├── resources/views/jobs/index.blade.php
├── resources/views/jobs/show.blade.php
├── resources/views/courses/index.blade.php
├── resources/views/courses/show.blade.php
├── resources/views/events/index.blade.php
├── resources/views/events/show.blade.php
├── resources/views/affiliated-courses/index.blade.php
├── resources/views/affiliated-courses/show.blade.php
├── resources/views/cv/index.blade.php
├── resources/views/cv/show.blade.php
├── resources/views/job-alerts/index.blade.php
├── resources/views/dashboard.blade.php
└── resources/views/layouts/
    ├── app.blade.php
    └── navigation.blade.php
```

UI/UX enhancements include:
- Reed.co.uk style horizontal job and course cards with premium badges
- Professional layout structures with clear information hierarchy
- Enhanced visual indicators (star icons, premium badges, featured labels)
- Dark purple header/footer theme with consistent styling
- Responsive design for all device sizes
- Improved search result layouts
- Better action buttons and call-to-actions
- Modern card-based design patterns

### 5. Event Management Module
```
├── app/Models/Event.php
├── app/Http/Controllers/EventsController.php
├── app/Http/Requests/EventRequest.php
├── app/Policies/EventPolicy.php
├── resources/views/events/
└── database/migrations/create_events_table.php
```

Features:
- Event creation with title, description, dates, location
- Event registration and attendance tracking
- Categorization and search
- Status management (draft, published, ongoing, completed, cancelled)

### 3. Course Management Module
```
├── app/Models/Course.php
├── app/Http/Controllers/CoursesController.php
├── app/Http/Controllers/CourseEnrollmentController.php
├── app/Policies/CoursePolicy.php
├── resources/views/courses/
└── database/migrations/create_courses_table.php
```

Features:
- Course creation with syllabus, prerequisites, duration
- Enrollment management
- Progress tracking
- Instructor assignment
- Rating and reviews

### 4. Job Management Module
```
├── app/Models/JobListing.php
├── app/Http/Controllers/JobListingsController.php
├── app/Http/Controllers/JobApplicationController.php
├── app/Policies/JobListingPolicy.php
├── resources/views/jobs/
└── database/migrations/create_job_listings_table.php
```

Features:
- Job posting with requirements, salary, type
- Application tracking
- Application management
- Status tracking (draft, published, closed)

### 5. Blog System (Newly Added)
```
├── app/Models/BlogPost.php
├── app/Models/BlogCategory.php
├── app/Models/BlogComment.php
├── app/Http/Controllers/BlogController.php
├── resources/views/blog/
└── database/migrations/create_blog_*_tables.php
```

Features:
- Post creation with categories and tags
- Comment system
- Rich text editor
- Search and filtering

### 6. Support System (Newly Added)
```
├── app/Models/SupportTicket.php
├── app/Models/SupportCategory.php
├── app/Models/SupportReply.php
├── app/Http/Controllers/SupportController.php
├── resources/views/support/
└── database/migrations/create_support_*_tables.php
```

Features:
- Ticket creation and management
- Reply system
- Status tracking
- Priority levels
- Category management

## Database Schema

### User Management Tables
- `users` - Core user information
- `user_profiles` - Extended profile information
- `password_reset_tokens` - Password reset tokens
- `sessions` - Session management

### Content Management Tables
- `events` - Events management
- `courses` - Courses management
- `job_listings` - Job listings
- `blog_posts` - Blog posts
- `blog_categories` - Blog categories
- `blog_comments` - Blog comments
- `support_tickets` - Support tickets
- `support_categories` - Support categories
- `support_replies` - Support ticket replies

### Relationship Tables
- `event_registrations` - Event attendance
- `course_enrollments` - Course enrollment
- `job_applications` - Job applications
- `affiliated_course_enrollments` - Affiliated course enrollment

### System Tables
- `settings` - System settings
- `site_settings` - Site settings
- `faqs` - Frequently asked questions
- `contact_messages` - Contact form messages
- `hero_contents` - Hero section content

### Migration Files
All migrations are organized in the `database/migrations/` directory with timestamps for proper execution order.

## Security Features

### Authentication & Authorization
- Laravel's built-in authentication
- Policy-based authorization
- CSRF protection
- Session management
- Password hashing with bcrypt

### Input Validation
- Request validation using FormRequest classes
- Sanitization of user inputs
- XSS protection via Blade's `{{ }}` escaping
- SQL injection prevention via Eloquent ORM

### File Security
- File type validation
- File size limits
- Upload directory isolation
- Malware scanning (if implemented)

## Development Workflow

### Setting Up Development Environment
1. Install PHP 8.3+, Composer, and your preferred database
2. Clone the repository
3. Run `composer install` and `npm install`
4. Create `.env` file and configure database
5. Run `php artisan migrate --seed` for initial data
6. Run `php artisan storage:link` for file storage
7. Start development server with `php artisan serve`

### Code Standards
- PSR-12 coding standards
- Laravel best practices
- Model-View-Controller pattern
- Repository pattern for business logic
- Service classes for complex operations

### Testing
```
# Run all tests
php artisan test

# Run feature tests
php artisan test --testsuite=Feature

# Run unit tests
php artisan test --testsuite=Unit
```

### Deployment Process
1. Run `php artisan config:cache` in production
2. Run `php artisan route:cache` in production
3. Run `php artisan view:cache` in production
4. Set `APP_ENV=production` and `APP_DEBUG=false`
5. Configure web server document root to `public/` directory

## API Endpoints

### Authentication Required Endpoints
```
POST   /events              # Create event
GET    /events/{id}/edit    # Edit event form
PUT    /events/{id}         # Update event
DELETE /events/{id}         # Delete event

POST   /courses             # Create course
GET    /courses/{id}/edit   # Edit course form
PUT    /courses/{id}        # Update course
DELETE /courses/{id}        # Delete course

POST   /jobs                # Create job
GET    /jobs/{id}/edit      # Edit job form
PUT    /jobs/{id}           # Update job
DELETE /jobs/{id}           # Delete job

POST   /blog                # Create blog post
GET    /blog/{slug}/edit    # Edit blog post form
PUT    /blog/{slug}         # Update blog post
DELETE /blog/{slug}         # Delete blog post

GET    /support/create      # Create support ticket form
POST   /support             # Create support ticket
POST   /support/{id}/reply  # Add reply to ticket
```

### Public Endpoints
```
GET    /                    # Home page
GET    /events              # List events
GET    /events/{id}         # View event
GET    /courses             # List courses
GET    /courses/{id}        # View course
GET    /jobs                # List jobs
GET    /jobs/{id}           # View job
GET    /blog                # List blog posts
GET    /blog/{slug}         # View blog post
GET    /support             # User's support tickets
```

## Configuration Files

### Main Configuration Files
- `config/app.php` - Application settings
- `config/database.php` - Database settings
- `config/auth.php` - Authentication settings
- `config/filesystems.php` - File storage settings

### Environment Variables
Key environment variables in `.env`:
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, production)
- `APP_KEY` - Encryption key
- `DB_*` - Database connection settings
- `MAIL_*` - Mail configuration
- `AWS_*` - AWS/S3 configuration (if used)

## Performance Optimization

### Caching Strategy
- Configuration caching with `config:cache`
- Route caching with `route:cache`
- View compilation with `view:cache`
- Database query caching

### Database Optimization
- Eloquent model relationships to minimize queries
- Query optimization with eager loading
- Indexing on frequently queried columns
- Pagination for large datasets

### Frontend Optimization
- Asset minification and compression
- CSS/JS bundling with Laravel Mix
- Image optimization
- Lazy loading where appropriate

## Error Handling & Logging

### Exception Handling
- Centralized exception handling in `app/Exceptions/Handler.php`
- Custom exception classes for specific error types
- Proper error response formatting

### Logging
- Multi-channel logging configuration
- Different log levels (debug, info, warning, error)
- Structured logging for easier analysis

## Maintenance Tasks

### Regular Maintenance
```
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear expired sessions
php artisan session:gc

# Clean up temporary files
php artisan files:clean
```

### Database Maintenance
- Regular backup of production databases
- Index optimization
- Query performance monitoring
- Data archiving for old records

## Deployment Checklist

### Pre-Deployment
- [ ] Run all tests successfully
- [ ] Update version numbers
- [ ] Prepare migration files
- [ ] Test locally with production settings

### On Production
- [ ] Backup current version
- [ ] Deploy new code
- [ ] Run migrations: `php artisan migrate`
- [ ] Clear caches: `php artisan config:clear`
- [ ] Cache config: `php artisan config:cache`
- [ ] Link storage: `php artisan storage:link`

### Post-Deployment
- [ ] Test critical functionality
- [ ] Monitor error logs
- [ ] Verify user access
- [ ] Update documentation if needed

## Troubleshooting Common Issues

### Database Issues
```
# Fix table inconsistencies
php artisan migrate:status
php artisan migrate:rollback
php artisan migrate

# Refresh database (destroys data)
php artisan migrate:refresh --seed
```

### Cache Issues
```
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Recreate caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### File Permission Issues
```
# Correct permissions for Laravel
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

This technical documentation provides a comprehensive overview of the Ukesps application, its architecture, and implementation details for developers working with the system.