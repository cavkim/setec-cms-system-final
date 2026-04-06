{{-- resources/views/roles/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Role Management — IRONCLAD FORGE')
@section('page-title', 'Role Management')

@section('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap"
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
                        "background": "#0b1326",
                        "surface": "#0b1326",
                        "secondary": "#ffb95f",
                        "outline": "#8c909f",
                        "surface-container-lowest": "#060e20",
                        "outline-variant": "#424754",
                        "surface-variant": "#2d3449",
                        "primary": "#3B82F6",
                        "surface-container": "#171f33",
                        "on-surface": "#dae2fd",
                        "error": "#ffb4ab",
                        "surface-container-high": "#222a3d",
                        "surface-bright": "#31394d",
                        "on-surface-variant": "#c2c6d6",
                        "surface-dim": "#0b1326",
                        "primary-container": "#1e3a8a",
                        "on-background": "#dae2fd",
                        "surface-container-highest": "#2d3449",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#dbeafe",
                        "tertiary-container": "#8392a6",
                        "tertiary": "#b9c8de",
                        "surface-container-low": "#131b2e",
                        "secondary-container": "#ee9800",
                        "error-container": "#93000a",
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
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

        .headline {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #171f33;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #424754;
            border-radius: 10px;
        }

        /* Permission Matrix Scroll */
        .perm-matrix-container {
            max-height: 600px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .perm-matrix-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .perm-matrix-container::-webkit-scrollbar-track {
            background: #0b1326;
            border-radius: 10px;
        }

        .perm-matrix-container::-webkit-scrollbar-thumb {
            background: #424754;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .perm-matrix-container::-webkit-scrollbar-thumb:hover {
            background: #5a6178;
        }

        .perm-matrix-container::-webkit-scrollbar-corner {
            background: #0b1326;
        }

        .perm-matrix-table thead tr {
            position: sticky;
            top: 0;
            z-index: 20;
        }

        /* Role List Scroll */
        .role-list-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .role-list-container::-webkit-scrollbar {
            width: 6px;
        }

        .role-list-container::-webkit-scrollbar-track {
            background: #0b1326;
            border-radius: 10px;
        }

        .role-list-container::-webkit-scrollbar-thumb {
            background: #424754;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .role-list-container::-webkit-scrollbar-thumb:hover {
            background: #5a6178;
        }

        /* Drawer animation */
        #create-role-drawer,
        #edit-role-drawer {
            transition: opacity 0.25s ease;
        }

        #create-role-drawer .drawer-panel,
        #edit-role-drawer .drawer-panel {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(100%);
        }

        #create-role-drawer.show,
        #edit-role-drawer.show {
            opacity: 1;
            pointer-events: all;
        }

        #create-role-drawer.show .drawer-panel,
        #edit-role-drawer.show .drawer-panel {
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

    <div class="p-10 space-y-10">

        {{-- ── KPI SECTION ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-surface-container-high rounded-xl p-6 shadow-[0px_8px_24px_rgba(6,14,32,0.2)]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-on-surface-variant font-label uppercase text-[10px] tracking-widest">Total
                        Roles</span>
                    <span class="material-symbols-outlined text-primary">groups</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold headline text-on-surface">{{ $stats['total_roles'] }}</span>
                    <span class="text-xs text-outline font-medium">System roles</span>
                </div>
            </div>

            <div class="bg-surface-container-high rounded-xl p-6 shadow-[0px_8px_24px_rgba(6,14,32,0.2)]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-on-surface-variant font-label uppercase text-[10px] tracking-widest">Active
                        Permissions</span>
                    <span class="material-symbols-outlined text-primary">key</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold headline text-on-surface">{{ $stats['total_permissions'] }}</span>
                    <span class="text-xs text-outline font-medium">Across all modules</span>
                </div>
            </div>

            <div class="bg-surface-container-high rounded-xl p-6 shadow-[0px_8px_24px_rgba(6,14,32,0.2)]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-on-surface-variant font-label uppercase text-[10px] tracking-widest">Total
                        Users</span>
                    <span class="material-symbols-outlined text-primary">person</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold headline text-on-surface">{{ $stats['total_users'] }}</span>
                    <span class="text-xs text-tertiary font-medium">Across all roles</span>
                </div>
            </div>

            <div class="bg-surface-container-high rounded-xl p-6 shadow-[0px_8px_24px_rgba(6,14,32,0.2)]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-on-surface-variant font-label uppercase text-[10px] tracking-widest">Custom
                        Roles</span>
                    <span class="material-symbols-outlined text-primary">tune</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold headline text-on-surface">{{ $stats['custom_roles'] }}</span>
                    <span class="text-xs text-outline font-medium">User defined</span>
                </div>
            </div>

        </div>

        {{-- ── MAIN LAYOUT ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.6fr] gap-8">

            {{-- LEFT: All Roles List --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-primary text-xs font-bold uppercase tracking-[0.2em] mb-1">Security Administration
                        </p>
                        <h1 class="text-3xl font-extrabold headline tracking-tight text-on-surface">Role Management</h1>
                    </div>
                    <button onclick="openCreateRoleDrawer()"
                        class="bg-gradient-to-br from-primary to-blue-700 text-on-primary px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:brightness-110 active:scale-95 transition-all text-sm">
                        <span class="material-symbols-outlined text-base"
                            style="font-variation-settings:'FILL' 1;">add</span>
                        Create Role
                    </button>
                </div>

                <div class="role-list-container space-y-4 pr-2">
                    @php
                        $roleColors = [
                            'super_admin' => ['badge' => 'bg-primary/10 text-primary', 'border' => 'border-primary', 'icon' => 'verified', 'type' => 'System'],
                            'admin' => ['badge' => 'bg-red-500/10 text-red-400', 'border' => 'border-red-500/60', 'icon' => 'shield_person', 'type' => 'System'],
                            'project_manager' => ['badge' => 'bg-blue-500/10 text-blue-400', 'border' => 'border-transparent', 'icon' => 'engineering', 'type' => 'Standard'],
                            'site_supervisor' => ['badge' => 'bg-teal-500/10 text-teal-400', 'border' => 'border-transparent', 'icon' => 'construction', 'type' => 'Standard'],
                            'team_member' => ['badge' => 'bg-secondary/10 text-secondary', 'border' => 'border-transparent', 'icon' => 'group', 'type' => 'Standard'],
                            'client' => ['badge' => 'bg-surface-variant text-outline', 'border' => 'border-transparent', 'icon' => 'person', 'type' => 'Guest'],
                        ];
                        $builtIn = ['super_admin', 'admin', 'project_manager', 'site_supervisor', 'team_member', 'client'];
                    @endphp

                    @foreach($roles as $role)
                        @php
                            $rc = $roleColors[$role->name] ?? ['badge' => 'bg-surface-variant text-outline', 'border' => 'border-transparent', 'icon' => 'badge', 'type' => 'Custom'];
                            $isBuiltIn = in_array($role->name, $builtIn);
                        @endphp

                        <div
                            class="bg-surface-container rounded-xl p-5 hover:bg-surface-container-highest transition-colors cursor-pointer border-l-4 {{ $rc['border'] }}">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="material-symbols-outlined {{ str_contains($rc['badge'], 'primary') ? 'text-primary' : 'text-on-surface-variant' }} p-2 bg-surface-container-highest rounded-lg text-base"
                                        style="font-variation-settings:'FILL' 1;">{{ $rc['icon'] }}</span>
                                    <div>
                                        <h3 class="font-bold text-on-surface headline text-sm">
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </h3>
                                        <p class="text-[10px] text-on-surface-variant">{{ $role->users_count }} personnel</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="{{ $rc['badge'] }} px-2 py-1 rounded text-[10px] font-bold uppercase tracking-tighter">
                                        {{ $isBuiltIn ? $rc['type'] : 'Custom' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Permission tags --}}
                            <div class="flex flex-wrap gap-1 mb-3">
                                @forelse($role->permissions->take(5) as $perm)
                                    <span
                                        class="bg-surface-container-highest px-2 py-0.5 rounded-lg text-[10px] text-outline">{{ $perm->name }}</span>
                                @empty
                                    <span class="text-[10px] text-on-surface-variant italic">No permissions assigned</span>
                                @endforelse
                                @if($role->permissions->count() > 5)
                                    <span class="bg-surface-container-highest px-2 py-0.5 rounded-lg text-[10px] text-outline/60">
                                        +{{ $role->permissions->count() - 5 }} more
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 justify-end">
                                <button
                                    onclick="openEditRoleDrawer({{ $role->id }}, '{{ $role->name }}', {{ json_encode($role->permissions->pluck('name')) }})"
                                    class="text-[10px] px-3 py-1.5 rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 transition-colors font-semibold font-label uppercase tracking-widest">
                                    Edit
                                </button>
                                @if(!$isBuiltIn)
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline"
                                        onsubmit="return confirm('Delete role {{ $role->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-[10px] px-3 py-1.5 rounded-lg bg-error/10 text-error border border-error/20 hover:bg-error/20 transition-colors font-semibold font-label uppercase tracking-widest">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- RIGHT: Permission Matrix --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold headline text-on-surface">Permission Matrix</h2>
                    <div class="flex gap-2">
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                            <input id="perm-matrix-search"
                                class="bg-surface-container-lowest border-none rounded-lg pl-10 pr-4 py-2 text-xs focus:ring-1 focus:ring-primary w-48 text-on-surface placeholder:text-outline outline-none"
                                placeholder="Search module..." type="text" oninput="filterMatrix(this.value)" />
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container rounded-xl overflow-hidden shadow-xl">
                    <div class="perm-matrix-container">
                        <table class="w-full text-left border-collapse perm-matrix-table" id="perm-matrix-table">
                            <thead>
                                <tr class="bg-surface-container-high">
                                    <th
                                        class="p-4 font-label uppercase text-[10px] tracking-widest text-on-surface-variant border-b border-surface-variant sticky left-0 bg-surface-container-high z-10">
                                        Module / Permission
                                    </th>
                                    @foreach($roles->take(6) as $r)
                                        <th
                                            class="p-4 font-label uppercase text-[10px] tracking-widest text-on-surface-variant border-b border-surface-variant text-center whitespace-nowrap">
                                            {{ strtoupper(str_replace('_', ' ', $r->name)) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-variant/30">
                                @foreach($allPermissions as $module => $perms)
                                    <tr class="bg-surface-container-low/50 matrix-module-row"
                                        data-module="{{ strtolower($module) }}">
                                        <td class="px-4 py-2 text-[9px] font-bold uppercase text-primary tracking-widest sticky left-0"
                                            colspan="{{ $roles->take(6)->count() + 1 }}">
                                            Module: {{ ucfirst($module) }}
                                        </td>
                                    </tr>
                                    @foreach($perms as $perm)
                                        <tr class="hover:bg-surface-container-high/50 transition-colors matrix-perm-row"
                                            data-module="{{ strtolower($module) }}">
                                            <td class="p-4 text-sm text-on-surface font-medium sticky left-0 bg-surface-container">
                                                {{ $perm->name }}
                                            </td>
                                            @foreach($roles->take(6) as $r)
                                                <td class="p-4 text-center">
                                                    @if($r->hasPermissionTo($perm->name))
                                                        <span class="material-symbols-outlined text-primary text-base"
                                                            style="font-variation-settings:'FILL' 1;">check_circle</span>
                                                    @else
                                                        <span
                                                            class="material-symbols-outlined text-outline/30 text-base">do_not_disturb_on</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 bg-surface-container-highest flex items-center justify-between">
                        <p class="text-[10px] text-outline italic">{{ $stats['total_permissions'] }} permissions across
                            {{ $allPermissions->count() }} modules</p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-primary/20 flex items-center justify-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                </span>
                                <span class="text-[10px] text-on-surface-variant font-medium">Permitted</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-outline/20 flex items-center justify-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline/50"></span>
                                </span>
                                <span class="text-[10px] text-on-surface-variant font-medium">Denied</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>


    {{-- ══════════════════════════════════════════
    CREATE ROLE — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="create-role-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeCreateRoleDrawer()">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-background/80 backdrop-blur-md"></div>

        {{-- Drawer Panel --}}
        <div
            class="drawer-panel relative w-full max-w-2xl bg-surface-container-high shadow-2xl flex flex-col h-full border-l border-outline-variant/20">

            {{-- Drawer Header --}}
            <div class="px-8 py-8 border-b border-outline-variant/10 bg-surface-container-highest/30 flex-shrink-0">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-3xl font-extrabold headline text-on-surface tracking-tight">Create New Role</h2>
                        <p class="text-on-surface-variant mt-1">Define system access levels and operational constraints.</p>
                    </div>
                    <button onclick="closeCreateRoleDrawer()"
                        class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-error-container/20 text-on-surface-variant hover:text-error transition-all">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Role identity inside header --}}
                <form id="create-role-form" method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <label class="text-[10px] uppercase tracking-widest font-label text-primary font-bold">Role
                                Identity</label>
                            <span class="text-[10px] text-on-surface-variant/50 font-mono">
                                Preview: <span id="create-role-preview" class="text-secondary italic">—</span>
                            </span>
                        </div>
                        <input type="text" name="name" id="create-role-name" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/20 focus:border-primary/50 focus:ring-4 focus:ring-primary/10 rounded-xl px-4 py-4 text-on-surface placeholder:text-on-surface-variant/30 transition-all outline-none font-medium"
                            placeholder="e.g. Regional Security Analyst" />
                    </div>
            </div>

            {{-- Drawer Body --}}
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <section class="space-y-6">
                    <div class="flex justify-between items-center border-b border-outline-variant/10 pb-4">
                        <label class="text-[10px] uppercase tracking-widest font-label text-primary font-bold">Assign
                            Permissions</label>
                        <div class="flex gap-6">
                            <button type="button" onclick="checkAll(true)"
                                class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">done_all</span> Check all
                            </button>
                            <button type="button" onclick="checkAll(false)"
                                class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant hover:text-error transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">close</span> Uncheck all
                            </button>
                        </div>
                    </div>

                    {{-- Permission Module Cards Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($allPermissions as $module => $perms)
                            <div
                                class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/10 hover:border-primary/30 transition-all">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="material-symbols-outlined text-primary p-2 bg-primary/10 rounded-lg text-base">lock</span>
                                        <h4 class="font-bold text-sm headline">{{ ucfirst($module) }}</h4>
                                    </div>
                                    <button type="button" onclick="checkGroup('{{ $module }}', true)"
                                        class="text-[9px] uppercase font-bold text-primary/70 hover:text-primary font-label">All</button>
                                </div>
                                <div class="space-y-4">
                                    @foreach($perms as $perm)
                                        <label class="flex items-center gap-3 cursor-pointer group/item">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                id="cp-{{ $perm->id }}" data-group="{{ $module }}"
                                                class="create-perm-cb w-4 h-4 rounded border-outline-variant/30 bg-surface-container-lowest text-primary focus:ring-primary/20 transition-all accent-primary" />
                                            <span
                                                class="text-xs text-on-surface-variant group-hover/item:text-on-surface">{{ $perm->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Drawer Footer --}}
            <div
                class="px-8 py-6 border-t border-outline-variant/10 flex items-center justify-end gap-6 bg-surface-container-highest/50 flex-shrink-0">
                <button type="button" onclick="closeCreateRoleDrawer()"
                    class="px-6 py-3 rounded-xl text-on-surface-variant hover:text-on-surface font-bold text-sm transition-all uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-gradient-to-br from-primary to-blue-700 text-on-primary px-10 py-3 rounded-xl font-bold text-sm shadow-[0_0_20px_rgba(59,130,246,0.25)] hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">
                    Create Role
                </button>
            </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
    EDIT ROLE — SLIDE-OVER DRAWER
    ════════════════════════════════════════════ --}}
    <div id="edit-role-drawer" class="fixed inset-0 z-[60] flex justify-end opacity-0 pointer-events-none"
        onclick="if(event.target===this)closeEditRoleDrawer()">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-background/80 backdrop-blur-md"></div>

        {{-- Drawer Panel --}}
        <div
            class="drawer-panel relative w-full max-w-2xl bg-surface-container-high shadow-2xl flex flex-col h-full border-l border-outline-variant/20">

            {{-- Drawer Header --}}
            <div class="px-8 py-8 border-b border-outline-variant/10 bg-surface-container-highest/30 flex-shrink-0">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest font-label text-primary font-bold mb-1">Editing Role
                        </p>
                        <h2 id="edit-role-title" class="text-3xl font-extrabold headline text-on-surface tracking-tight">
                            Edit Role</h2>
                        <p class="text-on-surface-variant mt-1">Update which permissions this role has.</p>
                    </div>
                    <button onclick="closeEditRoleDrawer()"
                        class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-error-container/20 text-on-surface-variant hover:text-error transition-all">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>

            <form id="edit-role-form" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
                @csrf @method('PUT')

                {{-- Drawer Body --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <section class="space-y-6">
                        <div class="flex justify-between items-center border-b border-outline-variant/10 pb-4">
                            <label
                                class="text-[10px] uppercase tracking-widest font-label text-primary font-bold">Permissions</label>
                            <div class="flex gap-6">
                                <button type="button" onclick="checkAllEdit(true)"
                                    class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">done_all</span> Check all
                                </button>
                                <button type="button" onclick="checkAllEdit(false)"
                                    class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant hover:text-error transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">close</span> Uncheck all
                                </button>
                            </div>
                        </div>

                        {{-- Permission Module Cards Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($allPermissions as $module => $perms)
                                <div
                                    class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/10 hover:border-primary/30 transition-all">
                                    <div class="flex items-center justify-between mb-5">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="material-symbols-outlined text-primary p-2 bg-primary/10 rounded-lg text-base">lock</span>
                                            <h4 class="font-bold text-sm headline">{{ ucfirst($module) }}</h4>
                                        </div>
                                        <button type="button" onclick="checkGroupEdit('{{ $module }}', true)"
                                            class="text-[9px] uppercase font-bold text-primary/70 hover:text-primary font-label">All</button>
                                    </div>
                                    <div class="space-y-4">
                                        @foreach($perms as $perm)
                                            <label class="flex items-center gap-3 cursor-pointer group/item">
                                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                    id="ep-{{ $perm->id }}" data-group="{{ $module }}"
                                                    class="edit-perm-cb w-4 h-4 rounded border-outline-variant/30 bg-surface-container-lowest text-primary focus:ring-primary/20 transition-all accent-primary" />
                                                <span
                                                    class="text-xs text-on-surface-variant group-hover/item:text-on-surface">{{ $perm->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Drawer Footer --}}
                <div
                    class="px-8 py-6 border-t border-outline-variant/10 flex items-center justify-end gap-6 bg-surface-container-highest/50 flex-shrink-0">
                    <button type="button" onclick="closeEditRoleDrawer()"
                        class="px-6 py-3 rounded-xl text-on-surface-variant hover:text-on-surface font-bold text-sm transition-all uppercase tracking-widest">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-gradient-to-br from-primary to-blue-700 text-on-primary px-10 py-3 rounded-xl font-bold text-sm shadow-[0_0_20px_rgba(59,130,246,0.25)] hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">
                        Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // ── ROLE NAME PREVIEW (create drawer) ────────────────────────
        document.getElementById('create-role-name')?.addEventListener('input', function () {
            const slug = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
            document.getElementById('create-role-preview').textContent = slug || '—';
        });

        // ── CREATE DRAWER ─────────────────────────────────────────────
        function openCreateRoleDrawer() {
            document.querySelectorAll('.create-perm-cb').forEach(cb => cb.checked = false);
            document.getElementById('create-role-name').value = '';
            document.getElementById('create-role-preview').textContent = '—';
            document.getElementById('create-role-drawer').classList.add('show');
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('create-role-name').focus(), 300);
        }
        function closeCreateRoleDrawer() {
            document.getElementById('create-role-drawer').classList.remove('show');
            document.body.style.overflow = '';
        }

        function checkAll(val) {
            document.querySelectorAll('.create-perm-cb').forEach(cb => cb.checked = val);
        }
        function checkGroup(group, val) {
            document.querySelectorAll(`.create-perm-cb[data-group="${group}"]`).forEach(cb => cb.checked = val);
        }

        // ── EDIT DRAWER ───────────────────────────────────────────────
        function openEditRoleDrawer(id, name, currentPerms) {
            document.getElementById('edit-role-title').textContent = name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            document.getElementById('edit-role-form').action = '/roles/' + id;

            document.querySelectorAll('.edit-perm-cb').forEach(cb => {
                cb.checked = currentPerms.includes(cb.value);
            });

            document.getElementById('edit-role-drawer').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeEditRoleDrawer() {
            document.getElementById('edit-role-drawer').classList.remove('show');
            document.body.style.overflow = '';
        }

        function checkAllEdit(val) {
            document.querySelectorAll('.edit-perm-cb').forEach(cb => cb.checked = val);
        }
        function checkGroupEdit(group, val) {
            document.querySelectorAll(`.edit-perm-cb[data-group="${group}"]`).forEach(cb => cb.checked = val);
        }

        // ── PERMISSION MATRIX SEARCH ──────────────────────────────────
        function filterMatrix(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.matrix-module-row, .matrix-perm-row').forEach(row => {
                if (!q) { row.style.display = ''; return; }
                const module = row.dataset.module || '';
                const text = row.textContent.toLowerCase();
                row.style.display = (module.includes(q) || text.includes(q)) ? '' : 'none';
            });
        }

        // ── KEYBOARD ESCAPE ────────────────────────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeCreateRoleDrawer();
                closeEditRoleDrawer();
            }
        });
    </script>
@endsection
