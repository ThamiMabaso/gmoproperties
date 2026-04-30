<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Company\BaseCompanyController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends BaseCompanyController
{
    /**
     * Display financial reports dashboard.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function index(Company $company)
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_financial_reports');

        // Revenue calculations
        $totalRevenue = $company->invoices()
            ->where('status', 'paid')
            ->sum('total_amount');

        $monthlyRevenue = $company->invoices()
            ->where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('total_amount');

        $outstandingInvoices = $company->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->sum(DB::raw('total_amount - paid_amount'));

        // Expense calculations
        $totalExpenses = $company->expenses()->sum('amount');

        $monthlyExpenses = $company->expenses()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        // Profit calculations
        $totalProfit = $totalRevenue - $totalExpenses;
        $monthlyProfit = $monthlyRevenue - $monthlyExpenses;

        // Revenue by month (last 12 months)
        $driverName = DB::connection()->getDriverName();
        $yearExpression = $driverName === 'sqlite'
            ? "CAST(strftime('%Y', paid_at) AS INTEGER)"
            : 'YEAR(paid_at)';
        $monthExpression = $driverName === 'sqlite'
            ? "CAST(strftime('%m', paid_at) AS INTEGER)"
            : 'MONTH(paid_at)';

        $revenueByMonth = $company->invoices()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->selectRaw("{$yearExpression} as year, {$monthExpression} as month, SUM(total_amount) as revenue")
            ->where('paid_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Expense by category
        $expensesByCategory = $company->expenses()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Top revenue units
        $topRevenueUnits = $company->invoices()
            ->where('status', 'paid')
            ->select('unit_id', DB::raw('SUM(total_amount) as revenue'))
            ->with('unit.building')
            ->groupBy('unit_id')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        return view('company.financial.index', compact(
            'company',
            'totalRevenue',
            'monthlyRevenue',
            'outstandingInvoices',
            'totalExpenses',
            'monthlyExpenses',
            'totalProfit',
            'monthlyProfit',
            'revenueByMonth',
            'expensesByCategory',
            'topRevenueUnits'
        ));
    }
}
