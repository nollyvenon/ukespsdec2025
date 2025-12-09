<?php

namespace App\Services;

use App\Models\Transaction;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $client;

    public function __construct()
    {
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.client_secret');
        $mode = config('paypal.mode', 'sandbox');

        if ($mode === 'sandbox') {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }

        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * Create a payment order
     */
    public function createOrder($data)
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => config('paypal.payment_action', 'CAPTURE'),
                'purchase_units' => [
                    [
                        'reference_id' => $data['reference'],
                        'amount' => [
                            'value' => number_format($data['amount'], 2, '.', ''),
                            'currency_code' => $data['currency'] ?? config('paypal.currency', 'USD'),
                        ],
                        'description' => $data['description'] ?? '',
                        'custom_id' => $data['custom_id'] ?? '',
                    ],
                ],
                'application_context' => [
                    'cancel_url' => $data['cancel_url'],
                    'return_url' => $data['return_url'],
                    'brand_name' => config('app.name'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW',
                ],
            ];

            $response = $this->client->execute($request);
            return $response->result;
        } catch (\Exception $e) {
            Log::error('PayPal create order error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Capture a payment
     */
    public function capturePayment($orderId)
    {
        try {
            $request = new OrdersCaptureRequest($orderId);
            $request->prefer('return=representation');
            
            $response = $this->client->execute($request);
            return $response->result;
        } catch (\Exception $e) {
            Log::error('PayPal capture payment error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get order details
     */
    public function getOrder($orderId)
    {
        try {
            $request = new OrdersGetRequest($orderId);
            $response = $this->client->execute($request);
            return $response->result;
        } catch (\Exception $e) {
            Log::error('PayPal get order error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process payment initiation
     */
    public function processPayment($transaction, $returnUrl, $cancelUrl)
    {
        $paymentData = [
            'reference' => $transaction->transaction_id,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'description' => $transaction->description,
            'custom_id' => $transaction->id,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
        ];

        return $this->createOrder($paymentData);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook($payload)
    {
        // Verify webhook - this is a simplified version
        // In production, you should verify the webhook signature properly
        $eventType = $payload['event_type'] ?? '';

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $resource = $payload['resource'] ?? [];
            $reference = $resource['custom_id'] ?? null;

            if ($reference) {
                $transaction = Transaction::find($reference);

                if ($transaction) {
                    $transaction->update([
                        'status' => 'completed',
                        'gateway_response' => $payload,
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
        }

        return false;
    }

    /**
     * Calculate fees for the transaction
     */
    private function calculateFees($amount)
    {
        // PayPal typically charges 2.9% + $0.30 per transaction
        $percentFee = config('paypal.transaction_fee_percent', 2.9);
        $fixedFee = config('paypal.transaction_fee_fixed', 0.30);

        $percentAmount = ($percentFee / 100) * $amount;
        return $percentAmount + $fixedFee;
    }
}