@php
    $activeProjectsPct = $totalProjects > 0 ? round(($activeProjects / $totalProjects) * 100) : 0;
    $totalTasks = $completedToday + $openTasks;
    $tasksDonePct = $totalTasks > 0 ? round(($completedToday / $totalTasks) * 100) : 0;
@endphp

<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- KPI 1 --}}
    <div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col gap-3 relative overflow-hidden cursor-pointer hover:border-primary/20 transition-colors"
        onclick="openModal('Active Projects','<div style=\'font-size:12px;color:var(--t2);line-height:1.8\'>{{ $activeProjects }} projects currently in progress out of {{ $totalProjects }} total projects.</div>')">
        <div class="flex justify-between items-start">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Active Projects</span>
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">domain</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-on-surface">{{ $activeProjects }}/{{ $totalProjects }}</span>
            <span class="text-xs font-bold text-primary">+2 this month</span>
        </div>
        <div class="w-full bg-surface-container-lowest h-1.5 rounded-full overflow-hidden mt-2">
            <div class="h-full bg-primary rounded-full" style="width: {{ $activeProjectsPct }}%;"></div>
        </div>
    </div>

    {{-- KPI 2 --}}
    <div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col gap-3 relative overflow-hidden cursor-pointer hover:border-secondary/20 transition-colors"
        onclick="openKanbanModal()">
        <div class="flex justify-between items-start">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Tasks Done
                Today</span>
            <span class="material-symbols-outlined text-secondary"
                style="font-variation-settings:'FILL' 1;">checklist</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span
                class="text-4xl font-extrabold text-on-surface">{{ $completedToday }}/{{ $openTasks + $completedToday }}</span>
            <span class="text-xs font-bold text-secondary">+{{ $completedToday }} completed</span>
        </div>
        <div class="w-full bg-surface-container-lowest h-1.5 rounded-full overflow-hidden mt-2">
            <div class="h-full bg-secondary rounded-full" style="width: {{ $tasksDonePct }}%;"></div>
        </div>
    </div>

    {{-- KPI 3 --}}
    <div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col gap-3 relative overflow-hidden cursor-pointer hover:border-tertiary/20 transition-colors"
        onclick="openBudgetModal()">
        <div class="flex justify-between items-start">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Weekly Spend</span>
            <span class="material-symbols-outlined text-tertiary"
                style="font-variation-settings:'FILL' 1;">payments</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-on-surface">${{ number_format($weeklySpend / 1000, 1) }}K</span>
            <span class="text-xs font-bold text-tertiary">On budget</span>
        </div>
        <div class="w-full bg-surface-container-lowest h-1.5 rounded-full overflow-hidden mt-2">
            <div class="h-full bg-tertiary rounded-full" style="width: 60%;"></div>
        </div>
    </div>

    {{-- KPI 4 --}}
    <div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col gap-3 relative overflow-hidden cursor-pointer hover:border-primary-container/20 transition-colors"
        onclick="openModal('Team Overview','<div style=\'font-size:12px;color:var(--t2)\'>{{ $activeWorkers }} workers currently on-site across all projects.</div>')">
        <div class="flex justify-between items-start">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Active Workers</span>
            <span class="material-symbols-outlined text-primary-container"
                style="font-variation-settings:'FILL' 1;">engineering</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-on-surface">{{ $activeWorkers }}</span>
            <span class="text-xs font-bold text-on-surface-variant">Across 8 sites</span>
        </div>
        <div class="flex -space-x-2 mt-2">
            <span
                class="w-8 h-8 rounded-full border-2 border-surface-container-high bg-surface-container text-[10px] flex items-center justify-center font-bold text-primary">
                +139
            </span>
        </div>
    </div>
</section>