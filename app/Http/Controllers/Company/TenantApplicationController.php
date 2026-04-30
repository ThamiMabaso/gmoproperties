<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\Contract;
use App\Models\TenantApplication;
use App\Models\User;
use App\Notifications\TenantApplicationDecisionNotification;
use App\Support\DashboardChartData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantApplicationController extends BaseCompanyController
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

        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = $this->scopedTenantApplicationsQuery($company, $buildingIds)
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
     * Paid invoice totals by month (last 6 months) — for dashboard-style charts.
     *
     * @return array<string, mixed>
     */
    protected function monthlyIncome(): array
    {
        $company = $this->resolveRouteCompany();
        $buildingIds = $this->managedBuildingIdsFor($company);
        $charts = DashboardChartData::forCompany($company, $buildingIds)['charts'];

        return $charts['paidInvoiceLine'] ?? [];
    }

    /**
     * Tenant applications grouped by status — for charts.
     *
     * @return array<string, mixed>
     */
    protected function tenantStatus(): array
    {
        $company = $this->resolveRouteCompany();
        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = $this->scopedTenantApplicationsQuery($company, $buildingIds)
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status');

        $rows = $query->pluck('c', 'status');

        $labels = [];
        $values = [];

        foreach ($rows as $status => $count) {
            $labels[] = ucfirst(str_replace('_', ' ', (string) $status));
            $values[] = (int) $count;
        }

        if ($labels === []) {
            $labels = ['No applications'];
            $values = [0];
        }

        return [
            'type' => 'doughnut',
            'title' => 'Applications by status',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Company income trend (same series as monthly paid invoices) — alias for dashboards that expect this name.
     *
     * @return array<string, mixed>
     */
    protected function companyIncome(): array
    {
        return $this->monthlyIncome();
    }

    /**
     * Company bound to the current `{company}` route segment.
     */
    private function resolveRouteCompany(): Company
    {
        $company = request()->route('company');

        if (!$company instanceof Company) {
            abort(500, 'Company context is required for chart data.');
        }

        return $company;
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

        $this->ensureTenantApplicationInScope($company, $application);

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

        $this->ensureTenantApplicationInScope($company, $application);

        if ($application->status !== 'pending' && $application->status !== 'under_review') {
            return back()->with('error', 'Only pending or under review applications can be approved.');
        }

        DB::transaction(function () use ($application) {
            // Update application status
            $application->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Reuse existing user by email to avoid duplicate-account failures.
            $tenant = User::query()->where('email', $application->email)->first();

            if ($tenant === null) {
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
            } else {
                $tenant->update([
                    'company_id' => $application->company_id,
                    'name' => $application->first_name . ' ' . $application->last_name,
                    'phone' => $application->phone,
                    'type' => 'tenant',
                    'id_number' => $application->id_number,
                    'student_number' => $application->student_number,
                    'employment_type' => $application->employment_type,
                    'next_of_kin_details' => $application->next_of_kin_details,
                    'source_of_funding' => $application->source_of_funding,
                    'is_active' => true,
                ]);
            }

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
                'terms_text' => (string) ($application->company?->contract_template ?? ''),
                'status' => 'pending_signature',
            ]);

            // Update unit status
            $application->unit->update(['status' => 'reserved']);

        });

        $application->refresh();
        $application->loadMissing('unit.building', 'company');
        $tenantUser = User::query()->where('email', $application->email)->first();

        if ($tenantUser !== null && $application->unit !== null) {
            $unitLabel = ($application->unit->building?->name ?? 'Building') . ' — Unit ' . $application->unit->unit_number;
            $companyName = $application->company?->name ?? $company->name;

            $tenantUser->notify(new TenantApplicationDecisionNotification(
                (int) $application->id,
                'approved',
                (string) $companyName,
                $unitLabel,
                null,
            ));
        }

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

        $this->ensureTenantApplicationInScope($company, $application);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $application->loadMissing('unit.building', 'company');
        $applicant = User::query()->where('email', $application->email)->first();

        if ($applicant !== null && $application->unit !== null) {
            $unitLabel = ($application->unit->building?->name ?? 'Building') . ' — Unit ' . $application->unit->unit_number;
            $companyName = $application->company?->name ?? $company->name;

            $applicant->notify(new TenantApplicationDecisionNotification(
                (int) $application->id,
                'rejected',
                (string) $companyName,
                $unitLabel,
                $validated['rejection_reason'],
            ));
        }

        return redirect()->route('company.applications.show', [$company, $application])
            ->with('success', 'Application rejected.');
    }
}
