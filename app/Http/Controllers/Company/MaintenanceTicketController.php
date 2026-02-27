<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use App\Models\MaintenanceTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaintenanceTicketController extends BaseCompanyController
{
    /**
     * Display a listing of maintenance tickets.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        

        $tickets = $company->maintenanceTickets()
            ->with(['tenant', 'unit.building', 'assignedUser'])
            ->latest()
            ->filter(request(['status', 'priority']))
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
        

        if ($ticket->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['tenant', 'unit.building', 'assignedUser', 'assigner', 'expense']);

        $availableAssignees = $company->users()
            ->whereIn('type', ['property_manager', 'company_admin'])
            ->get();

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
        

        if ($ticket->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $assignee = User::findOrFail($validated['assigned_to']);

        if ($assignee->company_id !== $company->id) {
            abort(403, 'Cannot assign to user from different company.');
        }

        $ticket->update([
            'assigned_to' => $assignee->id,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);

        // TODO: Send notification to assigned user

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
        

        if ($ticket->company_id !== $company->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

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

        // TODO: Send notification to tenant if completed

        return back()->with('success', 'Ticket status updated successfully.');
    }
}
