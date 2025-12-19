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
        // Add video CV field to CV uploads table
        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->string('video_cv_path')->nullable()
                  ->after('cover_letter_keywords');
            $table->string('video_cv_thumbnail')->nullable()
                  ->after('video_cv_path');
            $table->integer('video_cv_duration')->nullable() // Duration in seconds
                  ->after('video_cv_thumbnail');
            $table->boolean('video_cv_enabled')->default(false)
                  ->after('video_cv_duration');
        });

        // Create employer reviews table
        Schema::create('employer_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Employee who wrote the review
            $table->foreignId('company_id')->nullable()->constrained('users', 'id')->onDelete('set null'); // Company owner (if applicable)
            $table->string('company_name'); // Name of the company being reviewed
            $table->string('company_location')->nullable();
            $table->string('job_title')->nullable(); // Position of reviewer at the company
            $table->string('review_headline')->nullable();
            $table->text('review_text')->nullable();
            $table->decimal('overall_rating', 3, 2); // Out of 5
            $table->decimal('work_life_balance_rating', 3, 2)->nullable(); // Out of 5
            $table->decimal('salary_benefits_rating', 3, 2)->nullable(); // Out of 5
            $table->decimal('job_security_rating', 3, 2)->nullable(); // Out of 5
            $table->decimal('management_rating', 3, 2)->nullable(); // Out of 5
            $table->decimal('culture_rating', 3, 2)->nullable(); // Out of 5
            $table->boolean('pros_cons_available')->default(false); // Whether pros/cons are available
            $table->text('pros')->nullable(); // Positive aspects
            $table->text('cons')->nullable(); // Negative aspects
            $table->boolean('is_anonymous')->default(false); // Whether review is anonymous
            $table->boolean('is_current_employee')->default(false); // Whether reviewer is still at company
            $table->date('employment_start_date')->nullable(); // When employment started
            $table->date('employment_end_date')->nullable(); // When employment ended (if former employee)
            $table->boolean('is_verified')->default(false); // Whether the review is verified
            $table->boolean('is_approved')->default(true); // Whether review is approved for publication
            $table->timestamp('review_date')->nullable(); // Date of the review
            $table->integer('upvotes')->default(0);
            $table->integer('downvotes')->default(0);
            $table->string('reviewer_display_name')->nullable(); // Optional display name for anonymous reviews
            $table->json('review_tags')->nullable(); // Tags like 'remote', 'full-time', etc.
            $table->timestamps();
        });

        // Create interview preparation tools table
        Schema::create('interview_preparation_tools', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('category')->default('general'); // Types: common-questions, industry-specific, behavioral, technical, etc.
            $table->string('difficulty_level')->default('beginner'); // beginner, intermediate, advanced
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->json('tags')->nullable(); // Related tags
            $table->json('related_positions')->nullable(); // Positions this would be relevant for
            $table->json('question_examples')->nullable(); // Example questions in this category
            $table->json('answer_templates')->nullable(); // Template answers for common questions
            $table->json('resources')->nullable(); // Additional resources
            $table->json('video_resources')->nullable(); // Video tutorials or examples
            $table->json('practice_scenarios')->nullable(); // Practice interview scenarios
            $table->string('estimated_preparation_time')->default('15 min read');
            $table->json('skills_addressed')->nullable(); // Skills covered in this preparation tool
            $table->json('interview_types')->nullable(); // Interview types (phone, video, in-person, etc.)
            $table->string('author')->nullable();
            $table->timestamps();
        });

        // Add company information to users table if it doesn't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'company_description')) {
                $table->text('company_description')->nullable();
            }
            if (!Schema::hasColumn('users', 'company_website')) {
                $table->string('company_website')->nullable();
            }
            if (!Schema::hasColumn('users', 'company_industry')) {
                $table->string('company_industry')->nullable();
            }
            if (!Schema::hasColumn('users', 'company_size')) {
                $table->string('company_size')->nullable();
            }
            if (!Schema::hasColumn('users', 'company_logo')) {
                $table->string('company_logo')->nullable();
            }
        });

        // Add ratings to company/jobs table if it exists
        if (Schema::hasTable('job_listings')) {
            Schema::table('job_listings', function (Blueprint $table) {
                if (!Schema::hasColumn('job_listings', 'company_overall_rating')) {
                    $table->decimal('company_overall_rating', 3, 2)->nullable();
                }
                if (!Schema::hasColumn('job_listings', 'company_review_count')) {
                    $table->integer('company_review_count')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_preparation_tools');
        Schema::dropIfExists('employer_reviews');

        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'video_cv_path',
                'video_cv_thumbnail',
                'video_cv_duration',
                'video_cv_enabled',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_description',
                'company_website',
                'company_industry',
                'company_size',
                'company_logo',
            ]);
        });

        if (Schema::hasTable('job_listings')) {
            Schema::table('job_listings', function (Blueprint $table) {
                $table->dropColumn([
                    'company_overall_rating',
                    'company_review_count',
                ]);
            });
        }
    }
};
