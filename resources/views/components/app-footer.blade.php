<footer class="bg-gradient-to-r from-purple-900 to-indigo-900 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <i class="fas fa-graduation-cap mr-2 text-yellow-300"></i>
                    {{ config('app.name', 'Ukesps') }}
                </h3>
                <p class="text-purple-200">Connecting students, educators, and professionals in one platform.</p>
            </div>

            <div>
                <h4 class="text-md font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-home mr-2"></i> Home</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-book mr-2"></i> Courses</a></li>
                    <li><a href="{{ route('events.index') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-calendar mr-2"></i> Events</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-briefcase mr-2"></i> Jobs</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-md font-semibold mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-info-circle mr-2"></i> About Us</a></li>
                    <li><a href="{{ route('services') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-cogs mr-2"></i> Services</a></li>
                    <li><a href="{{ route('contact.form') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-envelope mr-2"></i> Contact</a></li>
                    <li><a href="{{ route('faqs.index') }}" class="text-purple-200 hover:text-white transition duration-300"><i class="fas fa-question-circle mr-2"></i> FAQ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-md font-semibold mb-4">Connect With Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-purple-200 hover:text-white text-xl transition duration-300 transform hover:scale-110"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-purple-200 hover:text-white text-xl transition duration-300 transform hover:scale-110"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-purple-200 hover:text-white text-xl transition duration-300 transform hover:scale-110"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-purple-200 hover:text-white text-xl transition duration-300 transform hover:scale-110"><i class="fab fa-instagram"></i></a>
                </div>
                <p class="mt-4 text-purple-200"><i class="fas fa-phone-alt mr-2"></i> +1 (555) 123-4567</p>
                <p class="text-purple-200"><i class="fas fa-envelope mr-2"></i> info@ukesps.com</p>
            </div>
        </div>

        <div class="border-t border-purple-700 mt-8 pt-6 text-center text-sm text-purple-300">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Ukesps') }}. All rights reserved.</p>
        </div>
    </div>
</footer>