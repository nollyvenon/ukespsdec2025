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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name');
            $table->string('section_key')->unique(); // A unique key for each section (e.g., 'hero_banner', 'featured_events', etc.)
            $table->boolean('is_enabled')->default(true);
            $table->integer('display_order')->default(0);
            $table->json('settings')->nullable(); // Additional settings for the section
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
