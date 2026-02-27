@extends('layouts.company')

@section('title', 'Create Building')
@section('page-title', 'Create Building')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('company.buildings.store', $company) }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Building Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Building Code</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700">Province *</label>
                        <input type="text" name="province" id="province" value="{{ old('province') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        @error('province')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="property_type" class="block text-sm font-medium text-gray-700">Property Type *</label>
                    <select name="property_type" id="property_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        <option value="">Select type</option>
                        <option value="residential" {{ old('property_type') === 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="student_accommodation" {{ old('property_type') === 'student_accommodation' ? 'selected' : '' }}>Student Accommodation</option>
                        <option value="mixed" {{ old('property_type') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                    @error('property_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_units" class="block text-sm font-medium text-gray-700">Total Units</label>
                    <input type="number" name="total_units" id="total_units" value="{{ old('total_units') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('total_units')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-gmo-gold focus:ring-gmo-gold border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('company.buildings.index', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                    Create Building
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
