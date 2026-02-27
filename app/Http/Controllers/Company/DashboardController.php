<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Building;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseCompanyController
{
    /**
     * Display the company dashboard.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        $this->ensureCompanyAccess($company);
        $user = Auth::user();

        $stats = [
            'total_buildings' => $company->buildings()->count(),
            'total_units' => $company->units()->count(),
            'occupied_units' => $company->units()->where('status', 'occupied')->count(),
            'available_units' => $company->units()->where('status', 'available')->count(),
            'total_tenants' => $company->users()->where('type', 'tenant')->count(),
            'active_contracts' => $company->contracts()->where('status', 'active')->count(),
            'pending_applications' => $company->tenantApplications()->where('status', 'pending')->count(),
            'open_tickets' => $company->maintenanceTickets()->where('status', 'open')->count(),
            'overdue_invoices' => $company->invoices()
                ->where('status', 'overdue')
                ->where('due_date', '<', now())
                ->count(),
            'monthly_revenue' => $company->invoices()
                ->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total_amount'),
        ];

        $recentApplications = $company->tenantApplications()
            ->with('unit')
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = $company->maintenanceTickets()
            ->with(['unit', 'tenant'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingRenewals = $company->contracts()
            ->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with(['tenant', 'unit'])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentApplications', 'recentTickets', 'upcomingRenewals'));
    }
}
