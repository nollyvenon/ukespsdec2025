<nav x-data="{ open: false }" class="bg-gradient-to-r from-purple-900 to-indigo-900 border-b border-purple-800 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-white animate-pulse">
                        @if($siteLogoPath ?? null)
                            <img src="{{ $siteLogoPath }}" alt="{{ $siteName ?? config('app.name', 'Ukesps') }}" class="h-8 w-auto">
                        @else
                            <i class="fas fa-graduation-cap text-yellow-300"></i>
                        @endif
                        <span class="font-bold text-xl">{{ $siteName ?? config('app.name', 'Ukesps') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    @auth
                        <!-- Role-specific navigation -->
                        @if(auth()->user()->hasRole('recruiter'))
                            <x-nav-link :href="route('recruiter.dashboard')" :active="request()->routeIs('recruiter.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-users mr-1"></i> Recruiters
                            </x-nav-link>
                        @elseif(auth()->user()->hasRole('university_manager'))
                            <x-nav-link :href="route('university.dashboard')" :active="request()->routeIs('university.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-university mr-1"></i> University Portal
                            </x-nav-link>
                        @elseif(auth()->user()->hasRole('event_hoster'))
                            <x-nav-link :href="route('events-manager.dashboard')" :active="request()->routeIs('events-manager.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-calendar-alt mr-1"></i> Events
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-user-graduate mr-1"></i> Student
                            </x-nav-link>
                        @endif

                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-purple-200">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('portal.events')" :active="request()->routeIs('portal.events')" class="text-white hover:text-purple-200">
                            <i class="fas fa-calendar mr-1"></i> Events
                        </x-nav-link>

                        <x-nav-link :href="route('portal.courses')" :active="request()->routeIs('portal.courses')" class="text-white hover:text-purple-200">
                            <i class="fas fa-book mr-1"></i> Courses
                        </x-nav-link>

                        <!--<x-nav-link :href="route('affiliated-courses.index')" :active="request()->routeIs('affiliated-courses.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-university mr-1"></i> Uni Courses
                        </x-nav-link>-->

                        <x-nav-link :href="route('universities.index')" :active="request()->routeIs('affiliated-courses.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-university mr-1"></i> Universities
                        </x-nav-link>

                        <x-nav-link :href="route('portal.jobs')" :active="request()->routeIs('portal.jobs')" class="text-white hover:text-purple-200">
                            <i class="fas fa-briefcase mr-1"></i> Jobs
                        </x-nav-link>

                        <x-nav-link :href="route('admin.portals.university')" :active="request()->routeIs('admin.portals.university')" class="text-white hover:text-purple-200">
                            <i class="fas fa-university mr-1"></i> Universities
                        </x-nav-link>

                        <x-nav-link :href="route('matching.index')" :active="request()->routeIs('matching.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-magic mr-1"></i> Matches
                        </x-nav-link>

                        @if(Auth::user()->is_admin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-cog mr-1"></i> Admin
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white hover:text-purple-200">
                            <i class="fas fa-home mr-1"></i> Home
                        </x-nav-link>

                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-calendar mr-1"></i> Events
                        </x-nav-link>

                        <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-book mr-1"></i> Courses
                        </x-nav-link>

                        <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-briefcase mr-1"></i> Jobs
                        </x-nav-link>

                        @if(auth()->check() && auth()->user()->canUploadCv())
                            <x-nav-link :href="route('cv.index')" :active="request()->routeIs('cv.*')" class="text-white hover:text-purple-200">
                                <i class="fas fa-file-alt mr-1"></i> My CVs
                            </x-nav-link>
                        @elseif(auth()->check() && auth()->user()->canSearchCvs())
                            <x-nav-link :href="route('cv.search')" :active="request()->routeIs('cv.search')" class="text-white hover:text-purple-200">
                                <i class="fas fa-search mr-1"></i> Search CVs
                            </x-nav-link>
                        @endif

                        <!--<x-nav-link :href="route('affiliated-courses.index')" :active="request()->routeIs('affiliated-courses.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-university mr-1"></i> Uni Courses
                        </x-nav-link>-->

                        <x-nav-link :href="route('universities.index')" :active="request()->routeIs('universities.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-university mr-1"></i> Universities
                        </x-nav-link>

                        <x-nav-link :href="route('faqs.index')" :active="request()->routeIs('faqs.*')" class="text-white hover:text-purple-200">
                            <i class="fas fa-question-circle mr-1"></i> FAQs
                        </x-nav-link>

                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')" class="text-white hover:text-purple-200">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </x-nav-link>

                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="text-white hover:text-purple-200">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('portal.events')" :active="request()->routeIs('portal.events')">
                    {{ __('Events') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('portal.courses')" :active="request()->routeIs('portal.courses')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('affiliated-courses.index')" :active="request()->routeIs('affiliated-courses.*')">
                    {{ __('University Courses') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('portal.jobs')" :active="request()->routeIs('portal.jobs')">
                    {{ __('Jobs') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.portals.university')" :active="request()->routeIs('admin.portals.university')">
                    {{ __('Universities') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('matching.index')" :active="request()->routeIs('matching.*')">
                    {{ __('Matches') }}
                </x-responsive-nav-link>

                @if(Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                @elseif(auth()->user()->hasRole('university_manager'))
                    <x-responsive-nav-link :href="route('university.dashboard')" :active="request()->routeIs('university.*')">
                        {{ __('University Portal') }}
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                    {{ __('Events') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                    {{ __('Jobs') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('universities.index')" :active="request()->routeIs('universities.*')">
                    {{ __('Universities') }}
                </x-responsive-nav-link>

                @auth
                    @if(auth()->user()->canUploadCv())
                        <x-responsive-nav-link :href="route('cv.index')" :active="request()->routeIs('cv.*')">
                            {{ __('My CVs') }}
                        </x-responsive-nav-link>
                    @elseif(auth()->user()->canSearchCvs())
                        <x-responsive-nav-link :href="route('cv.search')" :active="request()->routeIs('cv.search')">
                            {{ __('Search CVs') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth

                <x-responsive-nav-link :href="route('affiliated-courses.index')" :active="request()->routeIs('affiliated-courses.*')">
                    {{ __('University Courses') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('faqs.index')" :active="request()->routeIs('faqs.*')">
                    {{ __('FAQs') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endauth
    </div>
</nav>
