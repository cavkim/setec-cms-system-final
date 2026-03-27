<div class="mb-8">
    <div class="bg-surface-container-high border border-white/5 p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-extrabold text-on-surface">Active Alerts</h3>
            <button class="text-primary text-xs font-bold hover:underline" onclick="dismissAllAlerts()">
                Dismiss all
            </button>
        </div>

        {{-- Keep `.aw .al` classes so existing dismiss logic keeps working --}}
        <div class="aw grid grid-cols-1 md:grid-cols-3 gap-4 !p-0">
            {{-- Budget alerts from DB --}}
            @foreach ($budgetAlerts as $i => $alert)
                <div class="al al-r" id="alert-budget-{{ $i }}" style="margin-bottom:0">
                    <div class="al-dot" style="background:var(--red)"></div>
                    <div style="flex:1">
                        <div class="al-title flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-error"
                                style="font-variation-settings:'FILL' 1;">warning</span>
                            <span>Budget Critical — {{ $alert->project_name }} at {{ round($alert->budget_used_percent) }}%
                                used</span>
                        </div>
                        <div class="al-desc">
                            ${{ number_format($alert->budget_spent) }} of ${{ number_format($alert->budget_allocated) }}
                            allocated.
                        </div>
                    </div>
                    <div class="al-x" onclick="dismissAlert('alert-budget-{{ $i }}')">×</div>
                </div>
            @endforeach

            {{-- Deadline alerts from DB --}}
            @foreach ($deadlineTasks as $i => $task)
                <div class="al al-a" id="alert-deadline-{{ $i }}" style="margin-bottom:0">
                    <div class="al-dot" style="background:var(--amber)"></div>
                    <div style="flex:1">
                        <div class="al-title flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-secondary"
                                style="font-variation-settings:'FILL' 1;">event</span>
                            <span>Deadline soon — {{ $task->task_name }} due
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                        </div>
                        <div class="al-desc">
                            {{ $task->project_name }} — Currently {{ $task->progress_percent }}% complete
                        </div>
                    </div>
                    <div class="al-x" onclick="dismissAlert('alert-deadline-{{ $i }}')">×</div>
                </div>
            @endforeach

            {{-- Cert expiry alerts from DB --}}
            @foreach ($expiringCerts as $i => $member)
                <div class="al al-b" id="alert-cert-{{ $i }}" style="margin-bottom:0">
                    <div class="al-dot" style="background:var(--sky)"></div>
                    <div style="flex:1">
                        <div class="al-title flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-tertiary"
                                style="font-variation-settings:'FILL' 1;">badge</span>
                            <span>Certification expiring — {{ $member->name }} in
                                {{ \Carbon\Carbon::parse($member->certification_expiry)->diffInDays() }} days</span>
                        </div>
                        <div class="al-desc">
                            {{ $member->certification_number }} expires
                            {{ \Carbon\Carbon::parse($member->certification_expiry)->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="al-x" onclick="dismissAlert('alert-cert-{{ $i }}')">×</div>
                </div>
            @endforeach
        </div>
    </div>
</div>