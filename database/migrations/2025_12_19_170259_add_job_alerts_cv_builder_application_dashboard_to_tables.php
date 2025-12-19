<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create job alerts table
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('alert_name')->default('My Job Alert');
            $table->json('search_criteria')->nullable(); // JSON of search criteria
            $table->json('keywords')->nullable(); // Keywords to match
            $table->string('job_title_filter')->nullable(); // Job title filter
            $table->string('location_filter')->nullable(); // Location filter
            $table->json('job_types')->nullable(); // Job types (full-time, part-time, etc.)
            $table->decimal('min_salary', 10, 2)->nullable(); // Minimum salary requirement
            $table->decimal('max_salary', 10, 2)->nullable(); // Maximum salary requirement
            $table->json('industries')->nullable(); // Industry preferences
            $table->json('experience_levels')->nullable(); // Experience level preferences
            $table->boolean('is_active')->default(true); // Whether the alert is active
            $table->string('frequency')->default('daily'); // daily, weekly, monthly
            $table->timestamp('last_run')->nullable(); // Last time the alert was checked
            $table->timestamp('next_run')->nullable(); // Next time the alert will run
            $table->integer('jobs_found_count')->default(0); // Number of jobs found in last search
            $table->json('recent_matches')->nullable(); // Store recent job matches
            $table->boolean('email_notification_enabled')->default(true); // Whether to send email notifications
            $table->boolean('push_notification_enabled')->default(false); // Whether to send push notifications
            $table->json('notification_preferences')->nullable(); // Custom notification preferences
            $table->timestamps();
        });

        // Create CV builder templates table
        Schema::create('cv_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // URL-friendly name
            $table->text('description')->nullable();
            $table->string('category')->default('professional'); // professional, creative, modern, classic, etc.
            $table->json('sections')->nullable(); // Available sections (education, experience, skills, etc.)
            $table->string('preview_image')->nullable(); // Preview image for template
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('usage_count')->default(0); // How many times the template has been used
            $table->json('customization_options')->nullable(); // Customization options available
            $table->json('color_schemes')->nullable(); // Available color schemes
            $table->json('font_options')->nullable(); // Available fonts
            $table->json('layout_options')->nullable(); // Available layouts
            $table->json('features')->nullable(); // Special features of the template
            $table->string('author')->nullable(); // Who created the template
            $table->float('rating')->default(0); // Average rating
            $table->integer('rating_count')->default(0); // Number of ratings
            $table->text('instructions')->nullable(); // Instructions for using the template
            $table->timestamps();
        });

        // Enhanced CV uploads table with additional fields for builder functionality
        Schema::table('cv_uploads', function (Blueprint $table) {
            if (!Schema::hasColumn('cv_uploads', 'cv_builder_data')) {
                $table->json('cv_builder_data')->nullable()->after('cover_letter_keywords'); // JSON data for CV builder
            }
            if (!Schema::hasColumn('cv_uploads', 'is_cv_builder_template_used')) {
                $table->boolean('is_cv_builder_template_used')->default(false)->after('cv_builder_data'); // Whether CV was created with builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_template_id')) {
                $table->foreignId('cv_template_id')->nullable()->constrained('cv_templates')->after('is_cv_builder_template_used'); // Template used if applicable
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_sections_order')) {
                $table->json('cv_sections_order')->nullable()->after('cv_template_id'); // Order of CV sections
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_customizations')) {
                $table->json('cv_customizations')->nullable()->after('cv_sections_order'); // Customizations applied to CV
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_education_history')) {
                $table->json('cv_education_history')->nullable()->after('cv_customizations'); // Education history for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_work_history')) {
                $table->json('cv_work_history')->nullable()->after('cv_education_history'); // Work history for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_skills')) {
                $table->json('cv_skills')->nullable()->after('cv_work_history'); // Skills for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_languages')) {
                $table->json('cv_languages')->nullable()->after('cv_skills'); // Languages for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_certifications')) {
                $table->json('cv_certifications')->nullable()->after('cv_languages'); // Certifications for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_interests')) {
                $table->json('cv_interests')->nullable()->after('cv_certifications'); // Interests for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_references')) {
                $table->json('cv_references')->nullable()->after('cv_interests'); // References for builder
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_additional_sections')) {
                $table->json('cv_additional_sections')->nullable()->after('cv_references'); // Additional custom sections
            }
            if (!Schema::hasColumn('cv_uploads', 'is_cv_builder_active')) {
                $table->boolean('is_cv_builder_active')->default(false)->after('cv_additional_sections'); // Whether CV builder is active for this CV
            }
        });

        // Enhanced job applications table with better tracking and monitoring
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'application_stage')) {
                $table->string('application_stage')->default('submitted')->after('application_status'); // Submitted, Reviewed, Shortlisted, Interview, Rejected, Offered, Accepted
            }
            if (!Schema::hasColumn('job_applications', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('application_stage');
            }
            if (!Schema::hasColumn('job_applications', 'next_action_date')) {
                $table->date('next_action_date')->nullable()->after('status_updated_at');
            }
            if (!Schema::hasColumn('job_applications', 'follow_up_notes')) {
                $table->text('follow_up_notes')->nullable()->after('next_action_date');
            }
            if (!Schema::hasColumn('job_applications', 'interview_details')) {
                $table->json('interview_details')->nullable()->after('follow_up_notes'); // Interview date, type, details
            }
            if (!Schema::hasColumn('job_applications', 'offer_details')) {
                $table->json('offer_details')->nullable()->after('interview_details'); // Offer details if offered
            }
            if (!Schema::hasColumn('job_applications', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('offer_details'); // Internal notes for tracking
            }
            if (!Schema::hasColumn('job_applications', 'application_timeline')) {
                $table->json('application_timeline')->nullable()->after('internal_notes'); // Timeline of application events
            }
            if (!Schema::hasColumn('job_applications', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('application_timeline'); // Track if application is still active
            }
            if (!Schema::hasColumn('job_applications', 'last_reminder_sent')) {
                $table->timestamp('last_reminder_sent')->nullable()->after('is_active'); // Last reminder sent to employer
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
        Schema::dropIfExists('cv_templates');

        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->dropForeign(['cv_template_id']);
            $table->dropColumn([
                'cv_builder_data',
                'is_cv_builder_template_used',
                'cv_template_id',
                'cv_sections_order',
                'cv_customizations',
                'cv_education_history',
                'cv_work_history',
                'cv_skills',
                'cv_languages',
                'cv_certifications',
                'cv_interests',
                'cv_references',
                'cv_additional_sections',
                'is_cv_builder_active',
            ]);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'application_stage',
                'status_updated_at',
                'next_action_date',
                'follow_up_notes',
                'interview_details',
                'offer_details',
                'internal_notes',
                'application_timeline',
                'is_active',
                'last_reminder_sent',
            ]);
        });
    }
};
