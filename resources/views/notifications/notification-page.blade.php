@extends('layouts.app')
@section('title', 'Notifications — CMS')
@section('page-title', 'Notifications')

@section('styles')
<style>
    .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(23, 31, 51, 0.55);
    }
    .notif-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .notif-card:hover { transform: translateX(4px); }
    .notif-card[data-read="true"] { opacity: .65; filter: grayscale(.35); }
    .filter-btn.active {
        background: #adc6ff !important;
        color: #002e6a !important;
    }
    .bar-fill { transition: height .5s ease; }
</style>
@endsection

@section('content')

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'));</script>
@endif

@php
    $hasTable = \Illuminate\Support\Facades\Schema::hasTable('notifications');

    $allNotifs = collect();
    $stats = ['unread' => 0, 'total' => 0, 'read' => 0];

    if ($hasTable) {
        $allNotifs = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'unread' => $allNotifs->whereNull('read_at')->count(),
            'total'  => $allNotifs->count(),
            'read'   => $allNotifs->whereNotNull('read_at')->count(),
        ];
    }

    // Per-day counts for last 7 days (for bar chart)
    $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    $dayCounts = [];
    $maxDay = 1;
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $cnt = $hasTable
            ? \Illuminate\Support\Facades\DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', auth()->id())
                ->whereDate('created_at', $date)
                ->count()
            : rand(0, 8);
        $dayCounts[] = $cnt;
        $maxDay = max($maxDay, $cnt);
    }

    // Recent audit log entries
    $logTable = config('activitylog.table_name', 'activity_log');
    $recentLogs = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable($logTable)) {
            $recentLogs = \Illuminate\Support\Facades\DB::table($logTable)
                ->orderByDesc('created_at')->limit(4)->get();
        }
    } catch (\Exception $e) {}
@endphp

