<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Matching') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Find Your Perfect Match</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Our intelligent system analyzes your profile to suggest the most suitable courses for your career goals.
                </p>
            </div>

            <!-- User Profile Summary -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Your Profile</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700">Skills</h3>
                        <div class="mt-2">
                            @if($profile && $profile->skills)
                                @foreach(json_decode($profile->skills, true) as $skill)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mr-2 mb-2">{{ $skill }}</span>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No skills listed</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700">Interests</h3>
                        <div class="mt-2">
                            @if($profile && $profile->interests)
                                @foreach(json_decode($profile->interests, true) as $interest)
                                    <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full mr-2 mb-2">{{ $interest }}</span>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No interests listed</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700">Education Level</h3>
                        <p class="mt-2 text-gray-700">
                            {{ $profile->education_level ?? 'Not specified' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Matching Results -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Recommended Matches</h2>
                    <button id="find-matches-btn" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-search mr-2"></i>Find Matches
                    </button>
                </div>

                <div id="matches-container">
                    <div class="text-center py-12 text-gray-500" id="initial-message">
                        <i class="fas fa-robot text-4xl mb-4"></i>
                        <p>Click "Find Matches" to see personalized course recommendations</p>
                    </div>

                    <!-- Loading spinner -->
                    <div id="loading" class="hidden text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                        <p>Analyzing your profile and finding perfect matches...</p>
                    </div>

                    <!-- Matches results -->
                    <div id="results" class="hidden">
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <p id="total-matches" class="text-gray-700"></p>
                        </div>

                        <div id="matches-list" class="space-y-4">
                            <!-- Matches will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <x-slot name="scripts">
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const findMatchesBtn = document.getElementById('find-matches-btn');
            const initialMessage = document.getElementById('initial-message');
            const loading = document.getElementById('loading');
            const results = document.getElementById('results');
            const matchesList = document.getElementById('matches-list');
            const totalMatches = document.getElementById('total-matches');

            findMatchesBtn.addEventListener('click', function() {
                initialMessage.classList.add('hidden');
                loading.classList.remove('hidden');
                results.classList.add('hidden');

                fetch('/api/matching', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');
                    results.classList.remove('hidden');

                    // Update total matches count
                    totalMatches.textContent = `We found ${data.total_matches} matches based on your profile`;

                    // Populate matches list
                    matchesList.innerHTML = '';
                    if (data.matches.length > 0) {
                        data.matches.forEach(match => {
                            const course = match.match;
                            const score = match.score;

                            const matchCard = document.createElement('div');
                            matchCard.className = 'border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow';
                            matchCard.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">\${course.title || course.course_name || 'Untitled Course'}</h3>
                                        <p class="text-gray-600 mt-2">\${course.description || course.university_name || 'No description available'}</p>

                                        \${course.instructor_name ? \`<p class="text-sm text-gray-500 mt-2">Instructor: \${course.instructor_name}</p>\` : ''}
                                        \${course.level ? \`<p class="text-sm text-gray-500 mt-1">Level: \${course.level}</p>\` : ''}
                                        \${course.university_name ? \`<p class="text-sm text-gray-500 mt-1">University: \${course.university_name}</p>\` : ''}
                                    </div>

                                    <div class="text-right">
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                            Match: \${score}%
                                        </span>
                                        <a href="#" class="mt-2 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            `;

                            matchesList.appendChild(matchCard);
                        });
                    } else {
                        matchesList.innerHTML = '<div class="text-center py-8 text-gray-500"><p>No matches found. Try updating your profile with more skills and interests.</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.classList.add('hidden');
                    alert('An error occurred while fetching matches. Please try again.');
                });
            });
        });
        </script>
    </x-slot>
</x-app-layout>