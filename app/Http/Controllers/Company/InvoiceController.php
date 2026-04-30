<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends BaseCompanyController
{
    /**
     * Display a listing of invoices.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_invoices');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = $this->scopedInvoicesQuery($company, $buildingIds)
            ->with(['tenant', 'unit.building', 'contract'])
            ->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('type')) {
            $query->where('type', request('type'));
        }

        $invoices = $query->paginate(15);

        return view('company.invoices.index', compact('company', 'invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function create(Company $company)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_invoices');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $contracts = $this->scopedContractsQuery($company, $buildingIds)
            ->where('status', 'active')
            ->with(['tenant', 'unit'])
            ->get();

        return view('company.invoices.create', compact('company', 'contracts'));
    }

    /**
     * Store a newly created invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Company $company)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_invoices');

        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'type' => ['required', 'in:rent,deposit,maintenance,penalty,other'],
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:5000',
            'line_items' => 'nullable|array',
        ]);

        $contract = Contract::findOrFail($validated['contract_id']);

        if ($contract->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        $contract->loadMissing('unit');
        if ($contract->unit !== null) {
            $this->ensureBuildingIdAllowedForScope(
                $company,
                (int) $contract->unit->building_id,
                $this->managedBuildingIdsFor($company)
            );
        }

        $totalAmount = $validated['subtotal'] + ($validated['tax_amount'] ?? 0);

        $invoice = Invoice::create([
            'company_id' => $company->id,
            'contract_id' => $contract->id,
            'tenant_id' => $contract->tenant_id,
            'unit_id' => $contract->unit_id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'type' => $validated['type'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $validated['subtotal'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'status' => 'sent',
            'description' => $validated['description'] ?? null,
            'line_items' => $validated['line_items'] ?? null,
        ]);

        // TODO: Generate invoice PDF
        // TODO: Send invoice email to tenant

        return redirect()->route('company.invoices.show', [$company, $invoice])
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\View\View
     */
    public function show(Company $company, Invoice $invoice)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_invoices');
        $this->ensureInvoiceInScope($company, $invoice);

        $invoice->load(['tenant', 'unit.building', 'contract', 'payments', 'documents']);

        return view('company.invoices.show', compact('company', 'invoice'));
    }

    /**
     * Generate rent invoices for all active contracts.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateRentInvoices(Company $company)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_invoices');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $activeContracts = $this->scopedContractsQuery($company, $buildingIds)
            ->where('status', 'active')
            ->with('unit')
            ->get();

        $generated = 0;

        foreach ($activeContracts as $contract) {
            // Check if invoice already exists for this month
            $existingInvoice = Invoice::where('contract_id', $contract->id)
                ->where('type', 'rent')
                ->whereYear('issue_date', now()->year)
                ->whereMonth('issue_date', now()->month)
                ->first();

            if ($existingInvoice) {
                continue;
            }

            // Calculate due date (typically 7 days from issue date)
            $dueDate = now()->addDays(7);

            Invoice::create([
                'company_id' => $company->id,
                'contract_id' => $contract->id,
                'tenant_id' => $contract->tenant_id,
                'unit_id' => $contract->unit_id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'type' => 'rent',
                'issue_date' => now(),
                'due_date' => $dueDate,
                'subtotal' => $contract->monthly_rent,
                'tax_amount' => 0,
                'total_amount' => $contract->monthly_rent,
                'paid_amount' => 0,
                'status' => 'sent',
                'description' => 'Monthly rent for ' . now()->format('F Y'),
            ]);

            $generated++;
        }

        return back()->with('success', "Generated {$generated} rent invoices successfully.");
    }
}
