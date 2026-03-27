{{-- Recent Activity --}}
<div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-on-surface">Recent Activity</h3>
        <button class="text-primary text-xs font-bold hover:underline" onclick="openAuditModal()">Audit log →</button>
    </div>

    <div class="space-y-2 flex-1">
        @php $dotColors = ['bg-primary', 'bg-error', 'bg-secondary', 'bg-tertiary', 'bg-primary-container', 'bg-secondary-container']; @endphp
        @foreach ($recentActivity as $i => $activity)
            <div class="flex items-start gap-3 p-3 hover:bg-white/[0.04] rounded-lg cursor-pointer transition-colors"
                onclick="toast('{{ addslashes($activity->description) }}','info')">
                <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1.5 {{ $dotColors[$i % count($dotColors)] }}"></div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-on-surface">
                        <strong>{{ $activity->causer?->name ?? 'System' }}</strong>
                        {{ \Illuminate\Support\Str::limit($activity->description, 40) }}
                    </div>
                </div>
                <div class="text-xs text-on-surface-variant whitespace-nowrap">
                    {{ $activity->created_at->diffForHumans(null, true, true, 1) }}</div>
            </div>
        @endforeach
    </div>
</div>