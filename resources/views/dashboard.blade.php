@extends('layouts.app')

@section('title', 'Dashboard — BuildScape')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Header --}}
    <div class="mb-10">
        <p class="text-primary font-bold text-sm mb-1 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">dashboard</span> SITE 402-B / OPERATIONAL
        </p>
        <h1 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface mb-2">Dashboard</h1>
        <p class="text-on-surface-variant font-medium">Operational overview for Q1 Fiscal Period</p>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-primary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Active Projects</p>
            <p class="text-4xl font-extrabold text-white font-headline">12</p>
            <p class="text-xs text-primary mt-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm">trending_up</span> 3 new this month</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-primary-container shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Open Tasks</p>
            <p class="text-4xl font-extrabold text-white font-headline">24</p>
            <p class="text-xs text-tertiary mt-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> 8 completed today</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-secondary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Weekly Spend</p>
            <p class="text-4xl font-extrabold text-white font-headline">$45.6K</p>
            <p class="text-xs text-secondary mt-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm">trending_down</span> 12% over target</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-tertiary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Workers On-site</p>
            <p class="text-4xl font-extrabold text-white font-headline">142</p>
            <p class="text-xs text-on-surface-variant mt-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm">group</span> Same as yesterday</p>
        </div>
    </div>

    {{-- ALERTS --}}
    <div class="bg-surface-container rounded-2xl shadow-lg mb-8 p-6 border border-white/5">
        <h2 class="text-sm font-extrabold uppercase tracking-widest text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span> Active Alerts
        </h2>
        <div class="space-y-3">
            <div class="bg-error-container/20 border border-error/40 rounded-xl p-4 text-sm text-error flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 flex-shrink-0">error</span>
                <div>
                    <strong>Budget critical</strong> — Skyline Tower at 91% used. Only $810K remaining.
                </div>
            </div>
            <div class="bg-secondary-container/20 border border-secondary/40 rounded-xl p-4 text-sm text-secondary flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 flex-shrink-0">schedule</span>
                <div>
                    <strong>Deadline in 3 days</strong> — Steel Frame Installation due Jan 28. Currently 0% complete.
                </div>
            </div>
            <div class="bg-primary-container/20 border border-primary/40 rounded-xl p-4 text-sm text-primary flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 flex-shrink-0">info</span>
                <div>
                    <strong>Certification expiring</strong> — Sarah Johnson CSS-2024-002 expires in 30 days.
                </div>
            </div>
        </div>
    </div>

    {{-- PROJECTS + CHART --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Active Projects --}}
        <div class="bg-surface-container rounded-2xl shadow-lg p-6 border border-white/5">
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-on-surface mb-5">Active Projects</h2>
            <div class="space-y-4">
                @foreach([
                    ['Skyline Tower',      'Downtown',    68,  'bg-primary',      'In Progress'],
                    ['Metro Bridge',       'City Center', 82,  'bg-tertiary',      'In Progress'],
                    ['Harbor View',        'Waterfront',  45,  'bg-secondary','On Hold'],
                    ['Green Valley Homes', 'Suburb Area', 25,  'bg-primary-container',  'Planning'],
                ] as [$name, $loc, $pct, $color, $status])
                <div class="group">
                    <div class="flex items-center gap-3 py-2">
                        <div class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">{{ $name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $loc }}</div>
                        </div>
                        <div class="w-20 bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full {{ $color }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-on-surface-variant w-8 text-right">{{ $pct }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Budget Chart --}}
        <div class="bg-surface-container rounded-2xl shadow-lg p-6 border border-white/5">
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-on-surface mb-5">Budget Performance</h2>
            <div style="height:200px;position:relative">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>

    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="bg-surface-container rounded-2xl shadow-lg p-6 border border-white/5">
        <h2 class="text-sm font-extrabold uppercase tracking-widest text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">history</span> Recent Activity
        </h2>
        <div class="space-y-2">
            @foreach([
                ['John Smith updated Skyline Tower progress to 68%',     '2m ago',  'text-primary'],
                ['Sarah Johnson filed a safety incident on Floor 7',      '18m ago', 'text-error'],
                ['Mike Chen approved expense $95,000 crane rental',       '1h ago',  'text-secondary'],
                ['Admin uploaded Blueprint v3 to Skyline Tower',          '2h ago',  'text-tertiary'],
                ['Robert Park completed task: Safety Inspection',         '3h ago',  'text-primary-container'],
            ] as [$text, $time, $color])
            <div class="flex items-center gap-3 py-3 px-3 hover:bg-white/[0.04] rounded-lg transition-colors border-b border-white/5 last:border-0">
                <div class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0 opacity-60"></div>
                <div class="flex-1 text-sm text-on-surface">{{ $text }}</div>
                <div class="text-xs text-on-surface-variant whitespace-nowrap">{{ $time }}</div>
            </div>
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')
<script>
new Chart(document.getElementById('budgetChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'],
        datasets: [
            { label: 'Allocated', data: [420,580,490,610,720,650,490], backgroundColor: 'rgba(173,198,255,0.5)', borderRadius: 4 },
            { label: 'Spent',     data: [390,520,450,580,695,610,420], backgroundColor: 'rgba(173,198,255,0.9)', borderRadius: 4 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { 
                position: 'bottom', 
                labels: { 
                    font: { size: 11 },
                    color: '#b5ceec',
                    padding: 12
                } 
            } 
        },
        scales: {
            x: { 
                grid: { display: false },
                ticks: { color: '#8c92a4' }
            },
            y: { 
                grid: { color: 'rgba(255,255,255,0.05)' }, 
                ticks: { 
                    color: '#8c92a4',
                    callback: v => '$'+v+'K' 
                } 
            }
        }
    }
});
</script>
@endsection
