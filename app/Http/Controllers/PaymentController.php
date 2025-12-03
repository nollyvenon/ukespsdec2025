<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;
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
        // In a real implementation, this would redirect to the payment gateway
        // For now, we'll simulate the payment flow
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        // Get available payment gateways
        $gateways = PaymentGateway::where('is_active', true)->get();

        return view('payment.process', compact('transaction', 'gateways'));
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
        // Process webhook from payment gateway
        // This would typically validate the webhook signature and update transaction status
        // For now, returning a simple response

        return response()->json(['status' => 'received']);
    }
}
