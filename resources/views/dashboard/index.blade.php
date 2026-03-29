<!-- @extends('layouts.app')
@section('title', 'Dashboard — BuildScape CMS')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── KPI ROW ──────────────────────────────────────────── --}}
<div class="krow">

    <div class="kpi" style="--ac:linear-gradient(90deg,#1565C0,#42A5F5)"
         onclick="openModal('Active Projects','<div style=\'font-size:12px;color:var(--t2);line-height:1.8\'>{{ $activeProjects }} projects currently in progress out of {{ $totalProjects }} total projects.</div>')">
        <div class="kl">Active Projects</div>
        <div class="kv">{{ $activeProjects }} <span>/ {{ $totalProjects }} total</span></div>
        <div class="kd kd-up">▲ 3 new this month</div>
    </div>

    <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4DB6AC)"
         onclick="openKanbanModal()">
        <div class="kl">Tasks completed today</div>
        <div class="kv">{{ $completedToday }} <span>/ {{ $openTasks }} open</span></div>
        <div class="kd kd-up">▲ 33% completion rate</div>
    </div>

    <div class="kpi" style="--ac:linear-gradient(90deg,#F57C00,#FFB74D)"
         onclick="openBudgetModal()">
        <div class="kl">Weekly spend</div>
        <div class="kv">${{ number_format($weeklySpend/1000,1) }}K <span>USD</span></div>
        <div class="kd kd-dn">▼ 12% over weekly target</div>
    </div>

    <div class="kpi" style="--ac:linear-gradient(90deg,#C62828,#EF9A9A)"
         onclick="openModal('Team Overview','<div style=\'font-size:12px;color:var(--t2)\'>{{ $activeWorkers }} workers currently on-site across all projects.</div>')">
        <div class="kl">Active workers</div>
        <div class="kv">{{ $activeWorkers }} <span>on-site</span></div>
        <div class="kd kd-n">— Same as yesterday</div>
    </div>

</div>

