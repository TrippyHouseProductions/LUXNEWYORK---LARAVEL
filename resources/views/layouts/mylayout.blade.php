<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>LUXNEWYORK</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    @livewire('navbar')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    


    @vite('resources/js/app.js')
    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
