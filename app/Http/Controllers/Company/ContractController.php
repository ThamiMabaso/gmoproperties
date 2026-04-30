<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContractController extends BaseCompanyController
{
    /**
     * Display a listing of contracts.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_contracts');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = $this->scopedContractsQuery($company, $buildingIds)
            ->with(['tenant', 'unit.building', 'application'])
            ->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $contracts = $query->paginate(15);

        return view('company.contracts.index', compact('company', 'contracts'));
    }

    /**
     * Show the form for creating a contract.
     */
    public function create(Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_contracts');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $units = $this->scopedUnitsQuery($company, $buildingIds)
            ->with('building')
            ->whereIn('status', ['available', 'reserved'])
            ->orderBy('building_id')
            ->orderBy('unit_number')
            ->get();

        $tenants = $company->users()
            ->where('type', 'tenant')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('company.contracts.create', compact('company', 'units', 'tenants'));
    }

    /**
     * Store a newly created contract.
     */
    public function store(Request $request, Company $company): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_contracts');

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'terms_text' => 'nullable|string|max:15000',
        ]);

        $unit = Unit::query()->with('building')->findOrFail((int) $validated['unit_id']);
        $tenant = User::query()->findOrFail((int) $validated['tenant_id']);

        $this->ensureUnitInScope($company, $unit);

        if ((int) $tenant->company_id !== (int) $company->id || ! $tenant->isTenant()) {
            abort(403, 'Selected tenant is invalid for this company.');
        }

        $existingContract = Contract::query()
            ->where('company_id', $company->id)
            ->where('unit_id', $unit->id)
            ->whereIn('status', ['pending_signature', 'active'])
            ->exists();

        if ($existingContract) {
            return back()->withInput()->with('error', 'This unit already has a pending or active contract.');
        }

        $termsText = trim((string) ($validated['terms_text'] ?? ''));
        if ($termsText === '') {
            $termsText = (string) ($company->contract_template ?? '');
        }

        $contract = Contract::create([
            'company_id' => $company->id,
            'unit_id' => $unit->id,
            'tenant_id' => $tenant->id,
            'contract_number' => $this->generateContractNumber(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'monthly_rent' => $validated['monthly_rent'],
            'deposit' => $validated['deposit'] ?? 0,
            'terms_text' => $termsText !== '' ? $termsText : null,
            'status' => 'pending_signature',
        ]);

        return redirect()
            ->route('company.contracts.show', [$company, $contract])
            ->with('success', 'Contract generated successfully.');
    }

    /**
     * Display the specified contract.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Contract $contract): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_contracts');

        if ($contract->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        $contract->loadMissing('unit.building');

        if ($contract->unit === null || $contract->unit->building === null) {
            abort(404, 'Contract unit or building not found.');
        }

        $this->ensureBuildingInScope($company, $contract->unit->building);

        $contract->load(['tenant', 'unit.building', 'application', 'documents', 'tenantSigner', 'companySigner']);

        return view('company.contracts.show', compact('company', 'contract'));
    }

    /**
     * Sign the contract as company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signAsCompany(Request $request, Company $company, Contract $contract): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('sign_contracts');

        if ($contract->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        $contract->loadMissing('unit.building');

        if ($contract->unit === null || $contract->unit->building === null) {
            abort(404, 'Contract unit or building not found.');
        }

        $this->ensureBuildingInScope($company, $contract->unit->building);

        if ($contract->signed_by_company) {
            return back()->with('error', 'Contract already signed by company.');
        }

        $contract->update([
            'signed_by_company' => Auth::id(),
        ]);

        // If both parties have signed, mark as active
        if ($contract->signed_by_tenant && $contract->signed_by_company) {
            $contract->update([
                'status' => 'active',
                'signed_at' => now(),
            ]);

            // Update unit status
            $contract->unit->update(['status' => 'occupied']);

            // TODO: Generate signed contract PDF
            // TODO: Send notification emails
        }

        return back()->with('success', 'Contract signed successfully.');
    }

    /**
     * Download contract as PDF for company-side users.
     */
    public function download(Company $company, Contract $contract)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_contracts');

        if ($contract->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        $contract->loadMissing('unit.building');

        if ($contract->unit === null || $contract->unit->building === null) {
            abort(404, 'Contract unit or building not found.');
        }

        $this->ensureBuildingInScope($company, $contract->unit->building);

        $contract->load(['company', 'tenant', 'unit.building', 'tenantSigner', 'companySigner']);

        $pdf = Pdf::loadView('contracts.pdf', [
            'contract' => $contract,
            'isTenantView' => false,
        ]);

        return $pdf->download($contract->contract_number . '.pdf');
    }

    private function generateContractNumber(): string
    {
        do {
            $number = 'CON-' . strtoupper(Str::random(8));
        } while (Contract::query()->where('contract_number', $number)->exists());

        return $number;
    }
}
