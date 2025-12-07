<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job Alert') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Job Alert: {{ $jobAlert->name }}</h1>
                    
                    <form method="POST" action="{{ route('job-alerts.update', $jobAlert) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Alert Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $jobAlert->name) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $jobAlert->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alert Status Toggle -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Alert Status</h3>
                                    <p class="text-sm text-gray-600">Enable or disable this alert</p>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" 
                                           class="sr-only peer" {{ old('is_active', $jobAlert->is_active) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">
                                        {{ $jobAlert->is_active ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Job Criteria Fields -->
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Criteria</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="criteria_keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                                    <input type="text" name="criteria[keywords]" id="criteria_keywords" 
                                           value="{{ old('criteria.keywords', $jobAlert->criteria['keywords'] ?? '') }}"
                                           placeholder="e.g., developer, marketing, remote"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500">Separate multiple keywords with commas</p>
                                    @error('criteria.keywords')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="criteria_locations" class="block text-sm font-medium text-gray-700 mb-1">Locations</label>
                                    <input type="text" name="criteria[locations]" id="criteria_locations"
                                           value="{{ old('criteria.locations', $jobAlert->criteria['locations'] ?? '') }}"
                                           placeholder="e.g., London, New York, Remote"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500">Separate multiple locations with commas</p>
                                    @error('criteria.locations')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="criteria_job_types" class="block text-sm font-medium text-gray-700 mb-1">Job Types</label>
                                    <select name="criteria[job_types][]" id="criteria_job_types" multiple
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="full_time" {{ in_array('full_time', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ in_array('part_time', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ in_array('contract', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Contract</option>
                                        <option value="freelance" {{ in_array('freelance', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Freelance</option>
                                        <option value="temporary" {{ in_array('temporary', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Temporary</option>
                                        <option value="internship" {{ in_array('internship', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Internship</option>
                                        <option value="volunteer" {{ in_array('volunteer', old('criteria.job_types', $jobAlert->criteria['job_types'] ?? [])) ? 'selected' : '' }}>Volunteer</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple options</p>
                                    @error('criteria.job_types')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="criteria_experience_level" class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
                                    <select name="criteria[experience_level]" id="criteria_experience_level"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == '') ? 'selected' : '' }}>Any Level</option>
                                        <option value="entry" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == 'entry') ? 'selected' : '' }}>Entry Level</option>
                                        <option value="junior" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == 'junior') ? 'selected' : '' }}>Junior</option>
                                        <option value="mid" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == 'mid') ? 'selected' : '' }}>Mid Level</option>
                                        <option value="senior" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == 'senior') ? 'selected' : '' }}>Senior</option>
                                        <option value="executive" {{ (old('criteria.experience_level', $jobAlert->criteria['experience_level'] ?? '') == 'executive') ? 'selected' : '' }}>Executive</option>
                                    </select>
                                    @error('criteria.experience_level')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="criteria_salary_min" class="block text-sm font-medium text-gray-700 mb-1">Minimum Salary</label>
                                    <input type="number" name="criteria[salary_min]" id="criteria_salary_min" 
                                           value="{{ old('criteria.salary_min', $jobAlert->criteria['salary_min'] ?? '') }}"
                                           placeholder="e.g., 30000"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('criteria.salary_min')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="criteria_salary_max" class="block text-sm font-medium text-gray-700 mb-1">Maximum Salary</label>
                                    <input type="number" name="criteria[salary_max]" id="criteria_salary_max"
                                           value="{{ old('criteria.salary_max', $jobAlert->criteria['salary_max'] ?? '') }}"
                                           placeholder="e.g., 80000"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('criteria.salary_max')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-1">Alert Frequency</label>
                            <select name="frequency" id="frequency"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="daily" {{ old('frequency', $jobAlert->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency', $jobAlert->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="immediate" {{ old('frequency', $jobAlert->frequency) == 'immediate' ? 'selected' : '' }}>Immediate</option>
                            </select>
                            @error('frequency')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('job-alerts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                Update Alert
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>