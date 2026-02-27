<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $company->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gmo-black text-gmo-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gmo-gold">{{ $company->name }}</h1>
                <p class="text-sm text-gray-400 mt-1">Property Management</p>
            </div>
            <nav class="mt-8">
                <a href="{{ route('company.dashboard', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.dashboard') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('company.buildings.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.buildings.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Buildings
                </a>
                <a href="{{ route('company.units.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.units.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Units
                </a>
                <a href="{{ route('company.applications.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.applications.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Applications
                </a>
                <a href="{{ route('company.contracts.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.contracts.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Contracts
                </a>
                <a href="{{ route('company.invoices.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.invoices.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Invoices
                </a>
                <a href="{{ route('company.maintenance.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.maintenance.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Maintenance
                </a>
                <a href="{{ route('company.financial.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.financial.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
                    Financial Reports
                </a>
                <a href="{{ route('company.messages.index', $company) }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('company.messages.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : '' }}">
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
