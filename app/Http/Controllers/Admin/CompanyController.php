<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(): View
    {
        $query = Company::withCount(['users', 'buildings', 'units']);

        if (request('status')) {
            if (request('status') === 'active') {
                $query->where('is_active', true);
            } elseif (request('status') === 'pending') {
                $query->where('is_active', false);
            }
        }

        if (request('subscription_plan')) {
            $query->where('subscription_plan', request('subscription_plan'));
        }

        $companies = $query->latest()->paginate(15);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create(): View
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'registration_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'subscription_plan' => 'required|in:basic,professional,enterprise',
            'feature_access' => 'nullable|array',
            'subscription_expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Company::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        Company::create($validated);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company): View
    {
        $company->load([
            'users',
            'buildings',
            'units',
            'tenantApplications',
            'contracts',
            'invoices',
            'payments',
        ]);

        // Financial summary
        $financialSummary = [
            'total_revenue' => $company->payments()->sum('amount'),
            'monthly_revenue' => $company->payments()
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'pending_invoices' => $company->invoices()
                ->where('status', 'pending')
                ->sum('total_amount'),
            'overdue_invoices' => $company->invoices()
                ->where('status', 'overdue')
                ->sum('total_amount'),
        ];

        return view('admin.companies.show', compact('company', 'financialSummary'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company): View
    {
        $availableFeatures = [
            'buildings' => 'Buildings Management',
            'units' => 'Units Management',
            'tenants' => 'Tenant Management',
            'contracts' => 'Contract Management',
            'invoices' => 'Invoice Management',
            'maintenance' => 'Maintenance Tickets',
            'reports' => 'Financial Reports',
            'ai_screening' => 'AI Tenant Screening',
            'document_management' => 'Document Management',
            'messaging' => 'Messaging System',
        ];

        return view('admin.companies.edit', compact('company', 'availableFeatures'));
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'registration_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'subscription_plan' => 'required|in:basic,professional,enterprise',
            'feature_access' => 'nullable|array',
            'subscription_expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $company->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure unique slug
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (Company::query()->where('slug', $validated['slug'])->whereKeyNot($company->getKey())->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        $company->update($validated);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
