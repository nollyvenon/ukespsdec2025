<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('paystack.payment_url');
        $this->secretKey = config('paystack.secret_key');
    }

    /**
     * Initialize a payment
     */
    public function initializePayment($data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/transaction/initialize', [
            'email' => $data['email'],
            'amount' => $data['amount'] * 100, // Paystack expects amount in kobo
            'reference' => $data['reference'],
            'callback_url' => $data['callback_url'],
            'metadata' => $data['metadata'] ?? [],
        ]);

        return $response->json();
    }

    /**
     * Verify a payment
     */
    public function verifyPayment($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get($this->baseUrl . '/transaction/verify/' . $reference);

        return $response->json();
    }

    /**
     * Process payment initiation
     */
    public function processPayment($transaction, $callbackUrl)
    {
        $paymentData = [
            'email' => $transaction->user->email,
            'amount' => $transaction->amount,
            'reference' => $transaction->transaction_id,
            'callback_url' => $callbackUrl,
            'metadata' => [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'type' => $transaction->type,
            ],
        ];

        return $this->initializePayment($paymentData);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload)
    {
        // Verify webhook signature
        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
        
        if (!$signature) {
            return false;
        }

        $secret = config('paystack.webhook_secret');
        $computedSignature = hash_hmac('sha512', file_get_contents('php://input'), $secret);

        if ($signature !== $computedSignature) {
            return false;
        }

        $event = $payload['event'];
        $data = $payload['data'];

        if ($event === 'charge.success') {
            $reference = $data['reference'];
            $transaction = Transaction::where('transaction_id', $reference)->first();

            if ($transaction) {
                // Update transaction status
                $transaction->update([
                    'status' => 'completed',
                    'gateway_response' => $data,
                    'completed_at' => now(),
                ]);

                // Calculate fees and net amount
                $fees = $this->calculateFees($transaction->amount);
                $netAmount = $transaction->amount - $fees;

                $transaction->update([
                    'fees' => $fees,
                    'net_amount' => $netAmount,
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     * Calculate fees for the transaction
     */
    private function calculateFees($amount)
    {
        $percentFee = config('paystack.transaction_fee_percent', 0);
        $fixedFee = config('paystack.transaction_fee_fixed', 0);

        $percentAmount = ($percentFee / 100) * $amount;
        return $percentAmount + $fixedFee;
    }
}