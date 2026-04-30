<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\User;
use App\Notifications\ContractSignatureUpdateNotification;
use App\Support\CompanyStaffRecipients;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Display the tenant's active contract.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
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
    public function show(Contract $contract): View
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
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
    public function sign(Request $request, Contract $contract): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
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
        }

        $contract->refresh();
        $contract->loadMissing('company', 'tenant', 'unit.building');
        $slug = (string) ($contract->company?->slug ?? '');

        if ($contract->status === 'active' && $contract->tenant instanceof User) {
            $contract->tenant->notify(new ContractSignatureUpdateNotification(
                $slug,
                (int) $contract->id,
                (string) $contract->contract_number,
                ContractSignatureUpdateNotification::VARIANT_FULLY_ACTIVE,
            ));

            if ($contract->company !== null) {
                $staff = CompanyStaffRecipients::forBuilding($contract->company, $contract->unit?->building_id);

                Notification::send($staff, new ContractSignatureUpdateNotification(
                    $slug,
                    (int) $contract->id,
                    (string) $contract->contract_number,
                    ContractSignatureUpdateNotification::VARIANT_FULLY_ACTIVE,
                ));
            }
        } elseif ($contract->company !== null) {
            $staff = CompanyStaffRecipients::forBuilding($contract->company, $contract->unit?->building_id);

            Notification::send($staff, new ContractSignatureUpdateNotification(
                $slug,
                (int) $contract->id,
                (string) $contract->contract_number,
                ContractSignatureUpdateNotification::VARIANT_TENANT_SIGNED,
            ));
        }

        return back()->with('success', 'Contract signed successfully.');
    }

    /**
     * Download contract PDF for tenant after tenant signature.
     */
    public function download(Contract $contract)
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($contract->tenant_id !== $user->id) {
            abort(403, 'Unauthorized access to this contract.');
        }

        if (! $contract->signed_by_tenant) {
            abort(403, 'You can download the contract only after you sign it.');
        }

        $contract->load(['company', 'tenant', 'unit.building', 'tenantSigner', 'companySigner']);

        $pdf = Pdf::loadView('contracts.pdf', [
            'contract' => $contract,
            'isTenantView' => true,
        ]);

        return $pdf->download($contract->contract_number . '.pdf');
    }
}
