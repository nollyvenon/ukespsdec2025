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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Unique transaction ID from payment gateway
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // ad_payment, course_payment, etc.
            $table->string('status'); // pending, success, failed, refunded
            $table->string('payment_gateway'); // paystack, flutterwave, mpesa
            $table->string('payment_method')->nullable(); // card, bank, mobile_money, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->decimal('fees', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2); // amount after fees
            $table->json('metadata')->nullable(); // additional transaction data
            $table->timestamp('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable(); // Reference from the payment provider
            $table->json('gateway_response')->nullable(); // Raw response from payment gateway
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
