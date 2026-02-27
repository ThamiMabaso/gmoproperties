<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the tenant dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isTenant()) {
            abort(403, 'Access denied. Tenant portal only.');
        }

        $activeContract = $user->activeContract();
        $unit = $activeContract ? $activeContract->unit : null;

        $stats = [
            'pending_invoices' => $user->invoices()
                ->whereIn('status', ['sent', 'overdue'])
                ->count(),
            'total_due' => $user->invoices()
                ->whereIn('status', ['sent', 'overdue'])
                ->sum(DB::raw('total_amount - paid_amount')),
            'open_tickets' => $user->maintenanceTickets()
                ->whereIn('status', ['open', 'assigned', 'in_progress'])
                ->count(),
        ];

        $recentInvoices = $user->invoices()
            ->with(['unit', 'contract'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = $user->payments()
            ->with('invoice')
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = $user->maintenanceTickets()
            ->with('unit')
            ->latest()
            ->take(5)
            ->get();

        return view('tenant.dashboard', compact('user', 'activeContract', 'unit', 'stats', 'recentInvoices', 'recentPayments', 'recentTickets'));
    }
}
