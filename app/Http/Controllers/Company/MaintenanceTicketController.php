<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\MaintenanceTicket;
use App\Models\User;
use App\Notifications\MaintenanceTicketUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceTicketController extends BaseCompanyController
{
    /**
     * Display a listing of maintenance tickets.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, Company $company)
    {
        $this->ensureCompanyAccess($company);

        $buildingIds = $this->managedBuildingIdsFor($company);

        $tickets = $this->scopedMaintenanceTicketsQuery($company, $buildingIds)
            ->with(['tenant', 'unit.building', 'assignedUser'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('priority'), function ($query) use ($request) {
                $query->where('priority', $request->input('priority'));
            })
            ->latest()
            ->paginate(15);

        return view('company.maintenance.index', compact('company', 'tickets'));
    }

    /**
     * Display the specified ticket.
     *
     * @param  \App\Models\Company  $company
     * @param  \App\Models\MaintenanceTicket  $ticket
     * @return \Illuminate\View\View
     */
    public function show(Company $company, MaintenanceTicket $ticket)
    {
        $this->ensureCompanyAccess($company);
        $this->ensureMaintenanceTicketInScope($company, $ticket);

        $ticket->load(['tenant', 'unit.building', 'assignedUser', 'assigner', 'expense']);

        $availableAssignees = $this->maintenanceTicketAssigneeCandidates($company, $ticket);

        return view('company.maintenance.show', compact('company', 'ticket', 'availableAssignees'));
    }

    /**
     * Assign ticket to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @param  \App\Models\MaintenanceTicket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(Request $request, Company $company, MaintenanceTicket $ticket)
    {
        $this->ensureCompanyAccess($company);
        $this->ensureMaintenanceTicketInScope($company, $ticket);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $assignee = User::findOrFail($validated['assigned_to']);

        if ($assignee->company_id !== $company->id) {
            abort(403, 'Cannot assign to user from different company.');
        }

        $candidates = $this->maintenanceTicketAssigneeCandidates($company, $ticket);

        if (! $candidates->contains('id', (int) $assignee->id)) {
            abort(403, 'This user cannot be assigned to this ticket for this building.');
        }

        $ticket->update([
            'assigned_to' => $assignee->id,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);

        $ticket->loadMissing('company');
        $slug = (string) ($ticket->company?->slug ?? $company->slug);
        $line = "Ticket {$ticket->ticket_number} was assigned to you.";

        $assignee->notify(new MaintenanceTicketUpdatedNotification(
            $slug,
            (int) $ticket->id,
            (string) $ticket->ticket_number,
            $line,
        ));

        return back()->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Update ticket status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @param  \App\Models\MaintenanceTicket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Company $company, MaintenanceTicket $ticket)
    {
        $this->ensureCompanyAccess($company);
        $this->ensureMaintenanceTicketInScope($company, $ticket);

        $validated = $request->validate([
            'status' => ['required', 'in:open,assigned,in_progress,completed,cancelled'],
            'resolution_notes' => 'nullable|string|max:5000',
            'actual_cost' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'status' => $validated['status'],
        ];

        if (isset($validated['resolution_notes'])) {
            $updateData['resolution_notes'] = $validated['resolution_notes'];
        }

        if (isset($validated['actual_cost'])) {
            $updateData['actual_cost'] = $validated['actual_cost'];
        }

        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        }

        $ticket->update($updateData);

        $ticket->loadMissing('company', 'tenant');
        $slug = (string) ($ticket->company?->slug ?? $company->slug);
        $line = "Ticket {$ticket->ticket_number} status is now {$validated['status']}.";

        if ($ticket->tenant !== null) {
            $ticket->tenant->notify(new MaintenanceTicketUpdatedNotification(
                $slug,
                (int) $ticket->id,
                (string) $ticket->ticket_number,
                $line,
            ));
        }

        return back()->with('success', 'Ticket status updated successfully.');
    }
}
