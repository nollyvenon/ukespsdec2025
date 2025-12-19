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
                        @if($siteLogoPath ?? null)
                            <img src="{{ $siteLogoPath }}" alt="{{ $siteName ?? config('app.name', 'Ukesps') }}" class="h-10 w-auto mr-3">
                        @endif
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

        <!-- Render homepage sections based on configuration -->
        @if(isset($homepageSections))
            @foreach($homepageSections as $section)
                @switch($section->section_key)
                    @case('hero_banner')
                        @if($section->is_enabled)
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
                                                            <div class="w-full max-w-6xl mx-auto px-4">
                                                                <div class="text-center mb-10">
                                                                    <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight text-white">
                                                                        {{ $heroContent->title }}
                                                                    </h1>
                                                                    @if($heroContent->subtitle)
                                                                        <p class="text-xl md:text-2xl mb-10 opacity-90 text-white">
                                                                            {{ $heroContent->subtitle }}
                                                                        </p>
                                                                    @endif
                                                                </div>

                                                                <!-- Job Search Form -->
                                                                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-5xl mx-auto">
                                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                        <div class="relative">
                                                                            <label for="what" class="block text-sm font-medium text-gray-700 mb-1">What</label>
                                                                            <div class="relative">
                                                                                <input type="text" id="what" name="what" placeholder="Job title, keywords, or company" class="w-full rounded-lg border-gray-300 border py-3 px-4 pr-10 focus:border-indigo-500 focus:ring-indigo-500">
                                                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                                                    <i class="fas fa-search text-gray-400"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="relative">
                                                                            <label for="where" class="block text-sm font-medium text-gray-700 mb-1">Where</label>
                                                                            <input type="text" id="where" name="where" placeholder="City, region or postcode" class="w-full rounded-lg border-gray-300 border py-3 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                                                                        </div>
                                                                        <div class="relative">
                                                                            <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                                                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                                                                                Find Jobs
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-4 text-center">
                                                                        <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                                            Advanced job search
                                                                        </a>
                                                                    </div>
                                                                </div>
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
                                    <!-- Fallback static hero with job search form -->
                                    <div class="hero-bg h-[700px] flex items-center">
                                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                            <div class="text-center mb-10">
                                                <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight text-white">
                                                    Transform Your Career Journey
                                                </h1>
                                                <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90 text-white">
                                                    Connect with the best courses, events, and job opportunities tailored just for you.
                                                    Find your perfect match in education and career advancement.
                                                </p>
                                            </div>

                                            <!-- Job Search Form -->
                                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-5xl mx-auto">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="relative">
                                                        <label for="what" class="block text-sm font-medium text-gray-700 mb-1">What</label>
                                                        <div class="relative">
                                                            <input type="text" id="what" name="what" placeholder="Job title, keywords, or company" class="w-full rounded-lg border-gray-300 border py-3 px-4 pr-10 focus:border-indigo-500 focus:ring-indigo-500">
                                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                                <i class="fas fa-search text-gray-400"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="relative">
                                                        <label for="where" class="block text-sm font-medium text-gray-700 mb-1">Where</label>
                                                        <input type="text" id="where" name="where" placeholder="City, region or postcode" class="w-full rounded-lg border-gray-300 border py-3 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                                                    </div>
                                                    <div class="relative">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                                                            Find Jobs
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-4 text-center">
                                                    <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                        Advanced job search
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </section>
                        @endif
                        @break
                    @case('featured_events')
                        @if($section->is_enabled)
                            <!-- Premium Events Horizontal Scroll Section -->
                            @if(isset($premiumEvents) && $premiumEvents->count() > 0)
                            <section class="py-12 bg-white">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="flex justify-between items-center mb-8">
                                        <h2 class="text-3xl font-bold text-gray-900">Featured Events</h2>
                                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                                    </div>

                                    <div class="overflow-x-auto pb-4">
                                        <div class="flex space-x-4" style="scroll-snap-type: x mandatory;">
                                            @foreach($premiumEvents as $event)
                                                <div class="flex-shrink-0 w-80 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" style="scroll-snap-align: start;">
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ Str::limit($event->title, 40) }}</h3>
                                                    <p class="text-gray-600 mb-3">{{ Str::limit($event->description, 80) }}</p>
                                                    <div class="text-sm text-gray-500 mb-4">
                                                        <p><i class="fas fa-calendar-alt mr-2"></i>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                                                        <p><i class="fas fa-map-marker-alt mr-2"></i>{{ Str::limit($event->location, 30) }}</p>
                                                    </div>
                                                    <a href="{{ route('events.show', $event) }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
                                                        View Details
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                            @endif
                        @endif
                        @break
                    @case('featured_jobs')
                        @if($section->is_enabled)
                            <!-- Premium Jobs Horizontal Scroll Section -->
                            @if(isset($premiumJobs) && $premiumJobs->count() > 0)
                            <section class="py-12 bg-gray-50">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="flex justify-between items-center mb-8">
                                        <h2 class="text-3xl font-bold text-gray-900">Featured Jobs</h2>
                                        <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                                    </div>

                                    <div class="overflow-x-auto pb-4">
                                        <div class="flex space-x-4" style="scroll-snap-type: x mandatory;">
                                            @foreach($premiumJobs as $job)
                                                <div class="flex-shrink-0 w-80 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" style="scroll-snap-align: start;">
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ Str::limit($job->title, 40) }}</h3>
                                                    <p class="text-gray-600 mb-2">{{ Str::limit($job->company, 40) }}</p>
                                                    <p class="text-gray-600 mb-3">{{ Str::limit($job->description, 80) }}</p>
                                                    <div class="text-sm text-gray-500 mb-4">
                                                        <p><i class="fas fa-map-marker-alt mr-2"></i>{{ Str::limit($job->location, 30) }}</p>
                                                        <p><i class="fas fa-clock mr-2"></i>{{ $job->job_type }}</p>
                                                    </div>
                                                    <a href="{{ route('jobs.show', $job) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                                        View Details
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                            @endif
                        @endif
                        @break
                    @case('featured_posts')
                        @if($section->is_enabled)
                            <!-- Featured Posts Horizontal Scroll Section -->
                            @if(isset($featuredPosts) && $featuredPosts->count() > 0)
                            <section class="py-12 bg-white">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="flex justify-between items-center mb-8">
                                        <h2 class="text-3xl font-bold text-gray-900">Featured Posts</h2>
                                        <a href="{{ route('blog.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
                                    </div>

                                    <div class="overflow-x-auto pb-4">
                                        <div class="flex space-x-4" style="scroll-snap-type: x mandatory;">
                                            @foreach($featuredPosts as $post)
                                                <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow" style="scroll-snap-align: start;">
                                                    @if($post->featured_image)
                                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                                                    @endif
                                                    <div class="p-5">
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ Str::limit($post->title, 40) }}</h3>
                                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($post->excerpt ?: $post->content, 70) }}</p>
                                                        <div class="text-xs text-gray-500 mb-4">
                                                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                                        </div>
                                                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                                                            Read More
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                            @endif
                        @endif
                        @break
                    @case('cv_search')
                        @if($section->is_enabled)
                            <!-- CV Search Section -->
                            <section class="py-16 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="text-center mb-12">
                                        <h2 class="text-3xl font-bold mb-4">Find the Right Candidates</h2>
                                        <p class="text-xl opacity-90">Search our database of talented professionals</p>
                                    </div>

                                    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
                                        <div class="text-center mb-6">
                                            <i class="fas fa-search text-blue-500 text-4xl mb-4"></i>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2">CV Search Service</h3>
                                            <p class="text-gray-600">Access our premium CV database to find qualified candidates for your organization.</p>
                                        </div>

                                        <div class="space-y-4 mb-6">
                                            <div class="flex items-center">
                                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                                <span>Advanced search filters</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                                <span>Access to premium CV database</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                                <span>Download and contact candidates</span>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <a href="{{ route('cv.search') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition-colors">
                                                Search CVs Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @break
                    @case('top_jobs')
                        @if($section->is_enabled)
                            <!-- Top Jobs Section -->
                            <section class="py-16 bg-gray-100">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="flex justify-between items-center mb-8">
                                        <div>
                                            <h2 class="text-3xl font-bold text-gray-900">Latest Jobs</h2>
                                            <p class="text-lg text-gray-600">Find your next career opportunity</p>
                                        </div>
                                        <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">See all jobs</a>
                                    </div>

                                    @if(isset($latestJobs) && $latestJobs->count() > 0)
                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                            @foreach($latestJobs as $job)
                                                <div class="border-b border-gray-200 last:border-b-0 p-6">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1 pr-4">
                                                            <h3 class="text-lg font-bold text-indigo-700 mb-1 hover:text-indigo-900 transition-colors">
                                                                <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                                                            </h3>
                                                            <div class="text-gray-600 text-sm mb-1">
                                                                {{ $job->company }} · {{ $job->location }}
                                                            </div>
                                                            @if($job->salary_min || $job->salary_max)
                                                                <div class="text-green-700 font-semibold text-sm mb-2">
                                                                    @if($job->salary_min && $job->salary_max)
                                                                        £{{ number_format($job->salary_min) }} - £{{ number_format($job->salary_max) }}
                                                                    @elseif($job->salary_min)
                                                                        £{{ number_format($job->salary_min) }}+
                                                                    @elseif($job->salary_max)
                                                                        Up to £{{ number_format($job->salary_max) }}
                                                                    @endif
                                                                    @if($job->salary_type) {{ ucfirst($job->salary_type) }} @endif
                                                                </div>
                                                            @endif
                                                            <div class="text-gray-500 text-xs">
                                                                {{ $job->job_type }} · {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('jobs.show', $job) }}" class="self-start bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700 transition-colors">
                                                            View Job
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                                            <i class="fas fa-briefcase text-5xl text-gray-300 mb-4"></i>
                                            <h3 class="text-xl font-medium text-gray-900 mb-2">No jobs available at the moment</h3>
                                            <p class="text-gray-600">Check back later for new opportunities</p>
                                        </div>
                                    @endif

                                    <div class="text-center mt-8">
                                        <a href="{{ route('jobs.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                                            Browse All Jobs
                                        </a>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @break
                    @case('top_careers')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                    @case('features')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                    @case('services')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                    @case('different_sectors')
                        @if($section->is_enabled)
                            <!-- Different Sectors Section -->
                            <section class="py-16 bg-white">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                    <div class="text-center mb-12">
                                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Different Sectors</h2>
                                        <p class="text-lg text-gray-600">Explore opportunities across various industries and domains</p>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <a href="{{ route('jobs.index') }}?search=Manufacturing" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-cogs text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Manufacturing</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Healthcare" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-heartbeat text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Healthcare</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Finance" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-chart-bar text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Finance</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=IT Services" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-globe text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">IT Services</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Education" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-graduation-cap text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Education</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Automotive" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-car text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Automotive</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Environment" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-tree text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Environment</h3>
                                        </a>
                                        <a href="{{ route('jobs.index') }}?search=Food Service" class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-utensils text-3xl text-indigo-600 mb-3"></i>
                                            <h3 class="font-semibold text-gray-900">Food Service</h3>
                                        </a>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @break
                    @case('stats')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                    @case('testimonials')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                    @case('call_to_action')
                        @if($section->is_enabled)
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
                        @endif
                        @break
                @endswitch
            @endforeach
        @endif>

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
                        @if($siteLogoPath ?? null)
                            <img src="{{ $siteLogoPath }}" alt="{{ $siteName ?? config('app.name', 'Ukesps') }}" class="h-8 w-auto mb-4">
                        @endif
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
                            <li><a href="{{ route('universities.index') }}" class="text-gray-400 hover:text-white">Universities</a></li>
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

        // Job Search Form Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('.bg-white.rounded-xl.shadow-2xl');
            if (searchForm) {
                const searchButton = searchForm.querySelector('button[type="submit"]');
                if (searchButton) {
                    searchButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        const whatInput = searchForm.querySelector('#what');
                        const whereInput = searchForm.querySelector('#where');

                        let searchParams = new URLSearchParams();
                        if (whatInput && whatInput.value.trim() !== '') {
                            searchParams.append('search', whatInput.value.trim());
                        }
                        if (whereInput && whereInput.value.trim() !== '') {
                            searchParams.append('location', whereInput.value.trim());
                        }

                        let searchUrl = "{{ route('jobs.index') }}";
                        if (searchParams.toString() !== '') {
                            searchUrl += '?' + searchParams.toString();
                        }

                        window.location.href = searchUrl;
                    });
                }
            }
        });
    </script>
</body>
</html>

