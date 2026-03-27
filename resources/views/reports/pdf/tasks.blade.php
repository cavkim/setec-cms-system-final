<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1a1a2e}
.header{background:#0D1B2A;color:#fff;padding:20px 28px;margin-bottom:20px}
.header h1{font-size:18px;font-weight:700;margin-bottom:4px}
.header p{font-size:11px;opacity:.6}
table{width:calc(100% - 56px);margin:0 28px;border-collapse:collapse}
th{background:#0D1B2A;color:#E8EEF4;font-size:9px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:left}
td{padding:8px 10px;border-bottom:1px solid #eee;font-size:10px}
tr:nth-child(even) td{background:#f8f9fa}
.badge{padding:2px 7px;border-radius:6px;font-size:9px;font-weight:700}
.high{background:#ffebee;color:#c62828}
.medium{background:#fff3e0;color:#e65100}
.low{background:#e8f5e9;color:#2e7d32}
.completed{background:#e8f5e9;color:#2e7d32}
.in_progress{background:#e3f2fd;color:#1565c0}
.pending{background:#fff3e0;color:#e65100}
.blocked{background:#eceff1;color:#546e7a}
.footer{margin:20px 28px 0;font-size:9px;color:#999;border-top:1px solid #eee;padding-top:10px}
</style>
</head>
<body>
<div class="header">
    <h1>BuildScape CMS — Task Summary Report</h1>
    <p>Generated: {{ now()->format('F d, Y — H:i') }} · Total tasks: {{ $tasks->count() }}</p>
</div>
<table>
    <thead>
        <tr><th>Task</th><th>Project</th><th>Assigned To</th><th>Priority</th><th>Status</th><th>Due Date</th></tr>
    </thead>
    <tbody>
        @foreach($tasks as $t)
        <tr>
            <td><strong>{{ $t->task_name }}</strong></td>
            <td>{{ $t->project_name }}</td>
            <td>{{ $t->assignee ?? 'Unassigned' }}</td>
            <td><span class="badge {{ $t->priority }}">{{ strtoupper($t->priority) }}</span></td>
            <td><span class="badge {{ $t->status }}">{{ strtoupper(str_replace('_',' ',$t->status)) }}</span></td>
            <td>{{ $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('M d, Y') : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">BuildScape Construction Management System · Confidential · {{ now()->format('Y') }}</div>
</body>
</html>
