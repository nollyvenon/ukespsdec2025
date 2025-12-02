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
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who created the campaign
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
            $table->enum('type', ['banner', 'interstitial', 'rewarded_video', 'native'])->default('banner');
            $table->enum('position', ['top', 'bottom', 'middle', 'sidebar', 'popup'])->default('top');
            $table->json('target_audience')->nullable(); // JSON for targeting options
            $table->json('target_pages')->nullable(); // JSON for page targeting
            $table->json('target_locations')->nullable(); // JSON for location targeting
            $table->decimal('budget', 10, 2)->default(0); // Total campaign budget
            $table->decimal('daily_budget', 10, 2)->nullable(); // Daily spend limit
            $table->decimal('spent', 10, 2)->default(0); // Amount spent so far
            $table->integer('impressions')->default(0); // Number of times ad was shown
            $table->integer('clicks')->default(0); // Number of clicks
            $table->decimal('ctr', 5, 2)->default(0.00); // Click through rate
            $table->decimal('cpc', 8, 2)->default(0.00); // Cost per click
            $table->decimal('cpm', 8, 2)->default(0.00); // Cost per 1000 impressions
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('ad_unit_id')->nullable(); // Ad unit ID from platform
            $table->text('ad_code')->nullable(); // Custom ad code
            $table->json('creative_data')->nullable(); // Ad creative information
            $table->integer('impression_limit')->nullable(); // Max impressions allowed
            $table->integer('click_limit')->nullable(); // Max clicks allowed
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};
