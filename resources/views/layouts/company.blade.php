<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $company->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
@php
    $portalRole = Auth::user()->isCompanyAdmin() ? 'Company admin' : 'Property manager';
@endphp
    <div class="min-h-screen flex flex-col md:flex-row">
        <aside class="hidden md:flex md:flex-col w-64 bg-gmo-black text-white flex-shrink-0">
            <div class="p-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-gmo-gold">{{ $portalRole }}</p>
                <h1 class="text-lg font-bold text-white mt-1 leading-tight">{{ $company->name }}</h1>
                <p class="text-sm text-gray-400 mt-1">Property management</p>
                @if(Auth::user()->isPropertyManager() && Auth::user()->building_id)
                    @php
                        Auth::user()->loadMissing('building');
                    @endphp
                    <p class="text-xs text-amber-100/90 mt-2 leading-snug border-l-2 border-gmo-gold pl-2">
                        Your building: {{ Auth::user()->building?->name ?? '—' }}
                    </p>
                @endif
            </div>
            <nav class="mt-2 flex-1 space-y-0.5 px-3 pb-8 overflow-y-auto max-h-[calc(100vh-8rem)]">
                <a href="{{ route('company.dashboard', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.dashboard') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Dashboard</a>
                @if(Auth::user()->isCompanyAdmin())
                    <a href="{{ route('company.settings.edit', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.settings.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Company settings</a>
                    <a href="{{ route('company.users.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.users.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Team &amp; users</a>
                @endif
                <a href="{{ route('company.buildings.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.buildings.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Buildings</a>
                <a href="{{ route('company.units.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.units.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Units</a>
                <a href="{{ route('company.applications.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.applications.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Applications</a>
                <a href="{{ route('company.contracts.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.contracts.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Contracts</a>
                <a href="{{ route('company.invoices.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.invoices.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Invoices</a>
                <a href="{{ route('company.maintenance.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.maintenance.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Maintenance</a>
                @can('view_financial_reports')
                    <a href="{{ route('company.financial.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.financial.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Financial reports</a>
                @endcan
                @can('view_announcements')
                    <a href="{{ route('company.announcements.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.announcements.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Announcements</a>
                @endcan
                @can('view_notifications')
                    <a href="{{ route('company.notifications.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.notifications.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                        Notifications
                        @if(Auth::user()->unreadPortalNotificationsCount() > 0)
                            <span class="ml-2 inline-flex min-w-[1.25rem] justify-center rounded-full bg-amber-500 px-1.5 py-0.5 text-xs text-black">{{ Auth::user()->unreadPortalNotificationsCount() }}</span>
                        @endif
                    </a>
                @endcan
                @can('manage_notification_preferences')
                    <a href="{{ route('company.notification-preferences.edit', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.notification-preferences.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">Email preferences</a>
                @endcan
                <a href="{{ route('company.messages.index', $company) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ request()->routeIs('company.messages.*') ? 'bg-white/10 border-l-4 border-gmo-gold pl-[10px]' : '' }}">
                    Messages
                    @if(Auth::user()->unreadMessagesCount() > 0)
                        <span class="ml-2 inline-flex min-w-[1.25rem] justify-center rounded-full bg-blue-600 px-1.5 py-0.5 text-xs text-white">{{ Auth::user()->unreadMessagesCount() }}</span>
                    @endif
                </a>
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
                    <div class="flex gap-3 text-xs whitespace-nowrap">
                        <a href="{{ route('company.dashboard', $company) }}" class="font-medium {{ request()->routeIs('company.dashboard') ? 'text-gmo-gold' : 'text-gray-700' }}">Dashboard</a>
                        @if(Auth::user()->isCompanyAdmin())
                            <a href="{{ route('company.settings.edit', $company) }}" class="font-medium {{ request()->routeIs('company.settings.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Settings</a>
                            <a href="{{ route('company.users.index', $company) }}" class="font-medium {{ request()->routeIs('company.users.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Team</a>
                        @endif
                        <a href="{{ route('company.buildings.index', $company) }}" class="font-medium {{ request()->routeIs('company.buildings.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Buildings</a>
                        <a href="{{ route('company.units.index', $company) }}" class="font-medium {{ request()->routeIs('company.units.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Units</a>
                        <a href="{{ route('company.applications.index', $company) }}" class="font-medium {{ request()->routeIs('company.applications.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Applications</a>
                        <a href="{{ route('company.contracts.index', $company) }}" class="font-medium {{ request()->routeIs('company.contracts.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Contracts</a>
                        <a href="{{ route('company.invoices.index', $company) }}" class="font-medium {{ request()->routeIs('company.invoices.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Invoices</a>
                        <a href="{{ route('company.maintenance.index', $company) }}" class="font-medium {{ request()->routeIs('company.maintenance.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Maint.</a>
                        @can('view_financial_reports')
                            <a href="{{ route('company.financial.index', $company) }}" class="font-medium {{ request()->routeIs('company.financial.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Financial</a>
                        @endcan
                        @can('view_announcements')
                            <a href="{{ route('company.announcements.index', $company) }}" class="font-medium {{ request()->routeIs('company.announcements.*') ? 'text-gmo-gold' : 'text-gray-700' }}">News</a>
                        @endcan
                        @can('view_notifications')
                            <a href="{{ route('company.notifications.index', $company) }}" class="font-medium {{ request()->routeIs('company.notifications.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Notif.</a>
                        @endcan
                        <a href="{{ route('company.messages.index', $company) }}" class="font-medium {{ request()->routeIs('company.messages.*') ? 'text-gmo-gold' : 'text-gray-700' }}">Messages</a>
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
