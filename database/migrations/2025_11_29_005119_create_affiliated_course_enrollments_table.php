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
        Schema::create('affiliated_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliated_course_id')->constrained('affiliated_courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('enrollment_status', ['enrolled', 'in_progress', 'completed', 'dropped', 'pending'])->default('pending');
            $table->integer('completion_percentage')->default(0);
            $table->string('grade')->nullable();
            $table->string('certificate_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliated_course_enrollments');
    }
};
