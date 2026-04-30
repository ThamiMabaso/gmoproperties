<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Aggregates chart-ready series for portal dashboards (Chart.js on the client).
 */
final class DashboardChartData
{
    /**
     * @return array<string, mixed>
     */
    public static function forAdmin(): array
    {
        $payments = self::paymentsCompletedByMonth();
        $newCompanies = self::companiesRegisteredByMonth();

        return [
            'charts' => [
                'paymentsLine' => [
                    'type' => 'line',
                    'title' => 'Completed payments (last 6 months)',
                    'datasetLabel' => 'Amount (R)',
                    'labels' => $payments['labels'],
                    'values' => $payments['values'],
                ],
                'companiesBar' => [
                    'type' => 'bar',
                    'title' => 'New companies registered (last 6 months)',
                    'datasetLabel' => 'Companies',
                    'labels' => $newCompanies['labels'],
                    'values' => $newCompanies['values'],
                ],
                'plansDoughnut' => self::subscriptionPlanMix(),
            ],
        ];
    }

    /**
     * @param  array<int>|null  $onlyBuildingIds  null = entire company; [] = no data; non-empty = filter by buildings
     * @return array<string, mixed>
     */
    public static function forCompany(Company $company, ?array $onlyBuildingIds = null): array
    {
        $paid = self::companyPaidInvoicesByMonth($company, $onlyBuildingIds);
        $units = self::companyUnitStatusMix($company, $onlyBuildingIds);
        $maintenance = self::companyMaintenanceByStatus($company, $onlyBuildingIds);

        return [
            'charts' => [
                'paidInvoiceLine' => [
                    'type' => 'line',
                    'title' => 'Paid invoice total (last 6 months)',
                    'datasetLabel' => 'Amount (R)',
                    'labels' => $paid['labels'],
                    'values' => $paid['values'],
                ],
                'unitsDoughnut' => $units,
                'maintenanceBar' => $maintenance,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function forTenant(User $user): array
    {
        $issued = self::tenantInvoicesIssuedByMonth($user);
        $tickets = self::tenantTicketsByStatus($user);

        return [
            'charts' => [
                'invoicesBar' => [
                    'type' => 'bar',
                    'title' => 'Invoice totals by issue month (last 6 months)',
                    'datasetLabel' => 'Amount (R)',
                    'labels' => $issued['labels'],
                    'values' => $issued['values'],
                ],
                'ticketsDoughnut' => $tickets,
            ],
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private static function paymentsCompletedByMonth(): array
    {
        $labels = [];
        $values = [];

        foreach (self::rollingMonths(6) as $month) {
            $labels[] = $month->format('M Y');
            $values[] = (float) DB::table('payments')
                ->where('status', 'completed')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    private static function companiesRegisteredByMonth(): array
    {
        $labels = [];
        $values = [];

        foreach (self::rollingMonths(6) as $month) {
            $labels[] = $month->format('M Y');
            $values[] = (int) DB::table('companies')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array<string, mixed>
     */
    private static function subscriptionPlanMix(): array
    {
        $basic = (int) DB::table('companies')
            ->where('subscription_plan', 'basic')
            ->where('is_active', true)
            ->count();
        $professional = (int) DB::table('companies')
            ->where('subscription_plan', 'professional')
            ->where('is_active', true)
            ->count();
        $enterprise = (int) DB::table('companies')
            ->where('subscription_plan', 'enterprise')
            ->where('is_active', true)
            ->count();

        return [
            'type' => 'doughnut',
            'title' => 'Active companies by subscription plan',
            'labels' => ['Basic', 'Professional', 'Enterprise'],
            'values' => [$basic, $professional, $enterprise],
        ];
    }

    /**
     * @param  array<int>|null  $onlyBuildingIds
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private static function companyPaidInvoicesByMonth(Company $company, ?array $onlyBuildingIds = null): array
    {
        $labels = [];
        $values = [];

        foreach (self::rollingMonths(6) as $month) {
            $labels[] = $month->format('M Y');

            if ($onlyBuildingIds !== null && $onlyBuildingIds === []) {
                $values[] = 0.0;

                continue;
            }

            $query = $company->invoices()
                ->where('status', 'paid')
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month);

            if ($onlyBuildingIds !== null) {
                $query->whereHas('unit', function ($uq) use ($onlyBuildingIds) {
                    $uq->whereIn('building_id', $onlyBuildingIds);
                });
            }

            $values[] = (float) $query->sum('total_amount');
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @param  array<int>|null  $onlyBuildingIds
     * @return array<string, mixed>
     */
    private static function companyUnitStatusMix(Company $company, ?array $onlyBuildingIds = null): array
    {
        $unitQuery = $company->units();

        if ($onlyBuildingIds !== null) {
            if ($onlyBuildingIds === []) {
                return [
                    'type' => 'doughnut',
                    'title' => 'Units by status',
                    'labels' => ['No units'],
                    'values' => [0],
                ];
            }

            $unitQuery->whereIn('building_id', $onlyBuildingIds);
        }

        $rows = $unitQuery
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $labels = [];
        $values = [];

        foreach ($rows as $status => $count) {
            $labels[] = ucfirst(str_replace('_', ' ', (string) $status));
            $values[] = (int) $count;
        }

        if ($labels === []) {
            $labels = ['No units yet'];
            $values = [0];
        }

        return [
            'type' => 'doughnut',
            'title' => 'Units by status',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @param  array<int>|null  $onlyBuildingIds
     * @return array<string, mixed>
     */
    private static function companyMaintenanceByStatus(Company $company, ?array $onlyBuildingIds = null): array
    {
        $ticketQuery = $company->maintenanceTickets();

        if ($onlyBuildingIds !== null) {
            if ($onlyBuildingIds === []) {
                return [
                    'type' => 'bar',
                    'title' => 'Maintenance tickets by status',
                    'datasetLabel' => 'Tickets',
                    'labels' => ['No tickets'],
                    'values' => [0],
                ];
            }

            $ticketQuery->whereHas('unit', function ($uq) use ($onlyBuildingIds) {
                $uq->whereIn('building_id', $onlyBuildingIds);
            });
        }

        $rows = $ticketQuery
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $labels = [];
        $values = [];

        foreach ($rows as $status => $count) {
            $labels[] = ucfirst(str_replace('_', ' ', (string) $status));
            $values[] = (int) $count;
        }

        if ($labels === []) {
            $labels = ['No tickets'];
            $values = [0];
        }

        return [
            'type' => 'bar',
            'title' => 'Maintenance tickets by status',
            'datasetLabel' => 'Tickets',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private static function tenantInvoicesIssuedByMonth(User $user): array
    {
        $labels = [];
        $values = [];

        foreach (self::rollingMonths(6) as $month) {
            $labels[] = $month->format('M Y');
            $values[] = (float) $user->invoices()
                ->whereYear('issue_date', $month->year)
                ->whereMonth('issue_date', $month->month)
                ->sum('total_amount');
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array<string, mixed>
     */
    private static function tenantTicketsByStatus(User $user): array
    {
        $rows = $user->maintenanceTickets()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $labels = [];
        $values = [];

        foreach ($rows as $status => $count) {
            $labels[] = ucfirst(str_replace('_', ' ', (string) $status));
            $values[] = (int) $count;
        }

        if ($labels === []) {
            $labels = ['No tickets'];
            $values = [0];
        }

        return [
            'type' => 'doughnut',
            'title' => 'Your maintenance tickets by status',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @return \Generator<int, \Carbon\Carbon>
     */
    private static function rollingMonths(int $count): \Generator
    {
        for ($i = $count - 1; $i >= 0; $i--) {
            yield now()->subMonths($i);
        }
    }
}
