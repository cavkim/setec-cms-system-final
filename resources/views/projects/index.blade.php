{{-- resources/views/projects/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Projects — BuildScape CMS')
@section('page-title', 'Projects')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/buildscape/modules/projects.css') }}" />
@endsection

@section('content')

    <div id="projects-page-data" hidden data-index-url="{{ route('projects.index') }}"></div>

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast('{{ session('success') }}', 'success'))</script>
    @endif

    {{-- KPI ROW --}}
    <div class="krow">
        <div class="kpi" style="--ac:linear-gradient(90deg,#1565C0,#42A5F5)">
            <div class="kl">Total Projects</div>
            <div class="kv">{{ $stats['total'] }}</div>
            <div class="kd kd-n">All projects</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4DB6AC);cursor:pointer"
            onclick="filterStatus('in_progress')">
            <div class="kl">Active</div>
            <div class="kv" style="color:#4DB6AC">{{ $stats['active'] }}</div>
            <div class="kd kd-up">Currently in progress</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#F57C00,#FFB74D);cursor:pointer"
            onclick="filterStatus('on_hold')">
            <div class="kl">On Hold</div>
            <div class="kv" style="color:#FFB74D">{{ $stats['on_hold'] }}</div>
            <div class="kd kd-n">Paused projects</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#4CAF50,#81C784);cursor:pointer"
            onclick="filterStatus('completed')">
            <div class="kl">Completed</div>
            <div class="kv" style="color:#81C784">{{ $stats['completed'] }}</div>
            <div class="kd kd-up">Finished projects</div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="card">

        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">All Projects</div>
            @can('create projects')
            <button onclick="openCreatePopup()"
                style="font-size:11px;font-weight:600;padding:7px 16px;border-radius:8px;background:var(--blue);color:#fff;border:none;cursor:pointer;font-family:inherit;transition:background .15s;display:flex;align-items:center;gap:6px">
                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                New Project
            </button>
            @endcan
        </div>

        {{-- Search + Filter --}}
        <div
            style="display:flex;align-items:center;gap:10px;padding:12px 18px;border-bottom:1px solid var(--bd);flex-wrap:wrap">
            <form method="GET" action="{{ route('projects.index') }}" style="display:flex;gap:8px;flex:1;min-width:200px">
                <div style="flex:1;position:relative">
                    <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);opacity:.4" width="13"
                        height="13" viewBox="0 0 20 20" fill="#E8EEF4">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or location..."
                        style="width:100%;background:var(--card2);border:1px solid var(--bd);border-radius:8px;padding:7px 12px 7px 30px;font-size:12px;color:var(--t1);font-family:inherit;outline:none"
                        oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)">
                </div>
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            </form>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
                @foreach(['all' => 'All', 'in_progress' => 'Active', 'on_hold' => 'On Hold', 'planning' => 'Planning', 'completed' => 'Completed'] as $val => $label)
                        <a href="{{ route('projects.index', ['status' => $val, 'search' => request('search')]) }}" style="font-size:11px;font-weight:500;padding:6px 13px;border-radius:7px;text-decoration:none;transition:all .15s;
                                  {{ request('status', $val === 'all' ? 'all' : '') === $val
                    ? 'background:var(--blue);color:#fff;border:1px solid var(--blue)'
                    : 'background:transparent;color:var(--t2);border:1px solid var(--bd)' }}">
                            {{ $label }}
                        </a>
                @endforeach
            </div>
        </div>

        {{-- TABLE --}}
        <div style="overflow-x:auto;overflow-y:auto;max-height:calc(100vh - 500px)">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        @foreach(['Project Name', 'Location', 'Status', 'Progress', 'Budget', 'Tasks', 'Start', 'Deadline', 'Actions'] as $h)
                            <th
                                style="font-size:10px;font-weight:600;color:var(--t3);text-transform:uppercase;letter-spacing:.05em;padding:10px 16px;text-align:left;border-bottom:1px solid var(--bd);white-space:nowrap">
                                {{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        @php
                            $color = $project->status === 'in_progress' ? '#42A5F5' :
                                ($project->status === 'on_hold' ? '#FFB74D' :
                                    ($project->status === 'planning' ? '#64B5F6' :
                                        ($project->status === 'completed' ? '#81C784' : '#8BAABF')));
                        @endphp
                        <tr style="cursor:pointer;transition:background .15s"
                            onmouseenter="this.style.background='var(--card2)'"
                            onmouseleave="this.style.background='transparent'"
                            onclick="openDetailModal('{{ addslashes($project->project_name) }}','{{ $project->location }}','{{ $project->status }}',{{ $project->progress_percent }},{{ $project->budget_allocated }},{{ $project->budget_spent }},'{{ $project->start_date }}','{{ $project->end_date }}',{{ $project->total_tasks }},{{ $project->done_tasks }})">

                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)">
                                <div style="display:flex;align-items:center;gap:9px">
                                    <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $color }}">
                                    </div>
                                    <div style="font-size:12px;font-weight:500;color:var(--t1)">{{ $project->project_name }}
                                    </div>
                                </div>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd);font-size:11px;color:var(--t2)">
                                {{ $project->location ?: '—' }}</td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)">
                                <span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;white-space:nowrap"
                                    class="{{ $project->status === 'in_progress' ? 'sp-a' : ($project->status === 'on_hold' ? 'sp-h' : ($project->status === 'planning' ? 'sp-p' : 'sp-d')) }}">
                                    {{ $project->status === 'in_progress' ? 'Active' : ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div
                                        style="width:70px;height:4px;background:rgba(255,255,255,.07);border-radius:4px;overflow:hidden">
                                        <div
                                            style="height:4px;border-radius:4px;width:{{ $project->progress_percent }}%;background:{{ $color }}">
                                        </div>
                                    </div>
                                    <span style="font-size:11px;color:var(--t2)">{{ $project->progress_percent }}%</span>
                                </div>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)">
                                <div style="font-size:12px;font-weight:500;color:var(--t1)">
                                    ${{ number_format($project->budget_allocated / 1000000, 1) }}M</div>
                                <div
                                    style="font-size:10px;margin-top:1px;color:{{ $project->budget_pct > 85 ? 'var(--red)' : ($project->budget_pct > 70 ? '#FFB74D' : 'var(--t3)') }}">
                                    {{ $project->budget_pct }}% used @if($project->budget_pct > 85) ⚠ @endif
                                </div>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd);font-size:12px">
                                <span style="color:#81C784">{{ $project->done_tasks }}</span><span style="color:var(--t3)"> /
                                    {{ $project->total_tasks }}</span>
                                <div style="font-size:10px;color:var(--t3);margin-top:1px">done</div>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd);font-size:11px;color:var(--t2)">
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : '—' }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)">
                                @if($project->end_date)
                                    @php $dl = \Carbon\Carbon::parse($project->end_date); @endphp
                                    <div
                                        style="font-size:11px;color:{{ $dl->isPast() ? 'var(--red)' : ($dl->diffInDays() < 30 ? '#FFB74D' : 'var(--t2)') }}">
                                        {{ $dl->format('M d, Y') }}</div>
                                    <div style="font-size:10px;color:var(--t3);margin-top:1px">
                                        {{ $dl->isPast() ? 'Overdue' : $dl->diffForHumans() }}</div>
                                @else <span style="font-size:11px;color:var(--t3)">—</span>
                                @endif
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid var(--bd)" onclick="event.stopPropagation()">
                                <div style="display:flex;gap:6px">
                                    @can('edit projects')
                                    <button
                                        onclick="openEditPopup({{ $project->id }},'{{ addslashes($project->project_name) }}','{{ $project->location }}','{{ $project->status }}',{{ $project->progress_percent }},{{ $project->budget_allocated }},'{{ $project->start_date }}','{{ $project->end_date }}')"
                                        style="font-size:10px;padding:4px 10px;border-radius:6px;background:rgba(21,101,192,.2);color:#42A5F5;border:1px solid rgba(66,165,245,.2);cursor:pointer;font-family:inherit">Edit</button>
                                    @endcan
                                    @can('delete projects')
                                    <button
                                        onclick="confirmDelete({{ $project->id }},'{{ addslashes($project->project_name) }}')"
                                        style="font-size:10px;padding:4px 10px;border-radius:6px;background:rgba(198,40,40,.15);color:#EF9A9A;border:1px solid rgba(198,40,40,.2);cursor:pointer;font-family:inherit">Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:52px;text-align:center">
                                <div style="color:var(--t3);font-size:13px;margin-bottom:10px">No projects found</div>
                                @can('create projects')
                                <button onclick="openCreatePopup()"
                                    style="font-size:12px;padding:8px 18px;border-radius:8px;background:var(--blue);color:#fff;border:none;cursor:pointer;font-family:inherit">+
                                    Create your first project</button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
            <div
                style="padding:14px 18px;border-top:1px solid var(--bd);display:flex;align-items:center;justify-content:space-between">
                <div style="font-size:11px;color:var(--t3)">Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }} of
                    {{ $projects->total() }} projects</div>
                <div style="display:flex;gap:6px">
                    @if($projects->onFirstPage())
                        <span
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--card2);color:var(--t3);border:1px solid var(--bd)">←
                            Prev</span>
                    @else
                        <a href="{{ $projects->previousPageUrl() }}"
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--card2);color:var(--t2);border:1px solid var(--bd);text-decoration:none">←
                            Prev</a>
                    @endif
                    @if($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}"
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--blue);color:#fff;text-decoration:none">Next
                            →</a>
                    @else
                        <span
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--card2);color:var(--t3);border:1px solid var(--bd)">Next
                            →</span>
                    @endif
                </div>
            </div>
        @endif

    </div>

    @can('create projects')
    {{-- ══════════════════════════════════════════
    CREATE PROJECT POPUP
    ══════════════════════════════════════════ --}}
    <div class="popup-overlay" id="create-popup" onclick="if(event.target===this)closeCreatePopup()">
        <div class="popup-box">

            <div class="popup-head">
                <div
                    style="width:34px;height:34px;border-radius:9px;background:rgba(21,101,192,.25);border:1px solid rgba(66,165,245,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#42A5F5">
                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                </div>
                <div>
                    <div class="popup-title">Create New Project</div>
                    <div style="font-size:11px;color:#4A6880;margin-top:1px">Fill in the details below to create a new
                        project</div>
                </div>
                <div class="popup-close" onclick="closeCreatePopup()">×</div>
            </div>

            <form id="create-form" method="POST" action="{{ route('projects.store') }}">
                @csrf
                <div class="popup-body">

                    {{-- Error box --}}
                    <div class="f-error" id="create-error"></div>

                    {{-- Section: Basic Info --}}
                    <div class="f-section">Basic Information</div>

                    <div class="f-group">
                        <label class="f-label">Project Name <span>*</span></label>
                        <input type="text" name="project_name" id="c-name" class="f-input"
                            placeholder="e.g. Skyline Tower Phase 2" autocomplete="off" required>
                    </div>

                    <div class="f-group">
                        <label class="f-label">Location</label>
                        <input type="text" name="location" id="c-loc" class="f-input"
                            placeholder="e.g. Downtown, Phnom Penh" autocomplete="off">
                    </div>

                    <div class="f-group">
                        <label class="f-label">Description</label>
                        <textarea name="description" id="c-desc" class="f-input f-textarea"
                            placeholder="Brief description of this project..."></textarea>
                    </div>

                    {{-- Section: Status & Budget --}}
                    <div class="f-section">Status & Budget</div>

                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Status <span>*</span></label>
                            <select name="status" id="c-status" class="f-input f-select" onchange="updateStatusColor(this)">
                                <option value="planning">Planning</option>
                                <option value="in_progress">In Progress</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <div class="f-hint" id="status-hint"
                                style="display:flex;align-items:center;gap:5px;margin-top:5px">
                                <div id="status-dot"
                                    style="width:7px;height:7px;border-radius:50%;background:#64B5F6;flex-shrink:0"></div>
                                <span id="status-label" style="font-size:10px;color:#4A6880">Project is in planning
                                    phase</span>
                            </div>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Budget Allocated (USD)</label>
                            <input type="number" name="budget_allocated" id="c-budget" class="f-input"
                                placeholder="e.g. 9000000" min="0" step="1000" oninput="updateBudgetPreview(this.value)">
                            <div class="budget-preview" id="budget-preview">
                                <div style="font-size:10px;color:#4A6880;margin-bottom:3px">Budget preview</div>
                                <div id="budget-formatted" style="font-size:14px;font-weight:700;color:#E8EEF4"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Timeline --}}
                    <div class="f-section">Timeline</div>

                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Start Date</label>
                            <input type="date" name="start_date" id="c-start" class="f-input">
                        </div>
                        <div class="f-group">
                            <label class="f-label">End / Deadline Date</label>
                            <input type="date" name="end_date" id="c-end" class="f-input">
                            <div class="f-hint" id="duration-hint"></div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="popup-foot">
                    <button type="button" onclick="closeCreatePopup()"
                        style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500;transition:all .15s">
                        Cancel
                    </button>
                    <button type="button" onclick="submitCreate()"
                        style="padding:9px 22px;border-radius:8px;background:#1565C0;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;transition:background .15s;display:flex;align-items:center;gap:7px"
                        id="create-submit-btn">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Create Project
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endcan

    @can('edit projects')
    {{-- ══════════════════════════════════════════
    EDIT PROJECT POPUP
    ══════════════════════════════════════════ --}}
    <div class="popup-overlay" id="edit-popup" onclick="if(event.target===this)closeEditPopup()">
        <div class="popup-box">

            <div class="popup-head">
                <div
                    style="width:34px;height:34px;border-radius:9px;background:rgba(0,137,123,.2);border:1px solid rgba(77,182,172,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#4DB6AC">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </div>
                <div>
                    <div class="popup-title" id="edit-popup-title">Edit Project</div>
                    <div style="font-size:11px;color:#4A6880;margin-top:1px">Update project information and settings</div>
                </div>
                <div class="popup-close" onclick="closeEditPopup()">×</div>
            </div>

            <form id="edit-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="popup-body">

                    <div class="f-error" id="edit-error"></div>

                    <div class="f-section">Basic Information</div>

                    <div class="f-group">
                        <label class="f-label">Project Name <span>*</span></label>
                        <input type="text" name="project_name" id="e-name" class="f-input" required>
                    </div>

                    <div class="f-group">
                        <label class="f-label">Location</label>
                        <input type="text" name="location" id="e-loc" class="f-input">
                    </div>

                    <div class="f-section">Status & Budget</div>

                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Status <span>*</span></label>
                            <select name="status" id="e-status" class="f-input f-select">
                                <option value="planning">Planning</option>
                                <option value="in_progress">In Progress</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Budget Allocated (USD)</label>
                            <input type="number" name="budget_allocated" id="e-budget" class="f-input" min="0">
                        </div>
                    </div>

                    <div class="f-section">Progress & Timeline</div>

                    <div class="f-group">
                        <label class="f-label">Progress (%)</label>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:4px">
                            <input type="range" name="progress_percent" id="e-progress" min="0" max="100" value="0"
                                style="flex:1" oninput="document.getElementById('e-pv').textContent=this.value+'%'">
                            <span id="e-pv"
                                style="font-size:13px;font-weight:600;color:#E8EEF4;min-width:38px;text-align:right">0%</span>
                        </div>
                        <div
                            style="height:5px;background:rgba(255,255,255,.06);border-radius:5px;overflow:hidden;margin-top:8px">
                            <div id="e-progress-bar"
                                style="height:5px;border-radius:5px;background:#42A5F5;transition:width .2s;width:0%"></div>
                        </div>
                    </div>

                    <div class="f-grid-2">
                        <div class="f-group">
                            <label class="f-label">Start Date</label>
                            <input type="date" name="start_date" id="e-start" class="f-input">
                        </div>
                        <div class="f-group">
                            <label class="f-label">End Date</label>
                            <input type="date" name="end_date" id="e-end" class="f-input">
                        </div>
                    </div>

                </div>

                <div class="popup-foot">
                    <button type="button" onclick="closeEditPopup()"
                        style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500">
                        Cancel
                    </button>
                    <button type="submit"
                        style="padding:9px 22px;border-radius:8px;background:#00897B;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;display:flex;align-items:center;gap:7px">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endcan

    @can('delete projects')
    {{-- DELETE CONFIRM POPUP --}}
    <div class="popup-overlay" id="delete-popup" onclick="if(event.target===this)closeDeletePopup()">
        <div class="popup-box" style="width:420px">
            <div class="popup-head">
                <div
                    style="width:34px;height:34px;border-radius:9px;background:rgba(198,40,40,.2);border:1px solid rgba(198,40,40,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#EF9A9A">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                    </svg>
                </div>
                <div>
                    <div class="popup-title">Delete Project</div>
                    <div style="font-size:11px;color:#4A6880;margin-top:1px">This action cannot be undone</div>
                </div>
                <div class="popup-close" onclick="closeDeletePopup()">×</div>
            </div>
            <div class="popup-body" style="padding:20px 22px">
                <div style="font-size:13px;color:#8BAABF;line-height:1.75">
                    Are you sure you want to delete
                    <strong id="delete-name" style="color:#E8EEF4"></strong>?<br>
                    <span style="color:#EF9A9A;font-size:12px">All tasks, expenses, and documents linked to this project
                        will also be deleted.</span>
                </div>
            </div>
            <div class="popup-foot">
                <button onclick="closeDeletePopup()"
                    style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500">
                    Cancel
                </button>
                <form id="delete-form" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        style="padding:9px 22px;border-radius:8px;background:#C62828;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;display:flex;align-items:center;gap:7px">
                        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                        </svg>
                        Yes, delete it
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endcan

@endsection

@section('scripts')
    <script src="{{ asset('js/buildscape/modules/projects.js') }}"></script>
@endsection