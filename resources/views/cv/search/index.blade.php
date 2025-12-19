@extends('layouts.app')

@section('title', 'CV Search - Find Top Talent')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Find Top Talent with CV Search</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Access our premium database of skilled professionals. Search, filter, and connect with the right candidates for your organization.
        </p>
    </div>

    <!-- Search Credits and Subscription Info -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h3 class="text-lg font-semibold text-gray-900">Your Search Credits</h3>
                <p class="text-gray-600">
                    Current Credits: <span class="font-bold text-indigo-600">{{ Auth::user()->cv_search_credits }}</span> | 
                    Used: <span class="font-semibold">{{ Auth::user()->cv_search_credits_used }}</span>
                </p>
            </div>
            <div class="flex items-center space-x-4">
                @if(Auth::user()->hasActiveCvSearchSubscription())
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        Active Subscription
                    </span>
                @else
                    <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-medium">
                        Pay-per-View Mode
                    </span>
                @endif
                <a href="{{ route('cv-analysis.purchase-credits') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-medium">
                    Buy Credits
                </a>
                <a href="{{ route('cv-analysis.subscribe') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 font-medium">
                    Subscribe
                </a>
            </div>
        </div>
    </div>

    <!-- Credit Packages -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">CV Search Credit Packages</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Basic Package</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">5 Credits</div>
                <div class="text-gray-600 mb-4">£10.00</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 5 CV views/downloads</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Valid for 30 days</li>
                </ul>
                <button onclick="purchaseCredits(5)" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                    Purchase Now
                </button>
            </div>

            <div class="border-2 border-indigo-500 rounded-lg p-6 text-center bg-indigo-50">
                <div class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full inline-block mb-2">
                    POPULAR
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Standard Package</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">20 Credits</div>
                <div class="text-gray-600 mb-4">£32.00</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 20 CV views/downloads</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Better value: £1.60 per credit</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Valid for 60 days</li>
                </ul>
                <button onclick="purchaseCredits(20)" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                    Purchase Now
                </button>
            </div>

            <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Premium Package</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">100 Credits</div>
                <div class="text-gray-600 mb-4">£120.00</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 100 CV views/downloads</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Best value: £1.20 per credit</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Valid for 180 days</li>
                </ul>
                <button onclick="purchaseCredits(100)" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                    Purchase Now
                </button>
            </div>
        </div>
    </div>

    <!-- Subscription Plans -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Unlimited Access Subscription</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Monthly</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">£49.99</div>
                <div class="text-gray-600 mb-4">per month</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Unlimited CV search</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Priority access</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 30 days access</li>
                </ul>
                <button onclick="subscribe('monthly')" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                    Subscribe Monthly
                </button>
            </div>

            <div class="border-2 border-purple-500 rounded-lg p-6 text-center bg-purple-50">
                <div class="bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full inline-block mb-2">
                    BEST VALUE
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Quarterly</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">£129.99</div>
                <div class="text-gray-600 mb-4">every 3 months</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Unlimited CV search</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 20% savings vs monthly</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 90 days access</li>
                </ul>
                <button onclick="subscribe('quarterly')" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                    Subscribe Quarterly
                </button>
            </div>

            <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Annual</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-2">£399.99</div>
                <div class="text-gray-600 mb-4">per year</div>
                <ul class="mb-4 space-y-2 text-left">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Unlimited CV search</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 40% savings vs monthly</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> 365 days access</li>
                </ul>
                <button onclick="subscribe('yearly')" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                    Subscribe Annually
                </button>
            </div>
        </div>
    </div>

    <!-- CV Search Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Search CVs</h2>
        <form id="cv-search-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                    <input type="text" id="search" name="search" placeholder="Skills, job title, experience..." class="w-full rounded-lg border-gray-300 border py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" id="location" name="location" placeholder="City, region, postcode..." class="w-full rounded-lg border-gray-300 border py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                    <input type="text" id="skills" name="skills" placeholder="PHP, Laravel, Design..." class="w-full rounded-lg border-gray-300 border py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="results_per_page" class="block text-sm font-medium text-gray-700 mb-1">Results</label>
                    <select id="results_per_page" name="results_per_page" class="w-full rounded-lg border-gray-300 border py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="10">10 per page</option>
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-medium">
                    <i class="fas fa-search mr-2"></i>Search CVs
                </button>
            </div>
        </form>
    </div>

    <!-- CV Search Results -->
    <div id="cv-results">
        <p class="text-gray-600 text-center py-8">Enter your search criteria above to find suitable candidates...</p>
    </div>
