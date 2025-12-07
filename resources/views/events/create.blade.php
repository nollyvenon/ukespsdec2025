<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Create New Event</h1>
                    
                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700">Max Participants (Optional)</label>
                                <input type="number" name="max_participants" id="max_participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="registration_deadline" class="block text-sm font-medium text-gray-700">Registration Deadline (Optional)</label>
                                <input type="datetime-local" name="registration_deadline" id="registration_deadline" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="event_image" class="block text-sm font-medium text-gray-700">Event Image (Optional)</label>
                            <input type="file" name="event_image" id="event_image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        
                        <div class="mb-4">
                            <label for="event_status" class="block text-sm font-medium text-gray-700">Event Status</label>
                            <select name="event_status" id="event_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="hidden" name="is_premium" value="0">
                                <input type="checkbox" name="is_premium" id="is_premium" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="togglePremiumFields()">
                                <label for="is_premium" class="ml-2 block text-sm text-gray-900">Make this a premium event (featured)</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Premium events appear at the top of search results and get more visibility.</p>
                        </div>

                        <!-- Premium Fee Field (Hidden by default) -->
                        <div id="premium_fee_section" class="mb-4" style="display: none;">
                            <label for="premium_fee" class="block text-sm font-medium text-gray-700 mb-1">Premium Fee ($)</label>
                            <input type="number" name="premium_fee" id="premium_fee" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <small class="text-gray-500">This fee will be charged for premium placement</small>
                        </div>

                        <script>
                            function togglePremiumFields() {
                                const isPremiumChecked = document.getElementById('is_premium').checked;
                                const premiumSection = document.getElementById('premium_fee_section');

                                if (isPremiumChecked) {
                                    premiumSection.style.display = 'block';
                                } else {
                                    premiumSection.style.display = 'none';
                                    document.getElementById('premium_fee').value = '';
                                }
                            }
                        </script>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('events.index') }}" class="mr-4 px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>