<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Job Listings Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <i class="fas fa-briefcase text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Job Listings</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\JobListing::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.job-listings.index') }}" 
                               class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                                Manage Jobs <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Applications Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i class="fas fa-file-contract text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Applications</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\JobApplication::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.applications.index') }}" 
                               class="mt-4 inline-block text-green-600 hover:text-green-800">
                                Manage Apps <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Users Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Users</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" 
                               class="mt-4 inline-block text-purple-600 hover:text-purple-800">
                                Manage Users <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- FAQs Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <i class="fas fa-question-circle text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">FAQs</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\Faq::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.faqs.index') }}" 
                               class="mt-4 inline-block text-yellow-600 hover:text-yellow-800">
                                Manage FAQs <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Ads Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                                    <i class="fas fa-ad text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Advertisements</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\Ad::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.ads.index') }}"
                               class="mt-4 inline-block text-red-600 hover:text-red-800">
                                Manage Ads <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Hero Content Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-images text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Hero Content</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\HeroContent::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.hero-contents.index') }}"
                               class="mt-4 inline-block text-purple-600 hover:text-purple-800">
                                Manage Hero <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Affiliated Courses Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                                    <i class="fas fa-graduation-cap text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Affiliated Courses</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\AffiliatedCourse::count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.affiliated-courses.index') }}"
                               class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">
                                Manage Courses <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Settings Card -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <i class="fas fa-cog text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">General Settings</h3>
                                    <p class="text-2xl font-bold">Manage Site</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings') }}"
                               class="mt-4 inline-block text-yellow-600 hover:text-yellow-800">
                                Manage Settings <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Recent Activity</h2>
                        
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flow-root">
                                    <ul class="divide-y divide-gray-200">
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-briefcase text-blue-500 text-lg"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        New job listing added
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        Posted by John Doe - Web Developer Position
                                                    </p>
                                                </div>
                                                <div>
                                                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full text-blue-700 bg-blue-100 hover:bg-blue-200">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-contract text-green-500 text-lg"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        New application received
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        From Jane Smith - Software Engineer
                                                    </p>
                                                </div>
                                                <div>
                                                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full text-green-700 bg-green-100 hover:bg-green-200">
                                                        Review
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-user text-purple-500 text-lg"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        New user registered
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        Mary Johnson joined the platform
                                                    </p>
                                                </div>
                                                <div>
                                                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full text-purple-700 bg-purple-100 hover:bg-purple-200">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>