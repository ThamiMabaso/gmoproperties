@extends('layouts.admin')

@section('title', $company->name)

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Slug: {{ $company->slug }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.companies.edit', $company) }}" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Edit Company
            </a>
            <a href="{{ route('company.dashboard', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                View Company Portal
            </a>
        </div>
    </div>

    <!-- Company Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Company Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ $company->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Phone</p>
                <p class="font-medium">{{ $company->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-medium">{{ $company->address ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Registration Number</p>
                <p class="font-medium">{{ $company->registration_number ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">VAT Number</p>
                <p class="font-medium">{{ $company->vat_number ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p>
                    @if($company->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Subscription Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Subscription Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Plan</p>
                <p class="font-medium">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($company->subscription_plan) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Expires At</p>
                <p class="font-medium">
                    {{ $company->subscription_expires_at ? $company->subscription_expires_at->format('M d, Y') : 'Not set' }}
                    @if($company->subscription_expires_at && $company->subscription_expires_at->isPast())
                        <span class="text-red-600 text-xs">(Expired)</span>
                    @elseif($company->subscription_expires_at && $company->subscription_expires_at->isFuture())
                        <span class="text-gray-500 text-xs">({{ $company->subscription_expires_at->diffForHumans() }})</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Feature Access</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @if($company->feature_access && count($company->feature_access) > 0)
                        @foreach($company->feature_access as $feature)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gmo-gold bg-opacity-20 text-gmo-gold">
                                {{ ucfirst(str_replace('_', ' ', $feature)) }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-400 text-sm">No features enabled</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Financial Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gmo-gold">R {{ number_format($financialSummary['total_revenue'], 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Monthly Revenue</p>
                <p class="text-2xl font-bold text-green-600">R {{ number_format($financialSummary['monthly_revenue'], 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pending Invoices</p>
                <p class="text-2xl font-bold text-yellow-600">R {{ number_format($financialSummary['pending_invoices'], 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Overdue Invoices</p>
                <p class="text-2xl font-bold text-red-600">R {{ number_format($financialSummary['overdue_invoices'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Company Statistics</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Users</span>
                    <span class="font-semibold">{{ $company->users->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Buildings</span>
                    <span class="font-semibold">{{ $company->buildings->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Units</span>
                    <span class="font-semibold">{{ $company->units->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tenant Applications</span>
                    <span class="font-semibold">{{ $company->tenantApplications->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Active Contracts</span>
                    <span class="font-semibold">{{ $company->contracts->where('status', 'active')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="font-medium">{{ $company->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="font-medium">{{ $company->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