</div>

<script>
    // CV Search functionality
    document.getElementById('cv-search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        
        fetch(`{{ route('cv-analysis.search') }}?${params}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token'), // If using API tokens
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
                                    <a href="${cv.id ? '{{ route('cv-analysis.show-cv', '') }}/' + cv.id : '#'}" 
                                       class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mb-2">
                                        <i class="fas fa-eye mr-1"></i> View CV
                                    </a>
                                    <a href="${cv.id ? '{{ route('cv-analysis.download-cv', '') }}/' + cv.id : '#'}" 
                                       class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
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
                        paginationHtml += `<button onclick="searchCvs(${i})" class="px-4 py-2 text-sm ${i === data.data.current_page ? 'text-white bg-indigo-600' : 'text-gray-700 bg-white hover:bg-gray-50'} border border-gray-300 ${i === Math.max(1, data.data.current_page - 2) ? 'rounded-l-lg' : ''} ${i === Math.min(data.data.last_page, data.data.current_page + 2) ? 'rounded-r-lg' : ''}">${i}</button>`;
                    }
                    
                    // Next button
                    if (data.data.current_page < data.data.last_page) {
                        paginationHtml += `<button onclick="searchCvs(${data.data.current_page + 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50">Next</button>`;
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
                                    <a href="{{ route('cv-analysis.purchase-credits') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mr-2">
                                        Buy Credits
                                    </a>
                                    <a href="{{ route('cv-analysis.subscribe') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
                                        Subscribe Now
                                    </a>
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
            console.error('Error:', error);
            document.getElementById('cv-results').innerHTML = '<p class="text-red-600 text-center py-8">An error occurred while searching. Please try again.</p>';
        });
    });
    
    function searchCvs(page) {
        const form = document.getElementById('cv-search-form');
        const formData = new FormData(form);
        let params = new URLSearchParams(formData);
        params.set('page', page);
        
        fetch(`{{ route('cv-analysis.search') }}?${params}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token'), // If using API tokens
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Similar result rendering as above
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
                                    <a href="${cv.id ? '{{ route('cv-analysis.show-cv', '') }}/' + cv.id : '#'}" 
                                       class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm mb-2">
                                        <i class="fas fa-eye mr-1"></i> View CV
                                    </a>
                                    <a href="${cv.id ? '{{ route('cv-analysis.download-cv', '') }}/' + cv.id : '#'}" 
                                       class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
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
                    
                    // Previous button
                    if (data.data.current_page > 1) {
                        paginationHtml += `<button onclick="searchCvs(${data.data.current_page - 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50">Previous</button>`;
                    }
                    
                    // Pages
                    for (let i = Math.max(1, data.data.current_page - 2); i <= Math.min(data.data.last_page, data.data.current_page + 2); i++) {
                        paginationHtml += `<button onclick="searchCvs(${i})" class="px-4 py-2 text-sm ${i === data.data.current_page ? 'text-white bg-indigo-600' : 'text-gray-700 bg-white hover:bg-gray-50'} border border-gray-300 ${i === Math.max(1, data.data.current_page - 2) ? 'rounded-l-lg' : ''} ${i === Math.min(data.data.last_page, data.data.current_page + 2) ? 'rounded-r-lg' : ''}">${i}</button>`;
                    }
                    
                    // Next button
                    if (data.data.current_page < data.data.last_page) {
                        paginationHtml += `<button onclick="searchCvs(${data.data.current_page + 1})" class="px-4 py-2 text-sm text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-50">Next</button>`;
                    }
                    
                    paginationHtml += '</div>';
                    resultsHtml += paginationHtml;
                }
                
                document.getElementById('cv-results').innerHTML = resultsHtml;
            }
        });
    }
    
    function purchaseCredits(amount) {
        // In a real implementation, this would open a payment modal or redirect to payment processor
        alert('Purchase ' + amount + ' credits functionality would be implemented here');
    }
    
    function subscribe(plan) {
        // In a real implementation, this would open subscription modal
        alert('Subscribe to ' + plan + ' plan functionality would be implemented here');
    }
</script>
@endsection