<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Packages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Choose Your Subscription Package</h1>

                    @if($currentSubscription)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="text-blue-700">You currently have an active subscription: 
                                <strong>{{ $currentSubscription->package_name }}</strong> 
                                (expires {{ $currentSubscription->end_date->format('M j, Y') }})
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Example packages - these would come from the database in a real implementation -->
                        <div class="border rounded-lg overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                            <div class="bg-green-100 p-6 text-center">
                                <h3 class="text-xl font-bold text-green-800">Student Package</h3>
                                <p class="text-3xl font-bold text-green-600 mt-2">$9.99</p>
                                <p class="text-sm text-green-600">One-time</p>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Access to all courses</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Event registration</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Job application features</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Student community access</li>
                                </ul>
                                <form method="POST" action="{{ route('subscriptions.subscribe', 1) }}">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="stripe">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Subscribe Now
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="border rounded-lg overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                            <div class="bg-blue-100 p-6 text-center">
                                <h3 class="text-xl font-bold text-blue-800">Recruiter Package</h3>
                                <p class="text-3xl font-bold text-blue-600 mt-2">$49.99</p>
                                <p class="text-sm text-blue-600">Monthly</p>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i> Post unlimited jobs</li>
                                    <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i> View all applications</li>
                                    <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i> Premium job placement</li>
                                    <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i> Application analytics</li>
                                </ul>
                                <form method="POST" action="{{ route('subscriptions.subscribe', 2) }}">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="stripe">
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Subscribe Now
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="border rounded-lg overflow-hidden shadow-lg transform transition duration-500 hover:scale-105">
                            <div class="bg-purple-100 p-6 text-center">
                                <h3 class="text-xl font-bold text-purple-800">University Package</h3>
                                <p class="text-3xl font-bold text-purple-600 mt-2">$199.99</p>
                                <p class="text-sm text-purple-600">Yearly</p>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i> Create unlimited courses</li>
                                    <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i> University profile</li>
                                    <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i> Student management</li>
                                    <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i> Analytics dashboard</li>
                                </ul>
                                <form method="POST" action="{{ route('subscriptions.subscribe', 3) }}">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="stripe">
                                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                        Subscribe Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>