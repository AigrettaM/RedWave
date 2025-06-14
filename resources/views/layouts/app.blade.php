<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'RedWave')</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'redwave': '#B31312',
                        'redwave-dark': '#8B0000',
                        'redwave-light': '#DC2626'
                    },
                    
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui'],
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .botman-widget-bubble {
            background-color: #B31312 !important;
        }
        
        .botman-widget-header {
            background-color: #B31312 !important;
        }
        
        .botman-widget-message-text {
            color: #B31312 !important;
        }
        
        .botman-widget-input {
            border-color: #B31312 !important;
        }
        
        .botman-widget-input:focus {
            border-color: #8B0000 !important;
            box-shadow: 0 0 0 2px rgba(179, 19, 18, 0.2) !important;
        }
        
        .botman-widget-send {
            background-color: #B31312 !important;
        }
        
        .botman-widget-send:hover {
            background-color: #8B0000 !important;
        }
        
        /* Ensure main content doesn't overlap with fixed navbar */
        body {
            padding-top: 10px; /* Height of navbar (h-24 = 6rem = 96px) */
        }
    </style>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('navbar.css') }}">
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">

    {{-- Include Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main class="@yield('main-class', 'container mx-auto px-4 mt-6')">
        @yield('content')
    </main>
    
    {{-- Include Footer --}}
    @include('partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/dropdown.js') }}"></script>
    @stack('scripts')
    
    <!-- Global JavaScript utilities -->
    <script>
        window.Laravel = {
            csrToken: '{{ csrf_token() }}'
        };
    </script>
    
    <!-- BotMan Widget Scripts -->
    @stack('botman-widget')
</body>
</html>
