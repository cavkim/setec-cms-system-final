@extends('layouts.app')
@section('title', 'Safety — BuildScape CMS')
@section('page-title', 'Safety & Compliance')

@section('styles')
    <style>
        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 200;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s;
        }

        .popup-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .popup-box {
            background: #162840;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 16px;
            width: 520px;
            max-width: 96vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            transform: scale(.93) translateY(16px);
            transition: transform .25s;
        }

        .popup-overlay.show .popup-box {
            transform: scale(1) translateY(0);
        }

        .popup-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            flex-shrink: 0;
        }

        .popup-title {
            font-size: 15px;
            font-weight: 700;
            color: #E8EEF4;
            flex: 1;
        }

        .popup-close {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .08);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8BAABF;
            font-size: 18px;
            line-height: 1;
        }

        .popup-close:hover {
            background: rgba(255, 255, 255, .14);
            color: #E8EEF4;
        }

        .popup-body {
            padding: 22px;
            overflow-y: auto;
            flex: 1;
        }

        .popup-foot {
            padding: 14px 22px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-shrink: 0;
        }

        .f-group {
            margin-bottom: 14px;
        }

        .f-label {
            font-size: 10px;
            font-weight: 600;
            color: #4A6880;
            text-transform: uppercase;
            letter-spacing: .06em;
            display: block;
            margin-bottom: 5px;
        }

        .f-input {
            width: 100%;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 12px;
            color: #E8EEF4;
            font-family: inherit;
            outline: none;
            transition: border-color .15s;
        }

        .f-input:focus {
            border-color: rgba(66, 165, 245, .5);
        }

        .f-input::placeholder {
            color: #4A6880;
        }

        .f-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 20 20' fill='%234A6880'%3E%3Cpath d='M7 7l3 3 3-3'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }

        .f-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .f-section {
            font-size: 10px;
            font-weight: 600;
            color: #4A6880;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding-bottom: 7px;
            margin-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .06);
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'))</script>
    @endif

    <div class="krow">
        <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4CAF50)">
            <div class="kl">Days Since Last Incident</div>
            <div class="kv" style="color:#4CAF50;font-size:36px">{{ $stats['days_safe'] }}</div>
            <div class="kd kd-up">Keep it up!</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#C62828,#EF9A9A)">
            <div class="kl">Open Incidents</div>
            <div class="kv" style="color:#EF9A9A">{{ $stats['open'] }}</div>
            <div class="kd {{ $stats['open'] > 0 ? 'kd-dn' : 'kd-n' }}">{{ $stats['open'] > 0 ? 'Needs attention' : 'All clear' }}</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#F57C00,#FFB74D)">
            <div class="kl">Investigating</div>
            <div class="kv" style="color:#FFB74D">{{ $stats['investigating'] }}</div>
            <div class="kd kd-n">In progress</div>
        </div>
        <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4DB6AC)">
            <div class="kl">Resolved</div>
            <div class="kv" style="color:#4DB6AC">{{ $stats['resolved'] }}</div>
            <div class="kd kd-up">Total resolved</div>
        </div>
    </div>

    <div class="card">
        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">Safety Incidents</div>
            @can('create incidents')
                <button onclick="openReportPopup()"
                    style="font-size:11px;font-weight:600;padding:7px 16px;border-radius:8px;background:var(--red);color:#fff;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Report Incident
                </button>
            @endcan
        </div>

        <div style="display:flex;gap:8px;padding:12px 18px;border-bottom:1px solid var(--bd);flex-wrap:wrap">
            @foreach(['all' => 'All', 'open' => 'Open', 'investigating' => 'Investigating', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $v => $l)
                <a href="{{ route('safety.index', ['status' => $v, 'severity' => request('severity', 'all')]) }}"
                    style="font-size:11px;font-weight:500;padding:5px 11px;border-radius:7px;text-decoration:none;transition:all .15s;
                          {{ request('status', $v === 'all' ? 'all' : '') === $v ? 'background:var(--blue);color:#fff;border:1px solid var(--blue)' : 'background:transparent;color:var(--t2);border:1px solid var(--bd)' }}">
                    {{ $l }}
                </a>
            @endforeach
            <span style="width:1px;background:var(--bd);margin:0 4px"></span>
            @foreach(['all' => 'All Severity', 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $v => $l)
                <a href="{{ route('safety.index', ['severity' => $v, 'status' => request('status', 'all')]) }}"
                    style="font-size:11px;font-weight:500;padding:5px 11px;border-radius:7px;text-decoration:none;transition:all .15s;
                          {{ request('severity', $v === 'all' ? 'all' : '') === $v ? 'background:rgba(198,40,40,.3);color:#EF9A9A;border:1px solid rgba(198,40,40,.3)' : 'background:transparent;color:var(--t2);border:1px solid var(--bd)' }}">
                    {{ $l }}
                </a>
            @endforeach
        </div>

        <div style="overflow-x:auto;overflow-y:auto;max-height:calc(100vh - 500px)">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        @foreach(['Description', 'Location', 'Severity', 'Status', 'Reported By', 'Date', 'Actions'] as $h)
                            <th
                                style="font-size:10px;font-weight:600;color:var(--t3);text-transform:uppercase;letter-spacing:.05em;padding:10px 14px;text-align:left;border-bottom:1px solid var(--bd);white-space:nowrap">
                                {{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidents as $inc)
                        @php
                            $canCreate = auth()->user()->can('create incidents');
                        @endphp
                        <tr style="transition:background .15s;{{ $canCreate ? 'cursor:pointer' : '' }}" @if($canCreate)
                            onmouseenter="this.style.background='var(--card2)'"
                            onmouseleave="this.style.background='transparent'"
                            onclick="openIncidentDetail('{{ addslashes($inc->description) }}','{{ $inc->severity }}','{{ $inc->status }}','{{ $inc->location ?? '' }}','{{ $inc->reporter ?? 'Unknown' }}','{{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}',{{ $inc->id }})"
                        @endif>

                            <td
                                style="padding:10px 14px;border-bottom:1px solid var(--bd);font-size:12px;font-weight:500;color:var(--t1)">
                                {{ \Illuminate\Support\Str::limit($inc->description, 50) }}
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd);font-size:11px;color:var(--t2)">
                                {{ $inc->location ?: '—' }}
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd)">
                                <span
                                    style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;
                                    background:{{ $inc->severity === 'critical' ? 'rgba(198,40,40,.25)' : ($inc->severity === 'high' ? 'rgba(198,40,40,.15)' : ($inc->severity === 'medium' ? 'rgba(245,124,0,.15)' : 'rgba(0,137,123,.15)')) }};
                                    color:{{ $inc->severity === 'critical' || $inc->severity === 'high' ? '#EF9A9A' : ($inc->severity === 'medium' ? '#FFB74D' : '#80CBC4') }}">
                                    {{ strtoupper($inc->severity) }}
                                </span>
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd)">
                                <span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px"
                                    class="{{ $inc->status === 'resolved' || $inc->status === 'closed' ? 'sp-d' : ($inc->status === 'investigating' ? 'sp-p' : 'sp-h') }}">
                                    {{ ucfirst($inc->status) }}
                                </span>
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd);font-size:11px;color:var(--t2)">
                                {{ $inc->reporter ?? '—' }}
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd);font-size:11px;color:var(--t2)">
                                {{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}
                            </td>
                            <td style="padding:10px 14px;border-bottom:1px solid var(--bd)" onclick="event.stopPropagation()">
                                @can('resolve incidents')
                                    @if($inc->status !== 'resolved' && $inc->status !== 'closed')
                                        <form method="POST" action="{{ route('safety.update', $inc->id) }}" style="display:inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit"
                                                style="font-size:10px;padding:4px 9px;border-radius:6px;background:rgba(0,137,123,.2);color:#4DB6AC;border:1px solid rgba(0,137,123,.2);cursor:pointer;font-family:inherit">Resolve</button>
                                        </form>
                                    @else
                                        <span style="font-size:11px;color:var(--t3)">Closed</span>
                                    @endif
                                @else
                                    <span style="font-size:11px;color:var(--t3)">{{ ucfirst($inc->status) }}</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:52px;text-align:center">
                                <div style="color:#4CAF50;font-size:36px;margin-bottom:8px">✓</div>
                                <div style="color:var(--t3);font-size:13px">No incidents reported. Stay safe!</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($incidents->hasPages())
            <div
                style="padding:14px 18px;border-top:1px solid var(--bd);display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:11px;color:var(--t3)">Showing {{ $incidents->firstItem() }}–{{ $incidents->lastItem() }}
                    of {{ $incidents->total() }}</span>
                <div style="display:flex;gap:6px">
                    @if(!$incidents->onFirstPage())
                        <a href="{{ $incidents->previousPageUrl() }}"
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--card2);color:var(--t2);border:1px solid var(--bd);text-decoration:none">←
                            Prev</a>
                    @endif
                    @if($incidents->hasMorePages())
                        <a href="{{ $incidents->nextPageUrl() }}"
                            style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--blue);color:#fff;text-decoration:none">Next
                            →</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @can('create incidents')
        <div class="popup-overlay" id="report-popup" onclick="if(event.target===this)closeReportPopup()">
            <div class="popup-box">
                <div class="popup-head">
                    <div
                        style="width:34px;height:34px;border-radius:9px;background:rgba(198,40,40,.2);border:1px solid rgba(198,40,40,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="#EF9A9A">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                    <div>
                        <div class="popup-title">Report Safety Incident</div>
                        <div style="font-size:11px;color:#4A6880;margin-top:1px">Document a safety issue immediately</div>
                    </div>
                    <div class="popup-close" onclick="closeReportPopup()">×</div>
                </div>
                <form method="POST" action="{{ route('safety.store') }}">
                    @csrf
                    <div class="popup-body">
                        <div class="f-section">Incident Details</div>
                        <div class="f-group">
                            <label class="f-label">Description *</label>
                            <textarea name="description" class="f-input" style="resize:vertical;min-height:80px"
                                placeholder="Describe what happened..." required></textarea>
                        </div>
                        <div class="f-grid-2">
                            <div class="f-group">
                                <label class="f-label">Severity *</label>
                                <select name="severity" class="f-input f-select" required>
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
                            <input type="text" name="location" class="f-input" placeholder="e.g. Floor 3, Skyline Tower">
                        </div>
                    </div>
                    <div class="popup-foot">
                        <button type="button" onclick="closeReportPopup()"
                            style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px">Cancel</button>
                        <button type="submit"
                            style="padding:9px 22px;border-radius:8px;background:#C62828;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;display:flex;align-items:center;gap:7px">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" />
                            </svg>
                            Report Incident
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

