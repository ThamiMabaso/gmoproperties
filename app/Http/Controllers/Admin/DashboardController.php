<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the service provider admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        // Basic stats
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('is_active', true)->count(),
            'pending_companies' => Company::where('is_active', false)->count(),
            'total_properties' => DB::table('buildings')->count(),
            'total_units' => DB::table('units')->count(),
            'total_tenants' => User::where('type', 'tenant')->count(),
            'total_applications' => DB::table('tenant_applications')->count(),
            'pending_applications' => DB::table('tenant_applications')->where('status', 'pending')->count(),
        ];

        // Subscription plan breakdown
        $subscriptionStats = [
            'basic' => Company::where('subscription_plan', 'basic')->where('is_active', true)->count(),
            'professional' => Company::where('subscription_plan', 'professional')->where('is_active', true)->count(),
            'enterprise' => Company::where('subscription_plan', 'enterprise')->where('is_active', true)->count(),
        ];

        // Financial overview (platform revenue)
        $financialOverview = [
            'total_revenue' => DB::table('payments')->sum('amount'),
            'monthly_revenue' => DB::table('payments')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'pending_payments' => DB::table('invoices')
                ->where('status', 'pending')
                ->sum('total_amount'),
            'overdue_payments' => DB::table('invoices')
                ->where('status', 'overdue')
                ->sum('total_amount'),
        ];

        // Revenue by subscription plan (estimated based on active companies)
        $revenueByPlan = [
            'basic' => $subscriptionStats['basic'] * 500, // R500/month per basic plan
            'professional' => $subscriptionStats['professional'] * 1500, // R1500/month per professional plan
            'enterprise' => $subscriptionStats['enterprise'] * 3000, // R3000/month per enterprise plan
        ];

        // Recent activity
        $recentCompanies = Company::withCount(['users', 'buildings', 'units'])
            ->latest()
            ->take(5)
            ->get();

        $pendingCompanies = Company::where('is_active', false)
            ->latest()
            ->take(5)
            ->get();

        // Companies expiring soon (within 30 days)
        $expiringSoon = Company::where('is_active', true)
            ->whereNotNull('subscription_expires_at')
            ->whereBetween('subscription_expires_at', [now(), now()->addDays(30)])
            ->orderBy('subscription_expires_at')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'subscriptionStats',
            'financialOverview',
            'revenueByPlan',
            'recentCompanies',
            'pendingCompanies',
            'expiringSoon'
        ));
    }
}
