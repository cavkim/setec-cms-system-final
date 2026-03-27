@extends('layouts.app')
@section('title', 'Notifications — BuildScape CMS')
@section('page-title', 'Notifications')

@section('content')

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>toast(@json(session('success')),'success'))</script>
@endif

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px">
    <div class="kpi" style="--ac:linear-gradient(90deg,#C62828,#EF9A9A)">
        <div class="kl">Unread</div>
        <div class="kv" style="color:#EF9A9A">{{ $stats['unread'] }}</div>
        <div class="kd {{ $stats['unread']>0?'kd-dn':'kd-n' }}">{{ $stats['unread']>0?'Needs attention':'All caught up' }}</div>
    </div>
    <div class="kpi" style="--ac:linear-gradient(90deg,#1565C0,#42A5F5)">
        <div class="kl">Total</div>
        <div class="kv">{{ $stats['total'] }}</div>
        <div class="kd kd-n">All notifications</div>
    </div>
    <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4DB6AC)">
        <div class="kl">Read</div>
        <div class="kv" style="color:#4DB6AC">{{ $stats['read'] }}</div>
        <div class="kd kd-up">Already seen</div>
    </div>
</div>

<div class="card">
    <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
        <div class="ct">All Notifications</div>
        @if($stats['unread'] > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}" style="display:inline">
            @csrf
            <button type="submit"
                    style="font-size:11px;font-weight:600;padding:7px 16px;border-radius:8px;background:rgba(0,137,123,.2);color:#4DB6AC;border:1px solid rgba(0,137,123,.2);cursor:pointer;font-family:inherit">
                ✓ Mark all read
            </button>
        </form>
        @endif
    </div>

    @if($hasColumns && $notifications->total() > 0)

    @foreach($notifications as $notif)
    @php
        $data    = json_decode($notif->data, true) ?? [];
        $message = $data['message'] ?? $data['title'] ?? 'New notification';
        $type    = $data['type'] ?? 'info';
        $isUnread = is_null($notif->read_at);
        $dotColor = $type==='danger'?'var(--red)':($type==='warning'?'var(--amber)':($type==='success'?'var(--green)':'var(--sky)'));
        $borderColor = $isUnread
            ? ($type==='danger'?'rgba(198,40,40,.3)':($type==='warning'?'rgba(245,124,0,.25)':'rgba(21,101,192,.2)'))
            : 'var(--bd)';
    @endphp
    <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 18px;border-bottom:1px solid var(--bd);transition:background .15s;
                {{ $isUnread?'border-left:3px solid '.$dotColor.';background:rgba(255,255,255,.02)':'' }}"
         onmouseenter="this.style.background='var(--card2)'" onmouseleave="this.style.background='{{ $isUnread?'rgba(255,255,255,.02)':'transparent' }}'">

        <div style="width:9px;height:9px;border-radius:50%;background:{{ $dotColor }};flex-shrink:0;margin-top:5px;
                    {{ !$isUnread?'opacity:.3':'' }}"></div>

        <div style="flex:1;min-width:0">
            <div style="font-size:12px;font-weight:{{ $isUnread?'600':'400' }};color:{{ $isUnread?'var(--t1)':'var(--t2)' }};margin-bottom:3px">
                {{ $message }}
            </div>
            @if(isset($data['body']))
            <div style="font-size:11px;color:var(--t3);line-height:1.5">{{ $data['body'] }}</div>
            @endif
            <div style="font-size:10px;color:var(--t3);margin-top:4px">
                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
            </div>
        </div>

        @if($isUnread)
        <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:8px;background:var(--red);color:#fff;white-space:nowrap;flex-shrink:0">
            UNREAD
        </span>
        @else
        <span style="font-size:10px;color:var(--t3);flex-shrink:0">Read</span>
        @endif

    </div>
    @endforeach

    @if($notifications->hasPages())
    <div style="padding:14px 18px;border-top:1px solid var(--bd);display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:11px;color:var(--t3)">Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} of {{ $notifications->total() }}</span>
        <div style="display:flex;gap:6px">
            @if(!$notifications->onFirstPage())
            <a href="{{ $notifications->previousPageUrl() }}" style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--card2);color:var(--t2);border:1px solid var(--bd);text-decoration:none">← Prev</a>
            @endif
            @if($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}" style="font-size:11px;padding:5px 10px;border-radius:6px;background:var(--blue);color:#fff;text-decoration:none">Next →</a>
            @endif
        </div>
    </div>
    @endif

    @elseif(!$hasColumns)
    <div style="padding:52px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">🔔</div>
        <div style="font-size:14px;font-weight:600;color:var(--t1);margin-bottom:8px">Notifications need setup</div>
        <div style="font-size:12px;color:var(--t3);margin-bottom:16px;line-height:1.7">
            Run the migration to activate the notifications table.
        </div>
        <code style="background:var(--card2);padding:8px 16px;border-radius:8px;font-size:12px;color:#42A5F5">php artisan migrate</code>
    </div>

    @else
    <div style="padding:52px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">✅</div>
        <div style="font-size:14px;font-weight:600;color:var(--t1);margin-bottom:6px">All caught up!</div>
        <div style="font-size:12px;color:var(--t3)">No notifications yet. They'll appear here automatically.</div>
    </div>
    @endif

</div>

@endsection
