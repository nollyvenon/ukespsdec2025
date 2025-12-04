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
        Schema::create('cv_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->default('pdf'); // pdf, doc, docx, etc.
            $table->integer('file_size'); // in bytes
            $table->text('summary')->nullable(); // Auto-generated summary of the CV
            $table->json('extracted_skills')->nullable(); // JSON array of extracted skills
            $table->json('work_experience')->nullable(); // JSON array of work experience
            $table->json('education')->nullable(); // JSON array of education history
            $table->string('location')->nullable(); // Extracted location
            $table->string('desired_salary')->nullable(); // Extracted salary expectation
            $table->boolean('is_public')->default(false); // Whether the CV is searchable by recruiters
            $table->boolean('is_featured')->default(false); // Premium placement in search
            $table->timestamp('featured_until')->nullable(); // Expiration date for featured status
            $table->integer('view_count')->default(0); // How many times the CV was viewed by recruiters
            $table->string('status')->default('active'); // active, archived, suspended
            $table->timestamp('last_viewed_at')->nullable(); // Last time a recruiter viewed the CV
            $table->timestamps();

            // Indexes for faster search
            $table->index(['user_id']);
            $table->index(['is_public']);
            $table->index(['is_featured']);
            $table->index(['location']);
            $table->index(['status']);
            $table->fullText(['summary']); // For text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_uploads');
    }
};
