@extends('layouts.company')

@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Create Invoice</h2>

        <form method="POST" action="{{ route('company.invoices.store', $company) }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Contract *</label>
                <select name="contract_id" required class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a contract...</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                            {{ $contract->contract_number }} - {{ $contract->tenant->name }} ({{ $contract->unit->building->name }} - {{ $contract->unit->unit_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Type *</label>
                    <select name="type" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="deposit" {{ old('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="penalty" {{ old('type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal (R) *</label>
                    <input type="number" name="subtotal" value="{{ old('subtotal') }}" step="0.01" min="0" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount (R)</label>
                <input type="number" name="tax_amount" value="{{ old('tax_amount', 0) }}" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('company.invoices.index', $company) }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
