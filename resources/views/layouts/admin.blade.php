<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Admin | LUXNEWYORK</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(session('api_token'))
    <script>
        localStorage.setItem('api_token', '{{ session('api_token') }}');
    </script>
    @endif
    @if (request()->routeIs('logout') || session('logged_out'))
    <script>
        localStorage.removeItem('api_token');
    </script>
    @endif
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Admin Layout Structure -->
    <div class="flex h-screen">
        <!-- Admin Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Admin Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button (hidden on desktop) -->
                        <button id="mobile-menu-button" class="lg:hidden text-gray-500 focus:outline-none mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900">@yield('admin-title', 'Admin Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Admin User Menu -->
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center text-sm rounded-full focus:outline-none">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-medium">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.querySelector('x-admin-sidebar');
            sidebar.classList.toggle('hidden');
        });

        // User menu dropdown functionality
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
    @vite('resources/js/app.js')
</body>
</html>