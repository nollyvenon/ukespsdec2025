<?php

namespace App\Services;

use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Process a payment for premium content (job, course, event).
     *
     * @param User $user
     * @param string $type
     * @param mixed $content
     * @param float $amount
     * @param string $paymentGatewaySlug
     * @param array $metadata
     * @return array
     */
    public function processContentPayment(User $user, string $type, $content, float $amount, string $paymentGatewaySlug, array $metadata = []): array
    {
        // Get the payment gateway
        $gateway = PaymentGateway::where('slug', $paymentGatewaySlug)
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            return [
                'success' => false,
                'message' => 'Payment gateway not found or not active',
                'transaction_id' => null
            ];
        }

        // Generate a unique transaction ID
        $transactionId = 'TXN_' . strtoupper(Str::random(12));

        // Calculate fees
        $fees = ($amount * $gateway->transaction_fee_percent / 100) + $gateway->transaction_fee_fixed;
        $netAmount = $amount - $fees;

        // Create transaction record
        $transaction = Transaction::create([
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'type' => $type,
            'status' => 'pending',
            'payment_gateway' => $gateway->slug,
            'amount' => $amount,
            'fees' => $fees,
            'net_amount' => $netAmount,
            'metadata' => array_merge($metadata, [
                'content_type' => $type,
                'content_id' => $content->id
            ]),
            'description' => ucfirst(str_replace('_', ' ', $type)) . ' Payment',
        ]);

        // In a real implementation, you would integrate with the actual payment gateway here
        // For now, I'm simulating the payment process
        // In a real system, you'd redirect to the payment gateway or handle the payment flow
        
        return [
            'success' => true,
            'message' => 'Payment initiated successfully',
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'fees' => $fees,
            'net_amount' => $netAmount,
            'payment_url' => route('payment.process', ['transaction_id' => $transactionId])
        ];
    }
    
    /**
     * Process a payment for ad campaign.
     *
     * @param User $user
     * @param mixed $campaign
     * @param float $amount
     * @param string $paymentGatewaySlug
     * @param array $metadata
     * @return array
     */
    public function processAdPayment(User $user, $campaign, float $amount, string $paymentGatewaySlug, array $metadata = []): array
    {
        return $this->processContentPayment($user, 'ad_payment', $campaign, $amount, $paymentGatewaySlug, $metadata);
    }
    
    /**
     * Process a payment for premium job listing.
     *
     * @param User $user
     * @param mixed $job
     * @param float $amount
     * @param string $paymentGatewaySlug
     * @param array $metadata
     * @return array
     */
    public function processJobPayment(User $user, $job, float $amount, string $paymentGatewaySlug, array $metadata = []): array
    {
        return $this->processContentPayment($user, 'job_premium', $job, $amount, $paymentGatewaySlug, $metadata);
    }
    
    /**
     * Process a payment for premium course.
     *
     * @param User $user
     * @param mixed $course
     * @param float $amount
     * @param string $paymentGatewaySlug
     * @param array $metadata
     * @return array
     */
    public function processCoursePayment(User $user, $course, float $amount, string $paymentGatewaySlug, array $metadata = []): array
    {
        return $this->processContentPayment($user, 'course_premium', $course, $amount, $paymentGatewaySlug, $metadata);
    }
    
    /**
     * Process a payment for premium event.
     *
     * @param User $user
     * @param mixed $event
     * @param float $amount
     * @param string $paymentGatewaySlug
     * @param array $metadata
     * @return array
     */
    public function processEventPayment(User $user, $event, float $amount, string $paymentGatewaySlug, array $metadata = []): array
    {
        return $this->processContentPayment($user, 'event_premium', $event, $amount, $paymentGatewaySlug, $metadata);
    }

    /**
     * Complete a payment transaction.
     *
     * @param string $transactionId
     * @return bool
     */
    public function completePayment(string $transactionId): bool
    {
        $transaction = Transaction::where('transaction_id', $transactionId)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            return false;
        }

        $transaction->update([
            'status' => 'success',
            'completed_at' => now()
        ]);

        // Update the premium status of the related content
        $metadata = $transaction->metadata;
        if (isset($metadata['content_type']) && isset($metadata['content_id'])) {
            $this->activatePremiumContent($metadata['content_type'], $metadata['content_id']);
        }

        return true;
    }

    /**
     * Activate premium status for content.
     *
     * @param string $contentType
     * @param int $contentId
     * @return void
     */
    private function activatePremiumContent(string $contentType, int $contentId): void
    {
        // Map content types to models
        $modelMap = [
            'job_premium' => 'JobListing',
            'course_premium' => 'Course',
            'event_premium' => 'Event',
            'ad_payment' => 'AdCampaign'  // Assuming AdCampaign needs premium features
        ];

        if (!isset($modelMap[$contentType])) {
            return;
        }

        $modelClass = 'App\Models\\' . $modelMap[$contentType];
        $model = $modelClass::find($contentId);

        if ($model) {
            $model->update([
                'is_premium' => true,
                'premium_expires_at' => now()->addDays(30) // Premium content expires after 30 days
            ]);
        }
    }
}