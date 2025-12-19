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
            // Add fields for CV scoring and matching
            $table->json('skill_match_scores')->nullable()->after('overall_score');
            $table->json('qualification_match_scores')->nullable()->after('skill_match_scores');
            $table->json('experience_match_scores')->nullable()->after('qualification_match_scores');
            $table->decimal('total_match_score', 5, 2)->nullable()->after('experience_match_scores');

            // Add field for AI-powered recommendations
            $table->json('recommended_jobs')->nullable()->after('total_match_score');

            // Add field for job recommendation scores
            $table->json('job_recommendation_scores')->nullable()->after('recommended_jobs');

            // Add field for cover letter integration
            $table->string('cover_letter_path')->nullable()->after('job_recommendation_scores');
            $table->text('cover_letter_content')->nullable()->after('cover_letter_path');
            $table->json('cover_letter_keywords')->nullable()->after('cover_letter_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'skill_match_scores',
                'qualification_match_scores',
                'experience_match_scores',
                'total_match_score',
                'recommended_jobs',
                'job_recommendation_scores',
                'cover_letter_path',
                'cover_letter_content',
                'cover_letter_keywords',
            ]);
        });
    }
};
