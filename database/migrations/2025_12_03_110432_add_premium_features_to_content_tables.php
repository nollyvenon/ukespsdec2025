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
        // Add premium features to job listings
        Schema::table('job_listings', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('job_status');
            $table->decimal('premium_fee', 10, 2)->nullable()->after('is_premium');
            $table->timestamp('premium_expires_at')->nullable()->after('premium_fee');
            $table->index(['is_premium', 'created_at'], 'job_listings_premium_created_at_idx'); // For sorting premium content first
        });

        // Add premium features to courses
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('course_status');
            $table->decimal('premium_fee', 10, 2)->nullable()->after('is_premium');
            $table->timestamp('premium_expires_at')->nullable()->after('premium_fee');
            $table->index(['is_premium', 'created_at'], 'courses_premium_created_at_idx'); // For sorting premium content first
        });

        // Add premium features to events
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('event_status');
            $table->decimal('premium_fee', 10, 2)->nullable()->after('is_premium');
            $table->timestamp('premium_expires_at')->nullable()->after('premium_fee');
            $table->index(['is_premium', 'created_at'], 'events_premium_created_at_idx'); // For sorting premium content first
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex('job_listings_premium_created_at_idx'); // Drop the index first
            $table->dropColumn(['is_premium', 'premium_fee', 'premium_expires_at']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_premium_created_at_idx'); // Drop the index first
            $table->dropColumn(['is_premium', 'premium_fee', 'premium_expires_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_premium_created_at_idx'); // Drop the index first
            $table->dropColumn(['is_premium', 'premium_fee', 'premium_expires_at']);
        });
    }
};
