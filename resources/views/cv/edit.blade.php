<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit CV') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Update CV Settings</h1>

                    <form method="POST" action="{{ route('cv.update', $cvUpload) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h2 class="text-xl font-bold mb-2">Current CV File</h2>
                            <div class="flex items-center">
                                <i class="fas fa-file-{{ $cvUpload->file_type === 'pdf' ? 'pdf' : ($cvUpload->file_type === 'doc' || $cvUpload->file_type === 'docx' ? 'word' : 'alt') }} text-3xl mr-4 text-{{ $cvUpload->file_type === 'pdf' ? 'red' : ($cvUpload->file_type === 'doc' || $cvUpload->file_type === 'docx' ? 'blue' : 'gray') }}-500"></i>
                                <div>
                                    <p class="font-medium">{{ $cvUpload->original_name }}</p>
                                    <p class="text-sm text-gray-600">{{ strtoupper($cvUpload->file_type) }} • {{ number_format($cvUpload->file_size / 1024, 1) }} KB</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" id="location" 
                                       value="{{ old('location', $cvUpload->location) }}"
                                       placeholder="City, Country..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="desired_salary" class="block text-sm font-medium text-gray-700 mb-1">Expected Salary Range</label>
                                <input type="text" name="desired_salary" id="desired_salary" 
                                       value="{{ old('desired_salary', $cvUpload->desired_salary) }}"
                                       placeholder="e.g., $50,000 - $70,000"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('desired_salary')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_public" name="is_public" type="checkbox" 
                                           value="1"
                                           {{ old('is_public', $cvUpload->is_public) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_public" class="font-medium text-gray-700">Make my CV publicly searchable</label>
                                    <p class="text-gray-500">Allow recruiters to find and view my CV</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_featured" name="is_featured" type="checkbox"
                                           value="1"
                                           {{ old('is_featured', $cvUpload->is_featured) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-700">Make my CV featured</label>
                                    <p class="text-gray-500">Appear at the top of search results (requires payment - $49.99/month)</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h3 class="font-medium text-yellow-800 mb-2">Premium CV Benefits</h3>
                            <ul class="list-disc pl-5 space-y-1 text-sm text-yellow-700">
                                <li>Appears at the top of recruiter searches</li>
                                <li>Increased visibility to potential employers</li>
                                <li>Featured in premium CV sections</li>
                                <li>Higher chances of being contacted</li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('cv.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                                Update CV Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>