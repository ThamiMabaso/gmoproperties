<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use App\Models\Payment;
use App\Models\TenantApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class PlatformUpdatesReportBuilder
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function buildRows(Carbon $from, Carbon $to, ?int $companyId = null): Collection
    {
        $rows = collect();

        $this->appendCompanyRows($rows, $from, $to, $companyId);
        $this->appendUserRows($rows, $from, $to, $companyId);
        $this->appendApplicationRows($rows, $from, $to, $companyId);
        $this->appendContractRows($rows, $from, $to, $companyId);
        $this->appendInvoiceRows($rows, $from, $to, $companyId);
        $this->appendMaintenanceRows($rows, $from, $to, $companyId);
        $this->appendPaymentRows($rows, $from, $to, $companyId);

        return $rows
            ->sortByDesc(fn (array $row): int => $row['sort_ts'])
            ->values();
    }

    private function appendCompanyRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = Company::query()->whereBetween('updated_at', [$from, $to]);

        if ($companyId !== null) {
            $query->whereKey($companyId);
        }

        foreach ($query->cursor() as $company) {
            $rows->push([
                'sort_ts' => $company->updated_at?->getTimestamp() ?? 0,
                'at' => $company->updated_at,
                'company' => $company->name,
                'entity' => 'Company',
                'summary' => 'Company profile updated',
                'url' => null,
            ]);
        }
    }

    private function appendUserRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = User::query()->whereBetween('created_at', [$from, $to]);

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $user) {
            $companyName = $user->company?->name ?? '—';

            $rows->push([
                'sort_ts' => $user->created_at?->getTimestamp() ?? 0,
                'at' => $user->created_at,
                'company' => $companyName,
                'entity' => 'User',
                'summary' => 'User created: ' . $user->name . ' (' . $user->type . ')',
                'url' => null,
            ]);
        }
    }

    private function appendApplicationRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = TenantApplication::query()
            ->with('company')
            ->where(function ($q) use ($from, $to): void {
                $q->whereBetween('created_at', [$from, $to])
                    ->orWhereBetween('reviewed_at', [$from, $to]);
            });

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $application) {
            $ts = $application->reviewed_at ?? $application->created_at;
            $rows->push([
                'sort_ts' => $ts?->getTimestamp() ?? 0,
                'at' => $ts,
                'company' => $application->company?->name ?? '—',
                'entity' => 'Application',
                'summary' => 'Application ' . $application->status . ' (#' . $application->id . ')',
                'url' => null,
            ]);
        }
    }

    private function appendContractRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = Contract::query()
            ->with('company')
            ->where(function ($q) use ($from, $to): void {
                $q->whereBetween('created_at', [$from, $to])
                    ->orWhereBetween('signed_at', [$from, $to])
                    ->orWhereBetween('updated_at', [$from, $to]);
            });

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $contract) {
            $ts = $contract->signed_at ?? $contract->updated_at ?? $contract->created_at;

            $rows->push([
                'sort_ts' => $ts?->getTimestamp() ?? 0,
                'at' => $ts,
                'company' => $contract->company?->name ?? '—',
                'entity' => 'Contract',
                'summary' => 'Contract ' . $contract->contract_number . ' (' . $contract->status . ')',
                'url' => null,
            ]);
        }
    }

    private function appendInvoiceRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = Invoice::query()
            ->with('company')
            ->where(function ($q) use ($from, $to): void {
                $q->whereBetween('created_at', [$from, $to])
                    ->orWhereBetween('paid_at', [$from, $to])
                    ->orWhereBetween('updated_at', [$from, $to]);
            });

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $invoice) {
            $ts = $invoice->paid_at ?? $invoice->updated_at ?? $invoice->created_at;

            $rows->push([
                'sort_ts' => $ts?->getTimestamp() ?? 0,
                'at' => $ts,
                'company' => $invoice->company?->name ?? '—',
                'entity' => 'Invoice',
                'summary' => 'Invoice ' . $invoice->invoice_number . ' (' . $invoice->status . ')',
                'url' => null,
            ]);
        }
    }

    private function appendMaintenanceRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = MaintenanceTicket::query()
            ->with('company')
            ->whereBetween('updated_at', [$from, $to]);

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $ticket) {
            $rows->push([
                'sort_ts' => $ticket->updated_at?->getTimestamp() ?? 0,
                'at' => $ticket->updated_at,
                'company' => $ticket->company?->name ?? '—',
                'entity' => 'Maintenance',
                'summary' => 'Ticket ' . $ticket->ticket_number . ' (' . $ticket->status . ')',
                'url' => null,
            ]);
        }
    }

    private function appendPaymentRows(Collection $rows, Carbon $from, Carbon $to, ?int $companyId): void
    {
        $query = Payment::query()
            ->with('company')
            ->whereBetween('updated_at', [$from, $to]);

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        foreach ($query->cursor() as $payment) {
            $rows->push([
                'sort_ts' => $payment->updated_at?->getTimestamp() ?? 0,
                'at' => $payment->updated_at,
                'company' => $payment->company?->name ?? '—',
                'entity' => 'Payment',
                'summary' => 'Payment ' . ($payment->payment_reference ?? '#' . $payment->id) . ' (' . $payment->status . ')',
                'url' => null,
            ]);
        }
    }
}
