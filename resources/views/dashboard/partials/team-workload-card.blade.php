{{-- Team Workload --}}
<div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-on-surface">Team Workload</h3>
        <button class="text-primary text-xs font-bold hover:underline"
            onclick="toast('Team module coming soon','info')">Manage →</button>
    </div>

    <div class="space-y-3 flex-1">
        @php
            $avatarColors = ['bg-blue-700', 'bg-teal-700', 'bg-orange-700', 'bg-red-800', 'bg-purple-800', 'bg-cyan-700'];
        @endphp
        @foreach ($teamWorkload as $i => $member)
            <div class="flex items-center gap-3 p-2 hover:bg-white/[0.04] rounded-lg cursor-pointer transition-colors"
                onclick="openMemberModal('{{ $member->name }}', '{{ $member->role ?? 'Team Member' }}', {{ $member->task_count }})">
                <div
                    class="w-8 h-8 rounded-full {{ $avatarColors[$i % count($avatarColors)] }} flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-on-surface truncate">{{ $member->name }}</div>
                    <div class="text-xs text-on-surface-variant">{{ $member->role ?? 'Team Member' }}</div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-12 h-1.5 bg-surface-container-lowest rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $avatarColors[$i % count($avatarColors)] }}"
                            style="width:{{ min(($member->task_count / 10) * 100, 100) }}%"></div>
                    </div>
                    <span
                        class="text-xs font-bold text-on-surface-variant whitespace-nowrap">{{ $member->task_count }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>