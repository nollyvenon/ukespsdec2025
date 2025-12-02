<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteName ?? config('app.name', 'Ukesps') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
        }
        
        .ad-placement {
            background-color: #f9fafb;
            border: 1px dashed #d1d5db;
            padding: 10px;
            text-align: center;
            margin: 10px 0;
        }
        
        .swiper-slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
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

            function scrollToSection(sectionId) {
                event.preventDefault();
                const element = document.getElementById(sectionId);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        </script>

        <!-- Ad placement below header -->
        @if(isset($ads_below_header) && $ads_below_header->count() > 0)
            <div class="bg-blue-50 py-3 text-center">
                <div class="max-w-7xl mx-auto">
                    @foreach($ads_below_header as $ad)
                        <a href="{{ $ad->url }}" class="inline-block mx-2" target="_blank">
                            @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="height: 60px;">
                            @else
                                {{ $ad->title }}
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif>

        <!-- Hero Section -->
        <section class="relative bg-gray-900 text-white overflow-hidden">
            <!-- Hero Content Slider -->
            @if(isset($heroContents) && $heroContents->count() > 0)
                <div class="swiper heroSwiper">
                    <div class="swiper-wrapper">
                        @foreach($heroContents as $heroContent)
                            <div class="swiper-slide h-[700px]">
                                @if($heroContent->content_type === 'image' && $heroContent->content_url)
                                    <img src="{{ asset('storage/' . $heroContent->content_url) }}" alt="{{ $heroContent->title }}" class="w-full h-full object-cover">
                                @elseif($heroContent->content_type === 'youtube' && $heroContent->youtube_url)
                                    <div class="relative w-full h-full">
                                        <iframe
                                            class="w-full h-full"
                                            src="{{ str_replace('watch?v=', 'embed/', str_replace('youtu.be/', 'youtube.com/embed/', $heroContent->youtube_url)) }}"
                                            title="{{ $heroContent->title }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($heroContent->content_type === 'video' && $heroContent->content_url)
                                    <video autoplay muted loop class="w-full h-full object-cover">
                                        <source src="{{ asset('storage/' . $heroContent->content_url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="hero-bg w-full h-full flex items-center justify-center">
                                        <div class="text-center max-w-4xl px-4">
                                            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                                                {{ $heroContent->title }}
                                            </h1>
                                            @if($heroContent->subtitle)
                                                <p class="text-xl md:text-2xl mb-10 opacity-90">
                                                    {{ $heroContent->subtitle }}
                                                </p>
                                            @endif
                                            @if($heroContent->button_text && $heroContent->button_url)
                                                <a href="{{ $heroContent->button_url }}" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition-all duration-300 hover-lift">
                                                    {{ $heroContent->button_text }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Overlay for text when there's media content -->
                                @if(in_array($heroContent->content_type, ['image', 'video', 'youtube']))
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-center justify-center">
                                        <div class="text-center max-w-4xl px-4">
                                            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                                                {{ $heroContent->title }}
                                            </h1>
                                            @if($heroContent->subtitle)
                                                <p class="text-xl md:text-2xl mb-10 opacity-90">
                                                    {{ $heroContent->subtitle }}
                                                </p>
                                            @endif
                                            @if($heroContent->button_text && $heroContent->button_url)
                                                <a href="{{ $heroContent->button_url }}" class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition-all duration-300 hover-lift">
                                                    {{ $heroContent->button_text }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next text-white"></div>
                    <div class="swiper-button-prev text-white"></div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <!-- Fallback static hero in case no dynamic content exists -->
                <div class="hero-bg h-[700px] flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
                        <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight text-white">
                            Transform Your Career Journey
                        </h1>
                        <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90 text-white">
                            Connect with the best courses, events, and job opportunities tailored just for you.
                            Find your perfect match in education and career advancement.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-300 hover-lift inline-block">
                                Get Started
                            </a>
                            <a href="#features" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition inline-block">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <!-- Ad placement in hero section -->
        @if(isset($ads_hero) && $ads_hero->count() > 0)
            <div class="bg-yellow-50 py-4 text-center">
                <div class="max-w-7xl mx-auto">
                    <h3 class="text-lg font-semibold mb-2">Sponsored Content</h3>
                    <div class="flex justify-center space-x-4">
                        @foreach($ads_hero as $ad)
                            <a href="{{ $ad->url }}" class="inline-block" target="_blank">
                                @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="height: 80px;">
                                @else
                                    <span class="text-sm">{{ $ad->title }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif>

        <!-- Top Slider Section -->
        @if(isset($sliderAds) && $sliderAds->count() > 0)
            <section class="py-12 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Featured Opportunities</h2>
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach($sliderAds as $ad)
                                <div class="swiper-slide">
                                    @if($ad->ad_type === 'video' && $ad->video_url)
                                        <video controls width="100%" height="400">
                                            <source src="{{ $ad->video_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                                        <a href="{{ $ad->url }}" target="_blank">
                                            <img src="{{ $ad->image_url }}" alt="{{ $ad->slider_title ?: $ad->title }}" class="w-full h-96 object-cover">
                                        </a>
                                    @else
                                        <div class="bg-gray-100 h-96 flex flex-col items-center justify-center p-8 text-center">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $ad->slider_title ?: $ad->title }}</h3>
                                            <p class="text-gray-600 mb-6">{{ $ad->slider_description ?: $ad->description }}</p>
                                            <a href="{{ $ad->url }}" target="_blank" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                                Learn More
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </section>
        @endif>

        <!-- About Section -->
        <section id="about" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">About Our Platform</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        We're dedicated to connecting learners with the best educational opportunities and career paths.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                        <p class="text-gray-600 mb-6">
                            Our mission is to democratize access to quality education and career opportunities.
                            We connect students, professionals, and organizations through our comprehensive platform
                            that offers courses, job listings, and career matching services.
                        </p>

                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                        <p class="text-gray-600">
                            We envision a world where anyone can access the educational resources and career
                            opportunities they need to achieve their goals, regardless of their background or location.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Why Choose Us</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Curated from top institutions and industry experts</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Personalized recommendations based on your profile</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Direct connection with employers and educators</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Flexible learning options with self-paced courses</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Comprehensive solutions for all your educational and career development needs.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Online Courses</h3>
                        <p class="text-gray-600">
                            Access hundreds of courses from top institutions with flexible learning schedules.
                            Learn from industry experts at your own pace.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-briefcase text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Job Placements</h3>
                        <p class="text-gray-600">
                            Find career opportunities that match your skills and experience.
                            Connect directly with potential employers.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-alt text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Events & Workshops</h3>
                        <p class="text-gray-600">
                            Attend industry events, workshops, and conferences to network and enhance your skills.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-magic text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Matching</h3>
                        <p class="text-gray-600">
                            Our AI-powered system recommends the best courses and opportunities based on your profile.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users text-3xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Career Coaching</h3>
                        <p class="text-gray-600">
                            Get personalized guidance from industry experts to accelerate your career growth.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-xl text-center hover-lift">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-certificate text-3xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Certifications</h3>
                        <p class="text-gray-600">
                            Earn recognized certifications upon course completion to boost your professional profile.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Us?</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        We provide comprehensive solutions for all your educational and career needs in one platform.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div class="feature-card bg-gray-50 p-8 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Comprehensive Courses</h3>
                        <p class="text-gray-600 text-center">
                            Access hundreds of courses from top institutions and industry experts. Learn at your own pace with our flexible learning options.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card bg-gray-50 p-8 rounded-xl">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-briefcase text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Career Opportunities</h3>
                        <p class="text-gray-600 text-center">
                            Explore job listings and career opportunities tailored to your skills and interests. Connect with employers directly.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card bg-gray-50 p-8 rounded-xl">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-magic text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Smart Matching</h3>
                        <p class="text-gray-600 text-center">
                            Our intelligent system analyzes your profile to recommend the most suitable courses and opportunities for your career goals.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Jobs Section -->
        <section class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Top Job Opportunities</h2>
                    <p class="text-lg text-gray-600">Explore the top job listings available on our platform</p>
                </div>

                @if(isset($latestJobs) && $latestJobs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($latestJobs as $job)
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>
                                <p class="text-gray-600 mb-2">{{ Str::limit($job->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        <p>{{ $job->company }}</p>
                                        <p>{{ $job->location }}</p>
                                    </div>
                                    <a href="{{ route('jobs.show', $job) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500">No jobs available at the moment.</p>
                @endif

                <div class="text-center mt-8">
                    <a href="{{ route('jobs.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                        View All Jobs
                    </a>
                </div>
            </div>
        </section>

        <!-- Top Careers Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Top Career Paths</h2>
                    <p class="text-lg text-gray-600">Discover trending career paths that offer growth and stability</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-laptop-code text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Technology</h3>
                        <p class="text-sm text-gray-600">High-demand tech jobs with excellent growth prospects</p>
                    </div>

                    <div class="text-center p-6 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-stethoscope text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Healthcare</h3>
                        <p class="text-sm text-gray-600">Stable careers with societal impact and growth</p>
                    </div>

                    <div class="text-center p-6 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Finance</h3>
                        <p class="text-sm text-gray-600">Lucrative careers in banking, investment, and analytics</p>
                    </div>

                    <div class="text-center p-6 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-2xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Education</h3>
                        <p class="text-sm text-gray-600">Rewarding careers in teaching and academic leadership</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Services Section -->
        <section class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Services</h2>
                    <p class="text-lg text-gray-600">Comprehensive solutions for your career growth</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-6 hover-lift">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-chalkboard-teacher text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Online Courses</h3>
                        <p class="text-gray-600">Access high-quality courses from expert instructors across various subjects and skill levels.</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 hover-lift">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-briefcase text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Job Placements</h3>
                        <p class="text-gray-600">Connect with employers offering relevant job opportunities based on your skills.</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 hover-lift">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-network-wired text-xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Networking</h3>
                        <p class="text-gray-600">Build meaningful connections with peers, mentors, and industry leaders.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Different Sectors Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Different Sectors</h2>
                    <p class="text-lg text-gray-600">Explore opportunities across various industries and domains</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-cogs text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Manufacturing</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-heartbeat text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Healthcare</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-chart-bar text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Finance</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-globe text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">IT Services</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-graduation-cap text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Education</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-car text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Automotive</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-tree text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Environment</h3>
                    </div>
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md">
                        <i class="fas fa-utensils text-3xl text-indigo-600 mb-3"></i>
                        <h3 class="font-semibold text-gray-900">Food Service</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-5xl font-bold mb-2">500+</div>
                        <div class="text-xl">Courses Available</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">200+</div>
                        <div class="text-xl">Partner Universities</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">10K+</div>
                        <div class="text-xl">Happy Students</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">50+</div>
                        <div class="text-xl">Industry Experts</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Users Say</h2>
                    <p class="text-xl text-gray-600">
                        Hear from people who transformed their careers with our platform.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-700 font-bold">AJ</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900">Alex Johnson</h4>
                                <p class="text-gray-600">Software Engineer</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "The matching system helped me find courses that perfectly matched my career goals. I landed my dream job after completing a course recommended by the system!"
                        </p>
                        <div class="flex text-yellow-400 mt-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-700 font-bold">MS</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900">Maria Santos</h4>
                                <p class="text-gray-600">Marketing Director</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "I found the perfect job opportunity through this platform. The application process was smooth and I got hired within a month!"
                        </p>
                        <div class="flex text-yellow-400 mt-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-700 font-bold">DR</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900">David Roberts</h4>
                                <p class="text-gray-600">Data Scientist</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "The courses are of exceptional quality. The instructors are industry professionals with real-world experience. Highly recommended!"
                        </p>
                        <div class="flex text-yellow-400 mt-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ad placement above footer -->
        @if(isset($ads_above_footer) && $ads_above_footer->count() > 0)
            <div class="bg-yellow-50 py-4 text-center">
                <div class="max-w-7xl mx-auto">
                    <h3 class="text-sm font-semibold mb-2 text-gray-700">SPONSORED CONTENT</h3>
                    <div class="flex justify-center space-x-4">
                        @foreach($ads_above_footer as $ad)
                            <a href="{{ $ad->url }}" target="_blank" class="block">
                                @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="height: 70px;">
                                @else
                                    <span class="text-sm">{{ $ad->title }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif>

        <!-- Call to Action -->
        <section class="py-20 bg-indigo-700 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold mb-6">Ready to Transform Your Career?</h2>
                <p class="text-xl mb-10 opacity-90">
                    Join thousands of successful professionals who have achieved their career goals with our platform.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-10 py-5 rounded-lg font-bold text-xl hover:bg-gray-100 transition-all duration-300 hover-lift">
                    Start Your Journey Today
                </a>
            </div>
        </section>

        <!-- Side Ad Placements -->
        <div class="fixed top-1/2 left-2 transform -translate-y-1/2 z-50 hidden lg:block">
            @if(isset($ads_left_sidebar) && $ads_left_sidebar->count() > 0)
                @foreach($ads_left_sidebar as $ad)
                    <a href="{{ $ad->url }}" target="_blank" class="block mb-4">
                        @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="width: 100px; height: auto;">
                        @else
                            <div class="bg-gray-800 text-white text-xs p-2 rounded" style="width: 100px;">
                                {{ Str::limit($ad->title, 20) }}
                            </div>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <div class="fixed top-1/2 right-2 transform -translate-y-1/2 z-50 hidden lg:block">
            @if(isset($ads_right_sidebar) && $ads_right_sidebar->count() > 0)
                @foreach($ads_right_sidebar as $ad)
                    <a href="{{ $ad->url }}" target="_blank" class="block mb-4">
                        @if($ad->ad_type === 'image' || $ad->ad_type === 'banner')
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="width: 100px; height: auto;">
                        @else
                            <div class="bg-gray-800 text-white text-xs p-2 rounded" style="width: 100px;">
                                {{ Str::limit($ad->title, 20) }}
                            </div>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ $siteName ?? config('app.name', 'Ukesps') }}</h3>
                        <p class="text-gray-400">
                            Connecting learners with the best educational opportunities and career paths.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                            <li><a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white">Courses</a></li>
                            <li><a href="{{ route('jobs.index') }}" class="text-gray-400 hover:text-white">Jobs</a></li>
                            <li><a href="{{ route('events.index') }}" class="text-gray-400 hover:text-white">Events</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Resources</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('faqs.index') }}" class="text-gray-400 hover:text-white">FAQs</a></li>
                            <li><a href="{{ route('contact.form') }}" class="text-gray-400 hover:text-white">Contact</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Connect</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ $siteName ?? config('app.name', 'Ukesps') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize Hero Swiper
        var heroSwiper = new Swiper(".heroSwiper", {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".heroSwiper .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".heroSwiper .swiper-button-next",
                prevEl: ".heroSwiper .swiper-button-prev",
            },
        });

        // Initialize Secondary Swiper (for ads or other sliders)
        var secondarySwiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".mySwiper .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".mySwiper .swiper-button-next",
                prevEl: ".mySwiper .swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            },
        });
    </script>
</body>
</html>