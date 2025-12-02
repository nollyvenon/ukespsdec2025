<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex justify-center md:justify-start">
                <div class="flex items-center">
                    <x-application-logo class="h-8 w-auto text-gray-800" />
                    <span class="ml-2 text-xl font-bold text-gray-800">{{ config('app.name', 'Ukesps') }}</span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 md:order-1">
                <div class="flex justify-center space-x-6">
                    <a href="{{ route('faqs.index') }}" class="text-gray-500 hover:text-gray-700">
                        <span class="sr-only">FAQs</span>
                        FAQs
                    </a>
                    <a href="{{ route('contact.form') }}" class="text-gray-500 hover:text-gray-700">
                        <span class="sr-only">Contact</span>
                        Contact
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">
                        <span class="sr-only">Terms</span>
                        Terms
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">
                        <span class="sr-only">Privacy</span>
                        Privacy
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'Ukesps') }}. All rights reserved.</p>
        </div>
    </div>
</footer>