{{-- ── ALERTS ───────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:12px">
    <div class="ch">
        <div class="ct">Active alerts</div>
        <button class="ca" onclick="dismissAllAlerts()">Dismiss all</button>
    </div>
    <div class="aw">

        {{-- Budget alerts from DB --}}
        @foreach($budgetAlerts as $i => $alert)
        <div class="al al-r" id="alert-budget-{{ $i }}">
            <div class="al-dot" style="background:var(--red)"></div>
            <div style="flex:1">
                <div class="al-title">Budget critical — {{ $alert->project_name }} at {{ round($alert->budget_used_percent) }}% used</div>
                <div class="al-desc">${{ number_format($alert->budget_spent) }} of ${{ number_format($alert->budget_allocated) }} allocated.</div>
            </div>
            <div class="al-x" onclick="dismissAlert('alert-budget-{{ $i }}')">×</div>
        </div>
        @endforeach

        {{-- Deadline alerts from DB --}}
        @foreach($deadlineTasks as $i => $task)
        <div class="al al-a" id="alert-deadline-{{ $i }}">
            <div class="al-dot" style="background:var(--amber)"></div>
            <div style="flex:1">
                <div class="al-title">Deadline soon — {{ $task->task_name }} due {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</div>
                <div class="al-desc">{{ $task->project_name }} — Currently {{ $task->progress_percent }}% complete</div>
            </div>
            <div class="al-x" onclick="dismissAlert('alert-deadline-{{ $i }}')">×</div>
        </div>
        @endforeach

        {{-- Cert expiry alerts from DB --}}
        @foreach($expiringCerts as $i => $member)
        <div class="al al-b" id="alert-cert-{{ $i }}">
            <div class="al-dot" style="background:var(--sky)"></div>
            <div style="flex:1">
                <div class="al-title">Certification expiring — {{ $member->name }} in {{ \Carbon\Carbon::parse($member->certification_expiry)->diffInDays() }} days</div>
                <div class="al-desc">{{ $member->certification_number }} expires {{ \Carbon\Carbon::parse($member->certification_expiry)->format('M d, Y') }}</div>
            </div>
            <div class="al-x" onclick="dismissAlert('alert-cert-{{ $i }}')">×</div>
        </div>
        @endforeach

    </div>
</div>

{{-- ── ROW 1: Projects + Safety ────────────────────────── --}}
<div class="g2">

    {{-- Active Projects --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Active projects</div>
            <button class="ca" onclick="openModal('All Projects', allProjectsHTML())">View all →</button>
        </div>
        <div class="pl">
            @foreach($recentProjects as $project)
            <div class="pi" onclick="openProjectModal({{ $project->id }}, '{{ $project->project_name }}', {{ $project->budget_allocated }}, {{ $project->budget_spent }}, {{ $project->progress_percent }}, '{{ $project->status }}', '{{ $project->location }}')">
                <div class="pd" style="background:{{ $project->status==='in_progress'?'#42A5F5':($project->status==='on_hold'?'#FFB74D':($project->status==='planning'?'#64B5F6':'#81C784')) }}"></div>
                <div style="flex:1">
                    <div class="pn">{{ $project->project_name }}</div>
                    <div class="pm">{{ $project->location }} · {{ \Carbon\Carbon::parse($project->end_date)->format('M Y') }}</div>
                </div>
                <div class="pbar"><div class="pbf" style="width:{{ $project->progress_percent }}%;background:{{ $project->status==='in_progress'?'#42A5F5':($project->status==='on_hold'?'#FFB74D':($project->status==='planning'?'#64B5F6':'#81C784')) }}"></div></div>
                <div class="ppct">{{ $project->progress_percent }}%</div>
                <div class="sp {{ $project->status==='in_progress'?'sp-a':($project->status==='on_hold'?'sp-h':($project->status==='planning'?'sp-p':'sp-d')) }}">
                    {{ $project->status==='in_progress'?'Active':($project->status==='on_hold'?'On Hold':ucfirst($project->status)) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Safety Overview --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Safety overview</div>
            <button class="ca" onclick="openSafetyModal()">Report →</button>
        </div>
        <div class="saw">
            <div class="sdays">
                <div class="sdn">{{ $daysSafe }}</div>
                <div class="sl">Days since last incident</div>
            </div>
            <div style="display:flex;justify-content:space-around;padding:8px 0;border-top:1px solid var(--bd);border-bottom:1px solid var(--bd);margin-bottom:8px">
                <div style="text-align:center"><div style="font-weight:700;font-size:18px;color:#EF9A9A">{{ $openIncidents }}</div><div style="font-size:10px;color:var(--t3)">Open</div></div>
                <div style="text-align:center"><div style="font-weight:700;font-size:18px;color:#FFCC80">{{ $investigatingIncidents }}</div><div style="font-size:10px;color:var(--t3)">Investigating</div></div>
                <div style="text-align:center"><div style="font-weight:700;font-size:18px;color:#80CBC4">{{ $resolvedIncidents }}</div><div style="font-size:10px;color:var(--t3)">Resolved</div></div>
            </div>
            @foreach($recentIncidents as $incident)
            <div class="sinc" onclick="openIncidentModal('{{ addslashes($incident->description) }}', '{{ $incident->severity }}', '{{ $incident->status }}', '{{ $incident->location }}', '{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}')">
                <div style="width:6px;height:6px;border-radius:50%;flex-shrink:0;background:{{ in_array($incident->severity,['high','critical'])?'var(--red)':($incident->severity==='medium'?'var(--amber)':'var(--teal)') }}"></div>
                <div style="flex:1;color:var(--t2)">{{ Str::limit($incident->description, 42) }}</div>
                <div style="color:var(--t3);font-size:10px">{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d') }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ── ROW 2: Tasks + Team + Activity ──────────────────── --}}
<div class="g3">

    {{-- Pending Tasks --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Pending tasks</div>
            <button class="ca" onclick="openKanbanModal()">Kanban view →</button>
        </div>
        <div class="tw">
            @foreach($pendingTasks as $task)
            <div class="ti">
                <div class="tcb {{ $task->status==='completed'?'done':'' }}" onclick="tick(this)">
                    @if($task->status==='completed')
                    <svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </div>
                <div style="flex:1">
                    <div class="tn {{ $task->status==='completed'?'done':'' }}">{{ $task->task_name }}</div>
                    <div class="tsub">{{ $task->project_name }} · {{ $task->assignee ?? 'Unassigned' }}</div>
                </div>
                <span class="pr pr-{{ $task->priority==='high'?'h':($task->priority==='medium'?'m':'l') }}">{{ strtoupper($task->priority) }}</span>
                <span style="font-size:10px;color:{{ $task->status==='completed'?'var(--green)':(\Carbon\Carbon::parse($task->due_date)->isPast()?'var(--red)':'var(--t3)') }};margin-left:6px">
                    {{ $task->status==='completed'?'Done':\Carbon\Carbon::parse($task->due_date)->format('M d') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Team Workload --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Team workload</div>
            <button class="ca" onclick="toast('Team module coming soon','info')">Manage →</button>
        </div>
        <div class="tmw">
            @php $avatarColors = ['#1565C0','#00897B','#F57C00','#C62828','#6A1B9A','#00838F']; @endphp
            @foreach($teamWorkload as $i => $member)
            <div class="mr" onclick="openMemberModal('{{ $member->name }}', '{{ $member->role ?? 'Team Member' }}', {{ $member->task_count }})">
                <div class="mav" style="background:{{ $avatarColors[$i % count($avatarColors)] }}">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <div class="mn">{{ $member->name }}</div>
                    <div class="mrl">{{ $member->role ?? 'Team Member' }}</div>
                </div>
                <div class="mbar">
                    <div class="mbf" style="width:{{ min(($member->task_count/10)*100,100) }}%;background:{{ $avatarColors[$i % count($avatarColors)] }}"></div>
                </div>
                <div class="mts">{{ $member->task_count }} tasks</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Recent activity</div>
            <button class="ca" onclick="openAuditModal()">Audit log →</button>
        </div>
        <div class="acw">
            @php $dotColors = ['#42A5F5','#EF9A9A','#FFB74D','#4DB6AC','#CE93D8','#80CBC4']; @endphp
            @foreach($recentActivity as $i => $activity)
            <div class="ai" onclick="toast('{{ addslashes($activity->description) }}','info')">
                <div class="adot" style="background:{{ $dotColors[$i % count($dotColors)] }}"></div>
                <div class="at">
                    <b>{{ $activity->causer?->name ?? 'System' }}</b>
                    {{ Str::limit($activity->description, 40) }}
                </div>
                <div class="atime">{{ $activity->created_at->diffForHumans(null,true,true,1) }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ── ROW 3: Budget Chart ──────────────────────────────── --}}
<div class="card" style="margin-bottom:12px">
    <div class="ch">
        <div class="ct">Budget performance — monthly</div>
        <div style="display:flex;align-items:center;gap:14px">
            <span style="font-size:11px;color:var(--t3);display:flex;align-items:center;gap:5px">
                <span style="width:8px;height:8px;border-radius:2px;background:#1565C0;display:inline-block"></span>Allocated
            </span>
            <span style="font-size:11px;color:var(--t3);display:flex;align-items:center;gap:5px">
                <span style="width:8px;height:8px;border-radius:2px;background:#42A5F5;display:inline-block"></span>Spent
            </span>
        </div>
    </div>
    <div style="padding:0 18px 18px;height:200px;position:relative">
        <canvas id="bchart"></canvas>
    </div>
</div>

@endsection

@section('scripts')
<script>
window.addEventListener('load', () => {
    const ctx = document.getElementById('bchart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                { label:'Allocated', data:{!! json_encode($chartAllocated) !!}, backgroundColor:'rgba(21,101,192,0.65)', borderRadius:4, borderSkipped:false, barPercentage:0.55, categoryPercentage:0.72 },
                { label:'Spent',     data:{!! json_encode($chartSpent) !!},     backgroundColor:'rgba(66,165,245,0.85)', borderRadius:4, borderSkipped:false, barPercentage:0.55, categoryPercentage:0.72 }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#1a2e42', borderColor:'rgba(255,255,255,.1)', borderWidth:1, titleColor:'#E8EEF4', bodyColor:'#8BAABF', padding:10, callbacks:{ label: c => ' $'+c.parsed.y+'K' } } },
            scales:{
                x:{ grid:{color:'rgba(255,255,255,.04)'}, ticks:{color:'#4A6880',font:{size:10}}, border:{color:'rgba(255,255,255,.05)'} },
                y:{ grid:{color:'rgba(255,255,255,.05)'}, ticks:{color:'#4A6880',font:{size:10},callback:v=>'$'+v+'K'}, border:{display:false} }
            }
        }
    });
});

function openProjectModal(id, name, allocated, spent, progress, status, location) {
    const used = Math.round(spent/allocated*100);
    const remaining = allocated - spent;
    openModal(name + ' — Project detail',
        `<div style="display:flex;gap:7px;margin-bottom:12px">
            <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(0,137,123,.2);color:#4DB6AC">${status==='in_progress'?'Active':status}</span>
            ${used>85?'<span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(198,40,40,.2);color:#EF9A9A">Budget Critical</span>':''}
        </div>
        <div style="margin-bottom:8px">
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Overall progress</span><span style="color:var(--t1);font-weight:600">${progress}%</span></div>
            <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${progress}%;background:#42A5F5;border-radius:6px"></div></div>
        </div>
        <div style="margin-bottom:14px">
            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Budget consumed</span><span style="color:${used>85?'#EF9A9A':'#FFB74D'};font-weight:600">${used}%${used>85?' — CRITICAL':''}</span></div>
            <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${Math.min(used,100)}%;background:${used>85?'#EF9A9A':'#FFB74D'};border-radius:6px"></div></div>
        </div>
        <div class="dr"><span class="dl">Location</span><span class="dv">${location}</span></div>
        <div class="dr"><span class="dl">Budget allocated</span><span class="dv">$${Number(allocated).toLocaleString()}</span></div>
        <div class="dr"><span class="dl">Budget spent</span><span class="dv" style="color:${used>85?'#EF9A9A':'var(--t1)'}">$${Number(spent).toLocaleString()}</span></div>
        <div class="dr"><span class="dl">Remaining</span><span class="dv" style="color:#4DB6AC">$${Number(remaining).toLocaleString()}</span></div>`,
        `<button class="btn btn-p" onclick="toast('Edit project opened','info');closeModal()">Edit project</button>
         <button class="btn btn-g" onclick="toast('PDF report downloaded','success');closeModal()">Export PDF</button>`
    );
}

function openIncidentModal(desc, severity, status, location, date) {
    openModal('Safety Incident Report',
        `<div style="display:flex;gap:7px;margin-bottom:12px">
            <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(245,124,0,.15);color:#FFB74D">${severity}</span>
            <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(21,101,192,.2);color:#64B5F6">${status}</span>
        </div>
        <div class="dr"><span class="dl">Date</span><span class="dv">${date}</span></div>
        <div class="dr"><span class="dl">Location</span><span class="dv">${location}</span></div>
        <div style="margin-top:12px;font-size:11px;color:var(--t3);margin-bottom:6px">Description</div>
        <div style="font-size:12px;color:var(--t2);line-height:1.55;background:var(--card2);border-radius:8px;padding:10px 12px">${desc}</div>`,
        `<button class="btn btn-s" onclick="toast('Incident marked resolved','success');closeModal()">Mark resolved</button>
         <button class="btn btn-g" onclick="toast('Incident PDF exported','success');closeModal()">Export PDF</button>`
    );
}

function openMemberModal(name, role, tasks) {
    openModal(name + ' — Profile',
        `<div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--bd)">
            <div style="width:46px;height:46px;border-radius:50%;background:#1565C0;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff">
                ${name.substring(0,2).toUpperCase()}
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--t1)">${name}</div>
                <div style="font-size:11px;color:var(--t3)">${role} · Active</div>
            </div>
        </div>
        <div class="dr"><span class="dl">Role</span><span class="dv">${role}</span></div>
        <div class="dr"><span class="dl">Active tasks</span><span class="dv">${tasks} tasks assigned</span></div>`,
        `<button class="btn btn-g" onclick="closeModal()">Close</button>`
    );
}

function openBudgetModal() {
    openModal('Budget overview — all projects',
        `<div style="margin-bottom:14px">
            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px">
                <span style="color:var(--t3)">Total allocated</span>
                <span style="color:var(--t1);font-weight:700">${{ number_format(array_sum(array_column($recentProjects->toArray(),'budget_allocated'))) }}</span>
            </div>
            <div style="height:8px;background:rgba(255,255,255,.06);border-radius:8px;overflow:hidden">
                <div style="height:8px;width:72%;background:#1565C0;border-radius:8px"></div>
            </div>
        </div>`,
        `<button class="btn btn-p" onclick="toast('Excel exported','success');closeModal()">Export Excel</button>
         <button class="btn btn-g" onclick="toast('PDF downloaded','success');closeModal()">Export PDF</button>`
    );
}

function openSafetyModal() {
    openModal('Safety & Compliance',
        `<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:16px">
            <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#4CAF50">{{ $daysSafe }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Days safe</div></div>
            <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#EF9A9A">{{ $openIncidents }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Open</div></div>
            <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#4DB6AC">{{ $resolvedIncidents }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Resolved</div></div>
        </div>`,
        `<button class="btn btn-p" onclick="toast('Incident report form opened','info');closeModal()">+ Report incident</button>
         <button class="btn btn-g" onclick="toast('Safety report exported','success');closeModal()">Export report</button>`
    );
}

function openKanbanModal() {
    openModal('Task Kanban Board',
        `<div class="kboard">
            <div class="kcol">
                <div class="kch">Pending <span class="kbadge">0</span></div>
            </div>
            <div class="kcol">
                <div class="kch">In Progress <span class="kbadge">0</span></div>
            </div>
            <div class="kcol">
                <div class="kch">Done <span class="kbadge">0</span></div>
            </div>
        </div>`,
        `<button class="btn btn-p" onclick="toast('New task form opened','info');closeModal()">+ New task</button>`
    );
}

function openAuditModal() {
    openModal('Audit Log — recent actions',
        `<div style="font-size:11px;color:var(--t3);margin-bottom:12px">Every change tracked automatically.</div>`,
        `<button class="btn btn-g" onclick="toast('Audit log exported','success');closeModal()">Export PDF</button>`
    );
}

function allProjectsHTML() {
    return `All projects list`;
}
</script>
@endsection -->
