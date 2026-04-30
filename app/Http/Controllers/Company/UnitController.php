<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Building;
use App\Models\Company;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends BaseCompanyController
{
    /**
     * Display a listing of units.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = $this->scopedUnitsQuery($company, $buildingIds)
            ->with(['building', 'activeContract.tenant'])
            ->latest();

        if (request('building_id')) {
            $filterId = (int) request('building_id');

            if (is_array($buildingIds) && $buildingIds !== [] && ! in_array($filterId, array_map('intval', $buildingIds), true)) {
                abort(403, 'You cannot filter by a building outside your assignment.');
            }

            $query->where('building_id', $filterId);
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('unit_type')) {
            $query->where('unit_type', request('unit_type'));
        }

        $units = $query->paginate(15);
        $buildings = $this->scopedBuildingsQuery($company, $buildingIds)->orderBy('name')->get();

        return view('company.units.index', compact('company', 'units', 'buildings'));
    }

    /**
     * Show the form for creating a new unit.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function create(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        $buildingIds = $this->managedBuildingIdsFor($company);

        $buildings = $this->scopedBuildingsQuery($company, $buildingIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('company.units.create', compact('company', 'buildings'));
    }

    /**
     * Store a newly created unit.
     *
     * @param  \App\Models\Company  $company
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Company $company, Request $request): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id,company_id,' . $company->id,
            'unit_number' => 'required|string|max:50',
            'unit_type' => 'required|in:studio,one_bedroom,two_bedroom,three_bedroom,four_bedroom,shared',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_meters' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->ensureBuildingIdAllowedForScope(
            $company,
            (int) $validated['building_id'],
            $this->managedBuildingIdsFor($company)
        );

        $validated['company_id'] = $company->id;

        Unit::create($validated);

        return redirect()
            ->route('company.units.index', $company)
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified unit.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Unit $unit): View
    {
        $this->ensureCompanyAccess($company);
        $this->ensureUnitInScope($company, $unit);

        $unit->load([
            'building',
            'contracts.tenant',
            'invoices',
            'maintenanceTickets',
            'tenantApplications',
        ]);

        return view('company.units.show', compact('company', 'unit'));
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function edit(Company $company, Unit $unit): View
    {
        $this->ensureCompanyAccess($company);
        $this->ensureUnitInScope($company, $unit);

        $buildingIds = $this->managedBuildingIdsFor($company);

        $buildings = $this->scopedBuildingsQuery($company, $buildingIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('company.units.edit', compact('company', 'unit', 'buildings'));
    }

    /**
     * Update the specified unit.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Unit  $unit
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Company $company, Unit $unit, Request $request): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->ensureUnitInScope($company, $unit);

        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id,company_id,' . $company->id,
            'unit_number' => 'required|string|max:50',
            'unit_type' => 'required|in:studio,one_bedroom,two_bedroom,three_bedroom,four_bedroom,shared',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_meters' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->ensureBuildingIdAllowedForScope(
            $company,
            (int) $validated['building_id'],
            $this->managedBuildingIdsFor($company)
        );

        $unit->update($validated);

        return redirect()
            ->route('company.units.show', [$company, $unit])
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company, Unit $unit): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->ensureUnitInScope($company, $unit);

        // Check if unit has active contract
        if ($unit->activeContract) {
            return redirect()
                ->route('company.units.index', $company)
                ->with('error', 'Cannot delete unit with active contract. Please end the contract first.');
        }

        $unit->delete();

        return redirect()
            ->route('company.units.index', $company)
            ->with('success', 'Unit deleted successfully.');
    }
}
