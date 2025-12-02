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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('slider_title')->nullable();
            $table->text('slider_description')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_slider_featured')->default(false);
            $table->integer('slider_order')->default(0);
            $table->json('slider_metadata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'slider_title',
                'slider_description',
                'video_url',
                'is_slider_featured',
                'slider_order',
                'slider_metadata'
            ]);
        });
    }
};
