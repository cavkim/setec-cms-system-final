@extends('layouts.app')
@section('title', 'Safety — BuildScape CMS')
@section('page-title', 'Safety & Compliance')

@section('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #424754; border-radius: 10px; }

    /* Drawer */
    #drawer-backdrop {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    #drawer-backdrop.open {
        opacity: 1;
        pointer-events: all;
    }
    #incident-drawer {
        transform: translateX(100%);
        transition: transform 0.4s cubic-bezier(0.32,0.72,0,1);
    }
    #incident-drawer.open {
        transform: translateX(0);
    }

    /* Report Drawer */
    #report-drawer-backdrop {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    #report-drawer-backdrop.open {
        opacity: 1;
        pointer-events: all;
    }
    #report-drawer {
        transform: translateX(100%);
        transition: transform 0.4s cubic-bezier(0.32,0.72,0,1);
    }
    #report-drawer.open {
        transform: translateX(0);
    }

    /* Badge colors */
    .sev-critical { background: rgba(147,0,10,0.4); color: #ffdad6; }
    .sev-high     { background: rgba(147,0,10,0.2); color: #ffb4ab; }
    .sev-medium   { background: rgba(238,152,0,0.2); color: #ffb95f; }
    .sev-low      { background: rgba(57,72,90,0.4); color: #b9c8de; }

    .stat-open          { background: rgba(238,152,0,0.15); color: #ffb95f; }
    .stat-investigating { background: rgba(77,110,255,0.15); color: #adc6ff; }
    .stat-resolved      { background: rgba(0,137,123,0.15); color: #4db6ac; }
    .stat-closed        { background: rgba(66,71,84,0.3); color: #c2c6d6; }

    /* Filter pills */
    .pill {
        font-size: 11px; font-weight: 500; padding: 5px 13px; border-radius: 99px;
        text-decoration: none; transition: all .15s; border: 1px solid #424754;
        color: #c2c6d6; background: transparent;
    }
    .pill.active-status  { background: #adc6ff; color: #002e6a; border-color: #adc6ff; }
    .pill.active-sev     { background: rgba(147,0,10,0.3); color: #ffdad6; border-color: rgba(147,0,10,0.5); }
    .pill:hover:not(.active-status):not(.active-sev) { background: #222a3d; color: #dae2fd; }

    /* Table rows */
    tbody tr { cursor: pointer; }
    tbody tr:hover { background: #222a3d; }
    .incident-row.is-critical { border-left: 3px solid #ffb4ab; }

    /* f-* form helpers for report drawer */
    .f-group { margin-bottom: 14px; }
    .f-label { font-size: 10px; font-weight: 700; color: #c2c6d6; text-transform: uppercase; letter-spacing: .07em; display: block; margin-bottom: 6px; }
    .f-input {
        width: 100%; background: rgba(255,255,255,.05); border: 1px solid #424754;
        border-radius: 10px; padding: 10px 14px; font-size: 13px; color: #dae2fd;
        font-family: inherit; outline: none; transition: border-color .15s;
    }
    .f-input:focus { border-color: #adc6ff; }
    .f-input::placeholder { color: #424754; }
    select.f-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 20 20' fill='%23c2c6d6'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }
    select.f-input option { background: #171f33; }
    .f-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
</style>
@endsection

@section('content')

@if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'))</script>
@endif

{{-- ── Page Header ── --}}
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:28px;flex-wrap:wrap">
  
    {{-- Days Without Incident Counter --}}
    <div style="position:relative">
        <div style="background:#222a3d;border:1px solid #424754;border-radius:18px;padding:20px 32px;display:flex;flex-direction:column;align-items:center">
            <span style="font-size:42px;font-weight:900;color:#ffb95f;line-height:1">{{ $stats['days_safe'] }}</span>
            <span style="font-size:10px;font-weight:700;color:#c2c6d6;text-transform:uppercase;letter-spacing:.15em;margin-top:4px">Days without incident</span>
        </div>
    </div>
</div>

{{-- ── KPI Cards ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px">
    <div style="background:#222a3d;border-radius:12px;padding:22px;transition:background .2s" onmouseenter="this.style.background='#31394d'" onmouseleave="this.style.background='#222a3d'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
            <span class="material-symbols-outlined" style="color:#adc6ff">event_available</span>
            <span style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.1em">Baseline</span>
        </div>
        <div style="font-size:30px;font-weight:900;color:#dae2fd">{{ $stats['days_safe'] }}</div>
        <div style="font-size:12px;color:#c2c6d6;margin-top:4px">Days Since Last Incident</div>
    </div>
    <div style="background:#222a3d;border-radius:12px;padding:22px;transition:background .2s" onmouseenter="this.style.background='#31394d'" onmouseleave="this.style.background='#222a3d'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
            <span class="material-symbols-outlined" style="color:#ffb95f">report_problem</span>
            <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:6px;background:rgba(238,152,0,.15);color:#ffb95f">Action Req.</span>
        </div>
        <div style="font-size:30px;font-weight:900;color:#dae2fd">{{ str_pad($stats['open'], 2, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size:12px;color:#c2c6d6;margin-top:4px">Open Incidents</div>
    </div>
    <div style="background:#222a3d;border-radius:12px;padding:22px;transition:background .2s" onmouseenter="this.style.background='#31394d'" onmouseleave="this.style.background='#222a3d'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
            <span class="material-symbols-outlined" style="color:#adc6ff">manage_search</span>
            <span style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.1em">Active</span>
        </div>
        <div style="font-size:30px;font-weight:900;color:#dae2fd">{{ str_pad($stats['investigating'], 2, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size:12px;color:#c2c6d6;margin-top:4px">Investigating</div>
    </div>
    <div style="background:#222a3d;border-radius:12px;padding:22px;transition:background .2s" onmouseenter="this.style.background='#31394d'" onmouseleave="this.style.background='#222a3d'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
            <span class="material-symbols-outlined" style="color:#adc6ff">verified_user</span>
            <span style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.1em">Total</span>
        </div>
        <div style="font-size:30px;font-weight:900;color:#dae2fd">{{ str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size:12px;color:#c2c6d6;margin-top:4px">Resolved</div>
    </div>
</div>

{{-- ── Incidents Table ── --}}
<div style="background:#171f33;border-radius:14px;overflow:hidden;border:1px solid rgba(66,71,84,.3)">
    {{-- Table Header --}}
    <div style="padding:18px 22px 16px;border-bottom:1px solid rgba(66,71,84,.3);display:flex;justify-content:space-between;align-items:center;background:#222a3d">
        <h3 style="font-size:15px;font-weight:700;color:#dae2fd">Safety Incidents</h3>
        @can('create incidents')
            <button onclick="openReportDrawer()" style="font-size:11px;font-weight:700;padding:7px 16px;border-radius:8px;background:rgba(147,0,10,0.3);color:#ffdad6;border:1px solid rgba(147,0,10,.4);cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;transition:all .15s" onmouseenter="this.style.background='rgba(147,0,10,0.5)'" onmouseleave="this.style.background='rgba(147,0,10,0.3)'">
                <span class="material-symbols-outlined" style="font-size:14px">add_alert</span>
                Report Incident
            </button>
        @endcan
    </div>

    {{-- Filters --}}
    <div style="display:flex;gap:8px;padding:14px 22px;border-bottom:1px solid rgba(66,71,84,.2);flex-wrap:wrap;align-items:center">
        <span style="font-size:10px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.08em;margin-right:4px">Status:</span>
        @foreach(['all' => 'All', 'open' => 'Open', 'investigating' => 'Investigating', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $v => $l)
            <a href="{{ route('safety.index', ['status' => $v, 'severity' => request('severity', 'all')]) }}"
               class="pill {{ request('status', 'all') === $v ? 'active-status' : '' }}">{{ $l }}</a>
        @endforeach
        <span style="width:1px;height:18px;background:#424754;margin:0 6px;display:inline-block"></span>
        <span style="font-size:10px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.08em;margin-right:4px">Severity:</span>
        @foreach(['all' => 'All', 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $v => $l)
            <a href="{{ route('safety.index', ['severity' => $v, 'status' => request('status', 'all')]) }}"
               class="pill {{ request('severity', 'all') === $v ? 'active-sev' : '' }}">{{ $l }}</a>
        @endforeach
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="background:#131b2e">
                    @foreach(['Incident ID', 'Description', 'Location', 'Severity', 'Status', 'Reported By', 'Date', 'Actions'] as $h)
                        <th style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.08em;padding:12px 16px;text-align:left;border-bottom:1px solid rgba(66,71,84,.2);white-space:nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody style="divide-y:rgba(66,71,84,.1)">
                @forelse($incidents as $inc)
                    @php
                        $sevClass = match($inc->severity) {
                            'critical' => 'sev-critical',
                            'high'     => 'sev-high',
                            'medium'   => 'sev-medium',
                            default    => 'sev-low',
                        };
                        $statClass = match($inc->status) {
                            'open'          => 'stat-open',
                            'investigating' => 'stat-investigating',
                            'resolved'      => 'stat-resolved',
                            default         => 'stat-closed',
                        };
                        $isCritical = in_array($inc->severity, ['critical','high']);
                    @endphp
                    <tr class="incident-row {{ $isCritical ? 'is-critical' : '' }}"
                        style="border-bottom:1px solid rgba(66,71,84,.1);transition:background .15s"
                        onclick="openIncidentDrawer(
                            '#SI-{{ $inc->id }}',
                            '{{ addslashes($inc->description) }}',
                            '{{ $inc->severity }}',
                            '{{ $inc->status }}',
                            '{{ addslashes($inc->location ?? '') }}',
                            '{{ addslashes($inc->reporter ?? 'Unknown') }}',
                            '{{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}',
                            {{ $inc->id }}
                        )">
                        <td style="padding:12px 16px;font-size:12px;font-weight:700;color:#adc6ff">#SI-{{ $inc->id }}</td>
                        <td style="padding:12px 16px;font-size:12px;color:#dae2fd;max-width:260px">{{ \Illuminate\Support\Str::limit($inc->description, 55) }}</td>
                        <td style="padding:12px 16px;font-size:11px;color:#c2c6d6">{{ $inc->location ?: '—' }}</td>
                        <td style="padding:12px 16px">
                            <span class="{{ $sevClass }}" style="font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px">{{ strtoupper($inc->severity) }}</span>
                        </td>
                        <td style="padding:12px 16px">
                            <span class="{{ $statClass }}" style="font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px">{{ ucfirst($inc->status) }}</span>
                        </td>
                        <td style="padding:12px 16px;font-size:11px;color:#c2c6d6">{{ $inc->reporter ?? '—' }}</td>
                        <td style="padding:12px 16px;font-size:11px;color:#c2c6d6;white-space:nowrap">{{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}</td>
                        <td style="padding:12px 16px" onclick="event.stopPropagation()">
                            @can('resolve incidents')
                                @if(!in_array($inc->status, ['resolved','closed']))
                                    <form method="POST" action="{{ route('safety.update', $inc->id) }}" style="display:inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" style="font-size:10px;padding:4px 10px;border-radius:6px;background:rgba(0,137,123,.15);color:#4db6ac;border:1px solid rgba(0,137,123,.2);cursor:pointer;font-family:inherit;transition:all .15s" onmouseenter="this.style.background='rgba(0,137,123,.3)'" onmouseleave="this.style.background='rgba(0,137,123,.15)'">Resolve</button>
                                    </form>
                                @else
                                    <span style="font-size:11px;color:#424754">Closed</span>
                                @endif
                            @else
                                <span style="font-size:11px;color:#424754">{{ ucfirst($inc->status) }}</span>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:60px;text-align:center">
                            <span class="material-symbols-outlined" style="font-size:42px;color:#4db6ac;display:block;margin-bottom:8px">verified_user</span>
                            <div style="color:#8392a6;font-size:13px">No incidents reported. Stay safe!</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($incidents->hasPages())
        <div style="padding:14px 22px;border-top:1px solid rgba(66,71,84,.2);display:flex;justify-content:space-between;align-items:center">
            <span style="font-size:11px;color:#8392a6">Showing {{ $incidents->firstItem() }}–{{ $incidents->lastItem() }} of {{ $incidents->total() }}</span>
            <div style="display:flex;gap:6px">
                @if(!$incidents->onFirstPage())
                    <a href="{{ $incidents->previousPageUrl() }}" style="font-size:11px;padding:5px 12px;border-radius:7px;background:#222a3d;color:#c2c6d6;border:1px solid #424754;text-decoration:none">← Prev</a>
                @endif
                @if($incidents->hasMorePages())
                    <a href="{{ $incidents->nextPageUrl() }}" style="font-size:11px;padding:5px 12px;border-radius:7px;background:#adc6ff;color:#002e6a;text-decoration:none;font-weight:700">Next →</a>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════
     INCIDENT DETAIL DRAWER
═══════════════════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" id="drawer-backdrop" onclick="closeIncidentDrawer()"></div>

<div class="fixed top-0 right-0 h-full w-full max-w-lg bg-[#131b2e] shadow-2xl z-[70] border-l border-[#424754]/30 flex flex-col" id="incident-drawer">
    {{-- Drawer Header --}}
    <div style="padding:22px 24px 20px;border-bottom:1px solid rgba(66,71,84,.3);background:#171f33;display:flex;justify-content:space-between;align-items:flex-start">
        <div>
            <span id="d-priority-badge" style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;display:block;margin-bottom:6px;color:#ffb4ab">Critical Priority Incident</span>
            <h2 id="d-title" style="font-size:18px;font-weight:900;color:#dae2fd;text-transform:uppercase;line-height:1.2">#SI-XXXX: Loading...</h2>
        </div>
        <button onclick="closeIncidentDrawer()" style="padding:8px;border-radius:99px;background:transparent;border:none;cursor:pointer;color:#8392a6;transition:background .15s" onmouseenter="this.style.background='#2d3449'" onmouseleave="this.style.background='transparent'">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="custom-scrollbar" style="flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:28px">

        {{-- Incident Report Section --}}
        <section>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
                <span class="material-symbols-outlined" style="color:#adc6ff;font-size:16px">description</span>
                <h3 style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.15em">Incident Report</h3>
            </div>
            <div style="background:#171f33;border:1px solid rgba(66,71,84,.2);border-radius:10px;padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                    <div>
                        <p style="font-size:9px;color:#8392a6;text-transform:uppercase;font-weight:700;letter-spacing:.08em;margin-bottom:4px">Date</p>
                        <p id="d-date" style="font-size:13px;color:#dae2fd;font-weight:500">—</p>
                    </div>
                    <div>
                        <p style="font-size:9px;color:#8392a6;text-transform:uppercase;font-weight:700;letter-spacing:.08em;margin-bottom:4px">Location</p>
                        <p id="d-location" style="font-size:13px;color:#dae2fd;font-weight:500">—</p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;margin-bottom:14px">
                    <span id="d-sev-badge" style="font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px">—</span>
                    <span id="d-stat-badge" style="font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px">—</span>
                </div>
                <p style="font-size:9px;color:#8392a6;text-transform:uppercase;font-weight:700;letter-spacing:.08em;margin-bottom:6px">Description</p>
                <p id="d-description" style="font-size:13px;color:#dae2fd;line-height:1.7">—</p>
            </div>
        </section>

        {{-- Reporter Section --}}
        <section>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
                <span class="material-symbols-outlined" style="color:#ffb95f;font-size:16px">person</span>
                <h3 style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.15em">Reported By</h3>
            </div>
            <div style="background:#171f33;border:1px solid rgba(66,71,84,.2);border-radius:10px;padding:16px;display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:99px;background:rgba(173,198,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <span class="material-symbols-outlined" style="color:#adc6ff;font-size:18px">person</span>
                </div>
                <div>
                    <p id="d-reporter" style="font-size:13px;font-weight:700;color:#dae2fd">—</p>
                    <p style="font-size:11px;color:#8392a6">Field Reporter</p>
                </div>
            </div>
        </section>

        {{-- Investigation Status --}}
        <section>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
                <span class="material-symbols-outlined" style="color:#ffb95f;font-size:16px">search</span>
                <h3 style="font-size:9px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.15em">Investigation Status</h3>
            </div>
            <div id="d-timeline" style="display:flex;flex-direction:column;gap:16px">
                {{-- populated by JS --}}
            </div>
        </section>

        {{-- Resolve Action (inside drawer body) --}}
        <div id="d-resolve-section"></div>

    </div>

    {{-- Drawer Footer --}}
    <div style="padding:18px 24px;border-top:1px solid rgba(66,71,84,.3);background:#171f33;display:flex;gap:10px">
        <button id="d-resolve-btn" onclick="submitResolve()" style="flex:1;padding:11px;background:#ffb95f;color:#472a00;font-weight:900;font-size:10px;text-transform:uppercase;letter-spacing:.12em;border-radius:10px;border:none;cursor:pointer;font-family:inherit;transition:all .15s;display:none" onmouseenter="this.style.filter='brightness(1.1)'" onmouseleave="this.style.filter='brightness(1)'">Resolve Incident</button>
        <button onclick="closeIncidentDrawer()" style="flex:1;padding:11px;background:transparent;color:#c2c6d6;font-weight:700;font-size:10px;text-transform:uppercase;letter-spacing:.12em;border-radius:10px;border:1px solid #424754;cursor:pointer;font-family:inherit;transition:all .15s" onmouseenter="this.style.background='#2d3449'" onmouseleave="this.style.background='transparent'">Close</button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     REPORT INCIDENT DRAWER
═══════════════════════════════════════════════════════════ --}}
@can('create incidents')
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" id="report-drawer-backdrop" onclick="closeReportDrawer()"></div>

<div class="fixed top-0 right-0 h-full w-full max-w-lg bg-[#131b2e] shadow-2xl z-[70] border-l border-[#424754]/30 flex flex-col" id="report-drawer">
    <div style="padding:22px 24px 20px;border-bottom:1px solid rgba(66,71,84,.3);background:#171f33;display:flex;justify-content:space-between;align-items:center">
        <div>
            <span style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;display:block;margin-bottom:6px;color:#ffdad6">New Safety Report</span>
            <h2 style="font-size:18px;font-weight:900;color:#dae2fd;text-transform:uppercase">Report Safety Incident</h2>
        </div>
        <button onclick="closeReportDrawer()" style="padding:8px;border-radius:99px;background:transparent;border:none;cursor:pointer;color:#8392a6;transition:background .15s" onmouseenter="this.style.background='#2d3449'" onmouseleave="this.style.background='transparent'">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <div class="custom-scrollbar" style="flex:1;overflow-y:auto;padding:24px">
        <form method="POST" action="{{ route('safety.store') }}" id="report-form">
            @csrf
            <p style="font-size:10px;font-weight:700;color:#8392a6;text-transform:uppercase;letter-spacing:.08em;padding-bottom:10px;margin-bottom:16px;border-bottom:1px solid rgba(66,71,84,.2)">Incident Details</p>

            <div class="f-group">
                <label class="f-label">Description *</label>
                <textarea name="description" class="f-input" style="resize:vertical;min-height:90px" placeholder="Describe what happened in detail..." required></textarea>
            </div>
            <div class="f-grid-2">
                <div class="f-group">
                    <label class="f-label">Severity *</label>
                    <select name="severity" class="f-input" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="f-group">
                    <label class="f-label">Incident Date *</label>
                    <input type="date" name="incident_date" class="f-input" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="f-group">
                <label class="f-label">Location</label>
                <input type="text" name="location" class="f-input" placeholder="e.g. Floor 3, Block A">
            </div>
        </form>
    </div>

    <div style="padding:18px 24px;border-top:1px solid rgba(66,71,84,.3);background:#171f33;display:flex;gap:10px">
        <button type="submit" form="report-form" style="flex:1;padding:11px;background:rgba(147,0,10,0.6);color:#ffdad6;font-weight:900;font-size:10px;text-transform:uppercase;letter-spacing:.12em;border-radius:10px;border:1px solid rgba(147,0,10,.5);cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .15s" onmouseenter="this.style.background='rgba(147,0,10,0.8)'" onmouseleave="this.style.background='rgba(147,0,10,0.6)'">
            <span class="material-symbols-outlined" style="font-size:14px">add_alert</span>
            Report Incident
        </button>
        <button type="button" onclick="closeReportDrawer()" style="padding:11px 22px;background:transparent;color:#c2c6d6;font-weight:700;font-size:10px;text-transform:uppercase;letter-spacing:.12em;border-radius:10px;border:1px solid #424754;cursor:pointer;font-family:inherit;transition:all .15s" onmouseenter="this.style.background='#2d3449'" onmouseleave="this.style.background='transparent'">Cancel</button>
    </div>
</div>
@endcan

{{-- FAB --}}
@can('create incidents')
<button onclick="openReportDrawer()" class="fixed bottom-8 right-8 w-14 h-14 rounded-full shadow-2xl flex items-center justify-center text-on-secondary active:scale-95 transition-all z-50" style="background:linear-gradient(135deg,#ffb95f,#ee9800);color:#472a00;box-shadow:0 8px 30px rgba(238,152,0,.35)">
    <span class="material-symbols-outlined" style="font-size:28px;font-weight:700">add_alert</span>
</button>
@endcan

@endsection

@section('scripts')
<script>
// ── Hidden resolve form ──────────────────────────────────────
let _currentIncidentId = null;

// ── Incident Detail Drawer ───────────────────────────────────
function openIncidentDrawer(id, desc, severity, status, location, reporter, date, incidentId) {
    _currentIncidentId = incidentId;

    // Title
    document.getElementById('d-title').textContent = id + ': ' + desc.substring(0, 30) + (desc.length > 30 ? '…' : '');

    // Priority badge
    const priorityBadge = document.getElementById('d-priority-badge');
    const isCritical = ['critical','high'].includes(severity);
    priorityBadge.textContent = isCritical ? 'Critical Priority Incident' : 'Safety Incident';
    priorityBadge.style.color = isCritical ? '#ffb4ab' : '#b9c8de';

    // Fields
    document.getElementById('d-date').textContent       = date;
    document.getElementById('d-location').textContent   = location || '—';
    document.getElementById('d-description').textContent = desc;
    document.getElementById('d-reporter').textContent   = reporter;

    // Severity badge
    const sevMap = {
        critical: { cls: 'sev-critical', text: 'CRITICAL' },
        high:     { cls: 'sev-high',     text: 'HIGH' },
        medium:   { cls: 'sev-medium',   text: 'MEDIUM' },
        low:      { cls: 'sev-low',      text: 'LOW' },
    };
    const sev = sevMap[severity] || sevMap['low'];
    const sevBadge = document.getElementById('d-sev-badge');
    sevBadge.className = sev.cls;
    sevBadge.textContent = sev.text;
    sevBadge.style.cssText += ';font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px';

    // Status badge
    const statMap = {
        open:          { cls: 'stat-open',          text: 'OPEN' },
        investigating: { cls: 'stat-investigating',  text: 'INVESTIGATING' },
        resolved:      { cls: 'stat-resolved',       text: 'RESOLVED' },
        closed:        { cls: 'stat-closed',         text: 'CLOSED' },
    };
    const stat = statMap[status] || statMap['closed'];
    const statBadge = document.getElementById('d-stat-badge');
    statBadge.className = stat.cls;
    statBadge.textContent = stat.text;
    statBadge.style.cssText += ';font-size:9px;font-weight:700;padding:3px 10px;border-radius:99px';

    // Timeline
    const steps = [
        { label: 'Incident Reported', done: true },
        { label: 'Scene Secured / Initial Response', done: status !== 'open' },
        { label: 'Root Cause Investigation', done: ['resolved','closed'].includes(status), active: status === 'investigating' },
        { label: 'Final Safety Clearance', done: ['resolved','closed'].includes(status) },
    ];
    const timeline = document.getElementById('d-timeline');
    timeline.innerHTML = steps.map((s, i) => `
        <div style="display:flex;gap:14px;align-items:flex-start;${!s.done && !s.active ? 'opacity:0.4' : ''}">
            <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                <div style="width:10px;height:10px;border-radius:99px;margin-top:2px;
                    background:${s.done ? '#adc6ff' : (s.active ? '#ffb95f' : '#424754')};
                    ${s.active ? 'animation:pulse 2s infinite' : ''}
                    box-shadow:${s.done ? '0 0 0 3px rgba(173,198,255,.2)' : (s.active ? '0 0 0 3px rgba(255,185,95,.2)' : 'none')}"></div>
                ${i < steps.length - 1 ? '<div style="width:1px;height:20px;background:rgba(66,71,84,.4);margin-top:4px"></div>' : ''}
            </div>
            <p style="font-size:12px;font-weight:${s.done || s.active ? '700' : '400'};color:${s.done ? '#dae2fd' : (s.active ? '#ffb95f' : '#8392a6')};">${s.label}</p>
        </div>
    `).join('');

    // Resolve button
    const resolveBtn = document.getElementById('d-resolve-btn');
    @can('resolve incidents')
    if (!['resolved','closed'].includes(status)) {
        resolveBtn.style.display = 'block';
    } else {
        resolveBtn.style.display = 'none';
    }
    @else
    resolveBtn.style.display = 'none';
    @endcan

    // Open
    document.getElementById('drawer-backdrop').classList.add('open');
    document.getElementById('incident-drawer').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeIncidentDrawer() {
    document.getElementById('drawer-backdrop').classList.remove('open');
    document.getElementById('incident-drawer').classList.remove('open');
    document.body.style.overflow = '';
}

function submitResolve() {
    if (!_currentIncidentId) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/safety/' + _currentIncidentId;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="status" value="resolved">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ── Report Incident Drawer ───────────────────────────────────
function openReportDrawer() {
    document.getElementById('report-drawer-backdrop')?.classList.add('open');
    document.getElementById('report-drawer')?.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeReportDrawer() {
    document.getElementById('report-drawer-backdrop')?.classList.remove('open');
    document.getElementById('report-drawer')?.classList.remove('open');
    document.body.style.overflow = '';
}

// ESC key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeIncidentDrawer();
        closeReportDrawer();
    }
});
</script>
@endsection