@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/buildscape/modules/projects.css') }}" />
@endsection

@section('content')
    <div class="  mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-2xl font-bold text-white">Projects</h1>
                <p class="text-slate-400 text-sm mt-1">Manage and track all your construction projects</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-surface-container-high p-6 rounded-xl shadow-lg border-l-4 border-primary">
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Total Projects</p>
                <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['total'] }}</p>
                <div class="mt-4 flex items-center text-xs text-primary/80">
                    <span class="material-symbols-outlined text-sm mr-1"
                        style="font-variation-settings:'FILL' 1;">trending_up</span>
                    <span>+2 since last month</span>
                </div>
            </div>
            <div class="bg-surface-container-high p-6 rounded-xl shadow-lg border-l-4 border-tertiary">
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Active</p>
                <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['active'] }}</p>
                <div class="mt-4 flex items-center text-xs text-tertiary">
                    <span class="material-symbols-outlined text-sm mr-1"
                        style="font-variation-settings:'FILL' 1;">speed</span>
                    <span>75% Capacity</span>
                </div>
            </div>
            <div class="bg-surface-container-high p-6 rounded-xl shadow-lg border-l-4 border-secondary">
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">On Hold</p>
                <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['on_hold'] }}</p>
                <div class="mt-4 flex items-center text-xs text-secondary">
                    <span class="material-symbols-outlined text-sm mr-1"
                        style="font-variation-settings:'FILL' 1;">pause_circle</span>
                    <span>Awaiting Permit</span>
                </div>
            </div>
            <div class="bg-surface-container-high p-6 rounded-xl shadow-lg border-l-4 border-primary-container">
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Completed</p>
                <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['completed'] }}</p>
                <div class="mt-4 flex items-center text-xs text-primary-container">
                    <span class="material-symbols-outlined text-sm mr-1"
                        style="font-variation-settings:'FILL' 1;">check_circle</span>
                    <span>Archived records</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low rounded-2xl p-5 mb-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                @foreach(['all' => 'All Projects', 'in_progress' => 'Active', 'on_hold' => 'On Hold', 'planning' => 'Planning', 'completed' => 'Completed'] as $v => $l)
                    <a href="{{ route('projects.index', ['status' => $v, 'search' => request('search')]) }}"
                        class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors
                              {{ $currentStatus === $v ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant hover:text-on-surface' }}">
                        {{ $l }}
                    </a>
                @endforeach
            </div>
            <div class="relative w-full md:w-80">
                <form method="GET" action="{{ route('projects.index') }}">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                    <input
                        class="w-full bg-surface-container-lowest rounded-xl py-2.5 pl-11 pr-4 text-sm text-on-surface
                              placeholder:text-on-surface-variant border border-white/5 focus:border-primary focus:outline-none transition-all"
                        placeholder="Search projects..." type="text" name="search" value="{{ request('search') }}"
                        oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)">
                </form>
            </div>
        </div>


        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="bg-surface-container-low/50">
                            @foreach(['Project Name', 'Location', 'Status', 'Progress', 'Budget', 'Tasks', 'Start', 'Deadline', 'Actions'] as $h)
                                <th class="px-6 py-5 text-xs font-bold uppercase tracking-widest text-on-surface-variant">
                                    {{ $h }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($projects as $project)
                            @php
                                $statusLabel = $project->status === 'in_progress'
                                    ? 'Active'
                                    : ($project->status === 'on_hold' ? 'On Hold' : ucfirst($project->status));

                                $statusColor =
                                    $project->status === 'in_progress' ? '#42A5F5' :
                                    ($project->status === 'on_hold' ? '#FFB74D' :
                                        ($project->status === 'planning' ? '#64B5F6' :
                                            ($project->status === 'completed' ? '#81C784' : '#8BAABF')));

                                $budgetPct = isset($project->budget_pct) ? $project->budget_pct : ($project->budget_allocated > 0 ? round(($project->budget_spent / $project->budget_allocated) * 100, 1) : 0);
                                $progress = (int) ($project->progress_percent ?? 0);
                                $canUpdate = auth()->user()->can('update', $project);
                            @endphp
                            <tr class="hover:bg-white/[0.04] transition-colors {{ auth()->user()->can('edit projects') ? 'cursor-pointer' : '' }} group"
                                @can('edit projects')
                                    onclick="if(event.target.closest('button') || event.target.closest('td').querySelector('button:hover')) return; toggleDrawer(this, '{{ addslashes($project->project_name) }}', '{{ $statusLabel }}', {{ $project->id }}, '{{ addslashes($project->location) }}', {{ $progress }}, {{ (float) ($project->budget_allocated ?? 0) }}, {{ (float) ($project->budget_spent ?? 0) }}, '{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '' }}', '{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '' }}', {{ $project->total_tasks ?? 0 }}, {{ $project->done_tasks ?? 0 }})"
                                @endcan>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined"
                                                style="font-variation-settings:'FILL' 1;">domain</span>
                                        </div>
                                        <span class="font-bold text-white text-sm">{{ $project->project_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm text-on-surface-variant">{{ $project->location ?: '—' }}</td>
                                <td class="px-6 py-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter"
                                        style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08); color: {{ $statusColor }};">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 min-w-[140px]">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex justify-between text-[10px] font-bold text-on-surface-variant">
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-surface-container-highest rounded-full overflow-hidden">
                                            <div class="h-full bg-primary rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-white">
                                    ${{ number_format(($project->budget_allocated ?? 0), 1) }}
                                </td>
                                <td class="px-6 py-6 text-sm text-on-surface-variant">
                                    {{ $project->done_tasks ?? 0 }} / {{ $project->total_tasks ?? 0 }} done
                                </td>
                                <td class="px-6 py-6 text-sm text-on-surface-variant">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M Y') : '—' }}
                                </td>
                                <td class="px-6 py-6 text-sm text-on-surface-variant">
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M Y') : '—' }}
                                </td>
                                <td class="px-6 py-6 text-center min-w-[120px]">
                                    <div class="flex items-center justify-center gap-1">
                                        <button
                                            class="p-2.5 text-outline hover:text-secondary hover:bg-secondary/10 rounded-lg transition-all"
                                            title="View project details"
                                            onclick="event.stopPropagation(); openDetailModal('{{ addslashes($project->project_name) }}', '{{ addslashes($project->location) }}', '{{ $project->status }}', {{ $progress }}, {{ (float) ($project->budget_allocated ?? 0) }}, {{ (float) ($project->budget_spent ?? 0) }}, '{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '' }}', '{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '' }}', {{ $project->total_tasks ?? 0 }}, {{ $project->done_tasks ?? 0 }})">
                                            <span class="material-symbols-outlined text-sm"
                                                style="font-variation-settings:'FILL' 1;">visibility</span>
                                        </button>
                                        @can('edit projects')
                                            <button
                                                class="p-2.5 text-outline hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                                                title="Edit project"
                                                onclick="event.stopPropagation(); toggleDrawer(null, '{{ addslashes($project->project_name) }}', '{{ $statusLabel }}', {{ $project->id }}, '{{ addslashes($project->location) }}', {{ $progress }}, {{ (float) ($project->budget_allocated ?? 0) }}, {{ (float) ($project->budget_spent ?? 0) }}, '{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '' }}', '{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '' }}', {{ $project->total_tasks ?? 0 }}, {{ $project->done_tasks ?? 0 }})">
                                                <span class="material-symbols-outlined text-sm"
                                                    style="font-variation-settings:'FILL' 1;">edit</span>
                                            </button>
                                        @endcan
                                        @can('delete projects')
                                            <button
                                                class="p-2.5 text-outline hover:text-error hover:bg-error/10 rounded-lg transition-all"
                                                title="Delete project"
                                                onclick="event.stopPropagation(); window.__drawerProjectId = {{ $project->id }}; window.__drawerProjectName = '{{ addslashes($project->project_name) }}'; document.getElementById('drawerDeleteForm').action = '/projects/{{ $project->id }}'; submitDrawerDelete();">
                                                <span class="material-symbols-outlined text-sm"
                                                    style="font-variation-settings:'FILL' 1;">delete</span>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-10 text-center">
                                    <div class="text-on-surface-variant text-sm mb-4">No projects found</div>
                                    @can('create', App\Models\Project::class)
                                        <button class="px-6 py-3 text-sm font-semibold bg-primary text-white rounded-xl"
                                            onclick="openCreatePopup()">+ Create your first project</button>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-surface-container-low/50 border-t border-white/5 flex items-center justify-start gap-4">
                <p class="text-xs text-on-surface-variant">Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }}
                    of {{ $projects->total() }} projects</p>
                <div class="flex gap-2 ">
                    @if ($projects->onFirstPage())
                        <span
                            class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-60">Previous</span>
                    @else
                        <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md hover:bg-surface-variant border border-outline-variant/20"
                            href="{{ $projects->previousPageUrl() }}">Previous</a>
                    @endif
                    @if ($projects->hasMorePages())
                        <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-white rounded-md hover:bg-surface-variant border border-primary/30"
                            href="{{ $projects->nextPageUrl() }}">Next</a>
                    @else
                        <span
                            class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-60">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300"
        id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] translate-x-full transition-transform duration-300 flex flex-col border-l border-white/5"
        id="projectDrawer">


        <div class="p-6 border-b border-white/5 flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-2xl font-bold text-white" id="drawerProjectName">Project Details</h3>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/20 text-primary uppercase tracking-tighter"
                        id="drawerProjectStatus">Active</span>
                </div>
                <p class="text-sm text-on-surface-variant" id="drawerProjectRef">Reference ID: PRJ-2024-001</p>
            </div>
            <button class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-full transition-colors"
                onclick="closeDrawer()">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">close</span>
            </button>
        </div>

        <div id="drawerCreateBanner"
            class="hidden px-6 py-3 bg-primary/10 border-b border-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm"
                style="font-variation-settings:'FILL' 1;">add_circle</span>
            <span class="text-xs text-primary font-semibold">New project — fill in the details below</span>
        </div>


        <div id="drawerErrorContainer" class="hidden px-6 py-3 bg-error/10 border-b border-error/20">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-error text-sm flex-shrink-0 mt-0.5"
                    style="font-variation-settings:'FILL' 1;">error</span>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-error mb-1">Validation Error</p>
                    <p class="text-xs text-error/80" id="drawerErrorMessage"></p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <section class="space-y-3">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Project Name
                    <span class="text-red-400">*</span></label>
                <input id="drawerFieldName" type="text" placeholder="e.g. Skyline Tower Phase 2"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500"
                    required minlength="3" maxlength="200">

                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Location</label>
                <input id="drawerFieldLocation" type="text" placeholder="e.g. Downtown, Phnom Penh"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500"
                    minlength="2" maxlength="200">

                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Description</label>
                <textarea id="drawerFieldDescription" rows="2" placeholder="Brief description of this project..."
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500 resize-none"
                    maxlength="1000"></textarea>
            </section>

            <section class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Status
                        <span class="text-red-400">*</span></label>
                    <select id="drawerFieldStatus"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary"
                        required>
                        <option value="">Select status...</option>
                        <option value="planning">Planning</option>
                        <option value="in_progress">In Progress</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Budget
                        Allocated</label>
                    <input id="drawerFieldBudget" type="number" min="0" step="0.01"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                </div>
            </section>

            <section class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Start
                        Date</label>
                    <input id="drawerFieldStart" type="date"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">End
                        Date</label>
                    <input id="drawerFieldEnd" type="date"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                </div>
            </section>

            <section class="p-4 bg-surface-container rounded-xl">
                <div class="flex justify-between mb-2">
                    <p class="text-[10px] text-on-surface-variant font-bold uppercase">Work Completion</p>
                    <span class="text-xs font-bold text-primary" id="drawerCompletionPct">0%</span>
                </div>
                <input id="drawerFieldProgress" type="range" min="0" max="100" value="0" class="w-full mb-3"
                    oninput="document.getElementById('drawerCompletionPct').textContent=this.value+'%';document.getElementById('drawerCompletionBar').style.width=this.value+'%';">
                <div class="w-full h-2 bg-surface-container-highest rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full shadow-[0_0_8px_rgba(173,198,255,0.4)]"
                        id="drawerCompletionBar" style="width: 0%"></div>
                </div>
                <div class="mt-3 text-xs text-on-surface-variant">
                    Spent: <span id="drawerBudgetSpent" class="text-secondary font-bold">$0</span>
                </div>
            </section>
        </div>

        {{-- Drawer Footer --}}
        <div class="p-6 bg-surface-container-high border-t border-white/5 flex items-center gap-3">
            @can('edit projects')
                <button
                    class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-transform"
                    type="button" onclick="submitDrawerSave()" id="drawerSaveBtn">
                    Save Changes
                </button>
            @endcan
            <button class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors"
                type="button" onclick="closeDrawer()">Cancel</button>
            @can('delete projects')
                <div class="h-8 w-px bg-white/10 mx-2" id="drawerDeleteSep"></div>
                <button class="p-3 text-error hover:bg-error/10 rounded-xl transition-all group" type="button"
                    id="drawerDeleteBtn" onclick="submitDrawerDelete()">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">delete</span>
                </button>
            @endcan
        </div>
    </div>

    <form id="drawerUpdateForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="project_name" id="drawerUpdateProjectName">
        <input type="hidden" name="location" id="drawerUpdateLocation">
        <input type="hidden" name="status" id="drawerUpdateStatus">
        <input type="hidden" name="progress_percent" id="drawerUpdateProgress">
        <input type="hidden" name="budget_allocated" id="drawerUpdateBudget">
        <input type="hidden" name="start_date" id="drawerUpdateStart">
        <input type="hidden" name="end_date" id="drawerUpdateEnd">
    </form>

    <form id="drawerCreateForm" method="POST" action="{{ route('projects.store') }}" class="hidden">
        @csrf
        <input type="hidden" name="project_name" id="drawerCreateProjectName">
        <input type="hidden" name="location" id="drawerCreateLocation">
        <input type="hidden" name="description" id="drawerCreateDescription">
        <input type="hidden" name="status" id="drawerCreateStatus">
        <input type="hidden" name="progress_percent" id="drawerCreateProgress">
        <input type="hidden" name="budget_allocated" id="drawerCreateBudget">
        <input type="hidden" name="start_date" id="drawerCreateStart">
        <input type="hidden" name="end_date" id="drawerCreateEnd">
    </form>

    <form id="drawerDeleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @include('projects.partials.popups')

    {{-- Floating Action Button --}}
    @can('create projects')
        <div class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3 group">
            {{-- Tooltip label --}}
            <span
                class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0
                                                                                                                                                                        bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10 self-end mr-1">
                New Project
            </span>

            <button onclick="openCreateDrawer()"
                class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)]
                                                                                                                                                                               flex items-center justify-center
                                                                                                                                                                               hover:scale-110 hover:shadow-[0_6px_32px_rgba(77,142,255,0.6)]
                                                                                                                                                                               active:scale-95 transition-all duration-200"
                aria-label="Add new project">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">add</span>
            </button>
        </div>
    @endcan
