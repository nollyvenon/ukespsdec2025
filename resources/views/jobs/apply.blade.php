<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Job') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Apply for: {{ $jobListing->title }}</h1>
                        <p class="text-gray-600">At {{ $jobListing->poster->name ?? 'Company' }} • {{ $jobListing->location }}</p>
                        
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full">
                                <i class="fas fa-briefcase mr-1"></i> {{ ucfirst(str_replace('_', ' ', $jobListing->job_type)) }}
                            </span>
                            <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full">
                                <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($jobListing->experience_level) }} Level
                            </span>
                            @if($jobListing->salary_min && $jobListing->salary_max)
                                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                    <i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($jobListing->salary_min) }} - ${{ number_format($jobListing->salary_max) }}
                                </span>
                            @elseif($jobListing->salary_min)
                                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                    <i class="fas fa-dollar-sign mr-1"></i> Min ${{ number_format($jobListing->salary_min) }}
                                </span>
                            @else
                                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                    <i class="fas fa-dollar-sign mr-1"></i> Negotiable
                                </span>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('jobs.apply', $jobListing) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">Cover Letter</label>
                            <textarea name="cover_letter" id="cover_letter" rows="6" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      required
                                      placeholder="Write your cover letter explaining why you're a good fit for this position...">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">Upload Resume/CV</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="resume" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="resume" name="resume" type="file" accept=".pdf,.doc,.docx" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 10MB</p>
                                </div>
                            </div>
                            @error('resume')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('jobs.show', $jobListing) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>