@extends('layouts.tenant')

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

        @if($application->rejection_reason)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="font-semibold text-red-800 mb-2">Rejection Reason</h3>
            <p class="text-sm text-red-700">{{ $application->rejection_reason }}</p>
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

        <div class="flex justify-end">
            <a href="{{ route('tenant.applications.index') }}" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                Back to Applications
            </a>
        </div>
    </div>
</div>
@endsection
