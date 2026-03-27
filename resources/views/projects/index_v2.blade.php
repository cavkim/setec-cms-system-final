@extends('layouts.app')

@section('title', 'Projects — BuildScape CMS')
@section('page-title', 'Projects')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/buildscape/modules/projects.css') }}" />
@endsection

@section('content')
    <div class="p-10 max-w-[1600px] mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
            <div>
                <h2 class="text-4xl font-extrabold tracking-tight text-white">Projects</h2>
                <p class="text-on-surface-variant font-medium">Enterprise infrastructure and urban development portfolio.
                </p>
            </div>

            <div class="flex gap-2 p-1 bg-surface-container-low rounded-xl">
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                <a class="px-6 py-2 {{ $currentStatus === 'all' ? 'bg-surface-container-highest text-primary' : 'text-slate-400 hover:text-on-surface' }} font-semibold rounded-lg text-sm transition-all"
                    href="{{ route('projects.index', ['status' => 'all', 'search' => request('search')]) }}">
                    All
                </a>
                <a class="px-6 py-2 {{ $currentStatus === 'in_progress' ? 'bg-surface-container-highest text-primary' : 'text-slate-400 hover:text-on-surface' }} font-semibold rounded-lg text-sm transition-all"
                    href="{{ route('projects.index', ['status' => 'in_progress', 'search' => request('search')]) }}">
                    Active
                </a>
                <a class="px-6 py-2 {{ $currentStatus === 'on_hold' ? 'bg-surface-container-highest text-primary' : 'text-slate-400 hover:text-on-surface' }} font-semibold rounded-lg text-sm transition-all"
                    href="{{ route('projects.index', ['status' => 'on_hold', 'search' => request('search')]) }}">
                    On Hold
                </a>
                <a class="px-6 py-2 {{ $currentStatus === 'planning' ? 'bg-surface-container-highest text-primary' : 'text-slate-400 hover:text-on-surface' }} font-semibold rounded-lg text-sm transition-all"
                    href="{{ route('projects.index', ['status' => 'planning', 'search' => request('search')]) }}">
                    Planning
                </a>
            </div>
        </div>

        {{-- Bento Stats Grid --}}
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

        {{-- Search Bar --}}
        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl mb-6">
            <div class="p-6 flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-[220px]">
                    <div
                        class="flex items-center gap-3 bg-surface-container-low rounded-xl border border-outline-variant/20 px-4 py-2">
                        <span class="material-symbols-outlined text-outline text-sm"
                            style="font-variation-settings:'FILL' 1;">search</span>
                        <form method="GET" action="{{ route('projects.index') }}" class="flex-1">
                            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                            <input
                                class="bg-transparent border-none text-xs text-on-surface focus:ring-0 w-full placeholder:text-slate-500"
                                placeholder="Search projects..." type="text" name="search" value="{{ request('search') }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Project Table Section --}}
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
                            <tr class="hover:bg-white/[0.04] transition-colors {{ $canUpdate ? 'cursor-pointer group' : '' }}"
                                @if($canUpdate)
                                    onclick="toggleDrawer(this, '{{ addslashes($project->project_name) }}', '{{ $statusLabel }}', {{ $project->id }}, '{{ addslashes($project->location) }}', {{ $progress }}, {{ (float) ($project->budget_allocated ?? 0) }}, {{ (float) ($project->budget_spent ?? 0) }}, '{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '' }}', '{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '' }}', {{ $project->total_tasks ?? 0 }}, {{ $project->done_tasks ?? 0 }})"
                                @endif>
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
                                <td class="px-6 py-6 text-center" onclick="event.stopPropagation()  onclick="
                                    event.stopPropagation()>
                                    @can('update', $project)
                                        <button
                                            class="p-2 text-outline hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                                            onclick="this.closest('tr').click();">
                                            <span class="material-symbols-outlined text-sm"
                                                style="font-variation-settings:'FILL' 1;">edit</span>
                                        </button>
                                    @endcan
                                    @can('delete', $project)
                                        <button
                                            class="p-2 text-outline hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                                            onclick="this.closest('tr').click();">
                                            <span class="material-symbols-outlined text-sm"
                                                style="font-variation-settings:'FILL' 1;">delete</span>
                                        </button>
                                    @endcan
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

            <div class="px-6 py-4 bg-surface-container-low/50 border-t border-white/5 flex items-center justify-between">
                <p class="text-xs text-on-surface-variant">Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }}
                    of {{ $projects->total() }} projects</p>
                <div class="flex gap-2">
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

    {{-- Side Drawer (Slide-over) --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300"
        id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] translate-x-full transition-transform duration-300 flex flex-col border-l border-white/5"
        id="projectDrawer">

        {{-- Drawer Header --}}
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
        {{-- Create mode banner --}}
        <div id="drawerCreateBanner"
            class="hidden px-6 py-3 bg-primary/10 border-b border-primary/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm"
                style="font-variation-settings:'FILL' 1;">add_circle</span>
            <span class="text-xs text-primary font-semibold">New project — fill in the details below</span>
        </div>

        {{-- Drawer Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <section class="space-y-3">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Project Name
                    <span class="text-red-400">*</span></label>
                <input id="drawerFieldName" type="text" placeholder="e.g. Skyline Tower Phase 2"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">

                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Location</label>
                <input id="drawerFieldLocation" type="text" placeholder="e.g. Downtown, Phnom Penh"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">

                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Description</label>
                <textarea id="drawerFieldDescription" rows="2" placeholder="Brief description of this project..."
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500 resize-none"></textarea>
            </section>

            <section class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Status</label>
                    <select id="drawerFieldStatus"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
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
                    <input id="drawerFieldBudget" type="number" min="0"
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
            <button
                class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-transform"
                type="button" onclick="submitDrawerSave()" id="drawerSaveBtn">
                Save Changes
            </button>
            <button class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors"
                type="button" onclick="closeDrawer()">Cancel</button>
            <div class="h-8 w-px bg-white/10 mx-2" id="drawerDeleteSep"></div>
            <button class="p-3 text-error hover:bg-error/10 rounded-xl transition-all group" type="button"
                id="drawerDeleteBtn" onclick="submitDrawerDelete()">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">delete</span>
            </button>
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
    @can('create', App\Models\Project::class)
        <div class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 group">
            {{-- Tooltip label --}}
            <span
                class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0
                                        bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10 self-end mr-1">
                New Project
            </span>
            {{-- FAB button --}}
            <button onclick="openCreateDrawer()" class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)]
                                               flex items-center justify-center
                                               hover:scale-110 hover:shadow-[0_6px_32px_rgba(77,142,255,0.6)]
                                               active:scale-95 transition-all duration-200" aria-label="Add new project">
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

            // Clear selection highlight
            document.querySelectorAll('tbody tr').forEach(row => {
                row.classList.remove('bg-white/[0.04]', 'border-l-4', 'border-primary');
            });
            if (rowEl) rowEl.classList.add('bg-primary/10');

            titleEl.textContent = name;
            statusEl.textContent = statusLabel;
            statusEl.classList.remove('hidden');

            // Show delete button (edit mode)
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

            // Map pill colors
            if (statusLabel === 'Planning') statusEl.style.background = 'rgba(100,181,246,.20)';
            else if (statusLabel === 'On Hold') statusEl.style.background = 'rgba(245,124,0,.20)';
            else if (statusLabel === 'Completed') statusEl.style.background = 'rgba(129,199,132,.20)';
            else statusEl.style.background = 'rgba(21,101,192,.20)';

            // Save selected project data for Save/Delete buttons
            window.__drawerProjectId = id;
            window.__drawerProjectName = name;
            window.__drawerProjectLoc = loc;
            window.__drawerProjectStatus = statusLabel === 'Active' ? 'in_progress' : (statusLabel === 'On Hold' ? 'on_hold' : (statusLabel === 'Planning' ? 'planning' : (statusLabel === 'Completed' ? 'completed' : (statusLabel === 'Cancelled' ? 'cancelled' : statusLabel))));
            window.__drawerProjectProgress = progress;
            window.__drawerProjectBudget = budgetAllocated;
            window.__drawerProjectStart = start;
            window.__drawerProjectEnd = end;

            // Update hidden inline forms so update/delete happen directly from drawer
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

            // Populate editable drawer fields
            document.getElementById('drawerFieldName').value = name;
            document.getElementById('drawerFieldLocation').value = loc || '';
            document.getElementById('drawerFieldStatus').value = window.__drawerProjectStatus;
            document.getElementById('drawerFieldProgress').value = progress || 0;
            document.getElementById('drawerFieldBudget').value = budgetAllocated || 0;
            document.getElementById('drawerFieldStart').value = start || '';
            document.getElementById('drawerFieldEnd').value = end || '';

            // Drawer dynamic fields
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

            // Clear row highlights
            document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('bg-primary/10'));

            // Set create mode UI
            window.__drawerMode = 'create';
            window.__drawerProjectId = null;
            if (titleEl) titleEl.textContent = 'New Project';
            if (statusEl) statusEl.classList.add('hidden');
            if (refEl) refEl.textContent = 'Fill in the details to create a new project';
            if (saveBtn) saveBtn.textContent = 'Create Project';
            if (deleteBtn) deleteBtn.classList.add('hidden');
            if (deleteSep) deleteSep.classList.add('hidden');
            if (banner) banner.classList.remove('hidden');

            // Clear all fields
            ['drawerFieldName', 'drawerFieldLocation', 'drawerFieldStatus',
                'drawerFieldProgress', 'drawerFieldBudget', 'drawerFieldStart', 'drawerFieldEnd'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = el.tagName === 'SELECT' ? 'planning' : '';
                });
            const desc = document.getElementById('drawerFieldDescription');
            if (desc) desc.value = '';
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

        window.submitDrawerSave = function () {
            if (window.__drawerMode === 'create') {
                const name = document.getElementById('drawerFieldName').value.trim();
                if (!name) { toast('Project name is required', 'warn'); return; }
                document.getElementById('drawerCreateProjectName').value = name;
                document.getElementById('drawerCreateLocation').value = document.getElementById('drawerFieldLocation').value || '';
                document.getElementById('drawerCreateDescription').value = (document.getElementById('drawerFieldDescription') || {}).value || '';
                document.getElementById('drawerCreateStatus').value = document.getElementById('drawerFieldStatus').value;
                document.getElementById('drawerCreateProgress').value = document.getElementById('drawerFieldProgress').value || 0;
                document.getElementById('drawerCreateBudget').value = document.getElementById('drawerFieldBudget').value || 0;
                document.getElementById('drawerCreateStart').value = document.getElementById('drawerFieldStart').value || '';
                document.getElementById('drawerCreateEnd').value = document.getElementById('drawerFieldEnd').value || '';
                document.getElementById('drawerCreateForm').submit();
            } else {
                window.submitDrawerUpdate();
            }
        };

        window.submitDrawerUpdate = function () {
            if (!window.__drawerProjectId) {
                toast('No project selected', 'warn');
                return;
            }

            const name = document.getElementById('drawerFieldName').value.trim();
            if (!name) {
                toast('Project name is required', 'warn');
                return;
            }

            document.getElementById('drawerUpdateProjectName').value = name;
            document.getElementById('drawerUpdateLocation').value = document.getElementById('drawerFieldLocation').value || '';
            document.getElementById('drawerUpdateStatus').value = document.getElementById('drawerFieldStatus').value;
            document.getElementById('drawerUpdateProgress').value = document.getElementById('drawerFieldProgress').value || 0;
            document.getElementById('drawerUpdateBudget').value = document.getElementById('drawerFieldBudget').value || 0;
            document.getElementById('drawerUpdateStart').value = document.getElementById('drawerFieldStart').value || '';
            document.getElementById('drawerUpdateEnd').value = document.getElementById('drawerFieldEnd').value || '';

            document.getElementById('drawerUpdateForm').submit();
        };

        window.submitDrawerDelete = function () {
            if (!window.__drawerProjectId) {
                toast('No project selected', 'warn');
                return;
            }
            if (!confirm('Delete "' + window.__drawerProjectName + '"? This cannot be undone.')) return;
            document.getElementById('drawerDeleteForm').submit();
        };
    </script>
@endsection