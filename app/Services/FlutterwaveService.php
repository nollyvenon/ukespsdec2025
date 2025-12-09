<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('flutterwave.payment_url');
        $this->secretKey = config('flutterwave.secret_key');
        $this->publicKey = config('flutterwave.public_key');
    }

    /**
     * Initialize a payment
     */
    public function initializePayment($data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v3/payments', [
            'tx_ref' => $data['reference'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? config('flutterwave.currency', 'NGN'),
            'redirect_url' => $data['callback_url'],
            'customer' => [
                'email' => $data['email'],
                'name' => $data['name'] ?? '',
            ],
            'customizations' => [
                'title' => $data['title'] ?? 'Payment',
                'description' => $data['description'] ?? '',
            ],
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
        ])->get($this->baseUrl . '/v3/transactions/' . $reference . '/verify');

        return $response->json();
    }

    /**
     * Process payment initiation
     */
    public function processPayment($transaction, $callbackUrl)
    {
        $paymentData = [
            'tx_ref' => $transaction->transaction_id,
            'amount' => $transaction->amount,
            'email' => $transaction->user->email,
            'name' => $transaction->user->name,
            'currency' => $transaction->currency,
            'callback_url' => $callbackUrl,
            'title' => 'Payment for ' . $transaction->description,
            'description' => $transaction->description,
        ];

        return $this->initializePayment($paymentData);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload)
    {
        // Verify webhook signature
        $signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';
        $secret = config('flutterwave.webhook_secret');
        
        if (!$signature || $signature !== $secret) {
            return false;
        }

        $event = $payload['event'];
        $data = $payload['data'];

        if ($event === 'charge.completed') {
            $reference = $data['tx_ref'];
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
        $percentFee = config('flutterwave.transaction_fee_percent', 0);
        $fixedFee = config('flutterwave.transaction_fee_fixed', 0);

        $percentAmount = ($percentFee / 100) * $amount;
        return $percentAmount + $fixedFee;
    }
}