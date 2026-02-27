@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-2xl font-extrabold text-gray-900">Registration Submitted</h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ session('success', 'Your company registration has been submitted successfully. You will receive an email once your account is approved.') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-gmo-gold hover:bg-opacity-90">
                    Go to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
