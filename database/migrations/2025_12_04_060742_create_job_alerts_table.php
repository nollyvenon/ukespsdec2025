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
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Job Alert'); // Name for the alert
            $table->json('criteria'); // JSON containing search criteria (keywords, location, salary, job type, etc.)
            $table->text('description')->nullable(); // Description of the alert
            $table->enum('frequency', ['immediate', 'daily', 'weekly'])->default('daily'); // How often to send alerts
            $table->boolean('is_active')->default(true); // Whether the alert is active
            $table->timestamp('last_run_at')->nullable(); // When the alert was last checked
            $table->timestamp('last_notification_at')->nullable(); // When the last notification was sent
            $table->integer('matched_jobs_count')->default(0); // Number of jobs that matched the criteria
            $table->json('settings')->nullable(); // Additional settings for the alert
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['last_run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
    }
};
