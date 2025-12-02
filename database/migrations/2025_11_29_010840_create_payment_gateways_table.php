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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Paystack, Flutterwave, Mpesa
            $table->string('slug')->unique(); // paystack, flutterwave, mpesa
            $table->json('credentials'); // API keys and other credentials
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('supported_currencies')->nullable(); // JSON array of supported currencies
            $table->json('supported_countries')->nullable(); // JSON array of supported countries
            $table->decimal('transaction_fee_percent', 5, 2)->default(0); // Transaction fee percentage
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0); // Fixed transaction fee
            $table->json('additional_config')->nullable(); // Additional configuration options
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
