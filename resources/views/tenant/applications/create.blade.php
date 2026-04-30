@extends('layouts.tenant')

@section('title', 'Apply for Unit')
@section('page-title', 'Apply for Unit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Application Form</h2>
        
        <!-- Unit Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold mb-2">Unit Information</h3>
            <p class="text-sm text-gray-600">{{ $unit->building->name }} - {{ $unit->unit_number }}</p>
            <p class="text-sm text-gray-600">Monthly Rent: <span class="font-semibold text-gmo-gold">R {{ number_format($unit->monthly_rent, 2) }}</span></p>
        </div>

        <form method="POST" action="{{ route('tenant.applications.store', $unit) }}" enctype="multipart/form-data">
            @csrf

            <!-- Application Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Application Type *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="application_type" value="student" class="mr-2" onchange="toggleApplicationType()" required>
                        Student Tenant
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="application_type" value="working_tenant" class="mr-2" onchange="toggleApplicationType()" required>
                        Working Tenant
                    </label>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email (account)</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly required class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 cursor-not-allowed" title="Uses your portal login email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID Number *</label>
                <input type="text" name="id_number" value="{{ old('id_number') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Student Specific Fields -->
            <div id="student-fields" style="display: none;" class="mb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student Number</label>
                    <input type="text" name="student_number" value="{{ old('student_number') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source of Funding *</label>
                    <input type="text" name="source_of_funding" value="{{ old('source_of_funding') }}" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., NSFAS, Bursary, Parents">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Registration *</label>
                    <input type="file" name="proof_of_registration" accept=".pdf,.jpg,.jpeg,.png" class="w-full border-gray-300 rounded-md shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                </div>
            </div>

            <!-- Working Tenant Specific Fields -->
            <div id="working-tenant-fields" style="display: none;" class="mb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type *</label>
                    <select name="employment_type" class="w-full border-gray-300 rounded-md shadow-sm" onchange="toggleEmploymentType()">
                        <option value="">Select...</option>
                        <option value="employed" {{ old('employment_type') == 'employed' ? 'selected' : '' }}>Employed</option>
                        <option value="self_employed" {{ old('employment_type') == 'self_employed' ? 'selected' : '' }}>Self Employed</option>
                    </select>
                </div>
                <div id="proof-of-income-field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Income *</label>
                    <input type="file" name="proof_of_income" accept=".pdf,.jpg,.jpeg,.png" class="w-full border-gray-300 rounded-md shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                </div>
            </div>

            <!-- Lease Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lease Start Date *</label>
                    <input type="date" name="lease_start_date" value="{{ old('lease_start_date') }}" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lease End Date *</label>
                    <input type="date" name="lease_end_date" value="{{ old('lease_end_date') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <!-- Next of Kin -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Next of Kin Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="next_of_kin_name" value="{{ old('next_of_kin_name') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="text" name="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship *</label>
                        <input type="text" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Required Documents</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Document *</label>
                    <input type="file" name="id_document" accept=".pdf,.jpg,.jpeg,.png" required class="w-full border-gray-300 rounded-md shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('tenant.applications.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gmo-gold text-black rounded-md hover:bg-opacity-90">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleApplicationType() {
    const studentRadio = document.querySelector('input[name="application_type"][value="student"]');
    const workingRadio = document.querySelector('input[name="application_type"][value="working_tenant"]');
    const studentFields = document.getElementById('student-fields');
    const workingFields = document.getElementById('working-tenant-fields');

    if (studentRadio.checked) {
        studentFields.style.display = 'block';
        workingFields.style.display = 'none';
        // Make student fields required
        studentFields.querySelector('input[name="source_of_funding"]').required = true;
        studentFields.querySelector('input[name="proof_of_registration"]').required = true;
        // Remove required from working fields
        if (workingFields.querySelector('input[name="proof_of_income"]')) {
            workingFields.querySelector('input[name="proof_of_income"]').required = false;
        }
    } else if (workingRadio.checked) {
        studentFields.style.display = 'none';
        workingFields.style.display = 'block';
        // Remove required from student fields
        studentFields.querySelector('input[name="source_of_funding"]').required = false;
        studentFields.querySelector('input[name="proof_of_registration"]').required = false;
    }
}

function toggleEmploymentType() {
    const employmentType = document.querySelector('select[name="employment_type"]').value;
    const proofOfIncomeField = document.getElementById('proof-of-income-field');
    
    if (employmentType === 'employed') {
        proofOfIncomeField.style.display = 'block';
        proofOfIncomeField.querySelector('input[name="proof_of_income"]').required = true;
    } else {
        proofOfIncomeField.style.display = 'none';
        if (proofOfIncomeField.querySelector('input[name="proof_of_income"]')) {
            proofOfIncomeField.querySelector('input[name="proof_of_income"]').required = false;
        }
    }
}
</script>
@endsection
