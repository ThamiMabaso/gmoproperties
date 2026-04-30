<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends BaseCompanyController
{
    /**
     * Company profile / settings (organisation details).
     *
     * Do not filter Company with buildings.id on the companies query — that column does not exist on
     * `companies`. Access is enforced with ensureCompanyAccess(); building scope applies to buildings,
     * units, applications, etc., not to loading the company row itself.
     */
    public function edit(Company $company): View
    {
        abort_unless($company->exists && $company->getKey() !== null, 404);

        $this->ensureCompanyAccess($company);
        $this->ensureCompanyAdminForCompany($company);

        return view('company.settings.edit', compact('company'));
    }

    /**
     * Update company profile fields editable by the company administrator.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        abort_unless($company->exists && $company->getKey() !== null, 404);

        $this->ensureCompanyAccess($company);
        $this->ensureCompanyAdminForCompany($company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'registration_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'contract_template' => 'nullable|string|max:10000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $payload = collect($validated)->only([
            'name',
            'email',
            'phone',
            'address',
            'registration_number',
            'vat_number',
            'contract_template',
        ])->all();

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $payload['logo_path'] = $request->file('logo')->store('company-logos', 'public');
        }

        if ($validated['name'] !== $company->name) {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $counter = 1;

            while (
                Company::query()
                    ->where('slug', $slug)
                    ->whereKeyNot($company->getKey())
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $payload['slug'] = $slug;
        }

        $company->update($payload);

        return redirect()
            ->route('company.settings.edit', $company)
            ->with('success', 'Company details updated successfully.');
    }
}
