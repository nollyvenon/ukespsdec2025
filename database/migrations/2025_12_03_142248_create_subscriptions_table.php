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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'one_time', 'monthly', 'yearly'
            $table->string('package_name');
            $table->string('role_type'); // 'student', 'recruiter', 'university_manager', 'event_hoster'
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->json('features')->nullable(); // JSON array of features included
            $table->boolean('is_recurring')->default(false);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Create subscription packages table
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_type'); // 'student', 'recruiter', 'university_manager', 'event_hoster'
            $table->string('type'); // 'one_time', 'monthly', 'yearly'
            $table->decimal('price', 10, 2);
            $table->json('features')->nullable(); // JSON array of features
            $table->text('description')->nullable();
            $table->integer('duration_days')->nullable(); // How long the subscription lasts
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_packages');
    }
};
