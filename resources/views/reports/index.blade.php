@extends('layouts.app')
@section('title', 'Reports — Ironclad Forge')
@section('page-title', 'Project Reporting')

@section('styles')
<style>
    .industrial-scroll::-webkit-scrollbar { width: 4px; }
    .industrial-scroll::-webkit-scrollbar-track { background: transparent; }
    .industrial-scroll::-webkit-scrollbar-thumb { background: #424754; border-radius: 10px; }
    /* Report Drawer */
    #report-detail-backdrop { transition: opacity 0.3s ease; }
    #report-detail-drawer   { transition: transform 0.4s cubic-bezier(0.4,0,0.2,1); }
    #report-detail-drawer.drawer-open   { transform: translateX(0); }
    #report-detail-drawer.drawer-closed { transform: translateX(100%); }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#0b1326] text-[#dae2fd] font-body">

@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>setTimeout(()=>toast(@json(session('error')),'warn'),200))</script>
@endif

{{-- ── Page Title ──────────────────────────────────── --}}
<div class="flex justify-between items-end mb-10">
    <div>
        <p class="font-label text-xs text-[#adc6ff] uppercase tracking-[0.2em] font-bold mb-2">Analytics Engine</p>
        <h2 class="font-headline text-4xl font-extrabold text-[#dae2fd] tracking-tight">Project Reporting</h2>
    </div>
    @can('export reports')
    <a href="{{ route('reports.export','projects') }}"
        class="bg-gradient-to-br from-[#adc6ff] to-[#4d8eff] px-6 py-3 rounded-xl font-headline font-bold text-sm text-[#002e6a] shadow-lg shadow-[#adc6ff]/20 flex items-center gap-2 hover:opacity-90 transition-all">
        <span class="material-symbols-outlined text-sm">download</span>
        Export Report
    </a>
    @endcan
</div>

