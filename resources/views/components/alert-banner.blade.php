@props(['type' => 'info', 'message' => ''])
<div class="rounded border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-100">
    {{ $message ?: $slot }}
</div>
