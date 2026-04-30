@extends('layouts.tenant')

@section('title', 'Contract Details')
@section('page-title', 'Contract Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Contract Details</h2>
                <p class="text-gray-600">Contract #{{ $contract->contract_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($contract->signed_by_tenant)
                    <a href="{{ route('tenant.contracts.download', $contract) }}" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Download PDF
                    </a>
                @endif
                @if($contract->status === 'active')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                @elseif($contract->status === 'pending_signature')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Signature</span>
                @else
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($contract->status) }}</span>
                @endif
            </div>
        </div>

        <!-- Contract Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Unit Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Building:</span> {{ $contract->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $contract->unit->unit_number }}</p>
                    <p><span class="text-gray-600">Address:</span> {{ $contract->unit->building->address }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Lease Terms</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Start Date:</span> {{ $contract->start_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">End Date:</span> {{ $contract->end_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">Monthly Rent:</span> <span class="font-semibold text-gmo-gold">R {{ number_format($contract->monthly_rent, 2) }}</span></p>
                    <p><span class="text-gray-600">Deposit:</span> R {{ number_format($contract->deposit, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Signing Status -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold mb-4">Signing Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tenant Signature</p>
                    @if($contract->signed_by_tenant)
                        <p class="text-sm font-semibold text-green-600">
                            ✓ Signed
                            @if($contract->signed_at)
                                on {{ $contract->signed_at->format('M d, Y') }}
                            @endif
                        </p>
                    @else
                        <p class="text-sm font-semibold text-yellow-600">Pending</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Company Signature</p>
                    @if($contract->signed_by_company)
                        <p class="text-sm font-semibold text-green-600">✓ Signed</p>
                    @else
                        <p class="text-sm font-semibold text-yellow-600">Pending</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contract Terms -->
        @if($contract->terms)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Contract Terms</h3>
            <div class="bg-gray-50 rounded-lg p-4 text-sm">
                @if(is_array($contract->terms))
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($contract->terms as $term)
                            <li>{{ $term }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ $contract->terms }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($contract->terms_text)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Contract Content</h3>
            <div class="bg-gray-50 rounded-lg p-4 text-sm whitespace-pre-wrap border border-gray-200">{{ $contract->terms_text }}</div>
        </div>
        @endif

        <!-- Documents -->
        @if($contract->documents->count() > 0)
        <div class="mb-6">
            <h3 class="font-semibold mb-4">Contract Documents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($contract->documents as $document)
                    <div class="border rounded-lg p-4">
                        <p class="font-medium mb-2">{{ $document->name }}</p>
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-sm text-gmo-gold hover:underline">
                            View Document
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sign Contract -->
        @if($contract->status === 'pending_signature' && !$contract->signed_by_tenant)
        <div class="border-t pt-6 mt-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">Please review the contract terms above. By signing, you agree to all terms and conditions.</p>
            </div>
            <form method="POST" action="{{ route('tenant.contracts.sign', $contract) }}">
                @csrf
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" required class="mr-2">
                        <span class="text-sm">I have read and agree to the terms and conditions of this contract</span>
                    </label>
                </div>
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Sign Contract
                </button>
            </form>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('tenant.contracts.index') }}" class="text-gmo-gold hover:underline">
                ← Back to Contracts
            </a>
        </div>
    </div>
</div>
@endsection
