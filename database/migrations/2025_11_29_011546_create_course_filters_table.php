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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 3); // ISO code
            $table->string('continent')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('acronym')->nullable();
            $table->string('logo')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->nullable(); // University rating
            $table->json('programs')->nullable(); // Programs offered
            $table->json('admissions')->nullable(); // Admission requirements
            $table->json('facilities')->nullable(); // Facilities available
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // foundation, undergraduate, postgraduate, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_levels');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('countries');
    }
};
