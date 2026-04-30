<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Platform updates</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 11px; line-height: 1.4; }
        .header { border-bottom: 2px solid #e5e7eb; margin-bottom: 14px; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: 700; margin: 0; }
        .muted { color: #6b7280; font-size: 10px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f9fafb; font-size: 9px; text-transform: uppercase; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Platform updates report</p>
        <p class="muted">{{ $from->format('Y-m-d') }} — {{ $to->format('Y-m-d') }} · {{ $rows->count() }} row(s)</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>When</th>
                <th>Company</th>
                <th>Entity</th>
                <th>Summary</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['at'] instanceof \Carbon\Carbon ? $row['at']->format('Y-m-d H:i') : '' }}</td>
                    <td>{{ $row['company'] }}</td>
                    <td>{{ $row['entity'] }}</td>
                    <td>{{ $row['summary'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
