{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')
@section('title','Access Denied')
@section('page-title','Access Denied')

@section('content')
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;text-align:center">

    <div style="width:70px;height:70px;border-radius:50%;background:rgba(198,40,40,.15);border:1px solid rgba(198,40,40,.25);display:flex;align-items:center;justify-content:center;margin-bottom:20px">
        <svg width="28" height="28" viewBox="0 0 20 20" fill="#EF9A9A">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
        </svg>
    </div>

    <div style="font-size:48px;font-weight:700;color:var(--t3);margin-bottom:8px">403</div>
    <div style="font-size:20px;font-weight:600;color:var(--t1);margin-bottom:10px">Access Denied</div>
    <div style="font-size:13px;color:var(--t3);margin-bottom:24px;max-width:380px;line-height:1.7">
        You don't have permission to view this page.<br>
        Contact your administrator if you think this is a mistake.
    </div>

    <div style="display:flex;gap:10px">
        <a href="{{ route('dashboard') }}"
           style="font-size:12px;font-weight:600;padding:9px 22px;border-radius:8px;background:var(--blue);color:#fff;text-decoration:none">
            ← Back to Dashboard
        </a>
        <a href="javascript:history.back()"
           style="font-size:12px;font-weight:600;padding:9px 22px;border-radius:8px;background:var(--card2);color:var(--t2);border:1px solid var(--bd);text-decoration:none">
            Go back
        </a>
    </div>

    <div style="margin-top:28px;font-size:11px;color:var(--t3)">
        Logged in as <strong style="color:var(--t2)">{{ auth()->user()->name }}</strong>
        · Role: <strong style="color:var(--t2)">{{ ucfirst(str_replace('_',' ',auth()->user()->getRoleNames()->first() ?? 'Unknown')) }}</strong>
    </div>

</div>
@endsection
