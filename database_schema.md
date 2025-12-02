# Database Schema Design: Events, Courses, and Jobs Management Portal

## Overview
This document outlines the database schema for the Laravel application that includes three main features:
1. Events Registration and Management Portal
2. Course Management Portal
3. Job Management Portal

## Database Tables

### 1. Users Table (Default Laravel users table)
- id (unsignedBigInteger, primary key, autoincrement)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- remember_token (string, nullable)
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 2. User Profiles Table (Extended user information)
- id (unsignedBigInteger, primary key, autoincrement)
- user_id (unsignedBigInteger, foreign key references users.id)
- first_name (string)
- last_name (string)
- phone (string, nullable)
- address (text, nullable)
- date_of_birth (date, nullable)
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 3. Events Table
- id (unsignedBigInteger, primary key, autoincrement)
- title (string) - Name of the event
- description (text) - Detailed description of the event
- start_date (datetime) - When the event starts
- end_date (datetime) - When the event ends
- location (string) - Physical location or online
- max_participants (integer, nullable) - Maximum number of attendees
- registration_deadline (datetime, nullable) - Deadline for registration
- event_image (string, nullable) - URL/path to event image
- event_status (enum: 'draft', 'published', 'cancelled', 'completed') - Current status of the event
- created_by (unsignedBigInteger, foreign key references users.id) - User who created the event
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 4. Event Registrations Table
- id (unsignedBigInteger, primary key, autoincrement)
- event_id (unsignedBigInteger, foreign key references events.id)
- user_id (unsignedBigInteger, foreign key references users.id)
- registration_date (timestamp) - When the registration was made
- attendance_status (enum: 'registered', 'confirmed', 'attended', 'cancelled') - Status of attendance
- payment_status (enum: 'pending', 'paid', 'refunded', 'free') - Payment status for the event
- payment_amount (decimal(10, 2), nullable) - Amount paid for the event
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 5. Courses Table
- id (unsignedBigInteger, primary key, autoincrement)
- title (string) - Name of the course
- description (text) - Detailed description of the course
- duration (integer) - Duration in hours/days/weeks
- level (enum: 'beginner', 'intermediate', 'advanced', 'all_levels') - Difficulty level
- instructor_id (unsignedBigInteger, foreign key references users.id) - Instructor of the course
- start_date (date) - When the course starts
- end_date (date) - When the course ends
- course_image (string, nullable) - URL/path to course image
- course_status (enum: 'draft', 'published', 'ongoing', 'completed', 'cancelled') - Current status of the course
- max_enrollment (integer, nullable) - Maximum number of students
- prerequisites (text, nullable) - Prerequisites for the course
- syllabus (text, nullable) - Detailed syllabus of the course
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 6. Course Enrollments Table
- id (unsignedBigInteger, primary key, autoincrement)
- course_id (unsignedBigInteger, foreign key references courses.id)
- student_id (unsignedBigInteger, foreign key references users.id)
- enrollment_date (timestamp) - When the enrollment was made
- enrollment_status (enum: 'enrolled', 'in_progress', 'completed', 'dropped', 'pending') - Student's status in the course
- completion_percentage (integer, default: 0) - How much of the course is completed
- grade (string, nullable) - Final grade received (A, B, C, etc.)
- certificate_path (string, nullable) - Path to the certificate if issued
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 7. Job Listings Table
- id (unsignedBigInteger, primary key, autoincrement)
- title (string) - Title of the job position
- description (text) - Detailed description of the job
- requirements (text) - Requirements for the position
- responsibilities (text) - Responsibilities of the position
- salary_min (decimal(10, 2), nullable) - Minimum salary
- salary_max (decimal(10, 2), nullable) - Maximum salary
- job_type (enum: 'full_time', 'part_time', 'contract', 'internship', 'remote') - Type of employment
- experience_level (enum: 'entry', 'mid', 'senior', 'executive') - Required experience level
- location (string) - Job location
- posted_by (unsignedBigInteger, foreign key references users.id) - User who posted the job
- application_deadline (date, nullable) - Deadline for applications
- job_status (enum: 'draft', 'published', 'closed', 'cancelled') - Current status of the job listing
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

### 8. Job Applications Table
- id (unsignedBigInteger, primary key, autoincrement)
- job_id (unsignedBigInteger, foreign key references jobs.id)
- applicant_id (unsignedBigInteger, foreign key references users.id)
- application_date (timestamp) - When the application was submitted
- cover_letter (text, nullable) - Cover letter from the applicant
- resume_path (string, nullable) - Path to the resume file
- application_status (enum: 'pending', 'reviewed', 'shortlisted', 'rejected', 'hired') - Status of the application
- applied_position (string) - Position applied for
- created_at (timestamp, nullable)
- updated_at (timestamp, nullable)
- deleted_at (timestamp, nullable) - For soft deletes

## Relationships

### Event Relationships
- Events can have many Event Registrations
- Event Registrations belong to one Event
- Users can register for many Events
- Event Registrations belong to one User

### Course Relationships
- Courses can have many Course Enrollments
- Course Enrollments belong to one Course
- Students (Users) can enroll in many Courses
- Course Enrollments belong to one Student

### Job Relationships
- Jobs can have many Job Applications
- Job Applications belong to one Job
- Applicants (Users) can apply for many Jobs
- Job Applications belong to one Applicant

## Additional Notes
- All tables use soft deletes (deleted_at field) for data integrity
- Foreign key constraints ensure data consistency
- Timestamps (created_at, updated_at) are automatically managed by Laravel
- All user-related foreign keys reference the default Laravel users table
- Enums are used to maintain data consistency for status fields