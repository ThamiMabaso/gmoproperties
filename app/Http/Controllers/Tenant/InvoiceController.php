<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the tenant's invoices.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $query = $user->invoices()
            ->with(['unit.building', 'contract'])
            ->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $invoices = $query->paginate(15);

        return view('tenant.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\View\View
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($invoice->tenant_id !== $user->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->load(['unit.building', 'contract', 'payments', 'documents']);

        return view('tenant.invoices.show', compact('invoice'));
    }
}
