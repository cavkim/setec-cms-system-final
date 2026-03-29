@extends('layouts.app')
@section('title', 'Team')
@section('page-title', 'Team')

@section('styles')
    <style>
        .row-selected {
            background-color: rgba(173, 198, 255, 0.08) !important;
        }

        #member-drawer {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #member-drawer.open {
            transform: translateX(0);
        }

        #member-drawer-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #member-drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'))</script>
    @endif

    {{-- Cert expiry warning --}}
    @if($expiringCerts->count() > 0)
        <div class="flex items-start gap-3 bg-secondary/10 border border-secondary/25 rounded-2xl p-4 mb-6">
            <span class="material-symbols-outlined text-secondary mt-0.5"
                style="font-variation-settings:'FILL' 1;">warning</span>
            <div>
                <p class="text-sm font-bold text-secondary mb-1">{{ $expiringCerts->count() }} certification(s) expiring within
                    30 days</p>
                <p class="text-xs text-secondary/80">
                    @foreach($expiringCerts as $c)
                        <span class="mr-4">{{ $c->name }} — {{ $c->certification_number }} expires
                            {{ \Carbon\Carbon::parse($c->certification_expiry)->format('M d, Y') }}</span>
                    @endforeach
                </p>
            </div>
        </div>
    @endif
  

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <div
            class="bg-surface-container-high p-6 rounded-xl border-l-4 border-primary shadow-lg relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-20 h-20 bg-primary/5 rounded-full -mr-6 -mt-6 group-hover:scale-125 transition-transform">
            </div>
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Total Members</p>
            <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['total'] }}</p>
            <div class="mt-3 flex items-center text-xs text-on-surface-variant gap-1">
                <span class="material-symbols-outlined text-sm">group</span> Full capacity roster
            </div>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl border-l-4 border-primary-container shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Active</p>
            <p class="text-4xl font-extrabold text-primary font-headline">{{ $stats['active'] }}</p>
            <div class="mt-3 flex items-center text-xs text-primary gap-1">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">bolt</span>
                Operational
            </div>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl border-l-4 border-tertiary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">On Tasks</p>
            <p class="text-4xl font-extrabold text-white font-headline">{{ $stats['on_tasks'] }}</p>
            <div class="mt-3 flex items-center text-xs text-on-surface-variant gap-1">
                <span class="material-symbols-outlined text-sm">location_on</span> Has open tasks
            </div>
        </div>
        <div class="bg-surface-container-high p-6 rounded-xl border-l-4 border-secondary shadow-lg">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Expiring Certs</p>
            <p class="text-4xl font-extrabold font-headline {{ $stats['expiring'] > 0 ? 'text-secondary' : 'text-tertiary' }}">
                {{ $stats['expiring'] }}</p>
            <div
                class="mt-3 flex items-center text-xs {{ $stats['expiring'] > 0 ? 'text-secondary' : 'text-on-surface-variant' }} gap-1">
                <span class="material-symbols-outlined text-sm">{{ $stats['expiring'] > 0 ? 'warning' : 'check_circle' }}</span>
                {{ $stats['expiring'] > 0 ? 'Action required' : 'All valid' }}
            </div>
        </div>
    </div>

    {{-- Filters + Search --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="flex items-center p-1 bg-surface-container-low rounded-xl gap-1">
            <a href="{{ route('team.index', ['search' => request('search')]) }}"
                class="px-5 py-2 text-sm font-semibold rounded-lg transition-all
                      {{ !request('role') || request('role') === 'all' ? 'bg-surface-variant text-primary' : 'text-on-surface-variant hover:text-white' }}">All</a>
            @foreach($roles as $r)
                <a href="{{ route('team.index', ['role' => $r, 'search' => request('search')]) }}"
                    class="px-5 py-2 text-sm font-semibold rounded-lg transition-all
                          {{ request('role') === $r ? 'bg-surface-variant text-primary' : 'text-on-surface-variant hover:text-white' }}">
                    {{ ucfirst(str_replace('_', ' ', $r)) }}
                </a>
            @endforeach
        </div>
        <div class="relative w-full md:w-80">
            <form method="GET" action="{{ route('team.index') }}">
                <input type="hidden" name="role" value="{{ request('role', 'all') }}">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input
                    class="w-full bg-surface-container-lowest rounded-xl py-2.5 pl-11 pr-4 text-sm text-on-surface
                              placeholder:text-on-surface-variant border border-white/5 focus:border-primary focus:outline-none transition-all"
                    placeholder="Search by name, email, role..." type="text" name="search" value="{{ request('search') }}"
                    oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)">
            </form>
        </div>
    </div>

    {{-- Team Table --}}
    <div class="bg-surface-container rounded-2xl shadow-2xl overflow-hidden border border-white/5 flex flex-col">
        <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 500px);">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-high/50 border-b border-white/5">
                        @foreach(['Member', 'Role', 'Active Tasks', 'Workload', 'Cert Expiry', 'Member Since', 'Status', 'Actions'] as $h)
                            <th
                                class="px-6 py-5 text-xs font-bold uppercase tracking-[0.1em] text-on-surface-variant {{ in_array($h, ['Active Tasks']) ? ' text-center' : '' }}">
                                {{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5" id="team-table-body">
                    @php $avatarColors = ['bg-blue-700', 'bg-teal-700', 'bg-orange-700', 'bg-red-800', 'bg-purple-800', 'bg-cyan-700'];
                    $i = 0; @endphp
                    @forelse($members as $member)
                        @php
                            $ac = $avatarColors[$i++ % count($avatarColors)];
                            $pct = min(($member->active_tasks / 10) * 100, 100);
                            $isExpired = $member->certification_expiry && \Carbon\Carbon::parse($member->certification_expiry)->isPast();
                            $isSoon = !$isExpired && $member->certification_expiry && \Carbon\Carbon::parse($member->certification_expiry)->diffInDays() < 60;
                        @endphp
                        <tr class="hover:bg-white/[0.04] cursor-pointer transition-colors group"
                            onclick="openMemberDrawer(this, {{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->email }}', '{{ $member->role ?? 'team_member' }}', '{{ $member->certification_number ?? '' }}', '{{ $member->certification_expiry ?? '' }}', {{ $member->active_tasks }})">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full {{ $ac }} flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface leading-tight">{{ $member->name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($member->role)
                                                <span
                                                    class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full
                                                    {{ $member->role === 'project_manager' ? 'bg-blue-900/40 text-blue-300' :
                                    ($member->role === 'site_supervisor' ? 'bg-teal-900/40 text-teal-300' :
                                        ($member->role === 'admin' ? 'bg-purple-900/40 text-purple-300' : 'bg-white/5 text-on-surface-variant')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                                </span>
                                @else<span class="text-on-surface-variant text-sm">—</span>@endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center bg-surface-variant w-8 h-8 rounded-full text-xs font-bold
                                    {{ $member->active_tasks > 7 ? 'text-error' : ($member->active_tasks > 4 ? 'text-secondary' : 'text-primary') }}">
                                    {{ $member->active_tasks }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-between text-[10px] font-bold text-on-surface-variant mb-1">
                                    <span>{{ round($pct) }}%</span>
                                </div>
                                <div class="h-1.5 w-28 bg-surface-container-lowest rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $pct > 80 ? 'bg-error' : ($pct > 60 ? 'bg-secondary' : 'bg-primary-container') }}"
                                        style="width:{{ $pct }}%"></div>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-sm {{ $isExpired ? 'text-error' : ($isSoon ? 'text-secondary font-bold' : 'text-on-surface') }}">
                                @if($member->certification_expiry)
                                    {{ \Carbon\Carbon::parse($member->certification_expiry)->format('M Y') }}
                                    @if($isExpired)<span class="text-[10px] ml-1">⚠ Expired</span>
                                    @elseif($isSoon)<span class="text-[10px] ml-1">⚠ Soon</span>
                                    @endif
                                @else<span class="text-on-surface-variant">N/A</span>@endif
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant">
                                {{ \Carbon\Carbon::parse($member->created_at)->format('M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1 w-fit bg-tertiary-container/20 text-tertiary">
                                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span> Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                @can('edit team')
                                    <button
                                        class="p-2 text-outline hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                                        onclick="openMemberDrawer(this.closest('tr'), {{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->email }}', '{{ $member->role ?? 'team_member' }}', '{{ $member->certification_number ?? '' }}', '{{ $member->certification_expiry ?? '' }}', {{ $member->active_tasks }})">
                                        <span class="material-symbols-outlined text-sm"
                                            style="font-variation-settings:'FILL' 1;">edit</span>
                                    </button>
                                @endcan
                                @can('delete team')
                                    @if($member->id !== auth()->id())
                                        <button class="p-2 text-outline hover:text-error hover:bg-error/10 rounded-lg transition-all"
                                            onclick="confirmDeleteMember({{ $member->id }}, '{{ addslashes($member->name) }}')">
                                            <span class="material-symbols-outlined text-sm"
                                                style="font-variation-settings:'FILL' 1;">delete</span>
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="text-on-surface-variant text-sm mb-4">No team members found</div>
                                @can('create team')
                                    <button onclick="openAddMemberDrawer()"
                                        class="px-6 py-3 text-sm font-semibold bg-primary text-on-primary rounded-xl active:scale-95 transition-transform">
                                        + Add first member
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-6 py-4 bg-surface-container-high/30 border-t border-white/5 flex items-center justify-between">
                <p class="text-xs text-on-surface-variant">Showing {{ $members->firstItem() }}–{{ $members->lastItem() }} of
                    {{ $members->total() }} members</p>
                <div class="flex gap-2">
                    @if($members->onFirstPage())
                        <span
                            class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-50">Previous</span>
                    @else
                        <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md hover:bg-surface-variant border border-outline-variant/20"
                            href="{{ $members->previousPageUrl() }}">Previous</a>
                    @endif
                    @if($members->hasMorePages())
                        <a class="px-3 py-1 text-xs font-bold bg-primary text-on-primary rounded-md"
                            href="{{ $members->nextPageUrl() }}">Next</a>
                    @else
                        <span
                            class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md opacity-50">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- FAB: Add Member --}}
    @can('create team')
        <div class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 group">
            <span
                class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0
                bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10">
                Add Member
            </span>
            <button onclick="openAddMemberDrawer()" class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)]
                           flex items-center justify-center hover:scale-110 hover:shadow-[0_6px_32px_rgba(77,142,255,0.6)]
                           active:scale-95 transition-all duration-200" aria-label="Add member">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">person_add</span>
            </button>
        </div>
    @endcan

    {{-- Drawer Overlay --}}
    <div id="member-drawer-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" onclick="closeMemberDrawer()">
    </div>

    {{-- Member Drawer --}}
    <div id="member-drawer"
        class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] flex flex-col border-l border-white/5">

        {{-- Drawer Header --}}
        <div class="p-6 border-b border-white/5 bg-surface-container-high/30">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-headline font-extrabold text-white" id="md-title">Manage Member</h3>
                    <p class="text-sm text-on-surface-variant mt-0.5" id="md-subtitle">Edit member details</p>
                </div>
                <button class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-full transition-colors"
                    onclick="closeMemberDrawer()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex items-center gap-4" id="md-avatar-row">
                <div class="w-16 h-16 rounded-2xl bg-primary/20 flex items-center justify-center text-2xl font-bold text-primary flex-shrink-0"
                    id="md-avatar-initials">?</div>
                <div>
                    <h4 class="text-xl font-headline font-bold text-white" id="md-name">—</h4>
                    <p class="text-primary text-sm font-semibold" id="md-role-display">—</p>
                    <p class="text-xs text-on-surface-variant mt-0.5" id="md-email-display">—</p>
                </div>
            </div>
        </div>

        {{-- Drawer Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-5">
            {{-- Create banner --}}
            <div id="md-create-banner"
                class="hidden px-4 py-3 bg-primary/10 border border-primary/20 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm"
                    style="font-variation-settings:'FILL' 1;">person_add</span>
                <span class="text-xs text-primary font-semibold">New member — fill in the details below</span>
            </div>

            {{-- Account --}}
            <div class="space-y-3">
                <p class="text-[10px] font-bold uppercase tracking-widest text-primary">Account</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Full
                            Name <span class="text-red-400">*</span></label>
                        <input id="md-field-name" type="text" placeholder="e.g. John Smith"
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Email
                            <span class="text-red-400">*</span></label>
                        <input id="md-field-email" type="email" placeholder="john@buildscape.com"
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                    </div>
                </div>
                <div id="md-password-row">
                    <label
                        class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Password
                        <span class="text-red-400">*</span></label>
                    <input id="md-field-password" type="password" placeholder="Min 6 characters"
                        class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label
                    class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Role</label>
                <select id="md-field-role"
                    class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary">
                    <option value="team_member">Team Member</option>
                    <option value="site_supervisor">Site Supervisor</option>
                    <option value="project_manager">Project Manager</option>
                    <option value="admin">Admin</option>
                    <option value="client">Client</option>
                </select>
            </div>

            {{-- Certification --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">Certification</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Cert
                            Number</label>
                        <input id="md-field-cert" type="text" placeholder="e.g. OSHA-2024-001"
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Expiry
                            Date</label>
                        <input id="md-field-expiry" type="date"
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>
        </div>

        {{-- Drawer Footer --}}
        <div class="p-6 bg-surface-container-high border-t border-white/5 space-y-3">
            <div class="flex items-center gap-3">
                @can('edit team')
                    <button id="md-save-btn" type="button" onclick="submitMemberDrawer()"
                        class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl shadow-lg active:scale-95 transition-transform">
                        Save Info
                    </button>
                @endcan
                <button type="button" onclick="closeMemberDrawer()"
                    class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors">
                    Close
                </button>
            </div>
            @can('delete team')
                <button id="md-delete-btn" type="button" onclick="deleteMemberSubmit()"
                    class="w-full py-3 text-error font-bold hover:bg-error/10 rounded-xl border border-error/20 transition-all text-sm">
                    <span class="material-symbols-outlined text-sm align-middle mr-1"
                        style="font-variation-settings:'FILL' 1;">delete</span>
                    Remove Member
                </button>
            @endcan
        </div>
    </div>

    {{-- Hidden forms --}}
    @can('create team')
        <form id="md-create-form" method="POST" action="{{ route('team.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="name" id="mdc-name">
            <input type="hidden" name="email" id="mdc-email">
            <input type="hidden" name="password" id="mdc-password">
            <input type="hidden" name="role" id="mdc-role">
            <input type="hidden" name="certification_number" id="mdc-cert">
            <input type="hidden" name="certification_expiry" id="mdc-expiry">
        </form>
    @endcan

    @can('edit team')
        <form id="md-update-form" method="POST" class="hidden">
            @csrf @method('PUT')
            <input type="hidden" name="name" id="mdu-name">
            <input type="hidden" name="email" id="mdu-email">
            <input type="hidden" name="role" id="mdu-role">
            <input type="hidden" name="certification_number" id="mdu-cert">
            <input type="hidden" name="certification_expiry" id="mdu-expiry">
        </form>
    @endcan

    @can('delete team')
        <form id="md-delete-form" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endcan

@endsection

@section('scripts')
    <script>
        const teamBase = @json(url('/team'));
        window.__mdMode = null;
        window.__mdMemberId = null;

        function openMemberDrawer(row, id, name, email, role, cert, expiry, tasks) {
            window.__mdMode = 'edit';
            window.__mdMemberId = id;

            document.querySelectorAll('#team-table-body tr').forEach(r => r.classList.remove('row-selected'));
            if (row) row.classList.add('row-selected');

            document.getElementById('md-title').textContent = 'Edit Member';
            document.getElementById('md-subtitle').textContent = 'Update member information';
            document.getElementById('md-name').textContent = name;
            document.getElementById('md-email-display').textContent = email;
            document.getElementById('md-role-display').textContent = role.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            document.getElementById('md-avatar-initials').textContent = name.substring(0, 2).toUpperCase();

            document.getElementById('md-field-name').value = name;
            document.getElementById('md-field-email').value = email;
            document.getElementById('md-field-role').value = role;
            document.getElementById('md-field-cert').value = cert || '';
            document.getElementById('md-field-expiry').value = expiry || '';

            document.getElementById('md-save-btn').textContent = 'Save Changes';
            document.getElementById('md-delete-btn').classList.remove('hidden');
            document.getElementById('md-password-row').classList.add('hidden');
            document.getElementById('md-create-banner').classList.add('hidden');
            document.getElementById('md-update-form').action = teamBase + '/' + id;
            document.getElementById('md-delete-form').action = teamBase + '/' + id;

            _openMemberDrawer();
        }

        function openAddMemberDrawer() {
            window.__mdMode = 'create';
            window.__mdMemberId = null;

            document.querySelectorAll('#team-table-body tr').forEach(r => r.classList.remove('row-selected'));

            document.getElementById('md-title').textContent = 'Add Member';
            document.getElementById('md-subtitle').textContent = 'Create a new user account';
            document.getElementById('md-name').textContent = 'New Member';
            document.getElementById('md-email-display').textContent = 'Fill in details below';
            document.getElementById('md-role-display').textContent = 'Team Member';
            document.getElementById('md-avatar-initials').textContent = '+';

            ['md-field-name', 'md-field-email', 'md-field-cert'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('md-field-password').value = '';
            document.getElementById('md-field-role').value = 'team_member';
            document.getElementById('md-field-expiry').value = '';

            document.getElementById('md-save-btn').textContent = 'Add Member';
            document.getElementById('md-delete-btn').classList.add('hidden');
            document.getElementById('md-password-row').classList.remove('hidden');
            document.getElementById('md-create-banner').classList.remove('hidden');

            _openMemberDrawer();
            setTimeout(() => document.getElementById('md-field-name').focus(), 300);
        }

        function _openMemberDrawer() {
            document.getElementById('member-drawer').classList.add('open');
            document.getElementById('member-drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMemberDrawer() {
            document.getElementById('member-drawer').classList.remove('open');
            document.getElementById('member-drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
            document.querySelectorAll('#team-table-body tr').forEach(r => r.classList.remove('row-selected'));
            window.__mdMode = null;
        }

        function submitMemberDrawer() {
            const name = document.getElementById('md-field-name').value.trim();
            if (!name) { if (typeof toast === 'function') toast('Name is required', 'warn'); return; }

            const email = document.getElementById('md-field-email').value.trim();
if (!email) { if (typeof toast === 'function') toast('Email is required', 'warn'); return; }

            const fields = {
                name, email: document.getElementById('md-field-email').value,
                role: document.getElementById('md-field-role').value,
                cert: document.getElementById('md-field-cert').value,
                expiry: document.getElementById('md-field-expiry').value,
            };

            if (window.__mdMode === 'create') {
                const pw = document.getElementById('md-field-password').value;
                if (!pw) { if (typeof toast === 'function') toast('Password is required', 'warn'); return; }
                document.getElementById('mdc-name').value = fields.name;
                document.getElementById('mdc-email').value = fields.email;
                document.getElementById('mdc-password').value = pw;
                document.getElementById('mdc-role').value = fields.role;
                document.getElementById('mdc-cert').value = fields.cert;
                document.getElementById('mdc-expiry').value = fields.expiry;
                document.getElementById('md-create-form').submit();
            } else {
                document.getElementById('mdu-name').value = fields.name;
                document.getElementById('mdu-email').value = fields.email;
                document.getElementById('mdu-role').value = fields.role;
                document.getElementById('mdu-cert').value = fields.cert;
                document.getElementById('mdu-expiry').value = fields.expiry;
                document.getElementById('md-update-form').submit();
            }
        }

        function confirmDeleteMember(id, name) {
            if (!confirm('Remove "' + name + '" from the team? This cannot be undone.')) return;
            document.getElementById('md-delete-form').action = teamBase + '/' + id;
            document.getElementById('md-delete-form').submit();
        }

        function deleteMemberSubmit() {
            if (!window.__mdMemberId) return;
            const name = document.getElementById('md-name').textContent;
            if (!confirm('Remove "' + name + '" from the team? This cannot be undone.')) return;
            document.getElementById('md-delete-form').submit();
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMemberDrawer(); });
    </script>
@endsection