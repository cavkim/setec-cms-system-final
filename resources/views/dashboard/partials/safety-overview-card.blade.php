{{-- Safety Overview --}}
<div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-extrabold text-on-surface">Safety Overview</h3>
        <button class="text-primary text-xs font-bold hover:underline" onclick="openSafetyModal()">Report →</button>
    </div>

    <div class="flex flex-col gap-4">
        <div class="bg-surface-container rounded-xl p-6 text-center">
            <div class="text-5xl font-extrabold text-primary mb-2">{{ $daysSafe }}</div>
            <div class="text-sm font-semibold text-on-surface-variant">Days since last incident</div>
        </div>

        <div class="grid grid-cols-3 gap-2 border-y border-white/5 py-4">
            <div class="text-center">
                <div class="font-bold text-lg text-error">{{ $openIncidents }}</div>
                <div class="text-[10px] text-on-surface-variant">Open</div>
            </div>
            <div class="text-center">
                <div class="font-bold text-lg text-secondary">{{ $investigatingIncidents }}</div>
                <div class="text-[10px] text-on-surface-variant">Investigating</div>
            </div>
            <div class="text-center">
                <div class="font-bold text-lg text-tertiary">{{ $resolvedIncidents }}</div>
                <div class="text-[10px] text-on-surface-variant">Resolved</div>
            </div>
        </div>

        <div class="space-y-2 mt-2">
            @foreach ($recentIncidents as $incident)
                <div class="flex items-center gap-3 p-3 px-3.5 hover:bg-white/[0.04] rounded-lg cursor-pointer transition-colors"
                    onclick="openIncidentModal('{{ addslashes($incident->description) }}', '{{ $incident->severity }}', '{{ $incident->status }}', '{{ $incident->location }}', '{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}')">
                    <div
                        class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ in_array($incident->severity, ['high', 'critical']) ? 'bg-error' : ($incident->severity === 'medium' ? 'bg-secondary' : 'bg-tertiary') }}">
                    </div>
                    <div class="flex-1 text-sm text-on-surface-variant truncate">
                        {{ Str::limit($incident->description, 42) }}</div>
                    <div class="text-xs text-on-surface-variant whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d') }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>