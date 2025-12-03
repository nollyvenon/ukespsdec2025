<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Cancelled') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="mb-6">
                        <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-red-600 mb-4">Payment Cancelled</h1>
                    
                    <div class="mb-6 p-4 bg-red-50 rounded-lg">
                        <h3 class="font-semibold">Transaction Details</h3>
                        <p><strong>Transaction ID:</strong> {{ $transaction->transaction_id }}</p>
                        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
                        <p><strong>Status:</strong> <span class="text-red-600 capitalize">{{ $transaction->status }}</span></p>
                        <p><strong>Description:</strong> {{ $transaction->description }}</p>
                    </div>
                    
                    <p class="mb-6">Your payment has been cancelled. Your premium feature has not been activated.</p>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Go to Dashboard
                        </a>
                        <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Try Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>