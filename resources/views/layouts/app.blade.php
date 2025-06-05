<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'RedWave')</title>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-gray-1 text-gray-900">

    {{-- Reusable Navbar --}}
    @include('partials.navbar')

    {{-- Halaman Konten --}}
    <main class="@yield('main-class', 'container mx-auto px-4 mt-6')">
        @yield('content')
    </main>
    
    {{-- Section khusus footer yang full width --}}
    @yield('footer')

    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Additional Scripts -->
    <script>
        // Global JavaScript utilities
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>