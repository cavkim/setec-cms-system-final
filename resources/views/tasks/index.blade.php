@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', 'Tasks')

@section('styles')
    <style>
        .row-active {
            background-color: rgba(173, 198, 255, 0.08) !important;
            position: relative;
        }

        .row-active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: #adc6ff;
            border-radius: 0 2px 2px 0;
        }

        #task-drawer {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #task-drawer.open {
            transform: translateX(0);
        }

        #task-drawer-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #task-drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Kanban drag-and-drop */
        .kanban-card[draggable="true"] {
            cursor: grab;
        }
        .kanban-card[draggable="true"]:active {
            cursor: grabbing;
        }
        .kanban-card.dragging {
            opacity: 0.4;
            transform: scale(0.97);
        }
        .kanban-col.drag-over {
            outline: 2px dashed rgba(173,198,255,0.5);
            outline-offset: -4px;
            background: rgba(173,198,255,0.04);
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast('{{ session('success') }}', 'success'))</script>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-surface-container rounded-xl flex p-1">
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['view' => 'list'])) }}"
                    class="px-4 py-2 {{ request('view', 'list') === 'list' ? 'bg-surface-container-highest text-primary' : 'text-on-surface-variant hover:text-on-surface' }} rounded-lg font-bold text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-sm">format_list_bulleted</span> List view
                </a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['view' => 'kanban'])) }}"
                    class="px-4 py-2 {{ request('view') === 'kanban' ? 'bg-surface-container-highest text-primary' : 'text-on-surface-variant hover:text-on-surface' }} rounded-lg font-bold text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-sm">view_kanban</span> Kanban
                </a>
            </div>
        </div>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-primary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Total</p>
            <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['total'] }}</p>
            <p class="text-xs text-on-surface-variant mt-3">All tasks</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-secondary shadow-lg cursor-pointer"
            onclick="location.href='{{ route('tasks.index', ['status' => 'pending']) }}'">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Pending</p>
            <p class="text-4xl font-extrabold text-secondary font-headline">{{ $stats['pending'] }}</p>
            <p class="text-xs text-secondary mt-3">Not started</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-primary-container shadow-lg cursor-pointer"
            onclick="location.href='{{ route('tasks.index', ['status' => 'in_progress']) }}'">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">In Progress</p>
            <p class="text-4xl font-extrabold text-primary-container font-headline">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-on-surface-variant mt-3">Currently active</p>
        </div>
        <div class="bg-surface-container-high p-5 rounded-xl border-l-4 border-tertiary shadow-lg cursor-pointer"
            onclick="location.href='{{ route('tasks.index', ['status' => 'completed']) }}'">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Completed</p>
            <p class="text-4xl font-extrabold text-tertiary font-headline">{{ $stats['completed'] }}</p>
            <p class="text-xs text-on-surface-variant mt-3">Finished tasks</p>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div
        class="bg-surface-container-low rounded-2xl p-5 mb-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
            @foreach(['all' => 'All Tasks', 'pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Done', 'high_priority' => 'High Priority'] as $v => $l)
                <a href="{{ route('tasks.index', ['status' => $v, 'search' => request('search')]) }}"
                    class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors
                              {{ request('status', 'all') === $v ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant hover:text-on-surface' }}">
                    {{ $l }}
                </a>
            @endforeach
        </div>
        <div class="relative w-full md:w-80">
            <form method="GET" action="{{ route('tasks.index') }}">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input
                    class="w-full bg-surface-container-lowest rounded-xl py-2.5 pl-11 pr-4 text-sm text-on-surface
                              placeholder:text-on-surface-variant border border-white/5 focus:border-primary focus:outline-none transition-all"
                    placeholder="Search tasks, projects..." type="text" name="search" value="{{ request('search') }}"
                    oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)">
            </form>
        </div>
    </div>

    {{-- Table / Kanban --}}
    @if(request('view') === 'kanban')
        {{-- KANBAN VIEW --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="kanban-board">
            @foreach(['pending' => ['#ffb95f', 'Pending'], 'in_progress' => ['#adc6ff', 'In Progress'], 'completed' => ['#b9c8de', 'Completed']] as $status => [$color, $label])
                <div class="bg-surface-container rounded-2xl overflow-hidden shadow-lg"
                     data-col="{{ $status }}">
                    <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full" style="background:{{ $color }}"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">{{ $label }}</span>
                        </div>
                        <span
                            class="text-xs font-bold text-on-surface-variant bg-surface-container-highest px-2 py-0.5 rounded-full kanban-count"
                            id="kanban-count-{{ $status }}">
                            {{ $tasks->filter(fn($t) => $t->status === $status)->count() }}
                        </span>
                    </div>
                    <div class="p-3 space-y-2 min-h-[120px] kanban-col"
                         data-status="{{ $status }}"
                         ondragover="kanbanDragOver(event)"
                         ondragleave="kanbanDragLeave(event)"
                         ondrop="kanbanDrop(event)">
                        @foreach($tasks->filter(fn($t) => $t->status === $status) as $t)
                            <div
                                class="kanban-card bg-surface-container-high p-4 rounded-xl border border-white/5 hover:border-primary/30 transition-all select-none {{ $t->status === 'completed' ? 'opacity-60' : '' }}"
                                draggable="true"
                                data-task-id="{{ $t->id }}"
                                data-status="{{ $t->status }}"
                                ondragstart="kanbanDragStart(event)">
                                <p class="text-sm font-semibold text-on-surface mb-1 {{ $t->status === 'completed' ? 'line-through' : '' }}">
                                    {{ $t->task_name }}</p>
                                <p class="text-xs text-on-surface-variant mb-3">{{ $t->project_name }}</p>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full
                                            {{ $t->priority === 'high' ? 'bg-error-container text-on-error-container' : ($t->priority === 'medium' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-highest text-on-surface-variant') }}">
                                        {{ ucfirst($t->priority) }}
                                    </span>
                                    @if($t->assignee)
                                        <div
                                            class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center text-[10px] font-bold text-primary">
                                            {{ strtoupper(substr($t->assignee, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- LIST VIEW --}}
        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl flex flex-col">
            <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 500px);">
                <table class="w-full text-left border-collapse" id="task-table">
                    <thead>
                        <tr class="bg-surface-container-high border-b border-white/5">
                            @foreach(['Task', 'Project', 'Assigned To', 'Priority', 'Status', 'Due Date', 'Progress', 'Actions'] as $h)
                                <th class="px-6 py-5 text-[0.6875rem] font-bold uppercase tracking-[0.1em] text-on-surface-variant
                                                   {{ $h === 'Actions' ? 'text-center' : '' }}">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($tasks as $task)
                            @php
                                $priorityDot = $task->priority === 'high' ? 'bg-error' : ($task->priority === 'medium' ? 'bg-secondary' : 'bg-outline');
                                $priorityBadge = $task->priority === 'high'
                                    ? 'bg-error-container text-on-error-container'
                                    : ($task->priority === 'medium' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-highest text-on-surface-variant');
                                $statusBadge = $task->status === 'completed'
                                    ? 'bg-tertiary-container/40 text-tertiary'
                                    : ($task->status === 'in_progress' ? 'bg-primary-container/20 text-primary border border-primary/20'
                                        : ($task->status === 'cancelled' ? 'bg-error-container/20 text-error' : 'bg-surface-container-highest text-on-surface-variant'));
                                $statusLabel = ucfirst(str_replace('_', ' ', $task->status));
                                $canEdit = $task->_model ? auth()->user()->can('update', $task->_model) : false;
                            @endphp
                            <tr class="hover:bg-white/[0.04] transition-colors {{ $canEdit ? 'cursor-pointer group' : '' }} relative"
                                @if($canEdit)
                                    onclick="openTaskDrawer(this, {{ $task->id }}, '{{ addslashes($task->task_name) }}', '{{ $task->priority }}', '{{ $task->status }}', {{ $task->project_id }}, '{{ addslashes($task->project_name) }}', {{ $task->assigned_to ?? 'null' }}, '{{ addslashes($task->assignee ?? '') }}', '{{ $task->due_date ?? '' }}', {{ $task->progress_percent ?? 0 }}, '{{ addslashes($task->description ?? '') }}')"
                                @endif>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $priorityDot }}"></div>
                                        <span
                                            class="font-semibold text-on-surface group-hover:text-primary transition-colors text-sm {{ $task->status === 'completed' ? 'line-through opacity-60' : '' }}">
                                            {{ $task->task_name }}
                                        </span>
                                    </div>
                                    @if($task->description)
                                        <p class="text-[11px] text-on-surface-variant mt-0.5 ml-5">
                                            {{ Str::limit($task->description, 50) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-sm text-on-surface-variant">{{ $task->project_name }}</td>
                                <td class="px-6 py-5">
                                    @if($task->assignee)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-7 h-7 rounded-full bg-primary/15 flex items-center justify-center text-[10px] font-bold text-primary flex-shrink-0">
                                                {{ strtoupper(substr($task->assignee, 0, 2)) }}
                                            </div>
                                            <span class="text-sm text-on-surface">{{ $task->assignee }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-on-surface-variant italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $priorityBadge }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm text-on-surface-variant">
                                    @if($task->due_date)
                                        @php $due = \Carbon\Carbon::parse($task->due_date); @endphp
                                        <span
                                            class="{{ $task->status !== 'completed' && $due->isPast() ? 'text-error' : ($due->diffInDays() < 3 ? 'text-secondary' : '') }}">
                                            {{ $due->format('M d, Y') }}
                                        </span>
                                    @else —
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $task->status === 'completed' ? 'bg-tertiary' : 'bg-primary' }}"
                                                style="width:{{ $task->progress_percent ?? 0 }}%"></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-on-surface-variant">{{ $task->progress_percent ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center" onclick="event.stopPropagation()">
                                    @if($task->_model && auth()->user()->can('update', $task->_model))
                                        <button
                                            class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors text-on-surface-variant hover:text-primary"
                                            onclick="openTaskDrawer(this.closest('tr'), {{ $task->id }}, '{{ addslashes($task->task_name) }}', '{{ $task->priority }}', '{{ $task->status }}', {{ $task->project_id }}, '{{ addslashes($task->project_name) }}', {{ $task->assigned_to ?? 'null' }}, '{{ addslashes($task->assignee ?? '') }}', '{{ $task->due_date ?? '' }}', {{ $task->progress_percent ?? 0 }}, '{{ addslashes($task->description ?? '') }}')">
                                            <span class="material-symbols-outlined text-xl">edit_note</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <div class="text-on-surface-variant text-sm mb-4">No tasks found</div>
                                    @can('create tasks')
                                        <button onclick="openCreateTaskDrawer()"
                                            class="px-6 py-3 text-sm font-semibold bg-primary text-on-primary rounded-xl active:scale-95 transition-transform">
                                            + Create first task
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($tasks->hasPages())
                <div class="px-6 py-4 bg-surface-container-high/50 border-t border-white/5 flex items-center justify-between">
                    <p class="text-xs text-on-surface-variant">Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of
                        {{ $tasks->total() }} tasks</p>
                    <div class="flex gap-2">
                        @if($tasks->onFirstPage())
                            <span
                                class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-50">Previous</span>
                        @else
                            <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md hover:bg-surface-variant border border-outline-variant/20"
                                href="{{ $tasks->previousPageUrl() }}">Previous</a>
                        @endif
                        @if($tasks->hasMorePages())
                            <a class="px-3 py-1 text-xs font-bold bg-primary text-on-primary rounded-md"
                                href="{{ $tasks->nextPageUrl() }}">Next</a>
                        @else
                            <span
                                class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-50">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- FAB: New Task --}}
    @can('create tasks')
        <div class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 group">
            <span
                class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0
                bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10">
                New Task
            </span>
            <button onclick="openCreateTaskDrawer()" class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)]
                           flex items-center justify-center hover:scale-110 hover:shadow-[0_6px_32px_rgba(77,142,255,0.6)]
                           active:scale-95 transition-all duration-200" aria-label="New task">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">add_task</span>
            </button>
        </div>
    @endcan

    {{-- Drawer Overlay --}}
    <div id="task-drawer-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" onclick="closeTaskDrawer()">
    </div>

    {{-- Side Drawer --}}
    <div id="task-drawer"
        class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] flex flex-col border-l border-white/5">

        {{-- Drawer Header --}}
        <div class="p-6 border-b border-white/10 flex justify-between items-start">
            <div class="flex-grow">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full"
                        id="td-priority-badge">High</span>
                    <span class="text-xs text-on-surface-variant font-mono" id="td-task-id">TASK-001</span>
                </div>
                <h2 class="text-2xl font-bold text-white font-headline leading-tight" id="td-title">Task Name</h2>
                <p class="text-sm text-on-surface-variant mt-1" id="td-subtitle">New Task</p>
            </div>
            <button class="p-2 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all"
                onclick="closeTaskDrawer()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Drawer Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-5">
            {{-- Create mode banner --}}
            <div id="td-create-banner"
                class="hidden px-4 py-3 bg-primary/10 border border-primary/20 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm"
                    style="font-variation-settings:'FILL' 1;">add_task</span>
                <span class="text-xs text-primary font-semibold">New task — fill in the details below</span>
            </div>

            <div class="space-y-3">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Task Name <span
                        class="text-red-400">*</span></label>
                <input id="td-field-name" type="text" placeholder="e.g. Foundation Inspection"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">

                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Project <span
                        class="text-red-400">*</span></label>
                <select id="td-field-project"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary">
                    <option value="">— Select project —</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                    @endforeach
                </select>

                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Description</label>
                <textarea id="td-field-desc" rows="2" placeholder="What needs to be done..."
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500 resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label
                        class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Assigned
                        To</label>
                    <select id="td-field-assigned"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                        <option value="">— Unassigned —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Priority</label>
                    <select id="td-field-priority"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                        <option value="high">🔴 High</option>
                        <option value="medium" selected>🟡 Medium</option>
                        <option value="low">🟢 Low</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label
                        class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Status</label>
                    <select id="td-field-status"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Due
                        Date</label>
                    <input id="td-field-due" type="date"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            {{-- Progress --}}
            <div class="p-4 bg-surface-container rounded-xl">
                <div class="flex justify-between mb-2">
                    <p class="text-[10px] text-on-surface-variant font-bold uppercase">Progress</p>
                    <span class="text-xs font-bold text-primary" id="td-pct-label">0%</span>
                </div>
                <input id="td-field-progress" type="range" min="0" max="100" value="0" class="w-full mb-2"
                    oninput="document.getElementById('td-pct-label').textContent=this.value+'%';document.getElementById('td-progress-bar').style.width=this.value+'%'">
                <div class="w-full h-2 bg-surface-container-highest rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full shadow-[0_0_8px_rgba(173,198,255,0.4)]" id="td-progress-bar"
                        style="width:0%"></div>
                </div>
            </div>
        </div>

        {{-- Drawer Footer --}}
        <div class="p-6 bg-surface-container-high border-t border-white/5 flex items-center gap-3">
            @can('edit tasks')
                <button id="td-save-btn" type="button" onclick="submitTaskDrawer()"
                    class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-transform">
                    Save Changes
                </button>
            @endcan
            <button type="button" onclick="closeTaskDrawer()"
                class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors">
                Close
            </button>
            @can('delete tasks')
                <div class="h-8 w-px bg-white/10 mx-1" id="td-delete-sep"></div>
                <button id="td-delete-btn" type="button" onclick="deleteTask()"
                    class="p-3 text-error hover:bg-error/10 rounded-xl transition-all">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">delete</span>
                </button>
            @endcan
        </div>
    </div>

    {{-- Hidden forms --}}
    @can('create tasks')
        <form id="td-create-form" method="POST" action="{{ route('tasks.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="task_name" id="tdc-name">
            <input type="hidden" name="project_id" id="tdc-project">
            <input type="hidden" name="description" id="tdc-desc">
            <input type="hidden" name="assigned_to" id="tdc-assigned">
            <input type="hidden" name="priority" id="tdc-priority">
            <input type="hidden" name="status" id="tdc-status">
            <input type="hidden" name="due_date" id="tdc-due">
            <input type="hidden" name="progress_percent" id="tdc-progress">
        </form>
    @endcan

    @can('edit tasks')
        <form id="td-update-form" method="POST" class="hidden">
            @csrf @method('PUT')
            <input type="hidden" name="task_name" id="tdu-name">
            <input type="hidden" name="project_id" id="tdu-project">
            <input type="hidden" name="description" id="tdu-desc">
            <input type="hidden" name="assigned_to" id="tdu-assigned">
            <input type="hidden" name="priority" id="tdu-priority">
            <input type="hidden" name="status" id="tdu-status">
            <input type="hidden" name="due_date" id="tdu-due">
            <input type="hidden" name="progress_percent" id="tdu-progress">
        </form>
    @endcan

    @can('delete tasks')
        <form id="td-delete-form" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endcan

@endsection

@section('scripts')
    <script>
        const tasksBase = @json(url('/tasks'));
        const csrfToken = @json(csrf_token());

        // ── Kanban drag-and-drop ──────────────────────────────
        let _dragCard = null;

        function kanbanDragStart(e) {
            _dragCard = e.currentTarget;
            _dragCard.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', _dragCard.dataset.taskId);
        }

        document.addEventListener('dragend', () => {
            if (_dragCard) {
                _dragCard.classList.remove('dragging');
                _dragCard = null;
            }
            document.querySelectorAll('.kanban-col').forEach(c => c.classList.remove('drag-over'));
        });

        function kanbanDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            e.currentTarget.classList.add('drag-over');
        }

        function kanbanDragLeave(e) {
            if (!e.currentTarget.contains(e.relatedTarget)) {
                e.currentTarget.classList.remove('drag-over');
            }
        }

        function kanbanDrop(e) {
            e.preventDefault();
            const col = e.currentTarget;
            col.classList.remove('drag-over');

            const newStatus = col.dataset.status;
            if (!_dragCard || _dragCard.dataset.status === newStatus) return;

            const taskId = _dragCard.dataset.taskId;
            const oldStatus = _dragCard.dataset.status;

            // Optimistically move card in DOM
            col.appendChild(_dragCard);
            _dragCard.dataset.status = newStatus;

            // Update completed styling
            const nameEl = _dragCard.querySelector('p:first-child');
            if (newStatus === 'completed') {
                _dragCard.classList.add('opacity-60');
                nameEl && nameEl.classList.add('line-through');
            } else {
                _dragCard.classList.remove('opacity-60');
                nameEl && nameEl.classList.remove('line-through');
            }

            // Update column counts
            const oldCount = document.getElementById('kanban-count-' + oldStatus);
            const newCount = document.getElementById('kanban-count-' + newStatus);
            if (oldCount) oldCount.textContent = parseInt(oldCount.textContent) - 1;
            if (newCount) newCount.textContent = parseInt(newCount.textContent) + 1;

            // Persist to server
            fetch(tasksBase + '/' + taskId + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(r => {
                if (!r.ok) throw new Error('Server error ' + r.status);
                return r.json();
            })
            .then(() => {
                if (typeof toast === 'function') toast('Status updated', 'success');
            })
            .catch(() => {
                // Revert on failure
                const origCol = document.querySelector('.kanban-col[data-status="' + oldStatus + '"]');
                if (origCol) origCol.appendChild(_dragCard);
                _dragCard.dataset.status = oldStatus;
                if (oldCount) oldCount.textContent = parseInt(oldCount.textContent) + 1;
                if (newCount) newCount.textContent = parseInt(newCount.textContent) - 1;
                if (typeof toast === 'function') toast('Failed to update status', 'error');
            });
        }
        // ── End Kanban ───────────────────────────────────────

        window.__tdMode = null;
        window.__tdTaskId = null;

        function openTaskDrawer(row, id, name, priority, status, projectId, projectName, assignedToId, assigneeName, due, progress, desc) {
            window.__tdMode = 'edit';
            window.__tdTaskId = id;

            document.querySelectorAll('#task-table tbody tr').forEach(r => r.classList.remove('row-active'));
            if (row) row.classList.add('row-active');

            // Header
            document.getElementById('td-title').textContent = name;
            document.getElementById('td-subtitle').textContent = projectName;
            document.getElementById('td-task-id').textContent = 'TASK-' + String(id).padStart(4, '0');

            // Priority badge
            const badge = document.getElementById('td-priority-badge');
            badge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
            badge.className = 'px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full ' +
                (priority === 'high' ? 'bg-error-container text-on-error-container' :
                    (priority === 'medium' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-highest text-on-surface-variant'));

            // Fields
            const nameField = document.getElementById('td-field-name');
            if (nameField) nameField.value = name;
            const projectField = document.getElementById('td-field-project');
            if (projectField) projectField.value = projectId;
            const descField = document.getElementById('td-field-desc');
            if (descField) descField.value = desc || '';
            const priorityField = document.getElementById('td-field-priority');
            if (priorityField) priorityField.value = priority;
            const statusField = document.getElementById('td-field-status');
            if (statusField) statusField.value = status;
            const assignedField = document.getElementById('td-field-assigned');
            if (assignedField) assignedField.value = assignedToId || '';
            const dueField = document.getElementById('td-field-due');
            if (dueField) dueField.value = due || '';
            const prog = parseInt(progress) || 0;
            const progressField = document.getElementById('td-field-progress');
            if (progressField) progressField.value = prog;
            const pctLabel = document.getElementById('td-pct-label');
            if (pctLabel) pctLabel.textContent = prog + '%';
            const progressBar = document.getElementById('td-progress-bar');
            if (progressBar) progressBar.style.width = prog + '%';

            // UI state
            const saveBtn = document.getElementById('td-save-btn');
            if (saveBtn) saveBtn.textContent = 'Save Changes';
            const deleteBtn = document.getElementById('td-delete-btn');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            const deleteSep = document.getElementById('td-delete-sep');
            if (deleteSep) deleteSep.classList.remove('hidden');
            const createBanner = document.getElementById('td-create-banner');
            if (createBanner) createBanner.classList.add('hidden');

            // Forms - set actions only if forms exist
            const updateForm = document.getElementById('td-update-form');
            if (updateForm) updateForm.action = tasksBase + '/' + id;
            const deleteForm = document.getElementById('td-delete-form');
            if (deleteForm) deleteForm.action = tasksBase + '/' + id;

            _openDrawer();
        }

        function openCreateTaskDrawer() {
            window.__tdMode = 'create';
            window.__tdTaskId = null;

            document.querySelectorAll('#task-table tbody tr').forEach(r => r.classList.remove('row-active'));

            document.getElementById('td-title').textContent = 'New Task';
            document.getElementById('td-subtitle').textContent = 'Fill in the details to create a new task';
            document.getElementById('td-task-id').textContent = 'NEW';

            const badge = document.getElementById('td-priority-badge');
            badge.textContent = 'New';
            badge.className = 'px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-primary/20 text-primary';

            ['td-field-name', 'td-field-desc'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            const projectField = document.getElementById('td-field-project');
            if (projectField) projectField.value = '';
            const assignedField = document.getElementById('td-field-assigned');
            if (assignedField) assignedField.value = '';
            const priorityField = document.getElementById('td-field-priority');
            if (priorityField) priorityField.value = 'medium';
            const statusField = document.getElementById('td-field-status');
            if (statusField) statusField.value = 'pending';
            const dueField = document.getElementById('td-field-due');
            if (dueField) dueField.value = '';
            const progressField = document.getElementById('td-field-progress');
            if (progressField) progressField.value = 0;
            const pctLabel = document.getElementById('td-pct-label');
            if (pctLabel) pctLabel.textContent = '0%';
            const progressBar = document.getElementById('td-progress-bar');
            if (progressBar) progressBar.style.width = '0%';

            const saveBtn = document.getElementById('td-save-btn');
            if (saveBtn) saveBtn.textContent = 'Create Task';
            const deleteBtn = document.getElementById('td-delete-btn');
            if (deleteBtn) deleteBtn.classList.add('hidden');
            const deleteSep = document.getElementById('td-delete-sep');
            if (deleteSep) deleteSep.classList.add('hidden');
            const createBanner = document.getElementById('td-create-banner');
            if (createBanner) createBanner.classList.remove('hidden');

            _openDrawer();
            const nameField = document.getElementById('td-field-name');
            if (nameField) setTimeout(() => nameField.focus(), 300);
        }

        function _openDrawer() {
            document.getElementById('task-drawer').classList.add('open');
            document.getElementById('task-drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeTaskDrawer() {
            document.getElementById('task-drawer').classList.remove('open');
            document.getElementById('task-drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
            document.querySelectorAll('#task-table tbody tr').forEach(r => r.classList.remove('row-active'));
            window.__tdMode = null;
        }

        function submitTaskDrawer() {
            const name = document.getElementById('td-field-name').value.trim();
            if (!name) { if (typeof toast === 'function') toast('Task name is required', 'warn'); return; }

            const projectId = document.getElementById('td-field-project').value.trim();
            if (!projectId) { if (typeof toast === 'function') toast('Project is required', 'warn'); return; }

            const fields = {
                name: name,
                project: projectId,
                desc: document.getElementById('td-field-desc').value,
                assigned: document.getElementById('td-field-assigned').value,
                priority: document.getElementById('td-field-priority').value,
                status: document.getElementById('td-field-status').value,
                due: document.getElementById('td-field-due').value,
                progress: document.getElementById('td-field-progress').value,
            };

            if (window.__tdMode === 'create') {
                document.getElementById('tdc-name').value = fields.name;
                document.getElementById('tdc-project').value = fields.project;
                document.getElementById('tdc-desc').value = fields.desc;
                document.getElementById('tdc-assigned').value = fields.assigned;
                document.getElementById('tdc-priority').value = fields.priority;
                document.getElementById('tdc-status').value = fields.status;
                document.getElementById('tdc-due').value = fields.due;
                document.getElementById('tdc-progress').value = fields.progress;
                document.getElementById('td-create-form').submit();
            } else {
                document.getElementById('tdu-name').value = fields.name;
                document.getElementById('tdu-project').value = fields.project;
                document.getElementById('tdu-desc').value = fields.desc;
                document.getElementById('tdu-assigned').value = fields.assigned;
                document.getElementById('tdu-priority').value = fields.priority;
                document.getElementById('tdu-status').value = fields.status;
                document.getElementById('tdu-due').value = fields.due;
                document.getElementById('tdu-progress').value = fields.progress;
                document.getElementById('td-update-form').submit();
            }
        }

        function deleteTask() {
            if (!window.__tdTaskId) return;
            if (!confirm('Delete this task? This cannot be undone.')) return;
            document.getElementById('td-delete-form').submit();
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeTaskDrawer(); });
    </script>
@endsection