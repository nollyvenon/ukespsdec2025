@extends('layouts.app')

@section('title', 'CV Search Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">CV Search Dashboard</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Access our premium database of skilled professionals. Search, filter, and connect with the right candidates for your organization.
        </p>
    </div>

    <!-- Credit and Subscription Status -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-coins text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Search Credits</h3>
                <p class="text-3xl font-bold text-green-600">{{ $remainingCredits }}</p>
                <p class="text-sm text-gray-600">Available</p>
            </div>

            <div class="text-center p-6 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-3xl text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Subscription Status</h3>
                <p class="text-3xl font-bold text-green-600">
                    @if($hasSubscription)
                        ACTIVE
                    @else
                        INACTIVE
                    @endif
                </p>
                @if($hasSubscription)
                    <p class="text-sm text-gray-600">Unlimited access</p>
                @else
                    <p class="text-sm text-gray-600">Pay per search</p>
                @endif
            </div>

            <div class="text-center p-6 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Recent Searches</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $searchHistory->count() }}</p>
                <p class="text-sm text-gray-600">Last 10 searches</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Purchase Credits Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Purchase Search Credits</h2>
            <p class="text-gray-600 mb-6">Buy credits to search CVs on a pay-per-use basis</p>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Basic Package</h3>
                        <p class="text-sm text-gray-600">5 credits for £10.00</p>
                    </div>
                    <button onclick="purchaseCredits(5)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Purchase
                    </button>
                </div>

                <div class="flex justify-between items-center p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Popular Package</h3>
                        <p class="text-sm text-gray-600">20 credits for £32.00</p>
                    </div>
                    <button onclick="purchaseCredits(20)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Purchase
                    </button>
                </div>

                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Premium Package</h3>
                        <p class="text-sm text-gray-600">100 credits for £120.00</p>
                    </div>
                    <button onclick="purchaseCredits(100)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Purchase
                    </button>
                </div>
            </div>
        </div>

        <!-- Subscribe Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Unlimited Access Subscription</h2>
            <p class="text-gray-600 mb-6">Get unlimited CV search access with a monthly subscription</p>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 border border-purple-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Monthly Plan</h3>
                        <p class="text-sm text-gray-600">£49.99/month</p>
                    </div>
                    <button onclick="subscribe('monthly')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Subscribe
                    </button>
                </div>

                <div class="flex justify-between items-center p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Most Popular</h3>
                        <p class="text-sm text-gray-600">Quarterly Plan - £129.99 every 3 months</p>
                    </div>
                    <button onclick="subscribe('quarterly')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Subscribe
                    </button>
                </div>

                <div class="flex justify-between items-center p-4 border border-purple-200 rounded-lg">
                    <div>
                        <h3 class="font-semibold">Annual Plan</h3>
                        <p class="text-sm text-gray-600">£399.99/year (40% savings!)</p>
                    </div>
                    <button onclick="subscribe('yearly')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Search CV Database</h2>
        <form id="cv-search-form" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                    <input type="text" id="search" name="search" placeholder="Skills, job title, experience..." class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" placeholder="City, region, postcode..." class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">Skills</label>
                    <input type="text" id="skills" name="skills" placeholder="PHP, Laravel, React..." class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-2">Experience Level</label>
                    <select id="experience_level" name="experience_level" class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Any Level</option>
                        <option value="entry">Entry Level</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="senior">Senior</option>
                        <option value="executive">Executive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="education_level" class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                    <input type="text" id="education_level" name="education_level" placeholder="Educational requirements..." class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
                    <select id="job_type" name="job_type" class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Any Type</option>
                        <option value="full-time">Full-Time</option>
                        <option value="part-time">Part-Time</option>
                        <option value="contract">Contract</option>
                        <option value="remote">Remote</option>
                        <option value="temporary">Temporary</option>
                        <option value="internship">Internship</option>
                    </select>
                </div>
                <div>
                    <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">Min Salary (£)</label>
                    <input type="number" id="salary_min" name="salary_min" placeholder="Minimum salary expected..." class="w-full rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <label for="results_per_page" class="block text-sm font-medium text-gray-700 mb-2">Results Per Page</label>
                    <select id="results_per_page" name="results_per_page" class="rounded-lg border-gray-300 border py-2 px-4 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="10">10 per page</option>
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                    <i class="fas fa-search mr-2"></i>Search CVs
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Searches -->
    @if($searchHistory->count() > 0)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Recent Searches</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Query</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CVs Accessed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($searchHistory as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->searched_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->search_query ?: 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->cvs_accessed }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->credits_used }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $log->search_type === 'subscription_based' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $log->search_type)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif>

    <!-- Search Results Container -->
    <div id="cv-results" class="mt-8">
        <p class="text-gray-600 text-center py-8">Enter your search criteria above to find suitable candidates...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CV Search Form Submission
        const searchForm = document.getElementById('cv-search-form');
        
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(searchForm);
                const params = new URLSearchParams(formData);
                
                fetch('{{ route('cv-search.search') }}?' + params, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Authorization': 'Bearer ' + localStorage.getItem('token'), // If using API tokens
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Render search results
                        let resultsHtml = `
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                ${data.data.total_results} Results Found
                                <span class="text-sm font-normal text-gray-600 ml-2">(${data.data.current_page} of ${data.data.last_page})</span>
                            </h2>
                            <div class="space-y-6">
                        `;
                        
                        data.data.cvs.data.forEach(cv => {
                            resultsHtml += `
                                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-indigo-700 mb-2">${cv.title || cv.original_name}</h3>
                                            <div class="flex flex-wrap items-center text-sm text-gray-600 gap-4 mb-3">
                                                <span class="flex items-center">
                                                    <i class="fas fa-briefcase mr-1"></i> ${cv.experience_level || 'Experience level not specified'}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> ${cv.location || 'Location not specified'}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-graduation-cap mr-1"></i> ${cv.education_level || 'Education not specified'}
                                                </span>
                                            </div>
                                            <div class="text-gray-600 mb-4">
                                                ${cv.summary ? cv.summary.substring(0, 200) + '...' : 'No summary provided'}
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                ${(cv.extracted_skills || []).slice(0, 5).map(skill => `
                                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">${skill}</span>
                                                `).join('')}
                                            </div>
                                        </div>
                                        <div class="ml-6 text-right">
                                            <div class="mb-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Match: ${Math.floor(Math.random() * 40) + 60}% <!-- In a real app, this would come from the actual match score -->
                                                </span>
                                            </div>
                                            <a href="/cv/${cv.id}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mb-2">
                                                <i class="fas fa-eye mr-1"></i> View CV
                                            </a>
                                            <a href="/cv/${cv.id}/download" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        resultsHtml += '</div>';
                        
                        // Add pagination if needed
                        if (data.data.last_page > 1) {
                            let paginationHtml = '<div class="mt-8 flex justify-center">';
                            
                            // Previous button
                            if (data.data.current_page > 1) {
                                paginationHtml += `<button onclick="searchCvs(${data.data.current_page - 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50">Previous</button>`;
                            }
                            
                            // Pages
                            for (let i = Math.max(1, data.data.current_page - 2); i <= Math.min(data.data.last_page, data.data.current_page + 2); i++) {
                                paginationHtml += `<button onclick="searchCvs(${i})" class="px-4 py-2 text-sm ${i === data.data.current_page ? 'text-white bg-indigo-600' : 'text-gray-700 bg-white hover:bg-gray-50'} border-t border-b border-gray-300">${i}</button>`;
                            }
                            
                            // Next button
                            if (data.data.current_page < data.data.last_page) {
                                paginationHtml += `<button onclick="searchCvs(${data.data.current_page + 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50">Next</button>`;
                            }
                            
                            paginationHtml += '</div>';
                            resultsHtml += paginationHtml;
                        }
                        
                        document.getElementById('cv-results').innerHTML = resultsHtml;
                    } else if (data.requires_payment) {
                        document.getElementById('cv-results').innerHTML = `
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-yellow-400 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Payment Required:</strong> You don't have sufficient credits to perform this search. Please purchase credits or subscribe to continue.
                                        </p>
                                        <div class="mt-2">
                                            <button onclick="location.href='{{ route('cv-search.purchase-credits') }}'" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mr-2">
                                                Buy Credits
                                            </button>
                                            <button onclick="location.href='{{ route('cv-search.subscribe') }}'" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
                                                Subscribe Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        document.getElementById('cv-results').innerHTML = '<p class="text-gray-600 text-center py-8">No results found. Try different search criteria.</p>';
                    }
                })
                .catch(error => {
                    console.error('Search Error:', error);
                    document.getElementById('cv-results').innerHTML = '<p class="text-red-600 text-center py-8">An error occurred while searching. Please try again.</p>';
                });
            });
        }
    });
    
    function purchaseCredits(amount) {
        // In a real implementation, this would open a payment modal or redirect to payment processor
        alert('Purchase ' + amount + ' credits functionality would be implemented here');
    }
    
    function subscribe(plan) {
        // In a real implementation, this would open subscription modal
        alert('Subscribe to ' + plan + ' plan functionality would be implemented here');
    }
    
    function searchCvs(page) {
        const form = document.getElementById('cv-search-form');
        const formData = new FormData(form);
        let params = new URLSearchParams(formData);
        params.set('page', page);
        
        fetch('{{ route('cv-search.search') }}?' + params, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Similar to main search form handling
                let resultsHtml = `
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        ${data.data.total_results} Results Found
                        <span class="text-sm font-normal text-gray-600 ml-2">(${data.data.current_page} of ${data.data.last_page})</span>
                    </h2>
                    <div class="space-y-6">
                `;
                
                data.data.cvs.data.forEach(cv => {
                    resultsHtml += `
                        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-indigo-700 mb-2">${cv.title || cv.original_name}</h3>
                                    <div class="flex flex-wrap items-center text-sm text-gray-600 gap-4 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-briefcase mr-1"></i> ${cv.experience_level || 'Experience level not specified'}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1"></i> ${cv.location || 'Location not specified'}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-graduation-cap mr-1"></i> ${cv.education_level || 'Education not specified'}
                                        </span>
                                    </div>
                                    <div class="text-gray-600 mb-4">
                                        ${cv.summary ? cv.summary.substring(0, 200) + '...' : 'No summary provided'}
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        ${(cv.extracted_skills || []).slice(0, 5).map(skill => `
                                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">${skill}</span>
                                        `).join('')}
                                    </div>
                                </div>
                                <div class="ml-6 text-right">
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Match: ${Math.floor(Math.random() * 40) + 60}% <!-- In a real app, this would come from the actual match score -->
                                        </span>
                                    </div>
                                    <a href="/cv/${cv.id}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mb-2">
                                        <i class="fas fa-eye mr-1"></i> View CV
                                    </a>
                                    <a href="/cv/${cv.id}/download" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                resultsHtml += '</div>';
                
                // Add pagination
                if (data.data.last_page > 1) {
                    let paginationHtml = '<div class="mt-8 flex justify-center">';
                    
                    // Previous
                    if (data.data.current_page > 1) {
                        paginationHtml += `<button onclick="searchCvs(${data.data.current_page - 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50">Previous</button>`;
                    }
                    
                    // Pages
                    for (let i = Math.max(1, data.data.current_page - 2); i <= Math.min(data.data.last_page, data.data.current_page + 2); i++) {
                        paginationHtml += `<button onclick="searchCvs(${i})" class="px-4 py-2 text-sm ${i === data.data.current_page ? 'text-white bg-indigo-600' : 'text-gray-700 bg-white hover:bg-gray-50'} border-t border-b border-gray-300">${i}</button>`;
                    }
                    
                    // Next
                    if (data.data.current_page < data.data.last_page) {
                        paginationHtml += `<button onclick="searchCvs(${data.data.current_page + 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50">Next</button>`;
                    }
                    
                    paginationHtml += '</div>';
                    resultsHtml += paginationHtml;
                }
                
                document.getElementById('cv-results').innerHTML = resultsHtml;
            } else if (data.requires_payment) {
                // Handle payment required
                document.getElementById('cv-results').innerHTML = `
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Payment Required:</strong> You don't have sufficient credits to perform this search. Please purchase credits or subscribe to continue.
                                </p>
                                <div class="mt-2">
                                    <button onclick="location.href='{{ route('cv-search.purchase-credits') }}'" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mr-2">
                                        Buy Credits
                                    </button>
                                    <button onclick="location.href='{{ route('cv-search.subscribe') }}'" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
                                        Subscribe Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Pagination Error:', error);
            document.getElementById('cv-results').innerHTML = '<p class="text-red-600 text-center py-8">An error occurred while loading results. Please try again.</p>';
        });
    }
</script>
@endsection