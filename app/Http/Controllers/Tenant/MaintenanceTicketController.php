<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaintenanceTicketController extends Controller
{
    /**
     * Display a listing of the tenant's maintenance tickets.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $tickets = $user->maintenanceTickets()
            ->with('unit.building')
            ->latest()
            ->paginate(15);

        return view('tenant.maintenance.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new maintenance ticket.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $activeContract = $user->activeContract();
        $unit = $activeContract ? $activeContract->unit : null;

        if (!$unit) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You must have an active contract to create maintenance tickets.');
        }

        return view('tenant.maintenance.create', compact('unit'));
    }

    /**
     * Store a newly created maintenance ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $activeContract = $user->activeContract();

        if (!$activeContract) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You must have an active contract to create maintenance tickets.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $ticket = MaintenanceTicket::create([
            'company_id' => $user->company_id,
            'unit_id' => $activeContract->unit_id,
            'tenant_id' => $user->id,
            'ticket_number' => 'TKT-' . strtoupper(Str::random(10)),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        // TODO: Send notification to property manager

        return redirect()->route('tenant.maintenance.show', $ticket)
            ->with('success', 'Maintenance ticket created successfully.');
    }

    /**
     * Display the specified maintenance ticket.
     *
     * @param  \App\Models\MaintenanceTicket  $ticket
     * @return \Illuminate\View\View
     */
    public function show(MaintenanceTicket $ticket)
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        if ($ticket->tenant_id !== $user->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['unit.building', 'assignedUser', 'expense']);

        return view('tenant.maintenance.show', compact('ticket'));
    }
}
