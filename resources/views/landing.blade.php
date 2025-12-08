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
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-xl font-bold text-indigo-600">{{ config('app.name', 'Ukesps') }}</div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2">Login</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-bg text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl font-extrabold mb-6 leading-tight">
                    Transform Your Career Journey
                </h1>
                <p class="text-xl mb-10 max-w-3xl mx-auto opacity-90">
                    Connect with the best courses, events, and job opportunities tailored just for you. 
                    Find your perfect match in education and career advancement.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-300 hover-lift">
                        Get Started
                    </a>
                    <a href="#features" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition-all">
                        Learn More
                    </a>
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
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4"></div>
                            <div>
                                <h4 class="font-bold text-gray-900">Sarah Johnson</h4>
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
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4"></div>
                            <div>
                                <h4 class="font-bold text-gray-900">Michael Chen</h4>
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
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4"></div>
                            <div>
                                <h4 class="font-bold text-gray-900">Emma Rodriguez</h4>
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
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ config('app.name', 'Ukesps') }}</h3>
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
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Ukesps') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>