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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Title of the ad
            $table->text('description')->nullable(); // Description of the ad
            $table->string('url')->nullable(); // URL to redirect to
            $table->enum('ad_type', ['image', 'text', 'banner', 'video'])->default('banner');
            $table->string('target_audience')->nullable(); // Who to target
            $table->enum('position', ['top', 'below_header', 'above_footer', 'left_sidebar', 'right_sidebar', 'top_slider'])->default('top'); // Where to display
            $table->enum('status', ['active', 'pending', 'inactive', 'expired'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('daily_budget', 10, 2)->nullable();
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('image_url')->nullable(); // Path to image
            $table->foreignId('ad_campaign_id')->nullable()->constrained()->onDelete('set null'); // Link to campaign
            $table->integer('priority')->default(0); // For ad rotation
            $table->integer('placement_order')->default(0); // Order of display
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
