@extends('layouts.company')

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
            <div>
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
                <h3 class="font-semibold mb-2">Tenant Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Name:</span> {{ $contract->tenant->name }}</p>
                    <p><span class="text-gray-600">Email:</span> {{ $contract->tenant->email }}</p>
                    <p><span class="text-gray-600">Phone:</span> {{ $contract->tenant->phone }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Unit Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Building:</span> {{ $contract->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $contract->unit->unit_number }}</p>
                    <p><span class="text-gray-600">Address:</span> {{ $contract->unit->building->address }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Lease Terms</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Start Date:</span> {{ $contract->start_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">End Date:</span> {{ $contract->end_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">Monthly Rent:</span> <span class="font-semibold text-gmo-gold">R {{ number_format($contract->monthly_rent, 2) }}</span></p>
                    <p><span class="text-gray-600">Deposit:</span> R {{ number_format($contract->deposit, 2) }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Signing Status</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Tenant Signature:</span> 
                        @if($contract->signed_by_tenant)
                            <span class="text-green-600 font-semibold">✓ Signed</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Pending</span>
                        @endif
                    </p>
                    <p><span class="text-gray-600">Company Signature:</span> 
                        @if($contract->signed_by_company)
                            <span class="text-green-600 font-semibold">✓ Signed</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Pending</span>
                        @endif
                    </p>
                    @if($contract->signed_at)
                        <p><span class="text-gray-600">Signed At:</span> {{ $contract->signed_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sign Contract as Company -->
        @if($contract->status === 'pending_signature' && !$contract->signed_by_company)
        <div class="border-t pt-6 mt-6">
            <form method="POST" action="{{ route('company.contracts.sign', [$company, $contract]) }}">
                @csrf
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Sign Contract as Company
                </button>
            </form>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('company.contracts.index', $company) }}" class="text-gmo-gold hover:underline">
                ← Back to Contracts
            </a>
        </div>
    </div>
</div>
@endsection
