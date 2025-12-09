<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment with the specified gateway
     */
    public function processPayment($transaction, $gateway, $additionalData = [])
    {
        switch ($gateway) {
            case 'paystack':
                $service = new PaystackService();
                return $service->processPayment($transaction, $additionalData['callback_url'] ?? null);
                
            case 'flutterwave':
                $service = new FlutterwaveService();
                return $service->processPayment($transaction, $additionalData['callback_url'] ?? null);
                
            case 'paypal':
                $service = new PayPalService();
                return $service->processPayment(
                    $transaction,
                    $additionalData['return_url'] ?? null,
                    $additionalData['cancel_url'] ?? null
                );
                
            case 'stripe':
                $service = new StripeService();
                return $service->processPayment(
                    $transaction,
                    $additionalData['success_url'] ?? null,
                    $additionalData['cancel_url'] ?? null
                );
                
            default:
                throw new \Exception("Unsupported payment gateway: {$gateway}");
        }
    }

    /**
     * Verify a payment
     */
    public function verifyPayment($reference, $gateway)
    {
        switch ($gateway) {
            case 'paystack':
                $service = new PaystackService();
                return $service->verifyPayment($reference);
                
            case 'flutterwave':
                $service = new FlutterwaveService();
                return $service->verifyPayment($reference);
                
            default:
                throw new \Exception("Verification not supported for gateway: {$gateway}");
        }
    }

    /**
     * Handle webhook for the specified gateway
     */
    public function handleWebhook($gateway, $payload)
    {
        switch ($gateway) {
            case 'paystack':
                $service = new PaystackService();
                return $service->handleWebhook($payload);
                
            case 'flutterwave':
                $service = new FlutterwaveService();
                return $service->handleWebhook($payload);
                
            case 'paypal':
                $service = new PayPalService();
                return $service->handleWebhook($payload);
                
            case 'stripe':
                $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
                $service = new StripeService();
                return $service->handleWebhook($payload, $signature);
                
            default:
                return false;
        }
    }

    /**
     * Complete a payment
     */
    public function completePayment($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        
        // Update transaction status to completed
        $transaction->update(['status' => 'completed']);
        
        // Handle any additional logic based on transaction type
        $this->handlePostPaymentActions($transaction);
        
        return $transaction;
    }

    /**
     * Handle post-payment actions based on transaction type
     */
    protected function handlePostPaymentActions($transaction)
    {
        $metadata = $transaction->metadata;
        
        switch ($transaction->type) {
            case 'premium_job_post':
                if (isset($metadata['job_id'])) {
                    $job = \App\Models\JobListing::find($metadata['job_id']);
                    if ($job) {
                        $job->update([
                            'is_premium' => true,
                            'premium_expires_at' => now()->addDays($metadata['duration_days'] ?? 30),
                        ]);
                    }
                }
                break;

            case 'premium_course_promotion':
                if (isset($metadata['course_id'])) {
                    $course = \App\Models\Course::find($metadata['course_id']);
                    if ($course) {
                        $course->update([
                            'is_premium' => true,
                            'premium_expires_at' => now()->addDays($metadata['duration_days'] ?? 30),
                        ]);
                    }
                }
                break;

            case 'premium_event_promotion':
                if (isset($metadata['event_id'])) {
                    $event = \App\Models\Event::find($metadata['event_id']);
                    if ($event) {
                        $event->update([
                            'is_premium' => true,
                            'premium_expires_at' => now()->addDays($metadata['duration_days'] ?? 30),
                        ]);
                    }
                }
                break;

            case 'university_admission_service':
                if (isset($metadata['university_id'])) {
                    // Handle university admission service completion
                    // This could trigger notifications, process applications, etc.
                }
                break;

            default:
                // Handle other transaction types if needed
                break;
        }
    }
}