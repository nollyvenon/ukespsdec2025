<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display all payment gateways.
     */
    public function index()
    {
        $paymentGateways = PaymentGateway::paginate(10);
        return view('admin.payment-gateways.index', compact('paymentGateways'));
    }

    /**
     * Show the form for creating a new payment gateway.
     */
    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    /**
     * Store a newly created payment gateway in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_gateways,slug',
            'credentials' => 'required|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'supported_currencies' => 'nullable|array',
            'supported_countries' => 'nullable|array',
            'transaction_fee_percent' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PaymentGateway::create($validator->validated());

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway created successfully.');
    }

    /**
     * Show the form for editing the specified payment gateway.
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    /**
     * Update the specified payment gateway in storage.
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_gateways,slug,' . $paymentGateway->id,
            'credentials' => 'required|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'supported_currencies' => 'nullable|array',
            'supported_countries' => 'nullable|array',
            'transaction_fee_percent' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $paymentGateway->update($validator->validated());

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway updated successfully.');
    }

    /**
     * Remove the specified payment gateway from storage.
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway deleted successfully.');
    }

    /**
     * Toggle the active status of the payment gateway.
     */
    public function toggleStatus(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => !$paymentGateway->is_active
        ]);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway status updated successfully.');
    }

    /**
     * Process a payment for a specific transaction.
     */
    public function process($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        // Get available payment gateways
        $gateways = PaymentGateway::where('is_active', true)->get();

        return view('payment.process', compact('transaction', 'gateways'));
    }

    /**
     * Initialize payment with selected gateway.
     */
    public function initializePayment(Request $request, $transactionId)
    {
        $request->validate([
            'gateway' => 'required|in:paystack,flutterwave,paypal,stripe'
        ]);

        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        $paymentService = new \App\Services\PaymentService();

        try {
            $callbackUrl = route('payment.success', $transactionId);
            $cancelUrl = route('payment.cancel', $transactionId);
            $successUrl = route('payment.success', $transactionId);

            $additionalData = [
                'callback_url' => $callbackUrl,
                'cancel_url' => $cancelUrl,
                'success_url' => $successUrl,
                'return_url' => $callbackUrl,
            ];

            $result = $paymentService->processPayment($transaction, $request->gateway, $additionalData);

            if ($result) {
                // Update transaction with payment gateway
                $transaction->update(['payment_gateway' => $request->gateway]);

                // Return the appropriate response based on the gateway
                switch ($request->gateway) {
                    case 'paystack':
                    case 'flutterwave':
                        if (isset($result['data']['authorization_url'])) {
                            return redirect($result['data']['authorization_url']);
                        }
                        break;

                    case 'paypal':
                        // PayPal redirects happen on the frontend typically
                        return response()->json([
                            'approval_url' => $result->links[1]->href ?? null,
                            'order_id' => $result->id ?? null,
                        ]);

                    case 'stripe':
                        if (isset($result->url)) {
                            return redirect($result->url);
                        }
                        break;
                }
            }

            return redirect()->back()->with('error', 'Failed to initiate payment with selected gateway.');

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Handle payment success.
     */
    public function success($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        // Complete the payment
        $paymentService = new \App\Services\PaymentService();
        $paymentService->completePayment($transactionId);

        return view('payment.success', compact('transaction'));
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $transaction->update(['status' => 'cancelled']);

        return view('payment.cancel', compact('transaction'));
    }

    /**
     * Handle payment webhook.
     */
    public function webhook($gateway)
    {
        $request = request()->all();

        $paymentService = new \App\Services\PaymentService();
        $result = $paymentService->handleWebhook($gateway, $request);

        if ($result) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Process a premium job post payment.
     */
    public function processPremiumJobPost(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:job_listings,id',
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $job = JobListing::find($request->job_id);
        $package = \App\Models\SubscriptionPackage::find($request->package_id);

        // Verify the package is for job listings
        if ($package->role_type !== 'recruiter' || $package->type !== 'job_featured') {
            return redirect()->back()->withErrors(['package' => 'Invalid package selected for job posting']);
        }

        // Create a transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'type' => 'premium_job_post',
            'status' => 'pending',
            'payment_gateway' => $package->payment_gateway ?? 'default',
            'amount' => $package->price,
            'currency' => 'USD',
            'metadata' => [
                'job_id' => $request->job_id,
                'package_id' => $request->package_id,
                'content_type' => 'job',
            ],
            'description' => 'Premium job posting for ' . $job->title,
        ]);

        // In a real implementation, redirect to payment gateway
        // For now, simulate successful payment
        $transaction->update(['status' => 'completed']);

        // Update the job listing
        $job->update([
            'is_premium' => true,
            'premium_expires_at' => now()->addDays($package->duration_days ?? 30),
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job listing has been upgraded to premium successfully!');
    }

    /**
     * Process a premium course payment.
     */
    public function processPremiumCoursePayment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $course = \App\Models\Course::find($request->course_id);
        $package = \App\Models\SubscriptionPackage::find($request->package_id);

        // Verify the package is for course promotion
        if ($package->role_type !== 'university_manager' || $package->type !== 'course_promotion') {
            return redirect()->back()->withErrors(['package' => 'Invalid package selected for course promotion']);
        }

        // Create a transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'type' => 'premium_course_promotion',
            'status' => 'pending',
            'payment_gateway' => $package->payment_gateway ?? 'default',
            'amount' => $package->price,
            'currency' => 'USD',
            'metadata' => [
                'course_id' => $request->course_id,
                'package_id' => $request->package_id,
                'content_type' => 'course',
            ],
            'description' => 'Premium course promotion for ' . $course->title,
        ]);

        // In a real implementation, redirect to payment gateway
        // For now, simulate successful payment
        $transaction->update(['status' => 'completed']);

        // Update the course
        $course->update([
            'is_premium' => true,
            'premium_expires_at' => now()->addDays($package->duration_days ?? 30),
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course has been upgraded to premium successfully!');
    }

    /**
     * Process a premium event payment.
     */
    public function processPremiumEventPayment(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $event = \App\Models\Event::find($request->event_id);
        $package = \App\Models\SubscriptionPackage::find($request->package_id);

        // Verify the package is for event promotion
        if ($package->role_type !== 'event_hoster' || $package->type !== 'event_promotion') {
            return redirect()->back()->withErrors(['package' => 'Invalid package selected for event promotion']);
        }

        // Create a transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'type' => 'premium_event_promotion',
            'status' => 'pending',
            'payment_gateway' => $package->payment_gateway ?? 'default',
            'amount' => $package->price,
            'currency' => 'USD',
            'metadata' => [
                'event_id' => $request->event_id,
                'package_id' => $request->package_id,
                'content_type' => 'event',
            ],
            'description' => 'Premium event promotion for ' . $event->title,
        ]);

        // In a real implementation, redirect to payment gateway
        // For now, simulate successful payment
        $transaction->update(['status' => 'completed']);

        // Update the event
        $event->update([
            'is_premium' => true,
            'premium_expires_at' => now()->addDays($package->duration_days ?? 30),
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event has been upgraded to premium successfully!');
    }

    /**
     * Process a university admission services payment.
     */
    public function processUniversityAdmissionPayment(Request $request)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id', // Assuming a universities table exists
            'service_package_id' => 'required|exists:subscription_packages,id',
        ]);

        $package = \App\Models\SubscriptionPackage::find($request->service_package_id);

        // Verify the package is for university admission services
        if ($package->role_type !== 'university_manager' || !str_contains($package->name, 'Admission')) {
            return redirect()->back()->withErrors(['package' => 'Invalid package selected for university admission services']);
        }

        // Create a transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'type' => 'university_admission_service',
            'status' => 'pending',
            'payment_gateway' => $package->payment_gateway ?? 'default',
            'amount' => $package->price,
            'currency' => 'USD',
            'metadata' => [
                'university_id' => $request->university_id,
                'package_id' => $request->service_package_id,
                'content_type' => 'university_admission',
                'service_type' => 'admission_assistance',
            ],
            'description' => 'University admission assistance services',
        ]);

        // In a real implementation, redirect to payment gateway
        // For now, simulate successful payment
        $transaction->update(['status' => 'completed']);

        return redirect()->route('dashboard')
            ->with('success', 'University admission service payment processed successfully!');
    }
}
