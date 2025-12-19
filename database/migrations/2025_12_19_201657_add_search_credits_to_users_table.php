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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cv_search_credits')) {
                $table->integer('cv_search_credits')->default(0)->after('email'); // Credits for CV search
            }
            if (!Schema::hasColumn('users', 'cv_search_credits_used')) {
                $table->integer('cv_search_credits_used')->default(0)->after('cv_search_credits'); // Track used credits
            }
            if (!Schema::hasColumn('users', 'cv_search_subscription_active')) {
                $table->boolean('cv_search_subscription_active')->default(false)->after('cv_search_credits_used'); // Whether has active subscription
            }
            if (!Schema::hasColumn('users', 'cv_search_subscription_expiry')) {
                $table->timestamp('cv_search_subscription_expiry')->nullable()->after('cv_search_subscription_active'); // When subscription expires
            }
        });

        // Create table for tracking CV search activities
        Schema::create('cv_search_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recruiter/employer who performed search
            $table->string('search_query')->nullable(); // Search query performed
            $table->json('search_filters')->nullable(); // Filters applied during search
            $table->integer('cvs_accessed')->default(0); // Number of CVs accessed
            $table->integer('credits_used')->default(0); // Credits consumed for this search
            $table->string('search_type')->default('credit_based'); // credit_based, subscription_based, free_tier
            $table->timestamp('searched_at')->useCurrent(); // When search was performed

            $table->index(['user_id', 'searched_at']); // Index for performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_search_logs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cv_search_credits',
                'cv_search_credits_used',
                'cv_search_subscription_active',
                'cv_search_subscription_expiry',
            ]);
        });
    }
};
