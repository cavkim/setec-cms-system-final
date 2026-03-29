<!-- <aside class="h-screen w-64 fixed left-0 top-0 flex flex-col bg-[#171f33] shadow-2xl z-50 overflow-y-auto">
   <div class="px-4 py-5 flex items-center justify-center border-b border-white/10">
    <img src="{{ asset('branding/logo.png') }}" alt="{{ config('app.name') }}" 
         class="object-contain">
</div>

    <nav class="flex flex-col h-full py-2 space-y-1">
        <a class="{{ request()->routeIs('dashboard') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">dashboard</span>
            <span class="text-[0.875rem]">Dashboard</span>
        </a>

        <a class="{{ request()->routeIs('projects.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('projects.index') }}">
            <span class="material-symbols-outlined">construction</span>
            <span class="text-[0.875rem]">Projects</span>
        </a>

        <a class="{{ request()->routeIs('tasks.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('tasks.index') }}">
            <span class="material-symbols-outlined">assignment</span>
            <span class="text-[0.875rem]">Tasks</span>
        </a>

        <a class="{{ request()->routeIs('team.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('team.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span class="text-[0.875rem]">Team</span>
        </a>

        <a class="{{ request()->routeIs('budget.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('budget.index') }}">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-[0.875rem]">Budget</span>
        </a>

        <a class="{{ request()->routeIs('documents.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('documents.index') }}">
            <span class="material-symbols-outlined">description</span>
            <span class="text-[0.875rem]">Documents</span>
        </a>

        <a class="{{ request()->routeIs('safety.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('safety.index') }}">
            <span class="material-symbols-outlined">engineering</span>
            <span class="text-[0.875rem]">Safety</span>
        </a>

        <a class="{{ request()->routeIs('reports.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('reports.index') }}">
            <span class="material-symbols-outlined">analytics</span>
            <span class="text-[0.875rem]">Reports</span>
        </a>

        @hasrole('super_admin|admin')
        <a class="{{ request()->routeIs('audit.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('audit.index') }}">
            <span class="material-symbols-outlined">history</span>
            <span class="text-[0.875rem]">Audit Log</span>
        </a>
        @endhasrole

        <div class="mt-auto px-4 py-4 border-t border-white/5">
            <button
                class="w-full bg-[#4d8eff] text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-transform active:scale-95"
                onclick="typeof openCreatePopup === 'function' ? openCreatePopup() : toast('New project form coming soon','info')">
                <span class="material-symbols-outlined">add</span>
                New Project
            </button>
        </div>

        <div class="mt-2">
            <a class="{{ request()->routeIs('notifications.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="{{ route('notifications.index') }}">
                <span class="material-symbols-outlined">notifications</span>
                <span class="text-[0.875rem]">Notifications</span>
            </a>
            <a class="text-slate-400 hover:bg-white/5 rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="#"
                onclick="toast('Support coming soon','info'); return false;">
                <span class="material-symbols-outlined">help</span>
                <span class="text-[0.875rem]">Support</span>
            </a>
            <a class="{{ request()->routeIs('profile.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-[0.875rem]">Settings</span>
            </a>
        </div>
    </nav>
</aside> -->

