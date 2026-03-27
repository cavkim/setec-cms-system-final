{{-- Pending Tasks --}}
<div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col">
    <h3 class="text-lg font-bold text-on-surface mb-6">Pending Tasks</h3>

    <div class="flex justify-between items-center mb-4">
        <span></span>
        <button class="text-primary text-xs font-bold hover:underline" onclick="openKanbanModal()">Kanban view
            →</button>
    </div>

    <div class="space-y-2 flex-1">
        @foreach ($pendingTasks as $task)
            <div class="flex items-start gap-3 p-3 hover:bg-white/[0.04] rounded-lg transition-colors">
                <div class="w-4 h-4 rounded border border-outline-variant flex items-center justify-center flex-shrink-0 mt-0.5 cursor-pointer {{ $task->status === 'completed' ? 'bg-primary border-primary' : '' }}"
                    onclick="tick(this)">
                    @if ($task->status === 'completed')
                        <svg width="10" height="10" viewBox="0 0 9 9" fill="none">
                            <path d="M1.5 4.5l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="text-white" />
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div
                        class="text-sm font-semibold text-on-surface {{ $task->status === 'completed' ? 'line-through opacity-50' : '' }}">
                        {{ $task->task_name }}</div>
                    <div class="text-xs text-on-surface-variant">{{ $task->project_name }} ·
                        {{ $task->assignee ?? 'Unassigned' }}</div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <span
                        class="text-[10px] font-bold px-2 py-1 rounded
                            {{ $task->priority === 'high' ? 'bg-error/20 text-error' : ($task->priority === 'medium' ? 'bg-secondary/20 text-secondary' : 'bg-surface-container-lowest text-on-surface-variant') }}">
                        {{ strtoupper($task->priority) }}
                    </span>

                    <span
                        class="text-xs font-medium whitespace-nowrap {{ $task->status === 'completed' ? 'text-tertiary' : (\Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-error' : 'text-on-surface-variant') }}">
                        {{ $task->status === 'completed' ? 'Done' : \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>