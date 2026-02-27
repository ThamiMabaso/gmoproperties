@extends('layouts.company')

@section('title', 'Tenant Applications')
@section('page-title', 'Tenant Applications')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Tenant Applications</h2>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('company.applications.index', $company) }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Application Type</label>
                <select name="application_type" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Types</option>
                    <option value="student" {{ request('application_type') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="working_tenant" {{ request('application_type') == 'working_tenant' ? 'selected' : '' }}>Working Tenant</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>

        <!-- Applications Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company.applications.show', [$company, $application]) }}" class="text-gmo-gold hover:underline">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $application->email }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $application->unit->building->name }} - {{ $application->unit->unit_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $application->application_type)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($application->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($application->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($application->status === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($application->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('company.applications.show', [$company, $application]) }}" class="text-gmo-gold hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No applications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
