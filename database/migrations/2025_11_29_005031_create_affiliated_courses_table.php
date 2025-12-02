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
        Schema::create('affiliated_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('university_name');
            $table->string('university_logo')->nullable();
            $table->string('course_image')->nullable();
            $table->string('level'); // beginner, intermediate, advanced, all_levels
            $table->integer('duration'); // in weeks
            $table->date('start_date');
            $table->date('end_date');
            $table->text('prerequisites')->nullable();
            $table->text('syllabus')->nullable();
            $table->decimal('fee', 10, 2)->nullable(); // course fee in local currency
            $table->json('skills_covered')->nullable(); // skills that will be covered
            $table->json('career_outcomes')->nullable(); // career outcomes
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->integer('max_enrollment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliated_courses');
    }
};
