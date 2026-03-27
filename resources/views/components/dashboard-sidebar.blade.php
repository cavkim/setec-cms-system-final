<aside class="h-screen w-64 fixed left-0 top-0 flex flex-col bg-[#171f33] shadow-2xl z-50 overflow-y-auto">
    <div class="px-6 py-8 flex flex-col items-start gap-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#4d8eff] flex items-center justify-center rounded-xl">
                <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1;">construction</span>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-widest">Project Alpha</h2>
                <p class="text-xs text-slate-400 uppercase tracking-tighter">Site 402-B</p>
            </div>
        </div>
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
</aside>

