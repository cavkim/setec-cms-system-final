<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1a1a2e;background:#fff}
.header{background:#0D1B2A;color:#fff;padding:20px 28px;margin-bottom:20px}
.header h1{font-size:18px;font-weight:700;margin-bottom:4px}
.header p{font-size:11px;opacity:.6}
table{width:calc(100% - 56px);margin:0 28px;border-collapse:collapse}
th{background:#0D1B2A;color:#E8EEF4;font-size:9px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:left}
td{padding:8px 10px;border-bottom:1px solid #eee;font-size:10px}
tr:nth-child(even) td{background:#f8f9fa}
.badge{padding:2px 8px;border-radius:6px;font-size:9px;font-weight:700}
.in_progress{background:#e3f2fd;color:#1565c0}
.on_hold{background:#fff3e0;color:#e65100}
.planning{background:#e3f2fd;color:#1565c0}
.completed{background:#f3e5f5;color:#6a1b9a}
.footer{margin:20px 28px 0;font-size:9px;color:#999;border-top:1px solid #eee;padding-top:10px}
</style>
</head>
<body>
<div class="header">
    <h1>BuildScape CMS — Project Status Report</h1>
    <p>Generated: {{ now()->format('F d, Y — H:i') }} · Total projects: {{ $projects->count() }}</p>
</div>
<table>
    <thead>
        <tr>
            <th>Project Name</th>
            <th>Location</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Budget Allocated</th>
            <th>Budget Spent</th>
            <th>% Used</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $p)
        @php $pct = $p->budget_allocated>0?round(($p->budget_spent/$p->budget_allocated)*100,1):0; @endphp
        <tr>
            <td><strong>{{ $p->project_name }}</strong></td>
            <td>{{ $p->location ?? '—' }}</td>
            <td><span class="badge {{ $p->status }}">{{ strtoupper(str_replace('_',' ',$p->status)) }}</span></td>
            <td>{{ $p->progress_percent }}%</td>
            <td>${{ number_format($p->budget_allocated) }}</td>
            <td>${{ number_format($p->budget_spent) }}</td>
            <td style="color:{{ $pct>=85?'#c62828':'inherit' }}">{{ $pct }}%{{ $pct>=85?' ⚠':'' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">BuildScape Construction Management System · Confidential · {{ now()->format('Y') }}</div>
</body>
</html>
