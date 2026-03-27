<script>
    window.addEventListener('load', () => {
        const ctx = document.getElementById('bchart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Allocated',
                        data: {!! json_encode($chartAllocated) !!},
                        backgroundColor: 'rgba(21,101,192,0.65)',
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.55,
                        categoryPercentage: 0.72
                    },
                    {
                        label: 'Spent',
                        data: {!! json_encode($chartSpent) !!},
                        backgroundColor: 'rgba(66,165,245,0.85)',
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.55,
                        categoryPercentage: 0.72
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a2e42',
                        borderColor: 'rgba(255,255,255,.1)',
                        borderWidth: 1,
                        titleColor: '#E8EEF4',
                        bodyColor: '#8BAABF',
                        padding: 10,
                        callbacks: {
                            label: c => ' $' + c.parsed.y + 'K'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,.04)' },
                        ticks: { color: '#4A6880', font: { size: 10 } },
                        border: { color: 'rgba(255,255,255,.05)' }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,.05)' },
                        ticks: { color: '#4A6880', font: { size: 10 }, callback: v => '$' + v + 'K' },
                        border: { display: false }
                    }
                }
            }
        });
    });

    function openProjectModal(id, name, allocated, spent, progress, status, location) {
        const used = Math.round(spent / allocated * 100);
        const remaining = allocated - spent;
        openModal(name + ' — Project detail',
            `<div style="display:flex;gap:7px;margin-bottom:12px">
                <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(0,137,123,.2);color:#4DB6AC">${status === 'in_progress' ? 'Active' : status}</span>
                ${used > 85 ? '<span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(198,40,40,.2);color:#EF9A9A">Budget Critical</span>' : ''}
            </div>
            <div style="margin-bottom:8px">
                <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Overall progress</span><span style="color:var(--t1);font-weight:600">${progress}%</span></div>
                <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${progress}%;background:#42A5F5;border-radius:6px"></div></div>
            </div>
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Budget consumed</span><span style="color:${used > 85 ? '#EF9A9A' : '#FFB74D'};font-weight:600">${used}%${used > 85 ? ' — CRITICAL' : ''}</span></div>
                <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${Math.min(used, 100)}%;background:${used > 85 ? '#EF9A9A' : '#FFB74D'};border-radius:6px"></div></div>
            </div>
            <div class="dr"><span class="dl">Location</span><span class="dv">${location}</span></div>
            <div class="dr"><span class="dl">Budget allocated</span><span class="dv">$${Number(allocated).toLocaleString()}</span></div>
            <div class="dr"><span class="dl">Budget spent</span><span class="dv" style="color:${used > 85 ? '#EF9A9A' : 'var(--t1)'}">$${Number(spent).toLocaleString()}</span></div>
            <div class="dr"><span class="dl">Remaining</span><span class="dv" style="color:#4DB6AC">$${Number(remaining).toLocaleString()}</span></div>`,
            `<button class="btn btn-p" onclick="toast('Edit project opened','info');closeModal()">Edit project</button>
                <button class="btn btn-g" onclick="toast('PDF report downloaded','success');closeModal()">Export PDF</button>`
        );
    }

    function openIncidentModal(desc, severity, status, location, date) {
        openModal('Safety Incident Report',
            `<div style="display:flex;gap:7px;margin-bottom:12px">
                <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(245,124,0,.15);color:#FFB74D">${severity}</span>
                <span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:7px;background:rgba(21,101,192,.2);color:#64B5F6">${status}</span>
            </div>
            <div class="dr"><span class="dl">Date</span><span class="dv">${date}</span></div>
            <div class="dr"><span class="dl">Location</span><span class="dv">${location}</span></div>
            <div style="margin-top:12px;font-size:11px;color:var(--t3);margin-bottom:6px">Description</div>
            <div style="font-size:12px;color:var(--t2);line-height:1.55;background:var(--card2);border-radius:8px;padding:10px 12px">${desc}</div>`,
            `<button class="btn btn-s" onclick="toast('Incident marked resolved','success');closeModal()">Mark resolved</button>
                <button class="btn btn-g" onclick="toast('Incident PDF exported','success');closeModal()">Export PDF</button>`
        );
    }

    function openMemberModal(name, role, tasks) {
        openModal(name + ' — Profile',
            `<div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--bd)">
                <div style="width:46px;height:46px;border-radius:50%;background:#1565C0;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff">
                    ${name.substring(0, 2).toUpperCase()}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--t1)">${name}</div>
                    <div style="font-size:11px;color:var(--t3)">${role} · Active</div>
                </div>
            </div>
            <div class="dr"><span class="dl">Role</span><span class="dv">${role}</span></div>
            <div class="dr"><span class="dl">Active tasks</span><span class="dv">${tasks} tasks assigned</span></div>`,
            `<button class="btn btn-g" onclick="closeModal()">Close</button>`
        );
    }

    function openBudgetModal() {
        openModal('Budget overview — all projects',
            `<div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px">
                    <span style="color:var(--t3)">Total allocated</span>
                    <span style="color:var(--t1);font-weight:700">${{ number_format(array_sum(array_column($recentProjects->toArray(), 'budget_allocated'))) }}</span>
                </div>
                <div style="height:8px;background:rgba(255,255,255,.06);border-radius:8px;overflow:hidden">
                    <div style="height:8px;width:72%;background:#1565C0;border-radius:8px"></div>
                </div>
            </div>`,
            `<button class="btn btn-p" onclick="toast('Excel exported','success');closeModal()">Export Excel</button>
                <button class="btn btn-g" onclick="toast('PDF downloaded','success');closeModal()">Export PDF</button>`
        );
    }

    function openSafetyModal() {
        openModal('Safety & Compliance',
            `<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:16px">
                <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#4CAF50">{{ $daysSafe }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Days safe</div></div>
                <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#EF9A9A">{{ $openIncidents }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Open</div></div>
                <div style="background:var(--card2);border-radius:8px;padding:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#4DB6AC">{{ $resolvedIncidents }}</div><div style="font-size:10px;color:var(--t3);margin-top:2px">Resolved</div></div>
            </div>`,
            `<button class="btn btn-p" onclick="toast('Incident report form opened','info');closeModal()">+ Report incident</button>
                <button class="btn btn-g" onclick="toast('Safety report exported','success');closeModal()">Export report</button>`
        );
    }

    function openKanbanModal() {
        openModal('Task Kanban Board',
            `<div class="kboard">
                <div class="kcol">
                    <div class="kch">Pending <span class="kbadge">0</span></div>
                </div>
                <div class="kcol">
                    <div class="kch">In Progress <span class="kbadge">0</span></div>
                </div>
                <div class="kcol">
                    <div class="kch">Done <span class="kbadge">0</span></div>
                </div>
            </div>`,
            `<button class="btn btn-p" onclick="toast('New task form opened','info');closeModal()">+ New task</button>`
        );
    }

    function openAuditModal() {
        openModal('Audit Log — recent actions',
            `<div style="font-size:11px;color:var(--t3);margin-bottom:12px">Every change tracked automatically.</div>`,
            `<button class="btn btn-g" onclick="toast('Audit log exported','success');closeModal()">Export PDF</button>`
        );
    }

    function allProjectsHTML() {
        return `All projects list`;
    }
</script>