{{-- ═══ PAGE HEADER ═══ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h2 class="font-headline text-3xl font-extrabold tracking-tight text-[#dae2fd]">
            System Alerts <span class="text-[#adc6ff]">&</span> Notifications
        </h2>
        <p class="text-[#c2c6d6] text-sm mt-1">
            You have
            <span class="font-bold {{ $stats['unread'] > 0 ? 'text-[#ffb95f]' : 'text-[#adc6ff]' }}">
                {{ $stats['unread'] }} unread
            </span>
            of {{ $stats['total'] }} total notifications.
        </p>
    </div>
    <div class="flex gap-3">
        @if($stats['unread'] > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2.5 bg-[#2d3449] text-[#c2c6d6] text-sm font-semibold
                           rounded-xl hover:bg-[#31394d] transition-all border border-[#424754]/20">
                <span class="material-symbols-outlined text-[18px]">done_all</span>
                Mark all as read
            </button>
        </form>
        @endif
        <button onclick="filterNotifs('unread')"
                class="flex items-center gap-2 px-4 py-2.5 bg-[#2d3449] text-[#c2c6d6] text-sm font-semibold
                       rounded-xl hover:bg-[#31394d] transition-all border border-[#424754]/20">
            <span class="material-symbols-outlined text-[18px]">filter_list</span>
            Unread Only
        </button>
    </div>
</div>

{{-- ═══ KPI STRIP ═══ --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-[#131b2e] rounded-2xl p-5 border-l-4 border-[#ffb4ab] border border-[#424754]/10">
        <p class="text-xs font-bold uppercase tracking-widest text-[#c2c6d6] mb-1">Unread</p>
        <p class="text-3xl font-extrabold font-headline text-[#ffb4ab]">{{ $stats['unread'] }}</p>
        <p class="text-[10px] text-[#8c909f] mt-1">{{ $stats['unread'] > 0 ? 'Needs attention' : 'All caught up ✓' }}</p>
    </div>
    <div class="bg-[#131b2e] rounded-2xl p-5 border-l-4 border-[#adc6ff] border border-[#424754]/10">
        <p class="text-xs font-bold uppercase tracking-widest text-[#c2c6d6] mb-1">Total</p>
        <p class="text-3xl font-extrabold font-headline text-[#adc6ff]">{{ $stats['total'] }}</p>
        <p class="text-[10px] text-[#8c909f] mt-1">All notifications</p>
    </div>
    <div class="bg-[#131b2e] rounded-2xl p-5 border-l-4 border-[#4db6ac] border border-[#424754]/10">
        <p class="text-xs font-bold uppercase tracking-widest text-[#c2c6d6] mb-1">Read</p>
        <p class="text-3xl font-extrabold font-headline text-[#4db6ac]">{{ $stats['read'] }}</p>
        <p class="text-[10px] text-[#8c909f] mt-1">Already seen</p>
    </div>
</div>

{{-- ═══ FILTER BAR ═══ --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        @foreach(['all' => 'All', 'unread' => 'Unread', 'safety' => 'Safety', 'budget' => 'Budget', 'project' => 'Project', 'system' => 'System'] as $key => $label)
        <button onclick="filterNotifs('{{ $key }}')"
                id="filter-{{ $key }}"
                class="filter-btn px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider
                       bg-[#2d3449] text-[#c2c6d6] hover:text-[#dae2fd] transition-all
                       {{ $key === 'all' ? 'active' : '' }}">
            {{ $label }}
            @if($key === 'unread' && $stats['unread'] > 0)
            <span class="ml-1 px-1.5 py-0.5 bg-[#ffb95f] text-[#2a1700] rounded-full text-[9px] font-black">
                {{ $stats['unread'] }}
            </span>
            @endif
        </button>
        @endforeach
    </div>
    <div class="flex items-center gap-2 bg-[#131b2e] p-1 rounded-xl border border-[#424754]/15">
        <button id="view-list" onclick="setView('list')"
                class="p-2 bg-[#2d3449] text-[#adc6ff] rounded-lg transition-all">
            <span class="material-symbols-outlined text-[18px]">view_list</span>
        </button>
        <button id="view-grid" onclick="setView('grid')"
                class="p-2 text-[#8c909f] hover:bg-[#2d3449] rounded-lg transition-all">
            <span class="material-symbols-outlined text-[18px]">grid_view</span>
        </button>
    </div>
</div>

{{-- ═══ NOTIFICATIONS FEED ═══ --}}
<div id="notif-feed" class="space-y-3">
    @if($allNotifs->isNotEmpty())
        @foreach($allNotifs as $notif)
        @php
            $data    = json_decode($notif->data, true) ?? [];
            $message = $data['message'] ?? $data['title'] ?? 'New notification';
            $body    = $data['body'] ?? null;
            $type    = $data['type'] ?? 'info';
            $isUnread = is_null($notif->read_at);

            // Visual mapping
            $category = in_array($type, ['danger', 'safety'])   ? 'safety'
                      : (in_array($type, ['warning', 'budget']) ? 'budget'
                      : ($type === 'success'                    ? 'project'
                      : 'system'));

            $borderColor = match($category) {
                'safety'  => '#ffb4ab',
                'budget'  => '#ffb95f',
                'project' => '#adc6ff',
                default   => '#b9c8de',
            };
            $iconBg = match($category) {
                'safety'  => 'bg-[#93000a]/20 text-[#ffb4ab]',
                'budget'  => 'bg-[#ffb95f]/10 text-[#ffb95f]',
                'project' => 'bg-[#adc6ff]/10 text-[#adc6ff]',
                default   => 'bg-[#8392a6]/10 text-[#b9c8de]',
            };
            $labelColor = match($category) {
                'safety'  => 'text-[#ffb4ab]',
                'budget'  => 'text-[#ffb95f]',
                'project' => 'text-[#adc6ff]',
                default   => 'text-[#b9c8de]',
            };
            $iconName = match($category) {
                'safety'  => 'warning',
                'budget'  => 'account_balance_wallet',
                'project' => 'architecture',
                default   => 'cloud_done',
            };
            $categoryLabel = match($category) {
                'safety'  => 'Safety Critical',
                'budget'  => 'Budget Alert',
                'project' => 'Project Update',
                default   => 'System Status',
            };
        @endphp
        <div class="notif-card relative group bg-[#222a3d] rounded-xl border-l-4 overflow-hidden
                    border border-[#424754]/10"
             style="border-left-color: {{ $borderColor }};"
             data-category="{{ $category }}"
             data-read="{{ $isUnread ? 'false' : 'true' }}">
            <div class="p-5 flex flex-col md:flex-row gap-5 items-start">
                {{-- Icon --}}
                <div class="w-11 h-11 rounded-xl {{ $iconBg }} flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl"
                          @if($category === 'safety') style="font-variation-settings:'FILL' 1" @endif>
                        {{ $iconName }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1 gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $labelColor }}">
                            {{ $categoryLabel }}
                        </span>
                        <span class="text-[11px] text-[#8c909f] flex-shrink-0">
                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    <h3 class="font-headline text-base font-bold text-[#dae2fd] mb-1 leading-snug">
                        {{ $message }}
                    </h3>
                    @if($body)
                    <p class="text-[#c2c6d6] text-sm leading-relaxed">{{ $body }}</p>
                    @endif
                    @if($isUnread)
                    <span class="inline-block mt-2 px-2 py-0.5 bg-[#adc6ff]/10 text-[#adc6ff]
                                 text-[9px] font-black uppercase tracking-widest rounded ring-1 ring-[#adc6ff]/20">
                        Unread
                    </span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if($isUnread)
                    <span class="w-2 h-2 rounded-full flex-shrink-0"
                          style="background:{{ $borderColor }}"></span>
                    @endif
                    <button class="p-1.5 text-[#424754] hover:text-[#c2c6d6] transition-colors rounded-lg
                                   hover:bg-[#2d3449]">
                        <span class="material-symbols-outlined text-lg">more_vert</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach

    @else
        {{-- Empty / no notifications state --}}
        <div id="empty-state" class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-2xl bg-[#131b2e] flex items-center justify-center mb-5 border border-[#424754]/10">
                <span class="material-symbols-outlined text-4xl text-[#adc6ff]"
                      style="font-variation-settings:'FILL' 1;">notifications</span>
            </div>
            <h4 class="font-headline font-bold text-xl text-[#dae2fd] mb-2">All caught up!</h4>
            <p class="text-sm text-[#8c909f] max-w-sm leading-relaxed">
                No notifications yet. They'll appear here automatically when something needs your attention.
            </p>
        </div>
    @endif

    {{-- "No results" shown when filter has no match --}}
    <div id="no-filter-results" class="hidden flex-col items-center justify-center py-16 text-center">
        <span class="material-symbols-outlined text-4xl text-[#424754] mb-3">search_off</span>
        <p class="text-sm text-[#8c909f]">No notifications in this category.</p>
    </div>
</div>

{{-- Pagination (if using paginate()) --}}
@if(isset($notifications) && method_exists($notifications, 'hasPages') && $notifications->hasPages())
<div class="mt-8 flex justify-between items-center text-sm text-[#8c909f]">
    <span>Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} of {{ $notifications->total() }}</span>
    <div class="flex gap-2">
        @if(!$notifications->onFirstPage())
        <a href="{{ $notifications->previousPageUrl() }}"
           class="px-4 py-2 bg-[#2d3449] text-[#c2c6d6] rounded-xl border border-[#424754]/20 hover:bg-[#31394d]">← Prev</a>
        @endif
        @if($notifications->hasMorePages())
        <a href="{{ $notifications->nextPageUrl() }}"
           class="px-4 py-2 bg-[#adc6ff] text-[#002e6a] font-bold rounded-xl">Next →</a>
        @endif
    </div>
</div>
@endif

{{-- ═══ BOTTOM WIDGET GRID ═══ --}}
<div class="grid grid-cols-12 gap-6 mt-12 pt-10 border-t border-[#424754]/10">

    {{-- Recent Activity --}}
    <div class="col-span-12 lg:col-span-4 bg-[#131b2e] rounded-2xl p-6 border border-[#424754]/10 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 opacity-[0.04] pointer-events-none transition-transform group-hover:scale-110">
            <span class="material-symbols-outlined text-[9rem] text-[#adc6ff]">history</span>
        </div>
        <h4 class="font-headline font-bold text-lg mb-5 flex items-center gap-2 text-[#dae2fd]">
            <span class="material-symbols-outlined text-[#adc6ff]">history</span>
            Recent Activity
        </h4>
        <div class="space-y-4 relative z-10">
            @if($recentLogs->isNotEmpty())
                @foreach($recentLogs as $log)
                @php
                    $logColor = str_contains($log->event ?? $log->description ?? '', 'creat') ? '#adc6ff'
                              : (str_contains($log->event ?? $log->description ?? '', 'delet') ? '#ffb4ab'
                              : '#ffb95f');
                @endphp
                <div class="flex gap-3 items-start">
                    <div class="w-1 rounded-full mt-1 flex-shrink-0" style="height:32px; background:{{ $logColor }}"></div>
                    <div>
                        <p class="text-xs font-semibold text-[#dae2fd] leading-tight">
                            {{ ucfirst($log->event ?? 'Action') }}
                            {{ isset($log->subject_type) ? class_basename($log->subject_type) : '' }}
                        </p>
                        <p class="text-[10px] text-[#8c909f] mt-0.5">
                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach
            @else
                @foreach([
                    ['#adc6ff', 'Session started', 'Just now'],
                    ['#ffb95f', 'Notifications checked', '5 min ago'],
                    ['#b9c8de', 'Profile updated', '2 hr ago'],
                    ['#adc6ff', 'Dashboard accessed', 'Today, 9:00 AM'],
                ] as $item)
                <div class="flex gap-3 items-start">
                    <div class="w-1 rounded-full mt-1 flex-shrink-0" style="height:32px; background:{{ $item[0] }}"></div>
                    <div>
                        <p class="text-xs font-semibold text-[#dae2fd] leading-tight">{{ $item[1] }}</p>
                        <p class="text-[10px] text-[#8c909f] mt-0.5">{{ $item[2] }}</p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Notification Analytics Bar Chart --}}
    <div class="col-span-12 lg:col-span-8 glass-effect rounded-2xl p-6 border border-[#424754]/10">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h4 class="font-headline font-bold text-xl mb-1 text-[#dae2fd]">Notification Volume</h4>
                <p class="text-sm text-[#c2c6d6]">Your notifications over the last 7 days</p>
            </div>
            <span class="material-symbols-outlined text-[#8c909f]">bar_chart</span>
        </div>

        {{-- Bar chart --}}
        <div class="flex gap-3 h-28 items-end mb-3">
            @php
                $dayLabels = [];
                for ($i = 6; $i >= 0; $i--) {
                    $dayLabels[] = now()->subDays($i)->format('D');
                }
            @endphp
            @foreach($dayCounts as $idx => $cnt)
            @php
                $pct = $maxDay > 0 ? max(8, round(($cnt / $maxDay) * 100)) : 8;
                $isToday = ($idx === count($dayCounts) - 1);
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-[9px] font-bold text-[#8c909f] mb-1">{{ $cnt > 0 ? $cnt : '' }}</span>
                <div class="w-full rounded-lg bar-fill relative group/bar"
                     style="height: {{ $pct }}%; background: {{ $isToday ? '#adc6ff' : '#2d3449' }};">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 opacity-0 group-hover/bar:opacity-100
                                transition-opacity text-[9px] font-bold text-[#adc6ff] whitespace-nowrap">
                        {{ $dayLabels[$idx] }}
                    </div>
                </div>
                <span class="text-[9px] text-[#8c909f] uppercase">{{ $dayLabels[$idx] }}</span>
            </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-[#424754]/10 flex justify-between items-center">
            <div class="flex gap-8">
                <div>
                    <p class="text-[10px] uppercase font-bold text-[#8c909f] mb-1">Total (7d)</p>
                    <p class="text-xl font-headline font-bold text-[#dae2fd]">{{ array_sum($dayCounts) }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold text-[#8c909f] mb-1">Unread</p>
                    <p class="text-xl font-headline font-bold {{ $stats['unread'] > 0 ? 'text-[#ffb4ab]' : 'text-[#4db6ac]' }}">
                        {{ $stats['unread'] }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold text-[#8c909f] mb-1">Read Rate</p>
                    <p class="text-xl font-headline font-bold text-[#adc6ff]">
                        {{ $stats['total'] > 0 ? round(($stats['read'] / $stats['total']) * 100) : 100 }}%
                    </p>
                </div>
            </div>
            <a href="{{ route('notifications.index') }}"
               class="text-[#adc6ff] text-xs font-bold uppercase tracking-widest hover:underline">
                Refresh
            </a>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // ── Filter ──
    function filterNotifs(category) {
        // Update button states
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        const btn = document.getElementById('filter-' + category);
        if (btn) btn.classList.add('active');
        // Also sync unread button in header if applicable
        if (category === 'unread') {
            document.querySelectorAll('.filter-btn').forEach(b => {
                if (b.textContent.trim().startsWith('Unread')) b.classList.add('active');
            });
        }

        const cards = document.querySelectorAll('.notif-card');
        let visible = 0;

        cards.forEach(card => {
            const cat  = card.dataset.category;
            const read = card.dataset.read === 'true';

            const show =
                category === 'all'    ? true :
                category === 'unread' ? !read :
                cat === category;

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        const noResults = document.getElementById('no-filter-results');
        noResults.style.display = visible === 0 ? 'flex' : 'none';
    }

    // ── View toggle (list / grid) ──
    function setView(mode) {
        const feed = document.getElementById('notif-feed');
        const listBtn = document.getElementById('view-list');
        const gridBtn = document.getElementById('view-grid');

        if (mode === 'grid') {
            feed.classList.remove('space-y-3');
            feed.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-4');
            listBtn.classList.remove('bg-[#2d3449]', 'text-[#adc6ff]');
            listBtn.classList.add('text-[#8c909f]');
            gridBtn.classList.add('bg-[#2d3449]', 'text-[#adc6ff]');
            gridBtn.classList.remove('text-[#8c909f]');
        } else {
            feed.classList.add('space-y-3');
            feed.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-4');
            gridBtn.classList.remove('bg-[#2d3449]', 'text-[#adc6ff]');
            gridBtn.classList.add('text-[#8c909f]');
            listBtn.classList.add('bg-[#2d3449]', 'text-[#adc6ff]');
            listBtn.classList.remove('text-[#8c909f]');
        }
    }
</script>
@endsection