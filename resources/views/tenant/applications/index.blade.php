@extends('layouts.tenant')

@section('title', 'Available Units')
@section('page-title', 'Available Units')

@section('content')
<div class="space-y-6">
    @if($myApplications->isNotEmpty())
        <div class="bg-white rounded-lg shadow p-6 border border-gmo-gold/30">
            <h2 class="text-xl font-bold text-gray-900 mb-2">My applications</h2>
            <p class="text-sm text-gray-600 mb-4">Submitted under your account email ({{ Auth::user()->email }}).</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Unit</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Company</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Type</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Submitted</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-700"> </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($myApplications as $app)
                            <tr class="hover:bg-gray-50/80">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $app->unit?->building?->name ?? '—' }} — {{ $app->unit?->unit_number ?? '—' }}
                                </td>
                                <td class="px-4 py-3">{{ $app->company?->name ?? '—' }}</td>
                                <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $app->application_type) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium capitalize text-gray-800">{{ str_replace('_', ' ', $app->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $app->created_at->format('M j, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('tenant.applications.show', $app) }}" class="font-medium text-gmo-gold hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Available Units</h2>
        <p class="text-gray-600 mb-6">Browse available rental units and submit an application.</p>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('tenant.applications.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                <select name="company" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>

        <!-- Units Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($units as $unit)
                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">{{ $unit->building->name }} - {{ $unit->unit_number }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $unit->building->address }}</p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Monthly Rent:</span>
                                <span class="font-semibold text-gmo-gold">R {{ number_format($unit->monthly_rent, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Deposit:</span>
                                <span class="font-semibold">R {{ number_format($unit->deposit, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Type:</span>
                                <span class="font-semibold">{{ ucfirst($unit->unit_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Bedrooms:</span>
                                <span class="font-semibold">{{ $unit->bedrooms }}</span>
                            </div>
                        </div>

                        <a href="{{ route('tenant.applications.create', $unit) }}" class="block w-full text-center px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                            Apply Now
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No available units found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
