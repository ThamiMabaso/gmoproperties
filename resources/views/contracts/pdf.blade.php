<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $contract->contract_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; line-height: 1.45; }
        .header { border-bottom: 2px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; }
        .title { font-size: 20px; font-weight: 700; margin: 0; }
        .muted { color: #6b7280; }
        .section { margin-top: 14px; }
        .section-title { font-size: 13px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; vertical-align: top; }
        .label { width: 32%; color: #4b5563; }
        .terms { white-space: pre-wrap; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; background: #fafafa; }
        .signatures { margin-top: 20px; }
        .signature-row { margin-top: 24px; }
        .line { border-top: 1px solid #111827; width: 260px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="header">
        @if($contract->company?->logo_path)
            <div style="margin-bottom: 8px;">
                <img src="{{ public_path('storage/' . $contract->company->logo_path) }}" alt="Company logo" style="max-height: 64px; max-width: 220px;">
            </div>
        @endif
        <p class="title">Lease Contract</p>
        <p class="muted" style="margin: 4px 0 0;">{{ $contract->contract_number }}</p>
    </div>

    <div class="section">
        <p class="section-title">Company & Tenant</p>
        <table>
            <tr><td class="label">Company</td><td>{{ $contract->company?->name }}</td></tr>
            <tr><td class="label">Tenant</td><td>{{ $contract->tenant?->name }}</td></tr>
            <tr><td class="label">Tenant Email</td><td>{{ $contract->tenant?->email }}</td></tr>
            <tr><td class="label">Tenant Phone</td><td>{{ $contract->tenant?->phone ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Property</p>
        <table>
            <tr><td class="label">Building</td><td>{{ $contract->unit?->building?->name }}</td></tr>
            <tr><td class="label">Unit</td><td>{{ $contract->unit?->unit_number }}</td></tr>
            <tr><td class="label">Address</td><td>{{ $contract->unit?->building?->address ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Commercial Terms</p>
        <table>
            <tr><td class="label">Start Date</td><td>{{ optional($contract->start_date)->format('M d, Y') }}</td></tr>
            <tr><td class="label">End Date</td><td>{{ optional($contract->end_date)->format('M d, Y') }}</td></tr>
            <tr><td class="label">Monthly Rent</td><td>R {{ number_format((float) $contract->monthly_rent, 2) }}</td></tr>
            <tr><td class="label">Deposit</td><td>R {{ number_format((float) $contract->deposit, 2) }}</td></tr>
            <tr><td class="label">Status</td><td>{{ ucfirst(str_replace('_', ' ', (string) $contract->status)) }}</td></tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Contract Clauses</p>
        <div class="terms">{{ $contract->terms_text ?: 'No additional terms supplied.' }}</div>
    </div>

    <div class="section signatures">
        <p class="section-title">Signatures</p>
        <div class="signature-row">
            <p><strong>Tenant:</strong> {{ $contract->tenant?->name }}</p>
            @if($contract->signed_by_tenant)
                <p>Signed by tenant user ID {{ $contract->signed_by_tenant }}{{ $contract->signed_at ? ' on ' . $contract->signed_at->format('M d, Y H:i') : '' }}.</p>
            @else
                <p class="muted">Pending tenant signature</p>
            @endif
            <div class="line"></div>
        </div>
        <div class="signature-row">
            <p><strong>Company Representative:</strong> {{ $contract->company?->name }}</p>
            @if($contract->signed_by_company)
                <p>Signed by company user ID {{ $contract->signed_by_company }}{{ $contract->signed_at ? ' on ' . $contract->signed_at->format('M d, Y H:i') : '' }}.</p>
            @else
                <p class="muted">Pending company signature</p>
            @endif
            <div class="line"></div>
        </div>
    </div>
</body>
</html>
