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
        // Add is_featured column to events table
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('event_status');
            $table->index('is_featured', 'events_featured_idx');
        });

        // Add is_featured column to blog_posts table
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->index('is_featured', 'blog_posts_featured_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_featured_idx');
            $table->dropColumn('is_featured');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_featured_idx');
            $table->dropColumn('is_featured');
        });
    }
};
