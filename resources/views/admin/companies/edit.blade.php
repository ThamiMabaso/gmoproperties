@extends('layouts.admin')

@section('title', 'Edit Company')

@section('content')
<div class="p-6 max-w-4xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Company: {{ $company->name }}</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.companies.update', $company) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Company Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">{{ old('address', $company->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="registration_number" class="block text-sm font-medium text-gray-700">Registration Number</label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', $company->registration_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('registration_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vat_number" class="block text-sm font-medium text-gray-700">VAT Number</label>
                        <input type="text" name="vat_number" id="vat_number" value="{{ old('vat_number', $company->vat_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('vat_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subscription_plan" class="block text-sm font-medium text-gray-700">Subscription Plan *</label>
                        <select name="subscription_plan" id="subscription_plan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                            <option value="basic" {{ old('subscription_plan', $company->subscription_plan) === 'basic' ? 'selected' : '' }}>Basic (R500/month)</option>
                            <option value="professional" {{ old('subscription_plan', $company->subscription_plan) === 'professional' ? 'selected' : '' }}>Professional (R1,500/month)</option>
                            <option value="enterprise" {{ old('subscription_plan', $company->subscription_plan) === 'enterprise' ? 'selected' : '' }}>Enterprise (R3,000/month)</option>
                        </select>
                        @error('subscription_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700">Subscription Expires At</label>
                        <input type="date" name="subscription_expires_at" id="subscription_expires_at" value="{{ old('subscription_expires_at', $company->subscription_expires_at?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('subscription_expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Feature Access</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($availableFeatures as $key => $label)
                            <div class="flex items-center">
                                <input type="checkbox" name="feature_access[]" id="feature_{{ $key }}" value="{{ $key }}" {{ in_array($key, old('feature_access', $company->feature_access ?? [])) ? 'checked' : '' }} class="h-4 w-4 text-gmo-gold focus:ring-gmo-gold border-gray-300 rounded">
                                <label for="feature_{{ $key }}" class="ml-2 block text-sm text-gray-700">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('feature_access')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }} class="h-4 w-4 text-gmo-gold focus:ring-gmo-gold border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('admin.companies.show', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                    Update Company
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