@endsection

@section('scripts')
    <script>
        function openReportPopup() { document.getElementById('report-popup').classList.add('show'); }
        function closeReportPopup() { document.getElementById('report-popup').classList.remove('show'); }

        function openIncidentDetail(desc, severity, status, location, reporter, date, id) {
            const sevColor = severity === 'critical' || severity === 'high' ? '#EF9A9A' : severity === 'medium' ? '#FFB74D' : '#80CBC4';
            openModal('Incident Report',
                `<div style="display:flex;gap:7px;margin-bottom:14px">
                <span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;background:rgba(198,40,40,.2);color:${sevColor}">${severity.toUpperCase()}</span>
                <span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;background:rgba(255,255,255,.07);color:var(--t2)">${status}</span>
            </div>
            <div class="dr"><span class="dl">Date</span><span class="dv">${date}</span></div>
            <div class="dr"><span class="dl">Location</span><span class="dv">${location || '—'}</span></div>
            <div class="dr"><span class="dl">Reported by</span><span class="dv">${reporter}</span></div>
            <div style="margin-top:12px;font-size:11px;color:var(--t3);margin-bottom:6px">Description</div>
            <div style="font-size:12px;color:var(--t2);line-height:1.6;background:var(--card2);border-radius:8px;padding:10px 12px">${desc}</div>`,
                `<button class="btn btn-g" onclick="closeModal()">Close</button>`
            );
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReportPopup(); });
    </script>
@endsection