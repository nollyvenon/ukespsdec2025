<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Ads Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Advertisement Management</h1>
                        <a href="{{ route('admin.ads.create') }}"
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>Create Ad
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <a href="{{ route('admin.ads.index') }}" 
                           class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-lg text-center hover:bg-indigo-200">
                            All Ads
                        </a>
                        <a href="{{ route('admin.ads.slider') }}" 
                           class="bg-green-100 text-green-800 px-4 py-2 rounded-lg text-center hover:bg-green-200">
                            Slider Ads
                        </a>
                        <a href="{{ route('admin.ads.create') }}"
                           class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-center hover:bg-blue-200">
                            Create New Ad
                        </a>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-center hover:bg-gray-200">
                            Back to Dashboard
                        </a>
                    </div>

                    @if($ads->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-ad text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No advertisements found</h3>
                            <p class="text-gray-500 mb-6">Create your first advertisement to get started.</p>
                            <a href="{{ route('admin.ads.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Create Ad
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ads as $ad)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $ad->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($ad->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                    {{ Str::title(str_replace('_', ' ', $ad->position)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @switch($ad->status)
                                                        @case('active')
                                                            {{ 'bg-green-100 text-green-800' }}
                                                            @break
                                                        @case('pending')
                                                            {{ 'bg-yellow-100 text-yellow-800' }}
                                                            @break
                                                        @case('inactive')
                                                            {{ 'bg-gray-100 text-gray-800' }}
                                                            @break
                                                        @case('expired')
                                                            {{ 'bg-red-100 text-red-800' }}
                                                            @break
                                                        @default
                                                            {{ 'bg-gray-100 text-gray-800' }}
                                                    @endswitch">
                                                    {{ Str::title($ad->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $ad->start_date->format('M d, Y') }}</div>
                                                <div>{{ $ad->end_date->format('M d, Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($ad->daily_budget, 2) }}/day
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>Impressions: {{ $ad->impressions }}</div>
                                                <div>Clicks: {{ $ad->clicks }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('ads.show', $ad) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                <a href="{{ route('ads.edit', $ad) }}" 
                                                   class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                                <form method="POST" action="{{ route('ads.destroy', $ad) }}" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to delete this ad?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8">
                            {{ $ads->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>