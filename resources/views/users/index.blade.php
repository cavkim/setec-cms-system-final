{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')
@section('title', 'User Management — Tectonic Blueprint')
@section('page-title', 'User Management')

@section('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-low": "#131b2e",
                        "secondary": "#ffb95f",
                        "surface-variant": "#2d3449",
                        "surface-container-highest": "#2d3449",
                        "primary": "#adc6ff",
                        "outline": "#8c909f",
                        "on-surface": "#dae2fd",
                        "surface-container-lowest": "#060e20",
                        "primary-container": "#4d8eff",
                        "secondary-container": "#ee9800",
                        "on-surface-variant": "#c2c6d6",
                        "surface-container-high": "#222a3d",
                        "surface-container": "#171f33",
                        "tertiary-container": "#8392a6",
                        "on-primary": "#002e6a",
                        "outline-variant": "#424754",
                        "on-background": "#dae2fd",
                        "background": "#0b1326",
                        "surface": "#0b1326",
                        "on-primary-container": "#00285d",
                        "surface-bright": "#31394d",
                        "error": "#ffb4ab",
                        "error-container": "#93000a",
                        "tertiary": "#b9c8de",
                        "surface-dim": "#0b1326",
                    },
                    fontFamily: { "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"] },
                    borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1326;
            color: #dae2fd;
        }

        h1,
        h2,
        h3,
        .headline {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0b1326;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #2d3449;
            border-radius: 10px;
        }

        /* Drawer transitions */
        #create-drawer,
        #edit-drawer,
        #profile-drawer,
        #delete-drawer {
            transition: opacity .25s ease;
        }

        #create-drawer .drawer-panel,
        #edit-drawer .drawer-panel,
        #profile-drawer .drawer-panel,
        #delete-drawer .drawer-panel {
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            transform: translateX(100%);
        }

        #create-drawer.show,
        #edit-drawer.show,
        #profile-drawer.show,
        #delete-drawer.show {
            opacity: 1;
            pointer-events: all;
        }

        #create-drawer.show .drawer-panel,
        #edit-drawer.show .drawer-panel,
        #profile-drawer.show .drawer-panel,
        #delete-drawer.show .drawer-panel {
            transform: translateX(0);
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast('{{ session('success') }}', 'success'))</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => toast('{{ session('error') }}', 'danger'))</script>
    @endif

    <div class="p-10 min-h-screen">

        {{-- ── PAGE HEADER ── --}}
        <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-on-surface headline tracking-tight">User Management</h1>
                <p class="text-on-surface-variant mt-2 max-w-xl">Configure permissions, track operational activity, and
                    manage site access for all contractors and internal personnel.</p>
            </div>
            <button onclick="openCreateDrawer()"
                class="bg-gradient-to-br from-primary to-primary-container text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-2xl shadow-[#ee9800]/30 active:scale-95 transition-transform text-sm">
                <span class="material-symbols-outlined text-base"
                    style="font-variation-settings:'FILL' 1;">person_add</span>
                New User
            </button>
        </div>

        {{-- ── KPI METRICS ── --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

            <div class="bg-surface-container p-6 rounded-xl border-l-4 border-primary shadow-lg shadow-black/20">
                <div class="text-on-surface-variant text-[0.6875rem] font-bold uppercase tracking-widest mb-2">Total
                    Personnel</div>
                <div class="text-3xl font-extrabold headline">{{ $stats['total'] }}</div>
                <div class="text-xs text-primary mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">group</span><span>All accounts</span>
                </div>
            </div>

            <div class="bg-surface-container p-6 rounded-xl border-l-4 border-tertiary-container shadow-lg shadow-black/20">
                <div class="text-on-surface-variant text-[0.6875rem] font-bold uppercase tracking-widest mb-2">Active
                    On-Site</div>
                <div class="text-3xl font-extrabold headline">{{ $stats['active'] }}</div>
                <div class="text-xs text-on-surface-variant mt-2 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span><span>Currently active</span>
                </div>
            </div>

            <div
                class="bg-surface-container p-6 rounded-xl border-l-4 border-secondary-container shadow-lg shadow-black/20">
                <div class="text-on-surface-variant text-[0.6875rem] font-bold uppercase tracking-widest mb-2">Inactive
                </div>
                <div class="text-3xl font-extrabold headline">{{ $stats['inactive'] }}</div>
                <div class="text-xs text-secondary mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">hourglass_empty</span><span>Disabled accounts</span>
                </div>
            </div>

            <div class="bg-surface-container p-6 rounded-xl border-l-4 border-error-container shadow-lg shadow-black/20">
                <div class="text-on-surface-variant text-[0.6875rem] font-bold uppercase tracking-widest mb-2">Have Photo
                </div>
                <div class="text-3xl font-extrabold headline">{{ $stats['with_photo'] }}</div>
                <div class="text-xs text-error mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">account_circle</span><span>Profile pictures set</span>
                </div>
            </div>

        </div>

        {{-- ── USER DIRECTORY ── --}}
        <section
            class="bg-surface-container rounded-xl overflow-hidden shadow-2xl shadow-black/40 border border-outline-variant/10">

            {{-- Toolbar --}}
            <div class="px-6 py-5 flex flex-wrap items-center justify-between bg-surface-container-high/50 gap-4">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-bold headline">User Directory</h2>
                    <span
                        class="bg-surface-container-highest text-on-surface-variant px-3 py-1 rounded-full text-xs font-mono">
                        ALL_RECORDS: {{ $stats['total'] }}
                    </span>
                </div>
                <div class="flex items-center gap-3 flex-wrap">

                    {{-- Search --}}
                    <form method="GET" action="{{ route('users.index') }}" class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 rounded-lg py-2 pl-10 pr-4 text-xs focus:ring-2 focus:ring-primary/50 w-52 text-on-surface placeholder:text-on-surface-variant/50 outline-none"
                            oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)" />
                        <input type="hidden" name="role" value="{{ request('role', 'all') }}">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    </form>

                    {{-- Status filters --}}
                    <div class="flex gap-1 flex-wrap">
                        @foreach(['all' => 'All Status', 'active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended'] as $v => $l)
                                        <a href="{{ route('users.index', ['status' => $v, 'role' => request('role', 'all'), 'search' => request('search')]) }}"
                                            class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                                                                                                  {{ request('status', $v === 'all' ? 'all' : '') === $v
                            ? 'bg-primary/20 text-primary border-primary/30'
                            : 'bg-transparent text-on-surface-variant border-outline-variant/30 hover:text-on-surface' }}">
                                            {{ $l }}
                                        </a>
                        @endforeach
                    </div>

                    {{-- Role filters --}}
                    <div class="flex gap-1 flex-wrap">
                        <a href="{{ route('users.index', ['role' => 'all', 'status' => request('status', 'all'), 'search' => request('search')]) }}"
                            class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                  {{ !request('role') || request('role') === 'all'
        ? 'bg-primary/20 text-primary border-primary/30'
        : 'bg-transparent text-on-surface-variant border-outline-variant/30 hover:text-on-surface' }}">
                            All Roles
                        </a>
                        @foreach($roles as $role)
                                        <a href="{{ route('users.index', ['role' => $role->name, 'status' => request('status', 'all'), 'search' => request('search')]) }}"
                                            class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                                                                                                  {{ request('role') === $role->name
                            ? 'bg-primary/20 text-primary border-primary/30'
                            : 'bg-transparent text-on-surface-variant border-outline-variant/30 hover:text-on-surface' }}">
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </a>
                        @endforeach
                    </div>

                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/10">
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant">
                                User Entity</th>
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant">
                                Functional Role</th>
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant">
                                Status</th>
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant">
                                Direct Phone</th>
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant text-center">
                                Active Tasks</th>
                            <th
                                class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-widest text-on-surface-variant">
                                Joined</th>
                            <th class="px-6 py-4 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/5">
                        @forelse($users as $user)
                            @php
                                $roleColors = [
                                    'super_admin' => 'bg-purple-500/10 text-purple-300 border-purple-500/20',
                                    'admin' => 'bg-red-500/10 text-red-300 border-red-500/20',
                                    'project_manager' => 'bg-blue-500/10 text-blue-300 border-blue-500/20',
                                    'site_supervisor' => 'bg-teal-500/10 text-teal-300 border-teal-500/20',
                                    'team_member' => 'bg-amber-500/10 text-amber-300 border-amber-500/20',
                                    'client' => 'bg-surface-container-highest text-on-surface-variant border-outline-variant/20',
                                ];
                                $rc = $roleColors[$user->role] ?? 'bg-surface-container-highest text-on-surface-variant border-outline-variant/20';
                                $st = $user->status ?? 'active';
                                $statusClass = match ($st) {
                                    'active' => 'bg-tertiary-container/20 text-tertiary',
                                    'inactive' => 'bg-secondary-container/20 text-secondary',
                                    default => 'bg-error-container/20 text-error',
                                };
                                $dotClass = match ($st) {
                                    'active' => 'bg-tertiary',
                                    'inactive' => 'bg-secondary',
                                    default => 'bg-error',
                                };
                            @endphp
                            <tr class="hover:bg-surface-container-highest/30 transition-colors group cursor-pointer" onclick="openProfileDrawer(
                                                                    {{ $user->id }},
                                                                    '{{ addslashes($user->name) }}',
                                                                    '{{ $user->email }}',
                                                                    '{{ $user->role ?? 'member' }}',
                                                                    '{{ $user->phone ?? '' }}',
                                                                    '{{ $st }}',
                                                                    {{ $user->active_tasks }},
                                                                    '{{ $user->avatar ? Storage::url($user->avatar) : '' }}')">

                                {{-- Avatar + Name --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-full bg-surface-container-highest overflow-hidden border border-primary/20 flex-shrink-0">
                                            @if($user->avatar)
                                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                                    class="w-full h-full object-cover" />
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/30 to-primary-container/50 text-on-primary text-xs font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-bold headline text-on-surface group-hover:text-primary transition-colors">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-xs text-on-surface-variant">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-6 py-4">
                                    @if($user->role)
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[0.7rem] font-bold border uppercase tracking-tight {{ $rc }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @else
                                        <span class="text-on-surface-variant text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.7rem] font-bold {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>

                                {{-- Phone --}}
                                <td class="px-6 py-4 font-mono text-xs text-on-surface-variant">{{ $user->phone ?: '—' }}</td>

                                {{-- Tasks --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-bold headline
                                                                        {{ $user->active_tasks > 7 ? 'text-error' : ($user->active_tasks > 4 ? 'text-secondary' : 'text-on-surface') }}">
                                        {{ $user->active_tasks }}
                                    </span>
                                </td>

                                {{-- Joined --}}
                                <td class="px-6 py-4 text-xs text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button
                                            onclick="openEditDrawer(
                                                                                    {{ $user->id }},
                                                                                    '{{ addslashes($user->name) }}',
                                                                                    '{{ $user->email }}',
                                                                                    '{{ $user->phone ?? '' }}',
                                                                                    '{{ $st }}',
                                                                                    '{{ $user->role ?? '' }}',
                                                                                    '{{ $user->avatar ? Storage::url($user->avatar) : '' }}')"
                                            class="text-[10px] px-3 py-1.5 rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 transition-colors font-bold uppercase tracking-wider">
                                            Edit
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button onclick="confirmDeleteUser({{ $user->id }},'{{ addslashes($user->name) }}')"
                                                class="text-[10px] px-3 py-1.5 rounded-lg bg-error/10 text-error border border-error/20 hover:bg-error/20 transition-colors font-bold uppercase tracking-wider">
                                                Del
                                            </button>
                                        @else
                                            <span class="text-[10px] text-on-surface-variant px-3 py-1.5">You</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="text-on-surface-variant text-sm mb-4">No users found</div>
                                    <button onclick="openCreateDrawer()"
                                        class="bg-gradient-to-br from-primary to-primary-container text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-2xl shadow-[#ee9800]/30 active:scale-95 transition-transform">
                                        + Create first user
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-outline-variant/10 flex items-center justify-between">
                    <div class="text-xs text-on-surface-variant font-medium">
                        Showing <span class="text-on-surface">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
                        of <span class="text-on-surface">{{ $users->total() }}</span> results
                    </div>
                    <div class="flex gap-1">
                        @if(!$users->onFirstPage())
                            <a href="{{ $users->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded bg-surface-container-highest text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                        @endif
                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            <a href="{{ $url }}"
                                class="w-8 h-8 flex items-center justify-center rounded text-xs font-bold
                                                                                      {{ $page == $users->currentPage() ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant hover:text-on-surface' }}">
                                {{ $page }}
                            </a>
                        @endforeach
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded bg-surface-container-highest text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </section>

    </div>


    {{-- ══════════════════════════════════════════
    CREATE USER — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="create-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeCreateDrawer()">
        <div class="absolute inset-0 bg-surface-dim/80 backdrop-blur-md"></div>
        <div
            class="drawer-panel relative w-[480px] bg-surface-container-low shadow-[-20px_0px_60px_rgba(0,0,0,.5)] flex flex-col h-full border-l border-outline-variant/10">

            <header class="p-8 border-b border-outline-variant/10 flex justify-between items-start flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-on-background headline tracking-tight">Add New User</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Configure access and profile details for the team
                        member.</p>
                </div>
                <button onclick="closeCreateDrawer()"
                    class="p-2 hover:bg-surface-container-highest rounded-full transition-all text-on-surface-variant mt-1">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </header>

            <form id="create-form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="flex-1 overflow-y-auto p-8 space-y-10 custom-scrollbar">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="bg-error/10 border border-error/20 rounded-xl p-4">
                            @foreach($errors->all() as $error)
                                <p class="text-xs text-error flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    {{ $error }}
                                </p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Profile Identity --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">account_circle</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Profile Identity
                            </h3>
                        </div>
                        <div
                            class="flex items-center gap-6 p-6 bg-surface-container-lowest rounded-xl border border-outline-variant/5">
                            <div class="relative group cursor-pointer flex-shrink-0"
                                onclick="document.getElementById('create-avatar-input').click()">
                                <div
                                    class="w-20 h-20 rounded-full overflow-hidden border-2 border-outline-variant/20 relative">
                                    <div id="create-avatar-initials"
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/30 to-primary-container/50 text-on-primary text-xl font-bold">
                                        ?</div>
                                    <img id="create-avatar-preview"
                                        class="w-full h-full object-cover absolute inset-0 hidden" alt="preview" />
                                </div>
                                <div
                                    class="absolute inset-0 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/50 transition-opacity">
                                    <span class="material-symbols-outlined text-white">add_a_photo</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Upload Avatar</p>
                                <p class="text-xs text-on-surface-variant mt-1">JPG, PNG or WebP. Max size 2MB.</p>
                                <div class="mt-3 flex gap-3">
                                    <button type="button" onclick="document.getElementById('create-avatar-input').click()"
                                        class="text-[11px] font-bold uppercase tracking-wider text-primary hover:underline">Change</button>
                                    <button type="button" onclick="clearAvatar('create')"
                                        class="text-[11px] font-bold uppercase tracking-wider text-error opacity-70 hover:opacity-100 transition-opacity">Remove</button>
                                </div>
                            </div>
                            <input type="file" id="create-avatar-input" name="avatar" accept="image/*" class="hidden"
                                onchange="previewAvatar(this,'create')" />
                        </div>
                    </section>

                    {{-- Account Details --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">badge</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Account Details
                            </h3>
                        </div>
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Full
                                    Name <span class="text-error">*</span></label>
                                <input type="text" name="name" id="c-name" required
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none placeholder:text-on-surface-variant/40"
                                    placeholder="e.g. John Doe" oninput="updateInitials(this.value,'create')" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Email
                                    Address <span class="text-error">*</span></label>
                                <input type="email" name="email" required
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                    placeholder="j.doe@tectonic.com" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Password
                                        <span class="text-error">*</span></label>
                                    <input type="password" name="password" required
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                        placeholder="Min 6 characters" />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Confirm
                                        <span class="text-error">*</span></label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                        placeholder="Repeat password" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Phone
                                    Number</label>
                                <input type="text" name="phone"
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                    placeholder="e.g. +855-12-000-000" />
                            </div>
                        </div>
                    </section>

                    {{-- Permissions & Status --}}
                    <section class="space-y-4 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">security</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Permissions &
                                Status</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Assigned
                                    Role</label>
                                <div class="relative">
                                    <select name="role_id"
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer outline-none">
                                        <option value="">— Select role —</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Status</label>
                                <div class="relative">
                                    <select name="status"
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer outline-none">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <span
                                        class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

                <footer class="p-8 bg-surface-container-high border-t border-outline-variant/10 flex gap-4 flex-shrink-0">
                    <button type="button" onclick="closeCreateDrawer()"
                        class="flex-1 px-6 py-3 bg-surface-container-highest text-on-surface text-sm font-bold rounded-xl border border-outline-variant/15 hover:bg-surface-bright transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-[1.5] px-6 py-3 bg-gradient-to-br from-primary to-primary-container text-white text-sm font-bold rounded-xl shadow-2xl shadow-[#ee9800]/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Create Team Member
                    </button>
                </footer>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
    EDIT USER — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="edit-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeEditDrawer()">
        <div class="absolute inset-0 bg-surface-dim/80 backdrop-blur-md"></div>
        <div
            class="drawer-panel relative w-[480px] bg-surface-container-low shadow-[-20px_0px_60px_rgba(0,0,0,.5)] flex flex-col h-full border-l border-outline-variant/10">

            <header class="p-8 border-b border-outline-variant/10 flex justify-between items-start flex-shrink-0">
                <div>
                    <p class="text-[10px] uppercase tracking-widest font-bold text-primary mb-1">Editing User</p>
                    <h2 id="edit-drawer-title" class="text-2xl font-bold text-on-background headline tracking-tight">Edit
                        User</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Update user information and photo.</p>
                </div>
                <button onclick="closeEditDrawer()"
                    class="p-2 hover:bg-surface-container-highest rounded-full transition-all text-on-surface-variant mt-1">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </header>

            <form id="edit-form" method="POST" action="" enctype="multipart/form-data"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf @method('PUT')
                <div class="flex-1 overflow-y-auto p-8 space-y-10 custom-scrollbar">

                    {{-- Profile Identity --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">account_circle</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Profile Identity
                            </h3>
                        </div>
                        <div
                            class="flex items-center gap-6 p-6 bg-surface-container-lowest rounded-xl border border-outline-variant/5">
                            <div class="relative group cursor-pointer flex-shrink-0"
                                onclick="document.getElementById('edit-avatar-input').click()">
                                <div
                                    class="w-20 h-20 rounded-full overflow-hidden border-2 border-outline-variant/20 relative">
                                    <div id="edit-avatar-initials"
                                        class="w-full h-full hidden items-center justify-center bg-gradient-to-br from-primary/30 to-primary-container/50 text-on-primary text-xl font-bold">
                                    </div>
                                    <img id="edit-avatar-preview" class="w-full h-full object-cover" alt="preview" />
                                </div>
                                <div
                                    class="absolute inset-0 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/50 transition-opacity">
                                    <span class="material-symbols-outlined text-white">add_a_photo</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Change Photo</p>
                                <p class="text-xs text-on-surface-variant mt-1">JPG, PNG or WebP. Max size 2MB.</p>
                                <div class="mt-3">
                                    <button type="button" onclick="document.getElementById('edit-avatar-input').click()"
                                        class="text-[11px] font-bold uppercase tracking-wider text-primary hover:underline">Change</button>
                                </div>
                            </div>
                            <input type="file" id="edit-avatar-input" name="avatar" accept="image/*" class="hidden"
                                onchange="previewAvatar(this,'edit')" />
                        </div>
                    </section>

                    {{-- Account Details --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">badge</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Account Details
                            </h3>
                        </div>
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Full
                                        Name <span class="text-error">*</span></label>
                                    <input type="text" name="name" id="e-name" required
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none" />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Email
                                        <span class="text-error">*</span></label>
                                    <input type="email" name="email" id="e-email" required
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Phone</label>
                                <input type="text" name="phone" id="e-phone"
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                    placeholder="+855-12-000-000" />
                            </div>
                        </div>
                    </section>

                    {{-- Role & Status --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">security</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Permissions &
                                Status</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Assigned
                                    Role</label>
                                <div class="relative">
                                    <select name="role_id" id="e-role"
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer outline-none">
                                        <option value="">— No role —</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Status</label>
                                <div class="relative">
                                    <select name="status" id="e-status"
                                        class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer outline-none">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <span
                                        class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Change Password --}}
                    <section class="space-y-4 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary"
                                style="font-variation-settings:'FILL' 1;">lock</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">
                                Change Password
                                <span class="normal-case font-normal text-on-surface-variant/50 ml-1">(leave blank to keep
                                    current)</span>
                            </h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">New
                                    Password</label>
                                <input type="password" name="password" autocomplete="new-password"
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                    placeholder="Min 6 characters" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Confirm</label>
                                <input type="password" name="password_confirmation" autocomplete="new-password"
                                    class="w-full bg-surface-container-lowest border-none rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/50 outline-none"
                                    placeholder="Repeat password" />
                            </div>
                        </div>
                    </section>

                </div>

                <footer class="p-8 bg-surface-container-high border-t border-outline-variant/10 flex gap-4 flex-shrink-0">
                    <button type="button" onclick="closeEditDrawer()"
                        class="flex-1 px-6 py-3 bg-surface-container-highest text-on-surface text-sm font-bold rounded-xl border border-outline-variant/15 hover:bg-surface-bright transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-[1.5] px-6 py-3 bg-gradient-to-br from-primary to-primary-container text-white text-sm font-bold rounded-xl shadow-2xl shadow-[#ee9800]/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Save Changes
                    </button>
                </footer>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
    PROFILE VIEW — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="profile-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeProfileDrawer()">
        <div class="absolute inset-0 bg-surface-dim/80 backdrop-blur-md"></div>
        <div
            class="drawer-panel relative w-[420px] bg-surface-container-low shadow-[-20px_0px_60px_rgba(0,0,0,.5)] flex flex-col h-full border-l border-outline-variant/10">

            <header class="p-8 border-b border-outline-variant/10 flex-shrink-0">
                <div class="flex justify-between items-start mb-6">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-primary">User Profile</p>
                    <button onclick="closeProfileDrawer()"
                        class="p-2 hover:bg-surface-container-highest rounded-full transition-all text-on-surface-variant -mt-1">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-primary/30 flex-shrink-0 relative">
                        <div id="profile-av-initials"
                            class="w-full h-full hidden items-center justify-center bg-gradient-to-br from-primary/30 to-primary-container/50 text-on-primary text-xl font-bold">
                        </div>
                        <img id="profile-av-img" class="w-full h-full object-cover" alt="avatar" />
                    </div>
                    <div>
                        <h2 id="profile-name" class="text-xl font-bold headline text-on-surface"></h2>
                        <p id="profile-role-label" class="text-xs text-on-surface-variant mt-1"></p>
                    </div>
                </div>
            </header>

            <div id="profile-body" class="flex-1 overflow-y-auto p-8 custom-scrollbar"></div>

            <footer class="p-8 bg-surface-container-high border-t border-outline-variant/10 flex-shrink-0">
                <button onclick="closeProfileDrawer()"
                    class="w-full px-6 py-3 bg-surface-container-highest text-on-surface text-sm font-bold rounded-xl border border-outline-variant/15 hover:bg-surface-bright transition-all">
                    Close
                </button>
            </footer>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
    DELETE CONFIRM — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="delete-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeDeleteDrawer()">
        <div class="absolute inset-0 bg-surface-dim/80 backdrop-blur-md"></div>
        <div
            class="drawer-panel relative w-[400px] bg-surface-container-low shadow-[-20px_0px_60px_rgba(0,0,0,.5)] flex flex-col h-full border-l border-outline-variant/10">

            <header class="p-8 border-b border-outline-variant/10 flex justify-between items-center flex-shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-error/10 border border-error/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-error"
                            style="font-variation-settings:'FILL' 1;">delete</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold headline text-on-surface">Delete User</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">This action cannot be undone.</p>
                    </div>
                </div>
                <button onclick="closeDeleteDrawer()"
                    class="p-2 hover:bg-surface-container-highest rounded-full transition-all text-on-surface-variant">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </header>

            <div class="flex-1 p-8">
                <div class="p-6 bg-error/5 border border-error/15 rounded-xl">
                    <p class="text-sm text-on-surface leading-relaxed">
                        You are about to permanently delete
                        <strong id="delete-user-name" class="text-on-surface font-bold"></strong>.
                    </p>
                    <p class="text-xs text-error mt-3">Their account, photo, and all associated data will be permanently
                        removed.</p>
                </div>
            </div>

            <footer class="p-8 bg-surface-container-high border-t border-outline-variant/10 flex gap-4 flex-shrink-0">
                <button onclick="closeDeleteDrawer()"
                    class="flex-1 px-6 py-3 bg-surface-container-highest text-on-surface text-sm font-bold rounded-xl border border-outline-variant/15 hover:bg-surface-bright transition-all">
                    Cancel
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full px-6 py-3 bg-error/80 hover:bg-error text-on-surface text-sm font-bold rounded-xl transition-all active:scale-95">
                        Yes, delete
                    </button>
                </form>
            </footer>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // ── AVATAR HELPERS ─────────────────────────────────────────────
        function previewAvatar(input, prefix) {
            const file = input.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { toast('Photo must be under 2MB', 'danger'); return; }
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById(prefix + '-avatar-preview');
                const init = document.getElementById(prefix + '-avatar-initials');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (init) { init.classList.add('hidden'); init.style.display = ''; }
            };
            reader.readAsDataURL(file);
        }

        function clearAvatar(prefix) {
            const img = document.getElementById(prefix + '-avatar-preview');
            const init = document.getElementById(prefix + '-avatar-initials');
            img.src = ''; img.classList.add('hidden');
            if (init) { init.classList.remove('hidden'); init.style.display = 'flex'; }
            document.getElementById(prefix + '-avatar-input').value = '';
        }

        function updateInitials(name, prefix) {
            const init = document.getElementById(prefix + '-avatar-initials');
            if (init && !init.classList.contains('hidden')) {
                init.textContent = name.substring(0, 2).toUpperCase() || '?';
            }
        }

        // ── DRAWER CORE ────────────────────────────────────────────────
        function _open(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
        function _close(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }

        // ── CREATE ─────────────────────────────────────────────────────
        function openCreateDrawer() {
            document.getElementById('create-form').reset();
            clearAvatar('create');
            document.getElementById('create-avatar-initials').textContent = '?';
            document.getElementById('create-avatar-initials').style.display = 'flex';
            _open('create-drawer');
            setTimeout(() => document.getElementById('c-name').focus(), 300);
        }
        function closeCreateDrawer() { _close('create-drawer'); }

        // ── EDIT ───────────────────────────────────────────────────────
        function openEditDrawer(id, name, email, phone, status, role, avatarUrl) {
            document.getElementById('edit-drawer-title').textContent = name;
            document.getElementById('edit-form').action = '/users/' + id;
            document.getElementById('e-name').value = name;
            document.getElementById('e-email').value = email;
            document.getElementById('e-phone').value = phone || '';
            document.getElementById('e-status').value = status;

            const roleSelect = document.getElementById('e-role');
            const roleMap = @json($roles->pluck('name', 'id'));
            roleSelect.value = '';
            for (const [rid, rname] of Object.entries(roleMap)) {
                if (rname === role) { roleSelect.value = rid; break; }
            }

            const img = document.getElementById('edit-avatar-preview');
            const init = document.getElementById('edit-avatar-initials');
            if (avatarUrl) {
                img.src = avatarUrl; img.classList.remove('hidden');
                init.classList.add('hidden'); init.style.display = '';
            } else {
                img.src = ''; img.classList.add('hidden');
                init.textContent = name.substring(0, 2).toUpperCase();
                init.classList.remove('hidden'); init.style.display = 'flex';
            }

            _open('edit-drawer');
            setTimeout(() => document.getElementById('e-name').focus(), 300);
        }
        function closeEditDrawer() { _close('edit-drawer'); }

        // ── PROFILE ────────────────────────────────────────────────────
        function openProfileDrawer(id, name, email, role, phone, status, tasks, avatarUrl) {
            document.getElementById('profile-name').textContent = name;
            document.getElementById('profile-role-label').textContent =
                role.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) + ' · ' + status;

            const img = document.getElementById('profile-av-img');
            const init = document.getElementById('profile-av-initials');
            if (avatarUrl) {
                img.src = avatarUrl; img.classList.remove('hidden');
                init.classList.add('hidden'); init.style.display = '';
            } else {
                img.src = ''; img.classList.add('hidden');
                init.textContent = name.substring(0, 2).toUpperCase();
                init.classList.remove('hidden'); init.style.display = 'flex';
            }

            const sc = status === 'active' ? 'text-tertiary' : status === 'inactive' ? 'text-secondary' : 'text-error';
            document.getElementById('profile-body').innerHTML = `
                            <div class="space-y-1">
                            ${[
                    ['Email', `<span class="text-primary">${email}</span>`],
                    ['Phone', phone || '—'],
                    ['Role', role.replace(/_/g, ' ')],
                    ['Status', `<span class="${sc}">${status}</span>`],
                    ['Active Tasks', `${tasks} tasks assigned`],
                    ['Photo', avatarUrl ? '<span class="text-tertiary">✓ Uploaded</span>' : '<span class="opacity-40">No photo</span>'],
                ].map(([lbl, val]) => `
                                <div class="flex justify-between items-center py-3 border-b border-outline-variant/10">
                                    <span class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">${lbl}</span>
                                    <span class="text-sm text-on-surface">${val}</span>
                                </div>
                            `).join('')}
                            </div>`;

            _open('profile-drawer');
        }
        function closeProfileDrawer() { _close('profile-drawer'); }

        // ── DELETE ─────────────────────────────────────────────────────
        function confirmDeleteUser(id, name) {
            document.getElementById('delete-user-name').textContent = '"' + name + '"';
            document.getElementById('delete-form').action = '/users/' + id;
            _open('delete-drawer');
        }
        function closeDeleteDrawer() { _close('delete-drawer'); }

        // ── ESC ────────────────────────────────────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            closeCreateDrawer(); closeEditDrawer(); closeProfileDrawer(); closeDeleteDrawer();
        });
    </script>
@endsection