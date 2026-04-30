<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - GMO Properties</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col md:flex-row">
        <aside class="hidden md:flex md:flex-col w-64 bg-gmo-black text-white flex-shrink-0">
            <div class="p-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-gmo-gold">GMO Properties</p>
                <h1 class="text-xl font-bold text-white mt-1">Admin Portal</h1>
                <p class="text-sm text-gray-400 mt-1">Service provider</p>
            </div>
            <nav class="mt-4 flex-1 space-y-0.5 px-3 pb-8">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.companies.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.companies.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                    Companies
                </a>
                <a href="{{ route('search') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('search') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                    Search
                </a>
                @can('view_admin_reports')
                    <a href="{{ route('admin.reports.updates.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('admin.reports.updates.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                        Platform updates
                    </a>
                @endcan
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center gap-4 text-sm">
                        <a href="{{ route('home') }}" class="text-gmo-gold hover:underline font-medium">Public site</a>
                        <span class="text-gray-400 hidden sm:inline">|</span>
                        <span class="text-gray-600 truncate max-w-[10rem] sm:max-w-none">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gmo-gold font-medium">Logout</button>
                        </form>
                    </div>
                </div>
                <div class="md:hidden border-t border-gray-100 bg-gray-50 px-3 py-2 overflow-x-auto">
                    <div class="flex gap-4 text-sm whitespace-nowrap">
                        <a href="{{ route('admin.dashboard') }}" class="font-medium {{ request()->routeIs('admin.dashboard') ? 'text-gmo-gold' : 'text-gray-700' }}">Dashboard</a>
                        <a href="{{ route('admin.companies.index') }}" class="font-medium {{ request()->routeIs('admin.companies.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Companies</a>
                        <a href="{{ route('search') }}" class="font-medium {{ request()->routeIs('search') ? 'text-gmo-gold' : 'text-gray-700' }}">Search</a>
                        @can('view_admin_reports')
                            <a href="{{ route('admin.reports.updates.index') }}" class="font-medium {{ request()->routeIs('admin.reports.updates.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Updates</a>
                        @endcan
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
