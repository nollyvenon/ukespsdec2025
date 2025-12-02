@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Website Settings</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Site Name Setting -->
            <div class="mb-6">
                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Site Name
                </label>
                <input type="text" name="site_name" id="site_name"
                       value="{{ \App\Models\SiteSetting::get('site_name') ?: config('app.name', 'Your Site Name') }}"
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">
            </div>

            <!-- Site Description Setting -->
            <div class="mb-6">
                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Site Description
                </label>
                <textarea name="site_description" id="site_description" rows="3"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">{{ \App\Models\SiteSetting::get('site_description') ?: 'A description of your site' }}</textarea>
            </div>

            <!-- Logo Upload Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Logo Settings</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Current Logo
                        </label>
                        @php
                            $logoPath = \App\Models\SiteSetting::get('site_logo');
                        @endphp
                        @if($logoPath)
                            <img src="{{ asset('storage/'.$logoPath) }}" alt="Current Logo" class="h-16 w-auto mb-4">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-16 flex items-center justify-center">
                                <span class="text-gray-500">No Logo</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Logo
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600">
                                    <label for="site_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="site_logo" name="site_logo" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Us Section -->
            <div class="mb-6">
                <label for="about_us" class="block text-sm font-medium text-gray-700 mb-2">
                    About Us Content
                </label>
                <textarea name="about_us" id="about_us" rows="5"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">{{ \App\Models\SiteSetting::get('about_us') ?: 'Information about our company' }}</textarea>
            </div>

            <!-- Services Section -->
            <div class="mb-6">
                <label for="services_info" class="block text-sm font-medium text-gray-700 mb-2">
                    Services Information
                </label>
                <textarea name="services_info" id="services_info" rows="5"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">{{ \App\Models\SiteSetting::get('services_info') ?: 'Information about our services' }}</textarea>
            </div>

            <!-- Contact Page Content Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Page Settings</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email
                        </label>
                        <input type="email" name="contact_email" id="contact_email"
                               value="{{ \App\Models\SiteSetting::get('contact_email') ?: 'support@example.com' }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone"
                               value="{{ \App\Models\SiteSetting::get('contact_phone') ?: '+1 (123) 456-7890' }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Address
                    </label>
                    <textarea name="contact_address" id="contact_address" rows="2"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">{{ \App\Models\SiteSetting::get('contact_address') ?: '123 Main St, City, Country' }}</textarea>
                </div>

                <div>
                    <label for="contact_content" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Page Content
                    </label>
                    <textarea name="contact_content" id="contact_content" rows="5"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">{{ \App\Models\SiteSetting::get('contact_content') ?: 'Information for the contact page' }}</textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection