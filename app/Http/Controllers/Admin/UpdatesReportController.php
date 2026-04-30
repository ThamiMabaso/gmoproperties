<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUpdateReport;
use App\Models\Company;
use App\Services\Admin\PlatformUpdatesReportBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UpdatesReportController extends Controller
{
    public function __construct(
        private readonly PlatformUpdatesReportBuilder $builder,
    ) {
    }

    public function index(Request $request): View
    {
        abort_unless(Auth::user()?->can('view_admin_reports'), 403);

        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $from = isset($validated['date_from'])
            ? Carbon::parse($validated['date_from'])->startOfDay()
            : now()->subDays(30)->startOfDay();

        $to = isset($validated['date_to'])
            ? Carbon::parse($validated['date_to'])->endOfDay()
            : now()->endOfDay();

        $companyId = isset($validated['company_id']) ? (int) $validated['company_id'] : null;

        $rows = $this->builder->buildRows($from, $to, $companyId);
        $companies = Company::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.reports.updates.index', [
            'rows' => $rows,
            'companies' => $companies,
            'from' => $from,
            'to' => $to,
            'filters' => $validated,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        abort_unless(Auth::user()?->can('export_admin_reports'), 403);

        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'nullable|string|max:255',
        ]);

        $from = isset($validated['date_from'])
            ? Carbon::parse($validated['date_from'])->startOfDay()
            : now()->subDays(30)->startOfDay();

        $to = isset($validated['date_to'])
            ? Carbon::parse($validated['date_to'])->endOfDay()
            : now()->endOfDay();

        $companyId = isset($validated['company_id']) ? (int) $validated['company_id'] : null;

        $rows = $this->builder->buildRows($from, $to, $companyId);

        AdminUpdateReport::create([
            'user_id' => (int) Auth::id(),
            'title' => $validated['title'] ?? 'Updates export',
            'filters' => [
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
                'company_id' => $companyId,
            ],
            'format' => 'csv',
            'status' => 'completed',
        ]);

        $filename = 'platform-updates-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['When', 'Company', 'Entity', 'Summary']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['at'] instanceof Carbon ? $row['at']->format('Y-m-d H:i') : '',
                    $row['company'],
                    $row['entity'],
                    $row['summary'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request)
    {
        abort_unless(Auth::user()?->can('export_admin_reports'), 403);

        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'nullable|string|max:255',
        ]);

        $from = isset($validated['date_from'])
            ? Carbon::parse($validated['date_from'])->startOfDay()
            : now()->subDays(30)->startOfDay();

        $to = isset($validated['date_to'])
            ? Carbon::parse($validated['date_to'])->endOfDay()
            : now()->endOfDay();

        $companyId = isset($validated['company_id']) ? (int) $validated['company_id'] : null;

        $rows = $this->builder->buildRows($from, $to, $companyId);

        AdminUpdateReport::create([
            'user_id' => (int) Auth::id(),
            'title' => $validated['title'] ?? 'Updates export',
            'filters' => [
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
                'company_id' => $companyId,
            ],
            'format' => 'pdf',
            'status' => 'completed',
        ]);

        $pdf = Pdf::loadView('admin.reports.updates.pdf', [
            'rows' => $rows,
            'from' => $from,
            'to' => $to,
        ]);

        return $pdf->download('platform-updates-' . now()->format('Y-m-d-His') . '.pdf');
    }
}