<aside id="sidebar"
    class="h-screen w-64 fixed left-0 top-0 flex flex-col bg-[#171f33] shadow-2xl z-50 overflow-y-auto overflow-x-hidden transition-all duration-300">

    {{-- Toggle button — floats on the right edge --}}
    <button id="sidebar-toggle" onclick="toggleSidebar()" class="absolute -right-3.5 top-1/4 -translate-y-1/2 z-100
               w-7 h-7 rounded-full bg-[#171f33] border border-[#424754]/50
               flex items-center justify-center
               text-slate-400 hover:text-white hover:border-[#adc6ff]/40
               shadow-md transition-all duration-200">
        <span class="material-symbols-outlined text-base leading-none transition-transform duration-300"
            id="toggle-icon">
            chevron_left
        </span>
    </button>

    <div class="px-4 py-5 flex items-center justify-center border-b border-white/10 min-h-[72px]">
        <!-- <img src="{{ asset('branding/logo.png') }}" alt="{{ config('app.name') }}"
             class="object-contain sidebar-logo transition-all duration-300"> -->
    </div>

    <nav class="flex flex-col h-full py-2 space-y-1">

        <a class="{{ request()->routeIs('dashboard') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('dashboard') }}" title="Dashboard">
            <span class="material-symbols-outlined flex-shrink-0"
                style="font-variation-settings:'FILL' 1;">dashboard</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Dashboard</span>
        </a>

        <a class="{{ request()->routeIs('projects.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('projects.index') }}" title="Projects">
            <span class="material-symbols-outlined flex-shrink-0">construction</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Projects</span>
        </a>

        <a class="{{ request()->routeIs('tasks.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('tasks.index') }}" title="Tasks">
            <span class="material-symbols-outlined flex-shrink-0">assignment</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Tasks</span>
        </a>

        <a class="{{ request()->routeIs('team.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('team.index') }}" title="Team">
            <span class="material-symbols-outlined flex-shrink-0">group</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Team</span>
        </a>
        @hasrole('super_admin|admin')
        <a class="{{ request()->routeIs('users.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('users.index') }}" title="User Management">
            <span class="material-symbols-outlined flex-shrink-0">manage_accounts</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Users</span>
        </a>
        @endhasrole

        <a class="{{ request()->routeIs('roles.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('roles.index') }}" title="Roles Management">
            <span class="material-symbols-outlined flex-shrink-0">security</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Roles</span>
        </a>

        <a class="{{ request()->routeIs('budget.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('budget.index') }}" title="Budget">
            <span class="material-symbols-outlined flex-shrink-0">payments</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Budget</span>
        </a>

        <a class="{{ request()->routeIs('documents.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('documents.index') }}" title="Documents">
            <span class="material-symbols-outlined flex-shrink-0">description</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Documents</span>
        </a>

        <a class="{{ request()->routeIs('safety.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('safety.index') }}" title="Safety">
            <span class="material-symbols-outlined flex-shrink-0">engineering</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Safety</span>
        </a>

        <a class="{{ request()->routeIs('reports.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('reports.index') }}" title="Reports">
            <span class="material-symbols-outlined flex-shrink-0">analytics</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Reports</span>
        </a>

        @hasrole('super_admin|admin')
        <a class="{{ request()->routeIs('audit.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
            href="{{ route('audit.index') }}" title="Audit Log">
            <span class="material-symbols-outlined flex-shrink-0">history</span>
            <span
                class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Audit
                Log</span>
        </a>
        @endhasrole

        <!-- <div class="mt-auto px-2 py-4 border-t border-white/5">
            <button
                class="w-full bg-[#4d8eff] text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-transform active:scale-95"
                onclick="typeof openCreatePopup === 'function' ? openCreatePopup() : toast('New project form coming soon','info')"
                title="New Project">
                <span class="material-symbols-outlined flex-shrink-0">add</span>
                <span class="sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">New Project</span>
            </button>
        </div> -->

        <div>
            <a class="{{ request()->routeIs('notifications.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="{{ route('notifications.index') }}" title="Notifications">
                <span class="material-symbols-outlined flex-shrink-0">notifications</span>
                <span
                    class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Notifications</span>
            </a>
            <a class="text-slate-400 hover:bg-white/5 rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="#" onclick="toast('Support coming soon','info'); return false;" title="Support">
                <span class="material-symbols-outlined flex-shrink-0">help</span>
                <span
                    class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Support</span>
            </a>
            <a class="{{ request()->routeIs('profile.*') ? 'bg-[#2d3449] text-blue-400 font-semibold' : 'text-slate-400 hover:bg-white/5' }} rounded-lg mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-colors"
                href="{{ route('profile.edit') }}" title="Settings">
                <span class="material-symbols-outlined flex-shrink-0">settings</span>
                <span
                    class="text-[0.875rem] sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">Settings</span>
            </a>
        </div>

    </nav>
</aside>

<script>
    (function () {
        const KEY = 'sidebar-collapsed';

        function applyState(collapsed, animate) {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main-content');
            const labels = sidebar.querySelectorAll('.sidebar-label');
            const logo = sidebar.querySelector('.sidebar-logo');
            const icon = document.getElementById('toggle-icon');

            if (!animate) {
                sidebar.style.transition = 'none';
                if (main) main.style.transition = 'none';
            }

            if (collapsed) {
                sidebar.classList.replace('w-64', 'w-16');
                if (main) {
                    main.classList.replace('ml-64', 'ml-16');
                    main.classList.remove('w-[calc(100%-16rem)]');
                    main.classList.add('w-[calc(100%-4rem)]');
                }
                labels.forEach(l => { l.style.width = '0'; l.style.opacity = '0'; });
                if (logo) { logo.style.width = '0'; logo.style.opacity = '0'; }
                if (icon) icon.textContent = 'chevron_right';
            } else {
                sidebar.classList.replace('w-16', 'w-64');
                if (main) {
                    main.classList.replace('ml-16', 'ml-64');
                    main.classList.remove('w-[calc(100%-4rem)]');
                    main.classList.add('w-[calc(100%-16rem)]');
                }
                labels.forEach(l => { l.style.width = ''; l.style.opacity = '1'; });
                if (logo) { logo.style.width = ''; logo.style.opacity = '1'; }
                if (icon) icon.textContent = 'chevron_left';
            }

            if (!animate) {
                requestAnimationFrame(() => {
                    sidebar.style.transition = '';
                    if (main) main.style.transition = '';
                });
            }
        }

        window.toggleSidebar = function () {
            const collapsed = !document.getElementById('sidebar').classList.contains('w-16');
            localStorage.setItem(KEY, collapsed);
            applyState(collapsed, true);
        };

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem(KEY) === 'true') applyState(true, false);
        });
    })();
</script>