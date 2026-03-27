<header class="flex justify-between items-center w-full sticky top-0 z-40 py-2 bg-[#0b1326] -mx-10 px-10 mb-2">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-white">@yield('page-title', 'Dashboard')</h1>
        <p class="text-slate-300 text-sm mt-1">Operational Overview for Q{{ ceil(now()->month / 3) }} Fiscal Period</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 bg-[#171f33] rounded-xl px-4 py-2 border border-white/5">
            <span class="material-symbols-outlined text-slate-400 text-lg">search</span>
            <input class="bg-transparent border-none text-sm text-white focus:ring-0 w-48 outline-none" placeholder="Search projects..."
                type="text" />
        </div>

        <div class="flex items-center gap-3">
            <button
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#222a3d] text-[#dae2fd] hover:bg-[#2d3449] transition-colors"
                onclick="toast('Notifications panel coming soon','info')">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#222a3d] text-[#dae2fd] hover:bg-[#2d3449] transition-colors"
                onclick="toast('Settings panel coming soon','info')">
                <span class="material-symbols-outlined">settings</span>
            </button>

            {{-- User Avatar → triggers slide-over drawer --}}
            <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-[#adc6ff]/20 cursor-pointer hover:border-[#adc6ff]/50 transition-all"
                onclick="openUserDrawer()" title="My Account">
                <div class="w-full h-full bg-gradient-to-br from-[#1565C0] to-[#42A5F5] text-white text-[11px] font-bold flex items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════════ --}}
{{--  USER PROFILE DRAWER (triggered by avatar click)           --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[80] transition-opacity duration-300 opacity-0 pointer-events-none"
    id="user-drawer-backdrop" onclick="closeUserDrawer()"></div>

<div class="fixed top-0 right-0 h-full w-full max-w-sm bg-[#0b1326] border-l border-[#424754]/30 shadow-2xl z-[90]
            flex flex-col transform translate-x-full transition-transform duration-400 ease-in-out"
    id="user-drawer">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-[#424754]/20 bg-[#171f33] flex items-center justify-between">
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#adc6ff]">My Account</span>
        <button class="p-1.5 hover:bg-[#2d3449] rounded-full text-[#c2c6d6] transition-colors" onclick="closeUserDrawer()">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    {{-- Profile info --}}
    <div class="px-6 py-6 border-b border-[#424754]/10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1565C0] to-[#42A5F5] flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-900/30">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-base font-headline font-bold text-[#dae2fd] leading-tight">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-[#c2c6d6] mt-0.5">{{ auth()->user()->email }}</p>
                @php $roleLabel = auth()->user()->getRoleNames()->first() ?? 'user'; @endphp
                <span class="inline-block mt-1.5 px-2 py-0.5 bg-[#adc6ff]/10 text-[#adc6ff] text-[9px] rounded font-bold uppercase tracking-widest ring-1 ring-[#adc6ff]/20">
                    {{ $roleLabel }}
                </span>
            </div>
        </div>
    </div>

    {{-- Permissions --}}
    <div class="px-6 py-5 border-b border-[#424754]/10 flex-1 overflow-y-auto" style="scrollbar-width:thin">
        <p class="text-[9px] text-[#8c909f] uppercase font-bold tracking-[0.15em] mb-3">Your Permissions</p>
        <div class="grid grid-cols-2 gap-2">
            @foreach(auth()->user()->getAllPermissions()->pluck('name') as $perm)
            <div class="flex items-center gap-2 bg-[#131b2e] rounded-lg px-3 py-2 border border-[#424754]/10">
                <span class="w-1.5 h-1.5 rounded-full bg-[#adc6ff] flex-shrink-0"></span>
                <span class="text-[9px] text-[#c2c6d6] font-medium truncate">{{ $perm }}</span>
            </div>
            @endforeach

            @if(auth()->user()->getAllPermissions()->isEmpty())
            <div class="col-span-2 text-[10px] text-[#8c909f] italic">No specific permissions assigned.</div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="px-6 py-5 space-y-2 border-t border-[#424754]/10">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-[#171f33] hover:bg-[#222a3d] text-[#dae2fd] transition-colors">
            <span class="material-symbols-outlined text-[#adc6ff] text-lg">manage_accounts</span>
            <div>
                <p class="text-sm font-semibold leading-none">Edit Profile</p>
                <p class="text-[10px] text-[#8c909f] mt-0.5">Update name, email, password</p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-[#171f33] hover:bg-[#93000a]/20 text-[#ffb4ab] hover:text-[#ffdad6] transition-colors text-left">
                <span class="material-symbols-outlined text-lg">logout</span>
                <div>
                    <p class="text-sm font-semibold leading-none">Sign Out</p>
                    <p class="text-[10px] text-[#ffb4ab]/60 mt-0.5">End your current session</p>
                </div>
            </button>
        </form>
    </div>
</div>

<script>
function openUserDrawer() {
    document.getElementById('user-drawer-backdrop').classList.remove('opacity-0','pointer-events-none');
    document.getElementById('user-drawer-backdrop').classList.add('opacity-100');
    document.getElementById('user-drawer').style.transform = 'translateX(0)';
}
function closeUserDrawer() {
    document.getElementById('user-drawer-backdrop').classList.add('opacity-0','pointer-events-none');
    document.getElementById('user-drawer-backdrop').classList.remove('opacity-100');
    document.getElementById('user-drawer').style.transform = 'translateX(100%)';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeUserDrawer();
});
</script>
