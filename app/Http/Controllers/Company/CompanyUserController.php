<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CompanyUserController extends BaseCompanyController
{
    /**
     * Active users for this company (tenants, managers, admins).
     */
    public function index(Request $request, Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->ensureCompanyAdminForCompany($company);

        $query = User::query()
            ->where('company_id', $company->id)
            ->with([
                'building',
                'contracts' => function ($q) {
                    $q->where('status', 'active')
                        ->with('unit.building')
                        ->latest();
                },
            ])
            ->orderByRaw("CASE type WHEN 'company_admin' THEN 1 WHEN 'property_manager' THEN 2 ELSE 3 END")
            ->orderBy('name');

        if ($request->input('status_scope', 'active') !== 'all') {
            $query->where('is_active', true);
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        $users = $query->paginate(20)->withQueryString();

        $buildings = $company->buildings()->orderBy('name')->get();

        return view('company.users.index', compact('company', 'users', 'buildings'));
    }

    /**
     * Create a property manager account and optionally assign a building.
     */
    public function storeManager(Request $request, Company $company): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->ensureCompanyAdminForCompany($company);

        if ($request->input('building_id') === '') {
            $request->merge(['building_id' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'building_id' => 'nullable|exists:buildings,id,company_id,' . $company->id,
            'phone' => 'nullable|string|max:30',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'building_id' => $validated['building_id'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'type' => 'property_manager',
            'is_active' => true,
        ]);

        $user->assignRole('property_manager');

        return redirect()
            ->route('company.users.index', $company)
            ->with('success', 'Property manager account created. Share the login credentials securely.');
    }

    /**
     * Update manager building assignment or account status (not your own account).
     */
    public function update(Request $request, Company $company, User $user): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->ensureCompanyAdminForCompany($company);

        if ((int) $user->company_id !== (int) $company->id) {
            abort(404);
        }

        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot change your own account from this screen.');
        }

        if ($user->isPropertyManager()) {
            if ($request->input('building_id') === '') {
                $request->merge(['building_id' => null]);
            }

            $validated = $request->validate([
                'building_id' => 'nullable|exists:buildings,id,company_id,' . $company->id,
                'is_active' => 'sometimes|boolean',
            ]);

            $user->update([
                'building_id' => $validated['building_id'],
                'is_active' => $request->boolean('is_active'),
            ]);

            return back()->with('success', 'Manager updated.');
        }

        if ($user->isTenant()) {
            $request->validate([
                'is_active' => 'sometimes|boolean',
            ]);

            $user->update(['is_active' => $request->boolean('is_active')]);

            return back()->with('success', 'Tenant account updated.');
        }

        abort(403, 'This user type cannot be updated here.');
    }
}
