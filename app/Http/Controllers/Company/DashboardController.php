<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Support\DashboardChartData;

class DashboardController extends BaseCompanyController
{
    /**
     * Display the company dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        $this->ensureCompanyAccess($company);

        $buildingIds = $this->managedBuildingIdsFor($company);

        $unitsScope = $this->scopedUnitsQuery($company, $buildingIds);

        $stats = [
            'total_buildings' => $this->scopedBuildingsQuery($company, $buildingIds)->count(),
            'total_units' => $unitsScope->count(),
            'occupied_units' => (clone $unitsScope)->where('status', 'occupied')->count(),
            'available_units' => (clone $unitsScope)->where('status', 'available')->count(),
            'total_tenants' => $this->scopedTenantUserCount($company, $buildingIds),
            'active_contracts' => $this->scopedContractsQuery($company, $buildingIds)->where('status', 'active')->count(),
            'pending_applications' => $this->scopedTenantApplicationsQuery($company, $buildingIds)->where('status', 'pending')->count(),
            'open_tickets' => $this->scopedMaintenanceTicketsQuery($company, $buildingIds)->where('status', 'open')->count(),
            'overdue_invoices' => $this->scopedInvoicesQuery($company, $buildingIds)
                ->where('status', 'overdue')
                ->where('due_date', '<', now())
                ->count(),
            'monthly_revenue' => $this->scopedInvoicesQuery($company, $buildingIds)
                ->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total_amount'),
        ];

        $recentApplications = $this->scopedTenantApplicationsQuery($company, $buildingIds)
            ->with('unit')
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = $this->scopedMaintenanceTicketsQuery($company, $buildingIds)
            ->with(['unit', 'tenant'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingRenewals = $this->scopedContractsQuery($company, $buildingIds)
            ->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with(['tenant', 'unit'])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        $recentInvoices = $this->scopedInvoicesQuery($company, $buildingIds)
            ->latest()
            ->take(5)
            ->get();

        $chartData = DashboardChartData::forCompany($company, $buildingIds);

        return view('company.dashboard', compact(
            'company',
            'stats',
            'recentApplications',
            'recentTickets',
            'upcomingRenewals',
            'recentInvoices',
            'chartData'
        ));
    }
}
