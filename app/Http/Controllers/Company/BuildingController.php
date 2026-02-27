<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Building;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuildingController extends BaseCompanyController
{
    /**
     * Display a listing of buildings.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        $buildings = $company->buildings()
            ->withCount('units')
            ->latest()
            ->paginate(15);

        return view('company.buildings.index', compact('company', 'buildings'));
    }

    /**
     * Show the form for creating a new building.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function create(Company $company): View
    {
        $this->ensureCompanyAccess($company);

        return view('company.buildings.create', compact('company'));
    }

    /**
     * Store a newly created building.
     *
     * @param  \App\Models\Company  $company
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Company $company, Request $request): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:buildings,code,NULL,id,company_id,' . $company->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'property_type' => 'required|in:student_accommodation,residential,mixed',
            'total_units' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['company_id'] = $company->id;
        $validated['occupied_units'] = 0;

        Building::create($validated);

        return redirect()
            ->route('company.buildings.index', $company)
            ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified building.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Building  $building
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Building $building): View
    {
        $this->ensureCompanyAccess($company);

        if ($building->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this building.');
        }

        $building->load(['units', 'expenses']);

        $units = $building->units()
            ->with(['activeContract.tenant'])
            ->latest()
            ->paginate(15);

        return view('company.buildings.show', compact('company', 'building', 'units'));
    }

    /**
     * Show the form for editing the specified building.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Building  $building
     * @return \Illuminate\View\View
     */
    public function edit(Company $company, Building $building): View
    {
        $this->ensureCompanyAccess($company);

        if ($building->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this building.');
        }

        return view('company.buildings.edit', compact('company', 'building'));
    }

    /**
     * Update the specified building.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Building  $building
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Company $company, Building $building, Request $request): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        if ($building->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this building.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:buildings,code,' . $building->id . ',id,company_id,' . $company->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'property_type' => 'required|in:student_accommodation,residential,mixed',
            'total_units' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $building->update($validated);

        return redirect()
            ->route('company.buildings.show', [$company, $building])
            ->with('success', 'Building updated successfully.');
    }

    /**
     * Remove the specified building.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Building  $building
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company, Building $building): RedirectResponse
    {
        $this->ensureCompanyAccess($company);

        if ($building->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this building.');
        }

        // Check if building has units
        if ($building->units()->count() > 0) {
            return redirect()
                ->route('company.buildings.index', $company)
                ->with('error', 'Cannot delete building with existing units. Please remove all units first.');
        }

        $building->delete();

        return redirect()
            ->route('company.buildings.index', $company)
            ->with('success', 'Building deleted successfully.');
    }
}
