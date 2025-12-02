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
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->integer('max_score')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'graded', 'cancelled'])->default('in_progress');
            $table->integer('attempts')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null'); // who graded the assessment
            $table->text('grade_notes')->nullable(); // notes from grader
            $table->json('user_answers')->nullable(); // store user's answers
            $table->json('feedback')->nullable(); // feedback for each question
            $table->boolean('is_passed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};
