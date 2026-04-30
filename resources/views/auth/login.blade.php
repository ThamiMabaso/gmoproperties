@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex-1 flex flex-col justify-center py-10 sm:py-12 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="gmo-login-shell">
        <h1 class="text-center text-lg sm:text-xl font-medium text-black mb-7">
            Sign in to your account
        </h1>

        <form class="space-y-5" method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-100 p-4" role="alert">
                    <p class="text-sm font-medium text-red-800 mb-2">We could not sign you in</p>
                    <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-black mb-1.5">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                        class="gmo-login-input block w-full rounded-lg px-3 py-2.5 text-black placeholder-gray-400 text-sm shadow-sm"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-black mb-1.5">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="gmo-login-input block w-full rounded-lg px-3 py-2.5 text-black placeholder-gray-400 text-sm shadow-sm"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center pt-0.5">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold"
                >
                <label for="remember" class="ml-2.5 block text-sm text-gray-800">Remember me</label>
            </div>

            <button
                type="submit"
                class="w-full rounded-full bg-gmo-gold py-3 text-sm font-semibold text-black shadow-sm hover:bg-opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gmo-gold transition-colors"
            >
                Sign in
            </button>
        </form>

        @if (!empty($demoAccounts))
            <div class="mt-11 rounded-xl border border-gray-200 bg-gray-50/80 shadow-sm overflow-hidden">
                <div class="bg-white px-4 py-3.5 border-b border-gray-200 text-center">
                    <h2 class="text-sm font-semibold text-black">Local test accounts</h2>
                    <p class="text-xs text-gray-600 mt-1.5">
                        Password for all: <span class="font-mono text-black">password</span><span class="text-gray-500"> — click a row to fill the form</span>
                    </p>
                </div>
                <ul class="divide-y divide-gray-100" id="demo-account-list">
                    @foreach ($demoAccounts as $account)
                        <li>
                            <button
                                type="button"
                                class="demo-account-row w-full text-left px-4 py-3 text-sm transition-colors hover:bg-gmo-gold/5 focus:bg-gmo-gold/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gmo-gold/40"
                                data-email="{{ $account['email'] }}"
                                data-password="{{ $account['password'] }}"
                            >
                                <span class="font-medium text-black block">{{ $account['label'] }}</span>
                                <span class="text-xs text-gray-600 leading-snug block mt-0.5">{{ $account['hint'] }}</span>
                                <span class="text-xs font-mono text-gmo-black/80 mt-1 block">{{ $account['email'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <script>
                document.getElementById('demo-account-list')?.addEventListener('click', function (e) {
                    const row = e.target.closest('.demo-account-row');
                    if (!row) return;
                    const email = document.getElementById('email');
                    const password = document.getElementById('password');
                    if (email && password) {
                        email.value = row.dataset.email || '';
                        password.value = row.dataset.password || '';
                        email.focus();
                    }
                });
            </script>
        @endif
    </div>
</div>
@endsection
