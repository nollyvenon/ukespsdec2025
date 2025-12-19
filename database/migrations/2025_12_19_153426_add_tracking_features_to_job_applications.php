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
        Schema::table('job_applications', function (Blueprint $table) {
            // Add new tracking fields (don't add application_status since it already exists)
            $table->text('application_notes')->nullable()
                   ->after('application_status');
            $table->timestamp('reviewed_at')->nullable()
                   ->after('application_notes');
            $table->timestamp('interview_scheduled_at')->nullable()
                   ->after('reviewed_at');
            $table->timestamp('decision_made_at')->nullable()
                   ->after('interview_scheduled_at');
            $table->json('application_timeline')->nullable()
                   ->after('decision_made_at');
            $table->boolean('is_notified')->default(false)
                   ->after('application_timeline');
            $table->timestamp('last_notification_sent')->nullable()
                   ->after('is_notified');
            $table->integer('application_stage')->default(0) // 0=submitted, 1=reviewed, 2=shortlisted, 3=interview, 4=offer, 5=accepted
                   ->after('last_notification_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'application_notes',
                'reviewed_at',
                'interview_scheduled_at',
                'decision_made_at',
                'application_timeline',
                'is_notified',
                'last_notification_sent',
                'application_stage',
            ]);
        });
    }
};
