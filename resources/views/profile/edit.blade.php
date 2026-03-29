@extends('layouts.app')
@section('title', 'Edit Profile — CMS')
@section('page-title', 'Profile')

@section('styles')
<style>
    .profile-drawer-backdrop {
        background: rgba(6, 14, 32, 0.75);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .glass-drawer {
        background: rgba(23, 31, 51, 0.92);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }
    .toggle-btn {
        width: 48px; height: 26px;
        border-radius: 999px;
        position: relative;
        transition: background 0.25s;
        cursor: pointer;
        border: none;
        outline: none;
    }
    .toggle-btn .knob {
        position: absolute;
        top: 3px;
        width: 20px; height: 20px;
        border-radius: 50%;
        background: #fff;
        transition: left 0.25s, background 0.25s;
    }
    .toggle-btn.on  { background: #4d8eff; }
    .toggle-btn.off { background: #424754; }
    .toggle-btn.on  .knob { left: calc(100% - 23px); }
    .toggle-btn.off .knob { left: 3px; }
</style>
@endsection

@section('content')

{{-- Success flash --}}
@if(session('status') === 'profile-updated')
<script>document.addEventListener('DOMContentLoaded', () => toast('Profile updated successfully', 'success'));</script>
@endif

{{-- ── Backdrop ── --}}
<div id="profile-backdrop"
     class="profile-drawer-backdrop fixed inset-0 z-[70] transition-opacity duration-300 opacity-0 pointer-events-none"
     onclick="closeProfileDrawer()"></div>

{{-- ── Drawer Panel ── --}}
<aside id="profile-drawer"
       class="glass-drawer fixed top-0 right-0 h-full w-full max-w-lg border-l border-[#424754]/20
              shadow-[0px_8px_40px_rgba(6,14,32,0.6)] z-[80] flex flex-col
              transform translate-x-full transition-transform duration-400 ease-in-out">

    {{-- Header --}}
    <header class="px-6 py-5 flex items-center justify-between border-b border-[#424754]/15 bg-[#131b2e]/60">
        <h2 class="font-headline text-xl font-bold tracking-tight text-white">Edit Profile</h2>
        <button onclick="closeProfileDrawer()"
                class="p-2 rounded-xl hover:bg-[#2d3449] transition-colors group"
                aria-label="Close">
            <span class="material-symbols-outlined text-[#c2c6d6] group-hover:text-white text-lg">close</span>
        </button>
    </header>

    {{-- Scrollable body --}}
    <div class="flex-1 overflow-y-auto px-6 pb-10" style="scrollbar-width: thin;">

        {{-- Avatar + name --}}
        <section class="py-8 flex flex-col items-center">
            <div class="relative group">
                <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-[#2d3449] shadow-xl">
                    <div class="w-full h-full bg-gradient-to-br from-[#1565C0] to-[#4d8eff]
                                flex items-center justify-center text-white text-4xl font-black select-none">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 bg-[#4d8eff] p-2 rounded-full shadow-lg
                            border-2 border-[#0b1326] hover:scale-105 transition-transform cursor-pointer">
                    <span class="material-symbols-outlined text-[#002e6a] text-base leading-none">edit</span>
                </div>
            </div>
            <div class="mt-4 text-center">
                <h3 class="font-headline text-lg font-bold text-white tracking-tight">{{ $user->name }}</h3>
                <p class="text-xs uppercase tracking-widest text-[#adc6ff] font-semibold mt-1">
                    {{ $user->getRoleNames()->first() ?? 'Member' }}
                </p>
            </div>
        </section>

        {{-- ── Profile Update Form ── --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <section class="space-y-6">

                {{-- Personal Information --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-[#c2c6d6] uppercase tracking-widest">Personal Information</h4>
                    <div class="grid grid-cols-1 gap-4">

                        <div class="space-y-1.5">
                            <label for="name" class="text-xs font-semibold text-[#c2c6d6] ml-1 block">Full Name</label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name"
                                   class="w-full bg-[#131b2e] border border-[#424754]/30 rounded-xl px-4 py-3
                                          text-[#dae2fd] placeholder-[#8c909f]/50 outline-none
                                          focus:ring-2 focus:ring-[#4d8eff] transition-all" />
                            @error('name')
                            <p class="text-xs text-[#ffb4ab] ml-1 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="text-xs font-semibold text-[#c2c6d6] ml-1 block">Work Email</label>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email', $user->email) }}" required autocomplete="username"
                                   class="w-full bg-[#131b2e] border border-[#424754]/30 rounded-xl px-4 py-3
                                          text-[#dae2fd] placeholder-[#8c909f]/50 outline-none
                                          focus:ring-2 focus:ring-[#4d8eff] transition-all" />
                            @error('email')
                            <p class="text-xs text-[#ffb4ab] ml-1 mt-1">{{ $message }}</p>
                            @enderror
                            @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <p class="text-xs text-[#ffb95f] ml-1 mt-1">⚠ Email not verified.</p>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- System Preferences --}}
                <div class="pt-4 space-y-4">
                    <h4 class="text-xs font-bold text-[#c2c6d6] uppercase tracking-widest">System Preferences</h4>
                    <div class="bg-[#131b2e] rounded-xl p-4 space-y-4 border border-[#424754]/15">

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#ffb95f]">dark_mode</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#dae2fd]">Interface Theme</p>
                                    <p class="text-xs text-[#c2c6d6]">Always in dark industrial mode</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-[#adc6ff] bg-[#adc6ff]/10 px-3 py-1.5 rounded-lg ring-1 ring-[#adc6ff]/20">Dark</span>
                        </div>

                        <div class="border-t border-[#424754]/10"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#adc6ff]">notifications</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#dae2fd]">Push Notifications</p>
                                    <p class="text-xs text-[#c2c6d6]">Receive alerts on desktop</p>
                                </div>
                            </div>
                            <button type="button" class="toggle-btn on" id="notif-toggle"
                                    onclick="this.classList.toggle('on'); this.classList.toggle('off')">
                                <span class="knob"></span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#c2c6d6]">mail</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#dae2fd]">Weekly Summary Reports</p>
                                    <p class="text-xs text-[#c2c6d6]">Email digests of site safety reports</p>
                                </div>
                            </div>
                            <button type="button" class="toggle-btn off" id="email-toggle"
                                    onclick="this.classList.toggle('on'); this.classList.toggle('off')">
                                <span class="knob"></span>
                            </button>
                        </div>

                    </div>
                </div>

            </section>

            {{-- Footer actions (inside form) --}}
            <div class="pt-8 flex gap-3">
                <button type="button" onclick="closeProfileDrawer()"
                        class="flex-1 py-3 px-4 rounded-xl font-headline text-sm font-bold
                               text-[#c2c6d6] bg-[#171f33] hover:bg-[#222a3d] transition-colors border border-[#424754]/20">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-[2] py-3 px-4 rounded-xl font-headline text-sm font-bold text-[#002e6a]
                               bg-gradient-to-br from-[#adc6ff] to-[#4d8eff]
                               shadow-lg shadow-[#4d8eff]/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Save Changes
                </button>
            </div>
        </form>

        {{-- ── Change Password ── --}}
        @include('profile.partials.update-password-form')

    </div>
</aside>

<script>
function openProfileDrawer() {
    document.getElementById('profile-backdrop').classList.remove('opacity-0','pointer-events-none');
    document.getElementById('profile-backdrop').classList.add('opacity-100');
    document.getElementById('profile-drawer').style.transform = 'translateX(0)';
    document.body.style.overflow = 'hidden';
}
function closeProfileDrawer() {
    document.getElementById('profile-backdrop').classList.add('opacity-0','pointer-events-none');
    document.getElementById('profile-backdrop').classList.remove('opacity-100');
    document.getElementById('profile-drawer').style.transform = 'translateX(100%)';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeProfileDrawer(); });
document.addEventListener('DOMContentLoaded', () => openProfileDrawer());
</script>

@endsection
