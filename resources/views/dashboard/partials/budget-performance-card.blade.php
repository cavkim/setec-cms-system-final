<div class="bg-surface-container-high border border-white/5 p-8 rounded-xl shadow-lg">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h3 class="text-xl font-extrabold text-on-surface">Budget Performance</h3>
            <p class="text-on-surface-variant text-xs">Allocated vs Spent (last 6 months)</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-primary opacity-50"></span>
                <span class="text-xs text-on-surface-variant">Allocated</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-primary"></span>
                <span class="text-xs text-on-surface-variant">Spent</span>
            </div>
        </div>
    </div>

    <div class="h-48 w-full relative">
        <canvas id="bchart"></canvas>
    </div>
</div>