<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\TenantApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenantApplicationController extends Controller
{
    /**
     * Display a listing of tenant applications.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        $this->ensureCompanyAccess($company);

        $query = $company->tenantApplications()
            ->with(['unit.building', 'reviewer'])
            ->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('application_type')) {
            $query->where('application_type', request('application_type'));
        }

        $applications = $query->paginate(15);

        return view('company.applications.index', compact('company', 'applications'));
    }

    /**
     * Display the specified application.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\TenantApplication  $application
     * @return \Illuminate\View\View
     */
    public function show(Company $company, TenantApplication $application)
    {
        $this->ensureCompanyAccess($company);

        if ($application->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load(['unit.building', 'company', 'documents', 'reviewer']);

        return view('company.applications.show', compact('company', 'application'));
    }

    /**
     * Approve the specified application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @param  \App\Models\TenantApplication  $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Company $company, TenantApplication $application)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('approve_applications');

        if ($application->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        if ($application->status !== 'pending' && $application->status !== 'under_review') {
            return back()->with('error', 'Only pending or under review applications can be approved.');
        }

        DB::transaction(function () use ($application, $request) {
            // Update application status
            $application->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Create tenant user account
            $tenant = User::create([
                'company_id' => $application->company_id,
                'name' => $application->first_name . ' ' . $application->last_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'type' => 'tenant',
                'id_number' => $application->id_number,
                'student_number' => $application->student_number,
                'employment_type' => $application->employment_type,
                'next_of_kin_details' => $application->next_of_kin_details,
                'source_of_funding' => $application->source_of_funding,
                'is_active' => true,
                'password' => bcrypt(Str::random(12)), // Temporary password, will be reset
            ]);

            $tenant->assignRole('tenant');

            // Create contract
            $contract = Contract::create([
                'company_id' => $application->company_id,
                'unit_id' => $application->unit_id,
                'tenant_id' => $tenant->id,
                'application_id' => $application->id,
                'contract_number' => 'CON-' . strtoupper(Str::random(8)),
                'start_date' => $application->lease_start_date,
                'end_date' => $application->lease_end_date,
                'monthly_rent' => $application->unit->monthly_rent,
                'deposit' => $application->unit->deposit,
                'status' => 'pending_signature',
            ]);

            // Update unit status
            $application->unit->update(['status' => 'reserved']);

            // TODO: Send email to tenant with login credentials and contract signing link
        });

        return redirect()->route('company.applications.show', [$company, $application])
            ->with('success', 'Application approved. Tenant account created and contract generated.');
    }

    /**
     * Reject the specified application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @param  \App\Models\TenantApplication  $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Company $company, TenantApplication $application)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('approve_applications');

        if ($application->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // TODO: Send rejection email to applicant

        return redirect()->route('company.applications.show', [$company, $application])
            ->with('success', 'Application rejected.');
    }
}
