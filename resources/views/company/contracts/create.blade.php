@extends('layouts.company')

@section('title', 'Generate Contract')
@section('page-title', 'Generate Contract')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-2">Generate Contract</h2>
        <p class="text-sm text-gray-600 mb-6">Create a contract for a tenant and unit in your company portfolio.</p>

        <form method="POST" action="{{ route('company.contracts.store', $company) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit *</label>
                    <select name="unit_id" id="unit_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        <option value="">Select a unit...</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" @selected((string) old('unit_id') === (string) $unit->id)>
                                {{ $unit->building?->name }} - {{ $unit->unit_number }} ({{ ucfirst(str_replace('_', ' ', $unit->status)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tenant_id" class="block text-sm font-medium text-gray-700">Tenant *</label>
                    <select name="tenant_id" id="tenant_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        <option value="">Select a tenant...</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" @selected((string) old('tenant_id') === (string) $tenant->id)>
                                {{ $tenant->name }} ({{ $tenant->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('tenant_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="monthly_rent" class="block text-sm font-medium text-gray-700">Monthly rent (R) *</label>
                    <input type="number" step="0.01" min="0" name="monthly_rent" id="monthly_rent" value="{{ old('monthly_rent') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('monthly_rent')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="deposit" class="block text-sm font-medium text-gray-700">Deposit (R)</label>
                    <input type="number" step="0.01" min="0" name="deposit" id="deposit" value="{{ old('deposit', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('deposit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="terms_text" class="block text-sm font-medium text-gray-700">Contract content / clauses</label>
                <textarea name="terms_text" id="terms_text" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold" placeholder="Provide the terms to include in this contract...">{{ old('terms_text', $company->contract_template) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">You can customize this per contract. Leave as-is to use your company default template.</p>
                @error('terms_text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('company.contracts.index', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90">Generate Contract</button>
            </div>
        </form>
    </div>
</div>
@endsection
