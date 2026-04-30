@extends('layouts.company')

@section('title', 'Application Details')
@section('page-title', 'Application Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">Application Details</h2>
                <p class="text-gray-600">Application #{{ $application->id }}</p>
            </div>
            <div>
                @if($application->status === 'pending')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                @elseif($application->status === 'approved')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                @elseif($application->status === 'rejected')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                @else
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($application->status) }}</span>
                @endif
            </div>
        </div>

        <!-- Application Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Personal Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Name:</span> {{ $application->first_name }} {{ $application->last_name }}</p>
                    <p><span class="text-gray-600">Email:</span> {{ $application->email }}</p>
                    <p><span class="text-gray-600">Phone:</span> {{ $application->phone }}</p>
                    <p><span class="text-gray-600">ID Number:</span> {{ $application->id_number }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Unit Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Building:</span> {{ $application->unit->building->name }}</p>
                    <p><span class="text-gray-600">Unit:</span> {{ $application->unit->unit_number }}</p>
                    <p><span class="text-gray-600">Monthly Rent:</span> R {{ number_format($application->unit->monthly_rent, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Application Details</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Type:</span> {{ ucfirst(str_replace('_', ' ', $application->application_type)) }}</p>
                    @if($application->student_number)
                        <p><span class="text-gray-600">Student Number:</span> {{ $application->student_number }}</p>
                    @endif
                    @if($application->employment_type)
                        <p><span class="text-gray-600">Employment Type:</span> {{ ucfirst(str_replace('_', ' ', $application->employment_type)) }}</p>
                    @endif
                    @if($application->source_of_funding)
                        <p><span class="text-gray-600">Source of Funding:</span> {{ $application->source_of_funding }}</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Lease Information</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Start Date:</span> {{ $application->lease_start_date->format('M d, Y') }}</p>
                    <p><span class="text-gray-600">End Date:</span> {{ $application->lease_end_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        @if($application->next_of_kin_details)
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Next of Kin</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Name:</span> {{ $application->next_of_kin_details['name'] ?? 'N/A' }}</p>
                <p><span class="text-gray-600">Phone:</span> {{ $application->next_of_kin_details['phone'] ?? 'N/A' }}</p>
                <p><span class="text-gray-600">Relationship:</span> {{ $application->next_of_kin_details['relationship'] ?? 'N/A' }}</p>
            </div>
        </div>
        @endif

        <!-- Documents -->
        @if($application->documents->count() > 0)
        <div class="mb-6">
            <h3 class="font-semibold mb-4">Uploaded Documents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($application->documents as $document)
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

        <!-- Actions -->
        @if(($application->status === 'pending' || $application->status === 'under_review') && Auth::user()->can('approve_applications'))
        <div class="border-t pt-6 mt-6">
            <div class="flex space-x-4">
                <form method="POST" action="{{ route('company.applications.approve', [$company, $application]) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve Application
                    </button>
                </form>
                <button onclick="showRejectModal()" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Reject Application
                </button>
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="reject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Reject Application</h3>
                <form method="POST" action="{{ route('company.applications.reject', [$company, $application]) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                        <textarea name="rejection_reason" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideRejectModal()" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function showRejectModal() {
            document.getElementById('reject-modal').classList.remove('hidden');
        }
        function hideRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }
        </script>
        @endif

        <div class="mt-6">
            <a href="{{ route('company.applications.index', $company) }}" class="text-gmo-gold hover:underline">
                ← Back to Applications
            </a>
        </div>
    </div>
</div>
@endsection
