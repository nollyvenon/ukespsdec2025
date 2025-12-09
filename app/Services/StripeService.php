<?php

namespace App\Services;

use App\Models\Transaction;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    /**
     * Create a checkout session
     */
    public function createCheckoutSession($data)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $data['currency'] ?? config('stripe.currency', 'usd'),
                        'product_data' => [
                            'name' => $data['description'] ?? 'Payment',
                        ],
                        'unit_amount' => $data['amount'] * 100, // Stripe expects amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $data['success_url'] . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $data['cancel_url'],
                'client_reference_id' => $data['reference'],
                'metadata' => $data['metadata'] ?? [],
            ]);

            return $session;
        } catch (\Exception $e) {
            Log::error('Stripe create session error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get checkout session details
     */
    public function getCheckoutSession($sessionId)
    {
        try {
            return Session::retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe get session error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process payment initiation
     */
    public function processPayment($transaction, $successUrl, $cancelUrl)
    {
        $paymentData = [
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'description' => $transaction->description,
            'reference' => $transaction->transaction_id,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'type' => $transaction->type,
            ],
        ];

        return $this->createCheckoutSession($paymentData);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload, $signature)
    {
        $endpointSecret = config('stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $endpointSecret
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                // Get the transaction by client_reference_id
                $transaction = Transaction::where('transaction_id', $session->client_reference_id)->first();

                if ($transaction) {
                    // Update transaction status
                    $transaction->update([
                        'status' => 'completed',
                        'payment_method' => 'card',
                        'gateway_response' => $session->__toArray(),
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
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return false;
        }

        return false;
    }

    /**
     * Calculate fees for the transaction
     */
    private function calculateFees($amount)
    {
        // Stripe typically charges 2.9% + $0.30 per transaction
        $percentFee = config('stripe.transaction_fee_percent', 2.9);
        $fixedFee = config('stripe.transaction_fee_fixed', 0.30);

        $percentAmount = ($percentFee / 100) * $amount;
        return $percentAmount + $fixedFee;
    }
}