<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Complete Your Payment</h1>
                    
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold">Transaction Details</h3>
                        <p><strong>Transaction ID:</strong> {{ $transaction->transaction_id }}</p>
                        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
                        <p><strong>Status:</strong> <span class="capitalize">{{ $transaction->status }}</span></p>
                        <p><strong>Description:</strong> {{ $transaction->description }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="font-semibold mb-3">Select Payment Method</h3>
                        @if($gateways->count() > 0)
                            @foreach($gateways as $gateway)
                                <div class="mb-3 p-3 border rounded">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">{{ $gateway->name }}</span>
                                        <form method="POST" action="#" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                                Pay Now
                                            </button>
                                        </form>
                                    </div>
                                    @if($gateway->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $gateway->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-red-500">No payment methods available. Please contact support.</p>
                        @endif
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ url()->previous() }}" class="text-blue-500 hover:text-blue-700">← Back to previous page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>