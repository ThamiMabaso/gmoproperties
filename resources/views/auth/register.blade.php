@extends('layouts.app')

@section('title', 'Register Company')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Register Your Company</h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-semibold">Company Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name *</label>
                        <input id="company_name" name="company_name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold" value="{{ old('company_name') }}">
                    </div>
                    <div>
                        <label for="company_email" class="block text-sm font-medium text-gray-700">Company Email *</label>
                        <input id="company_email" name="company_email" type="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold" value="{{ old('company_email') }}">
                    </div>
                </div>
                <div>
                    <label for="company_phone" class="block text-sm font-medium text-gray-700">Company Phone</label>
                    <input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold" value="{{ old('company_phone') }}">
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-semibold">Admin Account</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input id="name" name="name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input id="email" name="email" type="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                        <input id="password" name="password" type="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gmo-gold focus:border-gmo-gold">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-black bg-gmo-gold hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gmo-gold">
                    Register Company
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
