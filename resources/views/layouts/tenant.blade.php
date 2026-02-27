<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Tenant Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gmo-black text-gmo-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gmo-gold">GMO Properties</h1>
                <p class="text-sm text-gray-400 mt-1">Tenant Portal</p>
            </div>
            <nav class="mt-8">
                <a href="{{ route('tenant.dashboard') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.dashboard') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('tenant.applications.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.applications.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Applications
                </a>
                <a href="{{ route('tenant.contracts.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.contracts.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Contracts
                </a>
                <a href="{{ route('tenant.invoices.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.invoices.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Invoices
                </a>
                <a href="{{ route('tenant.maintenance.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.maintenance.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Maintenance
                </a>
                <a href="{{ route('tenant.messages.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('tenant.messages.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Messages
                    @if(Auth::user()->unreadMessagesCount() > 0)
                        <span class="ml-2 px-2 py-0.5 text-xs bg-blue-600 text-white rounded-full">{{ Auth::user()->unreadMessagesCount() }}</span>
                    @endif
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gmo-gold">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
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