{{-- ── Summary Cards ─────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-[#222a3d] rounded-xl p-6 flex flex-col justify-between group hover:bg-[#2d3449] transition-colors shadow-2xl shadow-blue-950/20">
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-[#adc6ff] bg-[#adc6ff]/10 p-2 rounded-lg">description</span>
            <span class="text-[10px] font-label font-bold text-[#8392a6] uppercase tracking-widest">+12.4%</span>
        </div>
        <div>
            <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-[0.15em] font-semibold mb-1">Total Reports</p>
            <h3 class="font-headline text-3xl font-extrabold text-[#dae2fd] tracking-tight">{{ number_format($stats['projects'] + $stats['tasks'] + $stats['incidents']) }}</h3>
        </div>
    </div>
    <div class="bg-[#222a3d] rounded-xl p-6 flex flex-col justify-between group hover:bg-[#2d3449] transition-colors shadow-2xl shadow-blue-950/20">
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-[#ffb95f] bg-[#ffb95f]/10 p-2 rounded-lg">construction</span>
            <span class="text-[10px] font-label font-bold text-[#ffb95f] uppercase tracking-widest">Active</span>
        </div>
        <div>
            <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-[0.15em] font-semibold mb-1">Projects</p>
            <h3 class="font-headline text-3xl font-extrabold text-[#dae2fd] tracking-tight">{{ $stats['projects'] }}</h3>
        </div>
    </div>
    <div class="bg-[#222a3d] rounded-xl p-6 flex flex-col justify-between group hover:bg-[#2d3449] transition-colors shadow-2xl shadow-blue-950/20">
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-[#b9c8de] bg-[#b9c8de]/10 p-2 rounded-lg">assignment</span>
            <span class="text-[10px] font-label font-bold text-[#8c909f] uppercase tracking-widest">Total</span>
        </div>
        <div>
            <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-[0.15em] font-semibold mb-1">Tasks</p>
            <h3 class="font-headline text-3xl font-extrabold text-[#dae2fd] tracking-tight">{{ $stats['tasks'] }}</h3>
        </div>
    </div>
    <div class="bg-[#222a3d] rounded-xl p-6 flex flex-col justify-between group hover:bg-[#2d3449] transition-colors shadow-2xl shadow-blue-950/20">
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-[#adc6ff] bg-[#adc6ff]/10 p-2 rounded-lg">group</span>
            <span class="text-[10px] font-label font-bold text-[#8c909f] uppercase tracking-widest">Registered</span>
        </div>
        <div>
            <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-[0.15em] font-semibold mb-1">Team Members</p>
            <h3 class="font-headline text-3xl font-extrabold text-[#dae2fd] tracking-tight">{{ $stats['members'] }}</h3>
        </div>
    </div>
</div>

{{-- ── Main Grid ──────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- Available Reports --}}
    <div class="lg:col-span-8 flex flex-col gap-6">
        <div class="bg-[#131b2e] border border-[#424754]/10 rounded-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-[#424754]/10 flex justify-between items-center">
                <h3 class="font-headline text-lg font-bold text-[#dae2fd]">Available reports</h3>
                @can('export reports')
                <span class="text-[#adc6ff] font-label text-[10px] uppercase tracking-widest font-bold">PDF via DomPDF</span>
                @endcan
            </div>
            <div class="divide-y divide-[#424754]/10">
                @php
                $reportItems = [
                    ['type'=>'projects','icon'=>'bar_chart','title'=>'Weekly Progress Summary','sub'=>'Projects • Progress & budget data','color'=>'text-[#adc6ff] bg-[#adc6ff]/20'],
                    ['type'=>'safety','icon'=>'warning','title'=>'Safety Compliance Audit','sub'=>'Regional • All incidents with severity','color'=>'text-[#ffb95f] bg-[#ffb95f]/20'],
                    ['type'=>'tasks','icon'=>'inventory','title'=>'Task Summary Report','sub'=>'All tasks by status, priority, assignee','color'=>'text-[#b9c8de] bg-[#b9c8de]/20'],
                ];
                @endphp

                @foreach($reportItems as $r)
                <div class="px-8 py-5 flex items-center justify-between hover:bg-[#222a3d] transition-colors group cursor-pointer border-l-4 {{ $loop->first ? 'border-[#adc6ff] bg-[#222a3d]' : 'border-transparent' }}"
                    onclick="openReportDetailDrawer(@json($r['title']), @json($r['sub']), @json($r['type']))">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg {{ $loop->first ? $r['color'] : 'bg-[#171f33] text-[#8c909f] group-hover:'.$r['color'] }} flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined">{{ $r['icon'] }}</span>
                        </div>
                        <div>
                            <p class="font-body text-sm font-semibold text-[#dae2fd]">{{ $r['title'] }}</p>
                            <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-widest">{{ $r['sub'] }}</p>
                        </div>
                    </div>
                    @can('export reports')
                    <div class="flex items-center gap-3" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.export', $r['type']) }}"
                            class="p-2 text-[#8c909f] hover:text-[#dae2fd] transition-colors" title="Export PDF">
                            <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                        </a>
                    </div>
                    @endcan
                </div>
                @endforeach
            </div>
        </div>

        {{-- Budget Overview --}}
        <div class="bg-[#131b2e] border border-[#424754]/10 rounded-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-[#424754]/10">
                <h3 class="font-headline text-lg font-bold text-[#dae2fd]">Project budget overview</h3>
            </div>
            <div class="p-8 space-y-5">
                @foreach($projects->take(4) as $p)
                @php
                    $pct = $p->budget_allocated > 0 ? round(($p->budget_spent / $p->budget_allocated) * 100, 1) : 0;
                    $barColor = $pct >= 85 ? '#ffb4ab' : ($pct >= 70 ? '#ffb95f' : '#adc6ff');
                @endphp
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-[#c2c6d6]">{{ Str::limit($p->project_name, 32) }}</span>
                        <span class="font-bold" style="color:{{ $barColor }}">{{ $pct }}%</span>
                    </div>
                    <div class="h-2 bg-[#424754]/20 rounded-full overflow-hidden">
                        <div class="h-2 rounded-full transition-all" style="width:{{ min($pct,100) }}%;background:{{ $barColor }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Column: Chart + Status --}}
    <div class="lg:col-span-4 flex flex-col gap-8">
        <div class="bg-[#222a3d] rounded-xl p-8 shadow-2xl shadow-blue-950/20 relative overflow-hidden">
            <div class="relative z-10 mb-6">
                <h3 class="font-headline text-lg font-bold text-[#dae2fd] mb-1">Tasks by Status</h3>
                <p class="font-label text-[10px] text-[#8c909f] uppercase tracking-widest">Distribution overview</p>
            </div>
            <div class="relative h-48 w-full">
                <canvas id="taskChart"></canvas>
            </div>
        </div>

        <div class="bg-[#222a3d] rounded-xl p-8 shadow-2xl shadow-blue-950/20">
            <h3 class="font-headline text-sm font-bold text-[#dae2fd] mb-6 uppercase tracking-widest">Report Automation Status</h3>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-[#b9c8de]"></div>
                        <span class="text-xs font-body text-[#dae2fd]">PDF Generator API</span>
                    </div>
                    <span class="px-2 py-1 bg-[#b9c8de]/10 text-[#b9c8de] text-[9px] uppercase font-bold tracking-widest rounded">Online</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-[#ffb95f]"></div>
                        <span class="text-xs font-body text-[#dae2fd]">Batch Exporter</span>
                    </div>
                    <span class="px-2 py-1 bg-[#ffb95f]/10 text-[#ffb95f] text-[9px] uppercase font-bold tracking-widest rounded">Active</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-[#adc6ff]"></div>
                        <span class="text-xs font-body text-[#dae2fd]">Cloud Sync Engine</span>
                    </div>
                    <span class="px-2 py-1 bg-[#adc6ff]/10 text-[#adc6ff] text-[9px] uppercase font-bold tracking-widest rounded">Stable</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

{{-- ══════════════════════════════════════════════════════════ --}}
{{--  REPORT DETAIL DRAWER                                      --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-[#0b1326]/60 backdrop-blur-sm z-[60] transition-opacity duration-300 opacity-0 pointer-events-none" id="report-detail-backdrop"></div>
<div class="fixed top-0 right-0 h-full w-full max-w-lg bg-[#0b1326] border-l border-[#424754]/30 shadow-2xl z-[70] transform drawer-closed flex flex-col" id="report-detail-drawer">
    <div class="px-8 py-6 border-b border-[#424754]/20 flex items-center justify-between">
        <div>
            <h3 class="font-headline text-xl font-bold text-[#dae2fd]" id="rd-title">Report Details</h3>
            <p class="font-label text-[10px] text-[#adc6ff] uppercase tracking-widest mt-1" id="rd-sub">—</p>
        </div>
        <button class="p-2 text-[#8c909f] hover:text-[#dae2fd] transition-colors rounded-lg hover:bg-[#2d3449]" onclick="closeReportDetailDrawer()">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto industrial-scroll px-8 py-6 space-y-10">
        <section>
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[#adc6ff] text-sm">settings_input_component</span>
                <h4 class="font-headline text-xs font-bold text-[#dae2fd] uppercase tracking-widest">Report Configuration</h4>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#131b2e] p-4 rounded-xl border border-[#424754]/10">
                    <label class="block text-[10px] text-[#8c909f] uppercase tracking-widest font-bold mb-1">Timeframe</label>
                    <p class="text-sm font-semibold text-[#dae2fd]">Last 30 Days</p>
                </div>
                <div class="bg-[#131b2e] p-4 rounded-xl border border-[#424754]/10">
                    <label class="block text-[10px] text-[#8c909f] uppercase tracking-widest font-bold mb-1">Format</label>
                    <p class="text-sm font-semibold text-[#dae2fd]">PDF (DomPDF)</p>
                </div>
                <div class="col-span-2 bg-[#131b2e] p-4 rounded-xl border border-[#424754]/10">
                    <label class="block text-[10px] text-[#8c909f] uppercase tracking-widest font-bold mb-1">Focus Areas</label>
                    <div class="flex flex-wrap gap-2 mt-2" id="rd-focus-areas">
                        <span class="px-2 py-1 bg-[#adc6ff]/10 text-[#adc6ff] text-[9px] rounded font-bold">Structural</span>
                        <span class="px-2 py-1 bg-[#adc6ff]/10 text-[#adc6ff] text-[9px] rounded font-bold">Safety</span>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[#ffb95f] text-sm">schedule</span>
                <h4 class="font-headline text-xs font-bold text-[#dae2fd] uppercase tracking-widest">Scheduled Exports</h4>
            </div>
            <div class="bg-[#131b2e] p-4 rounded-xl border border-[#424754]/10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-[#dae2fd]">Every Monday</p>
                    <p class="text-[10px] text-[#8c909f] uppercase tracking-widest">08:00 AM Local</p>
                </div>
                <div class="w-10 h-6 bg-[#adc6ff]/20 rounded-full relative p-1 cursor-pointer">
                    <div class="absolute right-1 top-1 w-4 h-4 bg-[#adc6ff] rounded-full"></div>
                </div>
            </div>
        </section>
    </div>

    <div class="p-8 border-t border-[#424754]/20 bg-[#222a3d]/30 flex flex-col gap-3">
        @can('export reports')
        <div class="grid grid-cols-2 gap-3">
            <a id="rd-export-link" href="#"
                class="bg-gradient-to-br from-[#adc6ff] to-[#4d8eff] py-3 rounded-xl font-headline font-bold text-xs text-[#002e6a] flex items-center justify-center gap-2 shadow-lg shadow-[#adc6ff]/20 hover:opacity-90 transition">
                <span class="material-symbols-outlined text-sm">auto_graph</span>
                Generate PDF
            </a>
            <button onclick="closeReportDetailDrawer()"
                class="bg-[#2d3449] py-3 rounded-xl font-headline font-bold text-xs text-[#dae2fd] flex items-center justify-center gap-2 border border-[#424754]/20 hover:bg-[#31394d] transition">
                <span class="material-symbols-outlined text-sm">close</span>
                Close
            </button>
        </div>
        @else
        <button onclick="closeReportDetailDrawer()"
            class="w-full bg-[#2d3449] py-3 rounded-xl font-headline font-bold text-xs text-[#dae2fd] flex items-center justify-center gap-2 hover:bg-[#31394d] transition">
            Close
        </button>
        @endcan
    </div>
</div>

@endsection

@section('scripts')
<script>
/* ── Report Detail Drawer ── */
function openReportDetailDrawer(title, sub, type) {
    document.getElementById('rd-title').textContent  = title;
    document.getElementById('rd-sub').textContent    = sub;
    const exportLink = document.getElementById('rd-export-link');
    if (exportLink) exportLink.href = '/reports/export/' + type;

    document.getElementById('report-detail-backdrop').classList.remove('opacity-0','pointer-events-none');
    document.getElementById('report-detail-backdrop').classList.add('opacity-100');
    document.getElementById('report-detail-drawer').classList.remove('drawer-closed');
    document.getElementById('report-detail-drawer').classList.add('drawer-open');
}
function closeReportDetailDrawer() {
    document.getElementById('report-detail-backdrop').classList.add('opacity-0','pointer-events-none');
    document.getElementById('report-detail-backdrop').classList.remove('opacity-100');
    document.getElementById('report-detail-drawer').classList.remove('drawer-open');
    document.getElementById('report-detail-drawer').classList.add('drawer-closed');
}
document.getElementById('report-detail-backdrop')?.addEventListener('click', closeReportDetailDrawer);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReportDetailDrawer(); });

/* ── Task Doughnut Chart ── */
window.addEventListener('load', () => {
    const ctx = document.getElementById('taskChart')?.getContext('2d');
    if (!ctx) return;
    const raw = @json($tasksByStatus);
    const statusColors = { pending:'#ffb95f', in_progress:'#adc6ff', completed:'#b9c8de', blocked:'#ffb4ab' };
    const labels = raw.map(r => r.status.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase()));
    const data   = raw.map(r => r.count);
    const colors = raw.map(r => statusColors[r.status] || '#8c909f');
    new Chart(ctx, {
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 6 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { position:'right', labels:{ color:'#c2c6d6', font:{ size:11 }, padding:12, boxWidth:10 } },
                tooltip: { backgroundColor:'#171f33', borderColor:'rgba(255,255,255,.1)', borderWidth:1, titleColor:'#dae2fd', bodyColor:'#8c909f' }
            }
        }
    });
});
</script>
@endsection
