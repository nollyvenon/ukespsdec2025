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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->string('question_text');
            $table->text('question_description')->nullable();
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'essay', 'practical_task'])->default('multiple_choice');
            $table->json('options')->nullable(); // for multiple choice and true/false
            $table->json('correct_answer'); // to allow for multiple correct answers
            $table->integer('points')->default(1);
            $table->integer('order')->default(0); // for ordering questions
            $table->boolean('is_required')->default(true);
            $table->json('explanation')->nullable(); // explanation for correct answer
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
