<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GMO Properties - Property Management Solutions')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-black min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center shrink-0">
                            <span class="text-white font-bold text-lg lowercase tracking-tight">gmo</span>
                        </div>
                        <span class="text-black font-semibold text-sm lowercase tracking-tight">gmo properties</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-black hover:text-gmo-gold transition-colors {{ request()->routeIs('home') ? 'text-gmo-gold font-semibold' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="text-black hover:text-gmo-gold transition-colors {{ request()->routeIs('about') ? 'text-gmo-gold font-semibold' : '' }}">About</a>
                    <a href="{{ route('project') }}" class="text-black hover:text-gmo-gold transition-colors {{ request()->routeIs('project') ? 'text-gmo-gold font-semibold' : '' }}">Project</a>
                    <a href="{{ route('contact') }}" class="text-black hover:text-gmo-gold transition-colors {{ request()->routeIs('contact') ? 'text-gmo-gold font-semibold' : '' }}">Contact</a>
                    @include('partials.portal-entry', ['variant' => 'desktop'])
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button" class="text-black hover:text-gmo-gold focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-black hover:text-gmo-gold {{ request()->routeIs('home') ? 'text-gmo-gold font-semibold' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-black hover:text-gmo-gold {{ request()->routeIs('about') ? 'text-gmo-gold font-semibold' : '' }}">About</a>
                <a href="{{ route('project') }}" class="block px-3 py-2 text-black hover:text-gmo-gold {{ request()->routeIs('project') ? 'text-gmo-gold font-semibold' : '' }}">Project</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-black hover:text-gmo-gold {{ request()->routeIs('contact') ? 'text-gmo-gold font-semibold' : '' }}">Contact</a>
                @include('partials.portal-entry', ['variant' => 'mobile'])
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center shrink-0">
                            <span class="text-white font-bold text-lg lowercase tracking-tight">gmo</span>
                        </div>
                        <span class="text-white font-semibold text-sm lowercase tracking-tight">gmo properties</span>
                    </a>
                </div>
                <div class="text-sm">
                    <p>www.gmoproperties.co.za</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