@endsection

@section('scripts')
    <script src="{{ asset('js/buildscape/modules/projects.js') }}"></script>
    <script>
        window.toggleDrawer = function (rowEl, name, statusLabel, id, loc, progress, budgetAllocated, budgetSpent, start, end, totalTasks, doneTasks) {
            const drawer = document.getElementById('projectDrawer');
            const overlay = document.getElementById('drawerOverlay');
            const titleEl = document.getElementById('drawerProjectName');
            const statusEl = document.getElementById('drawerProjectStatus');

            window.hideDrawerError();


            document.querySelectorAll('tbody tr').forEach(row => {
                row.classList.remove('bg-white/[0.04]', 'border-l-4', 'border-primary');
            });
            if (rowEl) rowEl.classList.add('bg-primary/10');

            titleEl.textContent = name;
            statusEl.textContent = statusLabel;
            statusEl.classList.remove('hidden');


            window.__drawerMode = 'edit';
            const saveBtn = document.getElementById('drawerSaveBtn');
            const deleteBtn = document.getElementById('drawerDeleteBtn');
            const deleteSep = document.getElementById('drawerDeleteSep');
            const banner = document.getElementById('drawerCreateBanner');
            if (saveBtn) saveBtn.textContent = 'Save Changes';
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            if (deleteSep) deleteSep.classList.remove('hidden');
            if (banner) banner.classList.add('hidden');

            const refEl = document.getElementById('drawerProjectRef');
            if (refEl) refEl.textContent = 'Reference ID: PRJ-' + String(id).padStart(4, '0');

            if (statusLabel === 'Planning') statusEl.style.background = 'rgba(100,181,246,.20)';
            else if (statusLabel === 'On Hold') statusEl.style.background = 'rgba(245,124,0,.20)';
            else if (statusLabel === 'Completed') statusEl.style.background = 'rgba(129,199,132,.20)';
            else statusEl.style.background = 'rgba(21,101,192,.20)';


            window.__drawerProjectId = id;
            window.__drawerProjectName = name;
            window.__drawerProjectLoc = loc;
            window.__drawerProjectStatus = statusLabel === 'Active' ? 'in_progress' : (statusLabel === 'On Hold' ? 'on_hold' : (statusLabel === 'Planning' ? 'planning' : (statusLabel === 'Completed' ? 'completed' : (statusLabel === 'Cancelled' ? 'cancelled' : statusLabel))));
            window.__drawerProjectProgress = progress;
            window.__drawerProjectBudget = budgetAllocated;
            window.__drawerProjectStart = start;
            window.__drawerProjectEnd = end;


            const updateForm = document.getElementById('drawerUpdateForm');
            if (updateForm) updateForm.action = '/projects/' + id;
            const deleteForm = document.getElementById('drawerDeleteForm');
            if (deleteForm) deleteForm.action = '/projects/' + id;

            document.getElementById('drawerUpdateProjectName').value = name;
            document.getElementById('drawerUpdateLocation').value = loc || '';
            document.getElementById('drawerUpdateStatus').value = window.__drawerProjectStatus;
            document.getElementById('drawerUpdateProgress').value = progress;
            document.getElementById('drawerUpdateBudget').value = budgetAllocated || 0;
            document.getElementById('drawerUpdateStart').value = start || '';
            document.getElementById('drawerUpdateEnd').value = end || '';


            document.getElementById('drawerFieldName').value = name;
            document.getElementById('drawerFieldLocation').value = loc || '';
            document.getElementById('drawerFieldStatus').value = window.__drawerProjectStatus;
            document.getElementById('drawerFieldProgress').value = progress || 0;
            document.getElementById('drawerFieldBudget').value = budgetAllocated || 0;
            document.getElementById('drawerFieldStart').value = start || '';
            document.getElementById('drawerFieldEnd').value = end || '';


            document.getElementById('drawerCompletionPct').textContent = progress + '%';
            const bar = document.getElementById('drawerCompletionBar');
            if (bar) bar.style.width = progress + '%';

            const fmtBudget = (n) => Number(n || 0).toLocaleString('en-US');
            const budgetAllocatedEl = document.getElementById('drawerBudgetAllocated');
            if (budgetAllocatedEl) budgetAllocatedEl.textContent = '$' + fmtBudget(budgetAllocated);
            const budgetSpentEl = document.getElementById('drawerBudgetSpent');
            if (budgetSpentEl) budgetSpentEl.textContent = '$' + fmtBudget(budgetSpent);

            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');

            document.body.style.overflow = 'hidden';
        };

        window.closeDrawer = function () {
            const drawer = document.getElementById('projectDrawer');
            const overlay = document.getElementById('drawerOverlay');
            if (drawer) drawer.classList.add('translate-x-full');
            if (overlay) {
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            }
            window.hideDrawerError();
            document.body.style.overflow = '';
            window.__drawerMode = null;
        };

        window.openCreateDrawer = function () {
            const drawer = document.getElementById('projectDrawer');
            const overlay = document.getElementById('drawerOverlay');
            const titleEl = document.getElementById('drawerProjectName');
            const statusEl = document.getElementById('drawerProjectStatus');
            const saveBtn = document.getElementById('drawerSaveBtn');
            const deleteBtn = document.getElementById('drawerDeleteBtn');
            const deleteSep = document.getElementById('drawerDeleteSep');
            const banner = document.getElementById('drawerCreateBanner');
            const refEl = document.getElementById('drawerProjectRef');

            window.hideDrawerError();


            document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('bg-primary/10'));

            window.__drawerMode = 'create';
            window.__drawerProjectId = null;
            if (titleEl) titleEl.textContent = 'New Project';
            if (statusEl) statusEl.classList.add('hidden');
            if (refEl) refEl.textContent = 'Fill in the details to create a new project';
            if (saveBtn) saveBtn.textContent = 'Create Project';
            if (deleteBtn) deleteBtn.classList.add('hidden');
            if (deleteSep) deleteSep.classList.add('hidden');
            if (banner) banner.classList.remove('hidden');

            ['drawerFieldName', 'drawerFieldLocation', 'drawerFieldStatus',
                'drawerFieldProgress', 'drawerFieldBudget', 'drawerFieldStart', 'drawerFieldEnd'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        if (el.tagName === 'SELECT') {
                            el.value = '';
                        } else {
                            el.value = '';
                        }
                    }
                });
            const desc = document.getElementById('drawerFieldDescription');
            if (desc) desc.value = '';

            const statusSelect = document.getElementById('drawerFieldStatus');
            if (statusSelect) statusSelect.value = 'planning';
            const pct = document.getElementById('drawerCompletionPct');
            if (pct) pct.textContent = '0%';
            const bar = document.getElementById('drawerCompletionBar');
            if (bar) bar.style.width = '0%';
            const spent = document.getElementById('drawerBudgetSpent');
            if (spent) spent.textContent = '$0';

            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = 'hidden';

            setTimeout(() => { const f = document.getElementById('drawerFieldName'); if (f) f.focus(); }, 300);
        };


        window.validateProject = function () {
            const name = document.getElementById('drawerFieldName').value.trim();
            const location = document.getElementById('drawerFieldLocation').value.trim();
            const description = document.getElementById('drawerFieldDescription').value.trim();
            const status = document.getElementById('drawerFieldStatus').value;
            const budget = document.getElementById('drawerFieldBudget').value;
            const startDate = document.getElementById('drawerFieldStart').value;
            const endDate = document.getElementById('drawerFieldEnd').value;
            const progress = parseInt(document.getElementById('drawerFieldProgress').value) || 0;

            // Name - REQUIRED
            if (!name) return { valid: false, field: 'name', message: 'Project name is required' };
            if (name.length < 3) return { valid: false, field: 'name', message: 'Project name must be at least 3 characters' };
            if (name.length > 200) return { valid: false, field: 'name', message: 'Project name must not exceed 200 characters' };

            // Location - OPTIONAL
            // if (location && location.length < 2) return { valid: false, field: 'location', message: 'Location must be at least 2 characters' };
            // if (location && location.length > 200) return { valid: false, field: 'location', message: 'Location must not exceed 200 characters' };
            // Location - REQUIRED
            if (!location) return { valid: false, field: 'location', message: 'Location is required' };
            if (location.length < 2) return { valid: false, field: 'location', message: 'Location must be at least 2 characters' };
            if (location.length > 200) return { valid: false, field: 'location', message: 'Location must not exceed 200 characters' };

            // Description - OPTIONAL
            if (description && description.length > 1000) return { valid: false, field: 'description', message: 'Description must not exceed 1000 characters' };

            // Status - REQUIRED
            const validStatuses = ['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'];
            if (!status || !validStatuses.includes(status)) return { valid: false, field: 'status', message: 'Please select a valid status' };

            // Budget - REQUIRED
            if (!budget || parseFloat(budget) <= 0) return { valid: false, field: 'budget', message: 'Budget allocated is required and must be greater than 0' };
            const budgetNum = parseFloat(budget);
            if (isNaN(budgetNum)) return { valid: false, field: 'budget', message: 'Budget must be a valid number' };
            if (budgetNum > 999999999) return { valid: false, field: 'budget', message: 'Budget is too large' };

            // Start Date - REQUIRED
            if (!startDate) return { valid: false, field: 'start', message: 'Start date is required' };
            if (!window.isValidDate(startDate)) return { valid: false, field: 'start', message: 'Start date is invalid' };

            // End Date - REQUIRED
            if (!endDate) return { valid: false, field: 'end', message: 'End date is required' };
            if (!window.isValidDate(endDate)) return { valid: false, field: 'end', message: 'End date is invalid' };
            if (new Date(endDate) < new Date(startDate)) return { valid: false, field: 'end', message: 'End date cannot be before start date' };

            // Progress - must be 0-100
            if (progress < 0 || progress > 100) return { valid: false, field: 'progress', message: 'Progress must be between 0 and 100' };



            return { valid: true };
        };

        // Helper to validate date format
        window.isValidDate = function (dateString) {
            const date = new Date(dateString);
            return date instanceof Date && !isNaN(date);
        };

        // Show validation error in drawer
        window.showDrawerError = function (message) {
            const container = document.getElementById('drawerErrorContainer');
            const messageEl = document.getElementById('drawerErrorMessage');
            if (messageEl) {
                messageEl.textContent = message;
                console.error('❌ VALIDATION ERROR:', message);
            }
            if (container) {
                container.classList.remove('hidden');
                // Auto-hide after 7 seconds
                setTimeout(() => {
                    if (container) container.classList.add('hidden');
                }, 7000);
            }
        };

        // Hide validation error in drawer
        window.hideDrawerError = function () {
            const container = document.getElementById('drawerErrorContainer');
            if (container) container.classList.add('hidden');
        };

        // Setup date field validation feedback
        document.addEventListener('DOMContentLoaded', () => {
            const startDateInput = document.getElementById('drawerFieldStart');
            const endDateInput = document.getElementById('drawerFieldEnd');

            if (startDateInput) {
                startDateInput.addEventListener('change', () => {
                    const startDate = startDateInput.value;
                    const endDate = endDateInput?.value;
                    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                        endDateInput.style.borderColor = '#EF9A9A';
                    } else if (endDateInput) {
                        endDateInput.style.borderColor = 'rgba(255,255,255,.1)';
                    }
                });
            }

            if (endDateInput) {
                endDateInput.addEventListener('change', () => {
                    const startDate = startDateInput?.value;
                    const endDate = endDateInput.value;
                    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                        endDateInput.style.borderColor = '#EF9A9A';
                    } else {
                        endDateInput.style.borderColor = 'rgba(255,255,255,.1)';
                    }
                });
            }
        });

        window.submitDrawerSave = function () {
            console.log('🔵 SUBMIT DRAWER SAVE - Mode:', window.__drawerMode);
            console.log('='.repeat(50));

            // Validate form
            const validation = window.validateProject();
            console.log('VALIDATION RESULT:', validation);
            console.log('='.repeat(50));

            if (!validation.valid) {
                console.log('❌❌❌ VALIDATION FAILED ❌❌❌');
                console.log('Error field:', validation.field);
                console.log('Error message:', validation.message);
                window.showDrawerError(validation.message);
                return;
            }

            console.log('✅✅✅ ALL VALIDATIONS PASSED ✅✅✅');
            window.hideDrawerError();

            if (window.__drawerMode === 'create') {
                console.log('📝 Creating new project...');
                const name = document.getElementById('drawerFieldName').value.trim();
                document.getElementById('drawerCreateProjectName').value = name;
                document.getElementById('drawerCreateLocation').value = document.getElementById('drawerFieldLocation').value || '';
                document.getElementById('drawerCreateDescription').value = (document.getElementById('drawerFieldDescription') || {}).value || '';
                document.getElementById('drawerCreateStatus').value = document.getElementById('drawerFieldStatus').value;
                document.getElementById('drawerCreateProgress').value = document.getElementById('drawerFieldProgress').value || 0;
                document.getElementById('drawerCreateBudget').value = document.getElementById('drawerFieldBudget').value || 0;
                document.getElementById('drawerCreateStart').value = document.getElementById('drawerFieldStart').value || '';
                document.getElementById('drawerCreateEnd').value = document.getElementById('drawerFieldEnd').value || '';
                console.log('Submitting create form...');
                document.getElementById('drawerCreateForm').submit();
            } else {
                console.log('✏️ Updating project...');
                window.submitDrawerUpdate();
            }
        };

        window.submitDrawerUpdate = function () {
            console.log('🔵 SUBMIT DRAWER UPDATE');

            if (!window.__drawerProjectId) {
                console.log('❌ No project ID');
                window.showDrawerError('No project selected');
                return;
            }

            // Validate form
            const validation = window.validateProject();
            console.log('Validation result:', validation);

            if (!validation.valid) {
                console.log('❌ Validation failed:', validation.message);
                window.showDrawerError(validation.message);
                return;
            }

            console.log('✅ Validation passed, saving...');
            window.hideDrawerError();

            const name = document.getElementById('drawerFieldName').value.trim();

            document.getElementById('drawerUpdateProjectName').value = name;
            document.getElementById('drawerUpdateLocation').value = document.getElementById('drawerFieldLocation').value || '';
            document.getElementById('drawerUpdateStatus').value = document.getElementById('drawerFieldStatus').value;
            document.getElementById('drawerUpdateProgress').value = document.getElementById('drawerFieldProgress').value || 0;
            document.getElementById('drawerUpdateBudget').value = document.getElementById('drawerFieldBudget').value || 0;
            document.getElementById('drawerUpdateStart').value = document.getElementById('drawerFieldStart').value || '';
            document.getElementById('drawerUpdateEnd').value = document.getElementById('drawerFieldEnd').value || '';

            console.log('Submitting form to:', document.getElementById('drawerUpdateForm').action);
            document.getElementById('drawerUpdateForm').submit();
        };

        window.submitDrawerDelete = function () {
            console.log('🔴 DELETE REQUEST');
            console.log('Project ID:', window.__drawerProjectId);
            console.log('Project Name:', window.__drawerProjectName);

            if (!window.__drawerProjectId) {
                console.log('❌ No project ID set');
                window.showDrawerError('No project selected');
                return;
            }

            const deleteForm = document.getElementById('drawerDeleteForm');
            console.log('Form action:', deleteForm.action);
            console.log('Form method:', deleteForm.method);
            console.log('Has CSRF token:', deleteForm.querySelector('[name="_token"]') ? 'YES' : 'NO');
            console.log('Has method override:', deleteForm.querySelector('[name="_method"]') ? 'YES' : 'NO');

            if (!confirm('Delete "' + window.__drawerProjectName + '"? This cannot be undone.')) {
                console.log('❌ Delete cancelled by user');
                return;
            }

            console.log('✅ Confirmed delete, submitting form to:', deleteForm.action);
            deleteForm.submit();
        };
    </script>
@endsection
