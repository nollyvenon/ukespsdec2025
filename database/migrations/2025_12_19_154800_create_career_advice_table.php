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
        Schema::create('career_advices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('author')->nullable();
            $table->string('topic_category')->default('general'); // resume, interview, career-change, promotion, skills, networking, salary, etc.
            $table->string('career_level')->default('all'); // entry-level, mid-level, senior, executive, all
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->integer('views')->default(0);
            $table->json('tags')->nullable(); // Related tags/topics
            $table->json('action_steps')->nullable(); // Actionable steps for readers
            $table->json('external_sources')->nullable(); // Sources and references
            $table->string('estimated_reading_time')->default('5 min read');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('related_articles')->nullable(); // IDs of related articles
            $table->json('author_social_links')->nullable(); // Author's social media or contact info
            $table->boolean('allows_comments')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_advices');
    }
};
