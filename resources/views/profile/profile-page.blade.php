@extends('layouts.app')
@section('title', 'My Profile — CMS')
@section('page-title', 'My Profile')

@section('styles')
<style>
    .glass-card {
        background: rgba(23, 31, 51, 0.6);
        backdrop-filter: blur(12px);
    }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
    .tab-btn.active {
        color: #adc6ff;
        border-bottom-color: #adc6ff;
        font-weight: 700;
    }
    .toggle-switch {
        width: 48px; height: 26px; border-radius: 999px; position: relative;
        cursor: pointer; border: none; outline: none; transition: background .25s;
    }
    .toggle-switch .knob {
        position: absolute; top: 3px; width: 20px; height: 20px;
        border-radius: 50%; background: #fff; transition: left .25s;
    }
    .toggle-switch.on  { background: #4d8eff; }
    .toggle-switch.off { background: #424754; }
    .toggle-switch.on  .knob { left: calc(100% - 23px); }
    .toggle-switch.off .knob { left: 3px; }
    input[type="text"], input[type="email"], input[type="password"] {
        color-scheme: dark;
    }
</style>
@endsection

@section('content')

{{-- Flash Messages --}}
@if(session('status') === 'profile-updated')
<script>document.addEventListener('DOMContentLoaded', () => toast('Profile updated successfully', 'success'));</script>
@endif
@if(session('status') === 'password-updated')
<script>document.addEventListener('DOMContentLoaded', () => toast('Password updated successfully', 'success'));</script>
@endif

@php
    $roleLabel    = $user->getRoleNames()->first() ?? 'Member';
    $initials     = strtoupper(substr($user->name, 0, 2));
    $projectCount = \App\Models\Project::count() ?? 0;
    $teamCount    = \App\Models\User::count() ?? 0;
@endphp

{{-- ═══ HERO SECTION ═══ --}}
<section class="relative overflow-hidden rounded-2xl bg-[#131b2e] p-8 shadow-2xl border border-[#424754]/10">
    {{-- background decoration --}}
    <div class="absolute top-0 right-0 w-1/3 h-full opacity-[0.04] pointer-events-none select-none">
        <span class="material-symbols-outlined text-[18rem] text-[#adc6ff] leading-none"
              style="font-variation-settings:'FILL' 1;">engineering</span>
    </div>

    <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">

        {{-- Avatar --}}
        <div class="relative flex-shrink-0">
            <div class="w-28 h-28 rounded-2xl ring-4 ring-[#adc6ff]/20 shadow-xl
                        bg-gradient-to-br from-[#1565C0] to-[#4d8eff]
                        flex items-center justify-center text-white text-4xl font-black select-none">
                {{ $initials }}
            </div>
            <div class="absolute -bottom-2 -right-2 bg-[#ffb95f] p-1.5 rounded-lg shadow-lg">
                <span class="material-symbols-outlined text-[#2a1700] text-sm font-bold"
                      style="font-variation-settings:'FILL' 1;">verified</span>
            </div>
        </div>

        {{-- Info --}}
        <div class="flex-1 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                <h1 class="text-3xl font-extrabold tracking-tight font-headline text-white">{{ $user->name }}</h1>
                <span class="px-3 py-1 rounded-full bg-[#2d3449] text-[#adc6ff] text-xs font-bold uppercase tracking-wider self-center">
                    {{ $roleLabel }}
                </span>
            </div>
            <p class="text-[#c2c6d6] max-w-xl text-sm leading-relaxed mb-6">
                {{ $user->email }}
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#171f33] p-4 rounded-2xl border-l-4 border-[#adc6ff]">
                    <div class="text-[#c2c6d6] text-xs font-label uppercase mb-1">Projects</div>
                    <div class="text-2xl font-bold font-headline text-white">{{ $projectCount }}</div>
                </div>
                <div class="bg-[#171f33] p-4 rounded-2xl border-l-4 border-[#ffb95f]">
                    <div class="text-[#c2c6d6] text-xs font-label uppercase mb-1">Team Members</div>
                    <div class="text-2xl font-bold font-headline text-white">{{ $teamCount }}</div>
                </div>
                <div class="bg-[#171f33] p-4 rounded-2xl border-l-4 border-[#b9c8de]">
                    <div class="text-[#c2c6d6] text-xs font-label uppercase mb-1">Role</div>
                    <div class="text-base font-bold font-headline text-white capitalize truncate">{{ $roleLabel }}</div>
                </div>
                <div class="bg-[#171f33] p-4 rounded-2xl border-l-4 border-[#4d8eff]">
                    <div class="text-[#c2c6d6] text-xs font-label uppercase mb-1">Status</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <span class="text-sm font-bold text-white">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TAB NAV ═══ --}}
<section class="space-y-6 mt-8">
    <nav class="flex gap-8 border-b border-[#424754]/20 px-2">
        <button onclick="switchTab('activity')"
                id="tab-btn-activity"
                class="tab-btn active pb-4 border-b-2 border-[#adc6ff] text-[#adc6ff] font-bold
                       transition-all flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">monitoring</span>
            Activity Feed
        </button>
        <button onclick="switchTab('edit')"
                id="tab-btn-edit"
                class="tab-btn pb-4 border-b-2 border-transparent text-[#c2c6d6] font-medium
                       hover:text-[#dae2fd] transition-all flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">manage_accounts</span>
            Edit Profile
        </button>
        <button onclick="switchTab('security')"
                id="tab-btn-security"
                class="tab-btn pb-4 border-b-2 border-transparent text-[#c2c6d6] font-medium
                       hover:text-[#dae2fd] transition-all flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">shield</span>
            Security
        </button>
    </nav>

    {{-- ─── TAB: Activity Feed ─── --}}
    <div id="tab-activity" class="tab-panel active">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Timeline --}}
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-xl font-bold font-headline px-2">Operational Log</h2>
                <div class="space-y-1">
                    @php
                    $logTable = config('activitylog.table_name', 'activity_log');
                        $logs = collect();
                        try {
                            if (\Illuminate\Support\Facades\Schema::hasTable($logTable)) {
                                $logs = \Illuminate\Support\Facades\DB::table($logTable)
                                    ->orderByDesc('created_at')->limit(5)->get();
                            }
                        } catch (\Exception $e) {}

                    @endphp
                    @if($logs->isNotEmpty())
                        @foreach($logs as $log)
                        @php
                            $icon = match(true) {
                                str_contains($log->event ?? $log->description ?? '', 'creat') => 'add_circle',
                                str_contains($log->event ?? $log->description ?? '', 'updat') => 'edit',
                                str_contains($log->event ?? $log->description ?? '', 'delet') => 'delete',
                                default => 'history'
                            };
                            $color = match(true) {
                                str_contains($log->event ?? $log->description ?? '', 'creat') => 'text-[#adc6ff] bg-[#adc6ff]/10',
                                str_contains($log->event ?? $log->description ?? '', 'updat') => 'text-[#ffb95f] bg-[#ffb95f]/10',
                                str_contains($log->event ?? $log->description ?? '', 'delet') => 'text-[#ffb4ab] bg-[#ffb4ab]/10',
                                default => 'text-[#b9c8de] bg-[#b9c8de]/10',
                            };
                            $subjectLabel = isset($log->subject_type) ? class_basename($log->subject_type) : '';
                            $eventLabel   = $log->event ?? ucfirst($log->log_name ?? 'Action');
                            $body         = $log->description ?? $log->url ?? 'System action recorded.';
                        @endphp

                        <div class="group flex gap-6 p-5 rounded-2xl bg-[#131b2e] hover:bg-[#171f33] transition-all">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-base">{{ $icon }}</span>
                                </div>
                                <div class="w-0.5 flex-1 bg-[#424754]/20 mt-2"></div>
                            </div>
                            <div class="pb-4 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-[#dae2fd] capitalize">
                                        {{ $eventLabel }} {{ $subjectLabel }}
                                    </span>
                                    <span class="text-xs text-[#8c909f]">• {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-[#c2c6d6] truncate">{{ $body }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        {{-- Placeholder activity items --}}
                        @foreach([
                            ['icon' => 'login',       'color' => 'text-[#adc6ff] bg-[#adc6ff]/10', 'title' => 'Session Started',         'body' => 'You logged in from a new session.',               'time' => 'Just now'],
                            ['icon' => 'manage_accounts','color' => 'text-[#ffb95f] bg-[#ffb95f]/10', 'title' => 'Profile Access',        'body' => 'You viewed your profile page.',                   'time' => '2 min ago'],
                            ['icon' => 'notifications', 'color' => 'text-[#b9c8de] bg-[#b9c8de]/10', 'title' => 'Notifications Checked', 'body' => 'You opened the notification drawer.',              'time' => '10 min ago'],
                        ] as $item)
                        <div class="group flex gap-6 p-5 rounded-2xl bg-[#131b2e] hover:bg-[#171f33] transition-all">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-xl {{ $item['color'] }} flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-base">{{ $item['icon'] }}</span>
                                </div>
                                <div class="w-0.5 flex-1 bg-[#424754]/20 mt-2"></div>
                            </div>
                            <div class="pb-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-[#dae2fd]">{{ $item['title'] }}</span>
                                    <span class="text-xs text-[#8c909f]">• {{ $item['time'] }}</span>
                                </div>
                                <p class="text-sm text-[#c2c6d6]">{{ $item['body'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Right: Compliance Health --}}
            <div class="space-y-6">
                <h2 class="text-xl font-bold font-headline px-2">Quick Info</h2>
                <div class="glass-card p-6 rounded-2xl border border-white/5 space-y-5">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-label uppercase">
                            <span class="text-[#c2c6d6]">Safety Compliance</span>
                            <span class="text-[#adc6ff]">94%</span>
                        </div>
                        <div class="h-1.5 w-full bg-[#171f33] rounded-full overflow-hidden">
                            <div class="h-full bg-[#adc6ff] rounded-full" style="width: 94%"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-label uppercase">
                            <span class="text-[#c2c6d6]">Project Efficiency</span>
                            <span class="text-[#ffb95f]">82%</span>
                        </div>
                        <div class="h-1.5 w-full bg-[#171f33] rounded-full overflow-hidden">
                            <div class="h-full bg-[#ffb95f] rounded-full" style="width: 82%"></div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-[#424754]/10">
                        <h3 class="text-sm font-bold mb-3 text-[#dae2fd]">Account Details</h3>
                        <div class="space-y-2 text-xs text-[#c2c6d6]">
                            <div class="flex justify-between">
                                <span class="text-[#8c909f]">Email</span>
                                <span class="font-medium truncate ml-2">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#8c909f]">Role</span>
                                <span class="font-medium capitalize">{{ $roleLabel }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#8c909f]">Member Since</span>
                                <span class="font-medium">{{ $user->created_at?->format('M Y') ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#8c909f]">Verified</span>
                                <span class="{{ $user->email_verified_at ? 'text-green-400' : 'text-[#ffb4ab]' }} font-bold">
                                    {{ $user->email_verified_at ? '✓ Yes' : '✗ No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="space-y-2">
                    <button onclick="switchTab('edit')"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-[#131b2e] hover:bg-[#171f33]
                                   text-[#dae2fd] rounded-xl transition-all border border-[#424754]/10 text-sm">
                        <span class="material-symbols-outlined text-[#adc6ff]">manage_accounts</span>
                        Edit Profile Info
                    </button>
                    <button onclick="switchTab('security')"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-[#131b2e] hover:bg-[#171f33]
                                   text-[#dae2fd] rounded-xl transition-all border border-[#424754]/10 text-sm">
                        <span class="material-symbols-outlined text-[#ffb95f]">key</span>
                        Change Password
                    </button>
                    <a href="{{ route('notifications.index') }}"
                       class="w-full flex items-center gap-3 px-4 py-3 bg-[#131b2e] hover:bg-[#171f33]
                              text-[#dae2fd] rounded-xl transition-all border border-[#424754]/10 text-sm">
                        <span class="material-symbols-outlined text-[#b9c8de]">notifications</span>
                        View Notifications
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ─── TAB: Edit Profile ─── --}}
    <div id="tab-edit" class="tab-panel">
        <div class="max-w-2xl space-y-6">

            <div class="bg-[#131b2e] rounded-2xl p-8 border border-[#424754]/10 shadow-sm">
                <h2 class="text-lg font-bold font-headline text-white mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#adc6ff]">manage_accounts</span>
                    Personal Information
                </h2>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar preview --}}
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[#1565C0] to-[#4d8eff]
                                    flex items-center justify-center text-white text-2xl font-black select-none ring-4 ring-[#adc6ff]/20">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#dae2fd]">{{ $user->name }}</p>
                            <p class="text-xs text-[#8c909f] mt-0.5 capitalize">{{ $roleLabel }}</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c2c6d6] uppercase tracking-wider ml-1 block">Full Name</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-[#0b1326] border border-[#424754]/30 rounded-xl px-4 py-3
                                      text-[#dae2fd] outline-none focus:ring-2 focus:ring-[#4d8eff] transition-all
                                      placeholder-[#8c909f]/50" />
                        @error('name')
                        <p class="text-xs text-[#ffb4ab] ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c2c6d6] uppercase tracking-wider ml-1 block">Work Email</label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-[#0b1326] border border-[#424754]/30 rounded-xl px-4 py-3
                                      text-[#dae2fd] outline-none focus:ring-2 focus:ring-[#4d8eff] transition-all
                                      placeholder-[#8c909f]/50" />
                        @error('email')
                        <p class="text-xs text-[#ffb4ab] ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Preferences --}}
                    <div class="pt-4 border-t border-[#424754]/10 space-y-4">
                        <h4 class="text-xs font-bold text-[#c2c6d6] uppercase tracking-widest">Preferences</h4>

                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#adc6ff]">notifications</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#dae2fd]">Push Notifications</p>
                                    <p class="text-xs text-[#8c909f]">Receive alerts on desktop</p>
                                </div>
                            </div>
                            <button type="button" class="toggle-switch on"
                                    onclick="this.classList.toggle('on'); this.classList.toggle('off')">
                                <span class="knob"></span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#c2c6d6]">mail</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#dae2fd]">Weekly Digest Emails</p>
                                    <p class="text-xs text-[#8c909f]">Safety & project summary reports</p>
                                </div>
                            </div>
                            <button type="button" class="toggle-switch off"
                                    onclick="this.classList.toggle('on'); this.classList.toggle('off')">
                                <span class="knob"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                class="flex-1 py-3 px-6 rounded-xl font-headline text-sm font-bold text-[#002e6a]
                                       bg-gradient-to-br from-[#adc6ff] to-[#4d8eff]
                                       shadow-lg shadow-[#4d8eff]/20 hover:scale-[1.02] active:scale-95 transition-all">
                            Save Changes
                        </button>
                        <button type="button" onclick="switchTab('activity')"
                                class="py-3 px-6 rounded-xl font-headline text-sm font-bold
                                       text-[#c2c6d6] bg-[#171f33] hover:bg-[#222a3d] transition-colors border border-[#424754]/20">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- ─── TAB: Security ─── --}}
    <div id="tab-security" class="tab-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl">

            {{-- Change Password --}}
            <div class="bg-[#131b2e] p-8 rounded-2xl border border-[#424754]/10 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-[#adc6ff] text-2xl">key</span>
                    <h2 class="text-lg font-bold font-headline text-white">Change Password</h2>
                </div>
                <p class="text-xs text-[#8c909f] mb-6">Use a long, random password to keep your account secure.</p>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c2c6d6] uppercase tracking-wider ml-1 block">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password"
                               autocomplete="current-password"
                               class="w-full bg-[#0b1326] border border-[#424754]/30 rounded-xl px-4 py-3
                                      text-[#dae2fd] outline-none focus:ring-2 focus:ring-[#4d8eff] transition-all" />
                        @if($errors->updatePassword->get('current_password'))
                        <p class="text-xs text-[#ffb4ab] ml-1">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c2c6d6] uppercase tracking-wider ml-1 block">New Password</label>
                        <input id="update_password_password" name="password" type="password"
                               autocomplete="new-password"
                               class="w-full bg-[#0b1326] border border-[#424754]/30 rounded-xl px-4 py-3
                                      text-[#dae2fd] outline-none focus:ring-2 focus:ring-[#4d8eff] transition-all" />
                        @if($errors->updatePassword->get('password'))
                        <p class="text-xs text-[#ffb4ab] ml-1">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c2c6d6] uppercase tracking-wider ml-1 block">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                               autocomplete="new-password"
                               class="w-full bg-[#0b1326] border border-[#424754]/30 rounded-xl px-4 py-3
                                      text-[#dae2fd] outline-none focus:ring-2 focus:ring-[#4d8eff] transition-all" />
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl font-headline text-sm font-bold text-[#002e6a]
                                   bg-gradient-to-br from-[#adc6ff] to-[#4d8eff]
                                   shadow-lg shadow-[#4d8eff]/20 hover:scale-[1.02] active:scale-95 transition-all mt-2">
                        Update Password
                    </button>
                </form>
            </div>

            {{-- MFA / Session Info --}}
            <div class="bg-[#131b2e] p-8 rounded-2xl border border-[#424754]/10 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-[#ffb95f] text-2xl">vibration</span>
                        <h2 class="text-lg font-bold font-headline text-white">Multi-Factor Auth</h2>
                    </div>
                    <p class="text-sm text-[#c2c6d6] mb-6 leading-relaxed">
                        Add an extra layer of security to your account. Use hardware keys or
                        authenticator apps for critical actions.
                    </p>
                    <div class="flex items-center justify-between p-4 bg-[#0b1326] rounded-xl mb-4 border border-[#424754]/10">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#adc6ff]">smartphone</span>
                            <span class="text-sm font-semibold text-[#dae2fd]">Mobile Authenticator</span>
                        </div>
                        <div class="w-12 h-6 bg-[#adc6ff] rounded-full relative cursor-pointer">
                            <div class="absolute right-1 top-1 w-4 h-4 bg-[#002e6a] rounded-full"></div>
                        </div>
                    </div>
                    {{-- Account session info --}}
                    <div class="bg-[#0b1326] rounded-xl p-4 border border-[#424754]/10 space-y-2 text-xs">
                        <div class="flex justify-between text-[#c2c6d6]">
                            <span class="text-[#8c909f]">Email Verified</span>
                            <span class="{{ $user->email_verified_at ? 'text-green-400' : 'text-[#ffb4ab]' }} font-bold">
                                {{ $user->email_verified_at ? '✓ Verified' : '✗ Not Verified' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-[#c2c6d6]">
                            <span class="text-[#8c909f]">Last Updated</span>
                            <span>{{ $user->updated_at?->diffForHumans() ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-[#c2c6d6]">
                            <span class="text-[#8c909f]">Account Created</span>
                            <span>{{ $user->created_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-6 py-3 bg-[#adc6ff]/10 text-[#adc6ff] font-bold rounded-xl
                               border border-[#adc6ff]/20 hover:bg-[#adc6ff]/20 transition-all text-sm">
                    Configure Secure Device
                </button>
            </div>

        </div>
    </div>

</section>

{{-- Footer --}}
<footer class="pt-6 pb-2 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#131b2e] rounded-full text-[10px]
                font-label uppercase tracking-widest text-[#8c909f] border border-[#424754]/10">
        <span class="w-2 h-2 rounded-full bg-[#adc6ff] shadow-[0_0_8px_rgba(173,198,255,0.6)]"></span>
        System Connection Stable • Logged in as {{ $user->name }}
    </div>
</footer>

@endsection

@section('scripts')
<script>
function switchTab(tab) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    // Deactivate all buttons
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active', 'text-[#adc6ff]', 'border-[#adc6ff]', 'font-bold');
        b.classList.add('text-[#c2c6d6]', 'border-transparent');
    });
    // Show selected panel
    document.getElementById('tab-' + tab).classList.add('active');
    // Activate selected button
    const btn = document.getElementById('tab-btn-' + tab);
    btn.classList.add('active', 'text-[#adc6ff]', 'border-[#adc6ff]', 'font-bold');
    btn.classList.remove('text-[#c2c6d6]', 'border-transparent');
}

// Auto-open the correct tab if there are errors
document.addEventListener('DOMContentLoaded', () => {
    @if($errors->updatePassword->any())
        switchTab('security');
    @elseif($errors->any())
        switchTab('edit');
    @endif
});
</script>
@endsection