<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    /**
     * Display the tenant's active contract.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $contracts = $user->contracts()
            ->with(['unit.building', 'company'])
            ->latest()
            ->paginate(10);

        return view('tenant.contracts.index', compact('contracts'));
    }

    /**
     * Display the specified contract.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function show(Contract $contract)
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($contract->tenant_id !== $user->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        $contract->load(['unit.building', 'company', 'documents']);

        return view('tenant.contracts.show', compact('contract'));
    }

    /**
     * Sign the contract as tenant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sign(Request $request, Contract $contract)
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($contract->tenant_id !== $user->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        if ($contract->signed_by_tenant) {
            return back()->with('error', 'Contract already signed.');
        }

        $contract->update([
            'signed_by_tenant' => $user->id,
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
