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
        Schema::table('cv_uploads', function (Blueprint $table) {
            // Add scoring and matching fields if they don't exist
            if (!Schema::hasColumn('cv_uploads', 'overall_score')) {
                $table->float('overall_score')->nullable()->after('status'); // Overall CV score (0-100)
            }
            if (!Schema::hasColumn('cv_uploads', 'match_scores')) {
                $table->json('match_scores')->nullable()->after('overall_score'); // Match scores for different jobs
            }
            if (!Schema::hasColumn('cv_uploads', 'relevance_score')) {
                $table->integer('relevance_score')->nullable()->after('match_scores'); // Relevance score for search
            }
            if (!Schema::hasColumn('cv_uploads', 'parsed_data')) {
                $table->json('parsed_data')->nullable()->after('relevance_score'); // Full parsed CV data
            }

            // Update existing columns to JSON format if they're still text
            if (Schema::hasColumn('cv_uploads', 'extracted_skills')) {
                $table->json('extracted_skills')->change();
            }
            if (Schema::hasColumn('cv_uploads', 'work_experience')) {
                $table->json('work_experience')->change();
            }
            if (Schema::hasColumn('cv_uploads', 'education')) {
                $table->json('education')->change();
            }

            // Add new fields
            if (!Schema::hasColumn('cv_uploads', 'contact_info')) {
                $table->json('contact_info')->after('education'); // Store contact info as JSON
            }
            if (!Schema::hasColumn('cv_uploads', 'languages')) {
                $table->json('languages')->after('contact_info'); // Languages known
            }
            if (!Schema::hasColumn('cv_uploads', 'cv_completeness_score')) {
                $table->float('cv_completeness_score')->nullable()->after('languages'); // Completeness of CV (0-100)
            }
            if (!Schema::hasColumn('cv_uploads', 'last_parsed_at')) {
                $table->timestamp('last_parsed_at')->nullable()->after('cv_completeness_score'); // When CV was last parsed
            }
            if (!Schema::hasColumn('cv_uploads', 'auto_parse_enabled')) {
                $table->boolean('auto_parse_enabled')->default(true)->after('last_parsed_at'); // Whether to auto-parse uploaded CVs
            }

            // Additional fields for cover letters and applications
            if (!Schema::hasColumn('cv_uploads', 'last_position_applied')) {
                $table->string('last_position_applied')->nullable()->after('auto_parse_enabled'); // Last position applied for
            }
            if (!Schema::hasColumn('cv_uploads', 'application_count')) {
                $table->integer('application_count')->default(0)->after('last_position_applied'); // Number of applications submitted
            }
            if (!Schema::hasColumn('cv_uploads', 'last_application_at')) {
                $table->timestamp('last_application_at')->nullable()->after('application_count'); // Last application date
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'overall_score',
                'match_scores',
                'relevance_score',
                'parsed_data',
                'contact_info',
                'languages',
                'cv_completeness_score',
                'last_parsed_at',
                'auto_parse_enabled',
                'last_position_applied',
                'application_count',
                'last_application_at'
            ]);
            
            // Restore the original column types
            $table->text('summary')->change();
            $table->text('extracted_skills')->change();
            $table->text('work_experience')->change();
            $table->text('education')->change();
        });
    }
};