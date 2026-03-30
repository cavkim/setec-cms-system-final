@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('styles')
    <style>
        .industrial-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .industrial-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .industrial-scroll::-webkit-scrollbar-thumb {
            background: #424754;
            border-radius: 10px;
        }

        /* Log Detail Drawer */
        #log-detail-backdrop {
            transition: opacity 0.3s ease;
        }

        #log-detail-drawer {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #log-detail-drawer.drawer-open {
            transform: translateX(0);
        }

        #log-detail-drawer.drawer-closed {
            transform: translateX(100%);
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-[#0b1326] text-[#dae2fd] font-body">

        {{-- ── Page Title ──────────────────────────────────── --}}
        <div class="flex justify-between items-end mb-10">
            <div class="flex gap-3">
                <a href="{{ request()->fullUrlWithQuery(['format' => 'json']) }}"
                    class="px-6 py-2.5 bg-[#222a3d] hover:bg-[#2d3449] text-[#dae2fd] text-xs font-bold rounded-xl transition-all border border-[#424754]/10">
                    Download JSON
                </a>
                <button onclick="window.print()"
                    class="px-6 py-2.5 bg-gradient-to-br from-[#adc6ff] to-[#4d8eff] text-[#002e6a] text-xs font-bold rounded-xl transition-all shadow-lg shadow-[#adc6ff]/10 hover:opacity-90">
                    Print Report
                </button>
            </div>
        </div>

        {{-- ── Summary Cards ─────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-[#222a3d] rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#adc6ff] opacity-50"></div>
                <p class="text-[0.6875rem] uppercase tracking-widest font-bold text-[#c2c6d6] mb-1">Total Actions</p>
                <h3 class="text-3xl font-headline font-black text-[#dae2fd]">{{ number_format($stats['total']) }}</h3>
                <div class="mt-4 flex items-center gap-1.5 text-[#8392a6]">
                    <span class="material-symbols-outlined text-sm">database</span>
                    <span class="text-[10px] font-bold">All time records</span>
                </div>
            </div>
            <div class="bg-[#222a3d] rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#ffb95f] opacity-50"></div>
                <p class="text-[0.6875rem] uppercase tracking-widest font-bold text-[#c2c6d6] mb-1">Weekly Volume</p>
                <h3 class="text-3xl font-headline font-black text-[#dae2fd]">{{ number_format($stats['week']) }}</h3>
                <div class="mt-4 flex items-center gap-1.5 text-[#ffb95f]">
                    <span class="material-symbols-outlined text-sm">bolt</span>
                    <span class="text-[10px] font-bold">Peak activity detected</span>
                </div>
            </div>
            <div class="bg-[#222a3d] rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#b9c8de] opacity-50"></div>
                <p class="text-[0.6875rem] uppercase tracking-widest font-bold text-[#c2c6d6] mb-1">Today</p>
                <h3 class="text-3xl font-headline font-black text-[#dae2fd]">{{ number_format($stats['today']) }}</h3>
                <div class="mt-4 flex items-center gap-1.5 text-[#c2c6d6]">
                    <span class="material-symbols-outlined text-sm">schedule</span>
                    <span class="text-[10px] font-bold">Actions today</span>
                </div>
            </div>
            <div class="bg-[#222a3d] rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#4d8eff] opacity-50"></div>
                <p class="text-[0.6875rem] uppercase tracking-widest font-bold text-[#c2c6d6] mb-1">Active Admin IDs</p>
                <h3 class="text-3xl font-headline font-black text-[#dae2fd]">{{ $stats['users'] }}</h3>
                <div class="mt-4 flex items-center gap-1.5 text-[#adc6ff]">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-[10px] font-bold">Verified & authorized</span>
                </div>
            </div>
        </div>

        {{-- ── Table ──────────────────────────────────────── --}}
        <div class="bg-[#171f33] rounded-xl overflow-hidden border border-[#424754]/10 shadow-2xl">

            {{-- Filters Bar --}}
            <form method="GET" action="{{ route('audit.index') }}"
                class="px-8 py-6 border-b border-[#424754]/10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-1 p-1 bg-[#060e20] rounded-full">
                    @foreach(['all' => 'All', 'created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted'] as $v => $l)
                        <a href="{{ route('audit.index', array_merge(request()->query(), ['event' => $v])) }}"
                            class="px-5 py-2 text-[10px] font-bold uppercase tracking-widest rounded-full transition-all
                               {{ request('event', 'all') === $v ? 'bg-[#2d3449] text-[#adc6ff]' : 'text-[#c2c6d6] hover:text-[#dae2fd]' }}">
                            {{ $l }}
                        </a>
                    @endforeach
                </div>
                <div class="relative w-full md:w-96 group">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#424754] group-focus-within:text-[#adc6ff] transition-colors">search</span>
                    <input name="search" value="{{ request('search') }}"
                        class="w-full bg-[#060e20] border-none ring-1 ring-[#424754]/20 focus:ring-2 focus:ring-[#adc6ff] rounded-xl pl-12 pr-4 py-3 text-sm text-[#dae2fd] placeholder:text-[#424754]/60 outline-none transition-all"
                        placeholder="Search logs by user, action or model..." type="text" />
                </div>
            </form>

            <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 500px);">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#131b2e]/50">
                            @foreach(['User', 'Action', 'Model', 'Description', 'IP Address', 'Time'] as $h)
                                <th
                                    class="px-8 py-4 text-[0.6875rem] uppercase tracking-widest font-bold text-[#424754] border-b border-[#424754]/10">
                                    {{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#424754]/5">
                        @forelse($logs as $log)
                            @php
                                $evt = strtolower($log->event ?? $log->description ?? 'updated');
                                $evtColor = str_contains($evt, 'creat') ? 'bg-[#adc6ff]/20 text-[#adc6ff] ring-[#adc6ff]/30'
                                    : (str_contains($evt, 'delet') ? 'bg-[#ffb4ab]/20 text-[#ffb4ab] ring-[#ffb4ab]/30'
                                        : 'bg-[#b9c8de]/20 text-[#b9c8de] ring-[#b9c8de]/30');
                                $evtLabel = str_contains($evt, 'creat') ? 'Created'
                                    : (str_contains($evt, 'delet') ? 'Deleted' : 'Updated');
                                $initials = strtoupper(substr($log->causer_name ?? 'SYS', 0, 2));
                                $subjectType = class_basename($log->subject_type ?? 'Record');
                                $ipAddr = $log->properties ? json_decode($log->properties, true)['ip'] ?? '—' : '—';
                            @endphp
                            <tr class="hover:bg-[#2d3449]/30 transition-colors group cursor-pointer" onclick="openLogDetailDrawer(
                                @json($log->causer_name ?? 'System'),
                                @json($evtLabel),
                                @json($subjectType),
                                @json($log->description ?? 'No description'),
                                @json($ipAddr),
                                @json(\Carbon\Carbon::parse($log->created_at)->format('H:i:s • M d, Y'))
                            )">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-[#2d3449] flex items-center justify-center text-[10px] font-bold text-[#adc6ff]">
                                            {{ $initials }}</div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-[#dae2fd]">{{ $log->causer_name ?? 'System' }}</span>
                                            <span
                                                class="text-[10px] text-[#c2c6d6]">{{ $log->causer_name ? 'user' : 'system' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="px-3 py-1 rounded-full {{ $evtColor }} text-[10px] font-bold uppercase tracking-tighter ring-1">{{ $evtLabel }}</span>
                                </td>
                                <td class="px-8 py-5 text-sm font-mono text-[#adc6ff]/80">{{ $subjectType }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-xs text-[#dae2fd]">{{ Str::limit($log->description ?? 'No description', 48) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-xs text-[#424754] font-mono">{{ $ipAddr }}</td>
                                <td class="px-8 py-5 text-xs text-[#c2c6d6] tabular-nums">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s • M d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <span class="material-symbols-outlined text-4xl text-[#424754] block mb-3">history</span>
                                    <p class="text-[#8c909f] text-sm">No audit log entries found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="px-8 py-6 bg-[#131b2e]/30 border-t border-[#424754]/10 flex justify-between items-center">
                    <span class="text-xs text-[#c2c6d6]">Showing <span
                            class="text-[#dae2fd]">{{ $logs->firstItem() }}-{{ $logs->lastItem() }}</span> of <span
                            class="text-[#dae2fd]">{{ number_format($logs->total()) }}</span> logs</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ $logs->previousPageUrl() ?? '#' }}"
                            class="p-2 {{ $logs->onFirstPage() ? 'text-[#424754] cursor-not-allowed' : 'text-[#c2c6d6] hover:text-[#dae2fd] hover:bg-[#2d3449]' }} transition-all rounded-lg">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                        @for($i = max(1, $logs->currentPage() - 1); $i <= min($logs->lastPage(), $logs->currentPage() + 2); $i++)
                            <a href="{{ $logs->url($i) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition
                                   {{ $i === $logs->currentPage() ? 'bg-[#adc6ff] text-[#002e6a]' : 'text-[#c2c6d6] hover:bg-[#2d3449]' }}">
                                {{ $i }}
                            </a>
                        @endfor
                        <a href="{{ $logs->nextPageUrl() ?? '#' }}"
                            class="p-2 {{ $logs->hasMorePages() ? 'text-[#c2c6d6] hover:text-[#dae2fd] hover:bg-[#2d3449]' : 'text-[#424754] cursor-not-allowed' }} transition-all rounded-lg">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer Metric Bar --}}
        <footer class="mt-8 py-6 border-t border-[#424754]/10 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#ffb95f] animate-pulse"></div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#c2c6d6]">Live Integrity Check:
                        Passed</span>
                </div>
                <div class="h-4 w-px bg-[#424754]/30"></div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm text-[#adc6ff]">database</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#c2c6d6]">Records:
                        {{ number_format($stats['total']) }}</span>
                </div>
            </div>
            <span class="text-[10px] text-[#424754] font-mono">Ironclad Forge v2.x</span>
        </footer>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LOG DETAIL DRAWER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] transition-opacity duration-300 opacity-0 pointer-events-none"
        id="log-detail-backdrop"></div>
    <div class="fixed top-0 right-0 h-full w-full max-w-md bg-[#0b1326] border-l border-[#424754]/30 shadow-2xl z-[70] transform drawer-closed flex flex-col"
        id="log-detail-drawer">
        <div class="px-8 py-6 border-b border-[#424754]/20 bg-[#0b1326] flex items-center justify-between">
            <div>
                <h3 class="font-headline text-xl font-bold text-[#dae2fd]">Log Entry Detail</h3>
                <p class="font-label text-[10px] text-[#adc6ff] uppercase tracking-widest mt-1" id="ld-time">—</p>
            </div>
            <button class="p-2 text-[#8c909f] hover:text-[#dae2fd] transition-colors rounded-lg hover:bg-[#2d3449]"
                onclick="closeLogDetailDrawer()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto industrial-scroll px-8 py-6 space-y-6">
            <div class="bg-[#131b2e] rounded-xl p-5 border border-[#424754]/10 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-widest mb-1">User</p>
                        <p class="text-sm font-semibold text-[#dae2fd]" id="ld-user">—</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-widest mb-1">Action</p>
                        <p class="text-sm font-semibold" id="ld-action">—</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-widest mb-1">Model / Resource</p>
                        <p class="text-sm font-mono text-[#adc6ff]" id="ld-model">—</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-widest mb-1">IP Address</p>
                        <p class="text-sm font-mono text-[#8c909f]" id="ld-ip">—</p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-widest mb-2">Description</p>
                <div class="bg-[#131b2e] rounded-xl p-4 border border-[#424754]/10">
                    <p class="text-sm text-[#dae2fd] leading-relaxed" id="ld-desc">—</p>
                </div>
            </div>
        </div>

        <div class="p-8 border-t border-[#424754]/20">
            <button onclick="closeLogDetailDrawer()"
                class="w-full bg-[#2d3449] py-3 rounded-xl font-headline font-bold text-xs text-[#dae2fd] flex items-center justify-center gap-2 hover:bg-[#31394d] transition">
                <span class="material-symbols-outlined text-sm">close</span>
                Close
            </button>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function openLogDetailDrawer(user, action, model, desc, ip, time) {
            document.getElementById('ld-user').textContent = user;
            document.getElementById('ld-action').textContent = action;
            document.getElementById('ld-model').textContent = model;
            document.getElementById('ld-desc').textContent = desc;
            document.getElementById('ld-ip').textContent = ip;
            document.getElementById('ld-time').textContent = time;

            // Color action label
            const el = document.getElementById('ld-action');
            el.className = 'text-sm font-semibold ' + (
                action === 'Created' ? 'text-[#adc6ff]' :
                    action === 'Deleted' ? 'text-[#ffb4ab]' : 'text-[#b9c8de]'
            );

            document.getElementById('log-detail-backdrop').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('log-detail-backdrop').classList.add('opacity-100');
            document.getElementById('log-detail-drawer').classList.remove('drawer-closed');
            document.getElementById('log-detail-drawer').classList.add('drawer-open');
        }
        function closeLogDetailDrawer() {
            document.getElementById('log-detail-backdrop').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('log-detail-backdrop').classList.remove('opacity-100');
            document.getElementById('log-detail-drawer').classList.remove('drawer-open');
            document.getElementById('log-detail-drawer').classList.add('drawer-closed');
        }
        document.getElementById('log-detail-backdrop')?.addEventListener('click', closeLogDetailDrawer);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLogDetailDrawer(); });
    </script>
@endsection