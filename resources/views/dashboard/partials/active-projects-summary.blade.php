<div class="bg-surface-container-high border border-white/5 p-8 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-extrabold text-on-surface">Active Projects Summary</h3>
        <button class="text-primary text-xs font-bold hover:underline"
            onclick="openModal('All Projects', allProjectsHTML())">
            View All Projects
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($recentProjects as $project)
            @php
                $pillLabel = $project->status === 'in_progress'
                    ? 'Active'
                    : ($project->status === 'on_hold'
                        ? 'On Hold'
                        : ucfirst($project->status));

                $pillClass = $project->status === 'in_progress'
                    ? 'bg-primary/20 text-primary'
                    : ($project->status === 'on_hold'
                        ? 'bg-secondary/20 text-secondary'
                        : ($project->status === 'planning'
                            ? 'bg-tertiary/20 text-tertiary'
                            : 'bg-primary-container/20 text-primary-container'));

                $barColor = $project->status === 'in_progress'
                    ? 'bg-primary'
                    : ($project->status === 'on_hold'
                        ? 'bg-secondary'
                        : ($project->status === 'planning'
                            ? 'bg-tertiary'
                            : 'bg-primary-container'));
            @endphp

            <div class="bg-surface-container p-5 rounded-xl border border-white/5 flex flex-col gap-4 cursor-pointer hover:border-white/10 transition-colors"
                onclick="openProjectModal({{ $project->id }}, '{{ $project->project_name }}', {{ $project->budget_allocated }}, {{ $project->budget_spent }}, {{ $project->progress_percent }}, '{{ $project->status }}', '{{ $project->location }}')">
                <div class="flex justify-between items-start gap-3">
                    <h4 class="font-bold text-on-surface flex-1">{{ $project->project_name }}</h4>
                    <span
                        class="text-[10px] px-2 py-1 rounded font-bold uppercase tracking-widest whitespace-nowrap {{ $pillClass }}">
                        {{ $pillLabel }}
                    </span>
                </div>

                <div class="w-full bg-surface-container-lowest h-2 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $project->progress_percent }}%;">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="text-on-surface-variant">
                        {{ $project->location ?? '—' }} ·
                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M Y') : '—' }}
                    </div>
                    <div class="text-on-surface-variant font-bold">
                        {{ $project->progress_percent }}%
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>