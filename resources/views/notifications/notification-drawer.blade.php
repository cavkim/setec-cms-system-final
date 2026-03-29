{{-- ═══════════════════════════════════════════════════════════
     Notification Drawer Partial
     Included once in dashboard-topbar.blade.php.
     Opened via openNotifDrawer() / closed via closeNotifDrawer().
     ═══════════════════════════════════════════════════════════ --}}

{{-- Backdrop --}}
<div id="notif-backdrop"
     class="fixed inset-0 bg-[#060e20]/70 backdrop-blur-sm z-[70]
            transition-opacity duration-300 opacity-0 pointer-events-none"
     onclick="closeNotifDrawer()"></div>

{{-- Drawer Panel --}}
<aside id="notif-drawer"
       class="fixed top-0 right-0 h-full w-full max-w-md
              bg-[#171f33] border-l border-[#424754]/20
              shadow-[-20px_0_50px_rgba(0,0,0,0.5)]
              flex flex-col z-[80]
              transform translate-x-full transition-transform duration-400 ease-in-out">

    {{-- Header --}}
    <header class="px-6 pt-6 pb-4 border-b border-[#424754]/10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-headline text-2xl font-extrabold text-[#dae2fd] tracking-tight">Notifications</h2>
            <button onclick="closeNotifDrawer()"
                    class="h-10 w-10 flex items-center justify-center rounded-full
                           hover:bg-[#2d3449] transition-all text-[#c2c6d6]"
                    aria-label="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex justify-between items-center">
            @php
                $unreadCount = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                    $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('notifiable_id', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                }
            @endphp
            <span class="text-xs font-bold tracking-widest uppercase text-[#adc6ff]">
                {{ $unreadCount }} Unread Alert{{ $unreadCount !== 1 ? 's' : '' }}
            </span>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit"
                        class="text-sm font-semibold text-[#adc6ff] hover:text-white transition-colors
                               underline underline-offset-4 decoration-[#adc6ff]/30">
                    Mark all as read
                </button>
            </form>
            @endif
        </div>
    </header>

    {{-- Filter Tabs --}}
    <nav class="px-6 py-3 flex gap-2 overflow-x-auto border-b border-[#424754]/10"
         style="scrollbar-width: none;">
        <button onclick="filterNotifs('all', this)"
                class="notif-tab px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap
                       bg-[#adc6ff] text-[#002e6a] transition-all" data-tab="all">All</button>
        <button onclick="filterNotifs('safety', this)"
                class="notif-tab px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap
                       bg-[#2d3449] text-[#c2c6d6] hover:text-[#dae2fd] transition-all" data-tab="safety">Safety</button>
        <button onclick="filterNotifs('budget', this)"
                class="notif-tab px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap
                       bg-[#2d3449] text-[#c2c6d6] hover:text-[#dae2fd] transition-all" data-tab="budget">Budget</button>
        <button onclick="filterNotifs('project', this)"
                class="notif-tab px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap
                       bg-[#2d3449] text-[#c2c6d6] hover:text-[#dae2fd] transition-all" data-tab="project">Project</button>
        <button onclick="filterNotifs('system', this)"
                class="notif-tab px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap
                       bg-[#2d3449] text-[#c2c6d6] hover:text-[#dae2fd] transition-all" data-tab="system">System</button>
    </nav>

    {{-- Notification Feed --}}
    <div id="notif-feed" class="flex-1 overflow-y-auto p-4 space-y-3" style="scrollbar-width: thin;">

        @php
            $notifications = collect();
            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $notifications = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->limit(20)
                    ->get();
            }
        @endphp

        @if($notifications->isNotEmpty())
            @foreach($notifications as $notif)
            @php
                $data      = json_decode($notif->data, true) ?? [];
                $message   = $data['message'] ?? $data['title'] ?? 'New Notification';
                $body      = $data['body'] ?? null;
                $type      = $data['type'] ?? 'info';
                $isUnread  = is_null($notif->read_at);

                $borderColor = match($type) {
                    'danger','safety'  => '#ffb95f',
                    'warning','budget' => '#8392a6',
                    'success'          => '#adc6ff',
                    default            => '#adc6ff',
                };
                $iconName = match($type) {
                    'danger','safety'  => 'report_problem',
                    'warning','budget' => 'payments',
                    'success'          => 'check_circle',
                    default            => 'notifications',
                };
                $labelColor = match($type) {
                    'danger','safety'  => 'text-[#ffb95f]',
                    'warning','budget' => 'text-[#b9c8de]',
                    'success'          => 'text-[#adc6ff]',
                    default            => 'text-[#adc6ff]',
                };
                $tabCategory = in_array($type, ['danger','safety']) ? 'safety'
                    : (in_array($type, ['warning','budget']) ? 'budget'
                    : ($type === 'success' ? 'project' : 'system'));
            @endphp
            <div class="notif-item group relative bg-[#222a3d] p-4 rounded-xl
                        border-l-4 shadow-sm hover:shadow-lg hover:bg-[#2d3449]
                        transition-all duration-200 {{ $isUnread ? '' : 'opacity-70' }}"
                 style="border-left-color: {{ $borderColor }};"
                 data-category="{{ $tabCategory }}">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-[#131b2e]">
                        <span class="material-symbols-outlined text-base {{ $labelColor }}">{{ $iconName }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $labelColor }}">
                                {{ ucfirst($type) }}
                            </span>
                            <span class="text-[10px] text-[#8c909f] flex-shrink-0">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <h4 class="font-headline font-bold text-[#dae2fd] text-sm mb-1 leading-tight">{{ $message }}</h4>
                        @if($body)
                        <p class="text-xs text-[#c2c6d6] leading-relaxed">{{ $body }}</p>
                        @endif
                        @if($isUnread)
                        <span class="inline-block mt-2 text-[9px] font-bold uppercase tracking-widest
                                     px-2 py-0.5 rounded bg-[#adc6ff]/10 text-[#adc6ff] ring-1 ring-[#adc6ff]/20">
                            Unread
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-[#222a3d] flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-[#adc6ff]"
                          style="font-variation-settings: 'FILL' 1;">notifications</span>
                </div>
                <h4 class="font-headline font-bold text-[#dae2fd] mb-2">All caught up!</h4>
                <p class="text-xs text-[#8c909f] max-w-[200px] leading-relaxed">
                    No notifications yet. They'll appear here automatically when something needs your attention.
                </p>
            </div>
        @endif

    </div>

    {{-- Footer --}}
    <footer class="px-6 py-5 border-t border-[#424754]/10 bg-[#131b2e]/40">
        <a href="{{ route('notifications.index') }}"
           class="w-full bg-[#2d3449] border border-[#424754]/20 hover:border-[#adc6ff]/40
                  text-[#dae2fd] font-bold py-4 rounded-xl flex items-center justify-center gap-2
                  transition-all hover:bg-[#222a3d] block text-center text-sm">
            <span class="material-symbols-outlined text-lg">history</span>
            View Full Notification History
        </a>
    </footer>
</aside>

<script>
function openNotifDrawer() {
    document.getElementById('notif-backdrop').classList.remove('opacity-0', 'pointer-events-none');
    document.getElementById('notif-backdrop').classList.add('opacity-100');
    document.getElementById('notif-drawer').style.transform = 'translateX(0)';
    document.body.style.overflow = 'hidden';
}
function closeNotifDrawer() {
    document.getElementById('notif-backdrop').classList.add('opacity-0', 'pointer-events-none');
    document.getElementById('notif-backdrop').classList.remove('opacity-100');
    document.getElementById('notif-drawer').style.transform = 'translateX(100%)';
    document.body.style.overflow = '';
}
function filterNotifs(tab, btn) {
    // Update active tab style
    document.querySelectorAll('.notif-tab').forEach(t => {
        t.classList.remove('bg-[#adc6ff]', 'text-[#002e6a]');
        t.classList.add('bg-[#2d3449]', 'text-[#c2c6d6]');
    });
    btn.classList.remove('bg-[#2d3449]', 'text-[#c2c6d6]');
    btn.classList.add('bg-[#adc6ff]', 'text-[#002e6a]');

    // Filter items
    document.querySelectorAll('.notif-item').forEach(item => {
        item.style.display = (tab === 'all' || item.dataset.category === tab) ? '' : 'none';
    });
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNotifDrawer(); });
</script>