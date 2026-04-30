<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends BaseCompanyController
{
    /**
     * Display a listing of contracts.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
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
     * Display the specified contract.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Contract $contract)
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

        $contract->load(['tenant', 'unit.building', 'application', 'documents']);

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
    public function signAsCompany(Request $request, Company $company, Contract $contract)
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
}
