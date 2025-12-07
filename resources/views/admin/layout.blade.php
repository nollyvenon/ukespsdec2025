<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Ukesps') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4">
                <h1 class="text-xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="{{ route('admin.ads.index') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-ad mr-2"></i>All Ads
                </a>
                <a href="{{ route('admin.hero-contents.index') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-images mr-2"></i>Hero Content
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-question-circle mr-2"></i>FAQs
                </a>
                <a href="{{ route('admin.payment-gateways.index') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-credit-card mr-2"></i>Payment Gateways
                </a>

                <!-- Portal Links -->
                <div class="mt-4">
                    <h3 class="text-xs uppercase px-4 py-2 text-gray-500 font-semibold">Portals</h3>
                    <a href="{{ route('admin.portals.recruitment') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-bullhorn mr-2"></i>Recruitment Portal
                    </a>
                    <a href="{{ route('admin.portals.events') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-calendar-alt mr-2"></i>Events Portal
                    </a>
                    <a href="{{ route('admin.portals.blog') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-blog mr-2"></i>Blog Portal
                    </a>
                    <a href="{{ route('admin.portals.users') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-users mr-2"></i>Users Portal
                    </a>
                    <a href="{{ route('admin.portals.courses') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-book mr-2"></i>Courses Portal
                    </a>
                    <a href="{{ route('admin.portals.university') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-university mr-2"></i>University Portal
                    </a>
                    <a href="{{ route('admin.portals.jobs') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-briefcase mr-2"></i>Jobs Portal
                    </a>
                    <a href="{{ route('admin.portals.students') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-user-graduate mr-2"></i>Students Portal
                    </a>
                </div>

                <!-- Payment & Revenue Management -->
                <div class="mt-4">
                    <h3 class="text-xs uppercase px-4 py-2 text-gray-500 font-semibold">Payments & Revenue</h3>
                    <a href="{{ route('admin.payments.stats') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-chart-line mr-2"></i>Payment Statistics
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-receipt mr-2"></i>All Transactions
                    </a>
                    <a href="{{ route('admin.subscriptions.active') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-subscript mr-2"></i>Active Subscriptions
                    </a>
                    <a href="{{ route('admin.subscriptions.all') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-list mr-2"></i>All Subscriptions
                    </a>
                    <a href="{{ route('admin.payments.premium') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-bullhorn mr-2"></i>Premium Payments
                    </a>
                    <a href="{{ route('admin.payment-gateways.index') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-credit-card mr-2"></i>Payment Gateways
                    </a>
                    <a href="{{ route('admin.subscription-packages.index') }}" class="block py-2 px-4 hover:bg-gray-700 pl-8">
                        <i class="fas fa-box-open mr-2"></i>Subscription Packages
                    </a>
                </div>

                <a href="{{ route('admin.settings') }}" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-cog mr-2"></i>General Settings
                </a>
                <a href="/" class="block py-2 px-4 hover:bg-gray-700">
                    <i class="fas fa-external-link-alt mr-2"></i>Visit Site
                </a>
                <a href="{{ route('logout') }}" class="block py-2 px-4 hover:bg-gray-700"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </div>

        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="px-4 py-6">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title')</h1>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables if they exist
            if ($('.datatable').length > 0) {
                $('.datatable').DataTable();
            }
        });
    </script>
</body>
</html>