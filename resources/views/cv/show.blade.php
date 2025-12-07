<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CV Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- CV Header -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8">
                        <div class="flex-1 mb-6 md:mb-0">
                            <div class="flex items-center mb-4">
                                @if($cvUpload->is_premium)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mr-3">
                                        Featured
                                    </span>
                                    <span class="text-yellow-600 mr-2">⭐</span>
                                @endif
                                <h1 class="text-3xl font-bold text-gray-900">{{ $cvUpload->original_name }}</h1>
                            </div>
                            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                                <span class="mr-4">Uploaded: {{ $cvUpload->created_at->format('F j, Y') }}</span>
                                <span class="mr-4">Views: {{ $cvUpload->view_count }}</span>
                                @if($cvUpload->location)
                                    <span class="mr-4"><i class="fas fa-map-marker-alt mr-1"></i> {{ $cvUpload->location }}</span>
                                @endif
                                <span class="inline-block bg-{{ $cvUpload->status === 'active' ? 'green' : ($cvUpload->status === 'archived' ? 'yellow' : 'red') }}-100 text-{{ $cvUpload->status === 'active' ? 'green' : ($cvUpload->status === 'archived' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst($cvUpload->status) }}
                                </span>
                            </div>
                        </div>
                        
                        @if(Auth::id() === $cvUpload->user_id)
                            <div class="flex space-x-2">
                                <a href="{{ route('cv.edit', $cvUpload) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('cv.destroy', $cvUpload) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm" onclick="return confirm('Are you sure you want to delete this CV?')">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2">
                            <!-- CV Document Card -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                                        CV Document
                                    </h2>
                                    <div class="flex items-center space-x-3">
                                        @if($cvUpload->is_featured)
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-medium">
                                                Featured
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-600 flex items-center">
                                            @if($cvUpload->file_type === 'pdf')
                                                <i class="fas fa-file-pdf text-red-500 mr-1"></i>
                                            @elseif(in_array($cvUpload->file_type, ['doc', 'docx']))
                                                <i class="fas fa-file-word text-blue-500 mr-1"></i>
                                            @else
                                                <i class="fas fa-file text-gray-500 mr-1"></i>
                                            @endif
                                            {{ strtoupper($cvUpload->file_type) }} • {{ number_format($cvUpload->file_size / 1024, 2) }} KB
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('cv.download', $cvUpload) }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                                    <i class="fas fa-download mr-2"></i> Download CV
                                </a>
                            </div>

                            <!-- CV Information -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">CV Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Filename:</h3>
                                        <p class="text-gray-900">{{ $cvUpload->filename }}</p>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">File Size:</h3>
                                        <p class="text-gray-900">{{ number_format($cvUpload->file_size / 1024, 2) }} KB</p>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">File Type:</h3>
                                        <p class="text-gray-900">{{ strtoupper($cvUpload->file_type) }}</p>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Upload Date:</h3>
                                        <p class="text-gray-900">{{ $cvUpload->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                    @if($cvUpload->location)
                                        <div>
                                            <h3 class="font-semibold text-gray-700">Preferred Location:</h3>
                                            <p class="text-gray-900">{{ $cvUpload->location }}</p>
                                        </div>
                                    @endif
                                    @if($cvUpload->desired_salary)
                                        <div>
                                            <h3 class="font-semibold text-gray-700">Desired Salary:</h3>
                                            <p class="text-gray-900">{{ $cvUpload->desired_salary }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- CV Extraction Results (if available) -->
                            @if($cvUpload->extracted_skills || $cvUpload->work_experience || $cvUpload->education)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b">Extracted Information</h2>
                                    
                                    @if($cvUpload->extracted_skills && count($cvUpload->extracted_skills) > 0)
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Skills</h3>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($cvUpload->extracted_skills as $skill)
                                                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($cvUpload->work_experience && count($cvUpload->work_experience) > 0)
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Work Experience</h3>
                                            <ul class="space-y-3">
                                                @foreach($cvUpload->work_experience as $exp)
                                                    <li class="border-l-4 border-indigo-500 pl-4 py-1">
                                                        <p class="font-medium">{{ $exp['position'] ?? 'Position' }} at {{ $exp['company'] ?? 'Company' }}</p>
                                                        @if(isset($exp['dates']))
                                                            <p class="text-sm text-gray-600">{{ $exp['dates'] }}</p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if($cvUpload->education && count($cvUpload->education) > 0)
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Education</h3>
                                            <ul class="space-y-3">
                                                @foreach($cvUpload->education as $edu)
                                                    <li class="border-l-4 border-green-500 pl-4 py-1">
                                                        <p class="font-medium">{{ $edu['degree'] ?? 'Degree' }} from {{ $edu['institution'] ?? 'Institution' }}</p>
                                                        @if(isset($edu['dates']))
                                                            <p class="text-sm text-gray-600">{{ $edu['dates'] }}</p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1">
                            <!-- CV Status Card -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">CV Settings</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Visibility:</span>
                                        <span class="font-medium {{ $cvUpload->is_public ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ $cvUpload->is_public ? 'Public' : 'Private' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Featured:</span>
                                        <span class="font-medium {{ $cvUpload->is_featured ? 'text-yellow-600' : 'text-gray-600' }}">
                                            {{ $cvUpload->is_featured ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                    @if($cvUpload->is_featured && $cvUpload->featured_until)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Featured Until:</span>
                                            <span class="font-medium text-yellow-600">
                                                {{ $cvUpload->featured_until->format('M j, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="inline-block bg-{{ $cvUpload->status === 'active' ? 'green' : ($cvUpload->status === 'archived' ? 'yellow' : 'red') }}-100 text-{{ $cvUpload->status === 'active' ? 'green' : ($cvUpload->status === 'archived' ? 'yellow' : 'red') }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ ucfirst($cvUpload->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Card -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-4">Actions</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('cv.download', $cvUpload) }}" class="flex items-center text-gray-700 hover:text-indigo-600 py-2">
                                        <i class="fas fa-download mr-3"></i> Download CV
                                    </a>
                                    @if(Auth::id() === $cvUpload->user_id)
                                        <a href="{{ route('cv.edit', $cvUpload) }}" class="flex items-center text-gray-700 hover:text-indigo-600 py-2">
                                            <i class="fas fa-edit mr-3"></i> Edit Settings
                                        </a>
                                        <form action="{{ route('cv.destroy', $cvUpload) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center text-red-600 hover:text-red-800 py-2 w-full" onclick="return confirm('Are you sure you want to delete this CV?')">
                                                <i class="fas fa-trash mr-3"></i> Delete CV
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>