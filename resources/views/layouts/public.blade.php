<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ukesps') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Top Ad Placement -->
    @if(isset($ads_top) && $ads_top->count() > 0)
        <div class="bg-gray-100 py-2 text-center text-sm">
            <div class="max-w-7xl mx-auto">
                @foreach($ads_top as $ad)
                    <a href="{{ $ad->url }}" class="inline-block mx-2" target="_blank">
                        @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="height: 50px;">
                        @else
                            {{ $ad->title }}
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-xl font-bold text-indigo-600">{{ $siteName ?? config('app.name', 'Ukesps') }}</div>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Home</a>
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">About Us</a>
                        <a href="{{ route('services') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Services</a>
                        <a href="{{ route('courses.index') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Courses</a>
                        <a href="{{ route('universities.index') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Universities</a>
                        <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Jobs</a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Events</a>
                        <a href="{{ route('faqs.index') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">FAQs</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Dashboard</a>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Login</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Sign Up</a>
                        @endguest
                    </div>
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-600">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
                    <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About Us</a>
                    <a href="{{ route('services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Services</a>
                    <a href="{{ route('courses.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Courses</a>
                    <a href="{{ route('universities.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Universities</a>
                    <a href="{{ route('jobs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Jobs</a>
                    <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Events</a>
                    <a href="{{ route('faqs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">FAQs</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Dashboard</a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:text-indigo-700 hover:bg-gray-50">Sign Up</a>
                    @endguest
                </div>
            </div>
        </nav>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');

                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <x-app-footer />
</body>
</html>