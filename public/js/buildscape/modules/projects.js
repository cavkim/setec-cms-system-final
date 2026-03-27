
// ══════════════════════════════════════════
// CREATE POPUP
// ══════════════════════════════════════════
function openCreatePopup() {
    document.getElementById('create-form').reset();
    document.getElementById('create-error').style.display = 'none';
    document.getElementById('budget-preview').classList.remove('show');
    document.getElementById('duration-hint').textContent = '';
    updateStatusColor(document.getElementById('c-status'));
    document.getElementById('create-popup').classList.add('show');
    setTimeout(() => document.getElementById('c-name').focus(), 200);
}
function closeCreatePopup() {
    document.getElementById('create-popup').classList.remove('show');
}

function submitCreate() {
    const name = document.getElementById('c-name').value.trim();
    if (!name) {
        showErr('create-error', 'Project name is required.');
        document.getElementById('c-name').focus();
        return;
    }
    const start = document.getElementById('c-start').value;
    const end = document.getElementById('c-end').value;
    if (start && end && end < start) {
        showErr('create-error', 'End date cannot be before start date.');
        return;
    }
    const btn = document.getElementById('create-submit-btn');
    btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor" style="animation:spin .8s linear infinite"><path d="M10 3a7 7 0 100 14A7 7 0 0010 3zm0 2a5 5 0 110 10A5 5 0 0110 5z" opacity=".3"/><path d="M10 3a7 7 0 017 7h-2a5 5 0 00-5-5V3z"/></svg> Creating...';
    btn.disabled = true;
    document.getElementById('create-form').submit();
}

// Status color + hint
const statusInfo = {
    planning: { color: '#64B5F6', hint: 'Project is in planning phase' },
    in_progress: { color: '#42A5F5', hint: 'Project is actively being worked on' },
    on_hold: { color: '#FFB74D', hint: 'Project is temporarily paused' },
    completed: { color: '#81C784', hint: 'Project has been finished' },
    cancelled: { color: '#8BAABF', hint: 'Project has been cancelled' },
};
function updateStatusColor(sel) {
    const info = statusInfo[sel.value] || statusInfo.planning;
    document.getElementById('status-dot').style.background = info.color;
    document.getElementById('status-label').textContent = info.hint;
}

// Budget formatted preview
function updateBudgetPreview(val) {
    const prev = document.getElementById('budget-preview');
    const fmt = document.getElementById('budget-formatted');
    if (val && parseFloat(val) > 0) {
        prev.classList.add('show');
        const n = parseFloat(val);
        fmt.textContent = '$' + n.toLocaleString('en-US') + ' USD';
        fmt.style.color = n >= 10000000 ? '#EF9A9A' : n >= 1000000 ? '#FFCC80' : '#4DB6AC';
    } else {
        prev.classList.remove('show');
    }
}

// Duration hint
document.addEventListener('DOMContentLoaded', () => {
    ['c-start', 'c-end'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', updateDuration);
    });
});
function updateDuration() {
    const s = document.getElementById('c-start')?.value;
    const e = document.getElementById('c-end')?.value;
    const hint = document.getElementById('duration-hint');
    if (s && e) {
        const days = Math.round((new Date(e) - new Date(s)) / (1000 * 60 * 60 * 24));
        if (days < 0) {
            hint.textContent = '⚠ End date is before start date';
            hint.style.color = '#EF9A9A';
        } else {
            const months = Math.round(days / 30);
            hint.textContent = `Duration: ${days} days (~${months} months)`;
            hint.style.color = '#4A6880';
        }
    } else {
        hint.textContent = '';
    }
}

// ══════════════════════════════════════════
// EDIT POPUP
// ══════════════════════════════════════════
function openEditPopup(id, name, loc, status, progress, budget, start, end) {
    document.getElementById('edit-popup-title').textContent = 'Edit — ' + name;
    document.getElementById('edit-form').action = '/projects/' + id;
    document.getElementById('e-name').value = name;
    document.getElementById('e-loc').value = loc || '';
    document.getElementById('e-status').value = status;
    document.getElementById('e-budget').value = budget;
    document.getElementById('e-start').value = start || '';
    document.getElementById('e-end').value = end || '';

    const prog = document.getElementById('e-progress');
    prog.value = progress;
    document.getElementById('e-pv').textContent = progress + '%';
    document.getElementById('e-progress-bar').style.width = progress + '%';
    prog.oninput = function () {
        document.getElementById('e-pv').textContent = this.value + '%';
        document.getElementById('e-progress-bar').style.width = this.value + '%';
    };

    document.getElementById('edit-error').style.display = 'none';
    document.getElementById('edit-popup').classList.add('show');
    setTimeout(() => document.getElementById('e-name').focus(), 200);
}
function closeEditPopup() {
    document.getElementById('edit-popup').classList.remove('show');
}

// ══════════════════════════════════════════
// DELETE POPUP
// ══════════════════════════════════════════
function confirmDelete(id, name) {
    document.getElementById('delete-name').textContent = '"' + name + '"';
    document.getElementById('delete-form').action = '/projects/' + id;
    document.getElementById('delete-popup').classList.add('show');
}
function closeDeletePopup() {
    document.getElementById('delete-popup').classList.remove('show');
}

// ══════════════════════════════════════════
// DETAIL MODAL (reuse app.blade modal)
// ══════════════════════════════════════════
function openDetailModal(name, loc, status, progress, allocated, spent, start, end, totalTasks, doneTasks) {
    const used = allocated > 0 ? Math.round(spent / allocated * 100) : 0;
    const remaining = allocated - spent;
    const color = status === 'in_progress' ? '#42A5F5' : (status === 'on_hold' ? '#FFB74D' : (status === 'planning' ? '#64B5F6' : (status === 'completed' ? '#81C784' : '#8BAABF')));
    const label = status === 'in_progress' ? 'Active' : status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    openModal(name,
        `<div style="display:flex;gap:7px;margin-bottom:14px">
        <span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;background:rgba(21,101,192,.2);color:${color}">${label}</span>
        ${used > 85 ? '<span style="font-size:9px;font-weight:700;padding:3px 9px;border-radius:8px;background:rgba(198,40,40,.2);color:#EF9A9A">Budget Critical ⚠</span>' : ''}
    </div>
    <div style="margin-bottom:10px">
        <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Progress</span><span style="color:var(--t1);font-weight:600">${progress}%</span></div>
        <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${progress}%;background:${color};border-radius:6px"></div></div>
    </div>
    <div style="margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--t3)">Budget used</span><span style="color:${used > 85 ? 'var(--red)' : used > 70 ? '#FFB74D' : '#4DB6AC'};font-weight:600">${used}%</span></div>
        <div style="height:6px;background:rgba(255,255,255,.06);border-radius:6px"><div style="height:6px;width:${Math.min(used, 100)}%;background:${used > 85 ? 'var(--red)' : used > 70 ? '#FFB74D' : '#4DB6AC'};border-radius:6px"></div></div>
    </div>
    <div class="dr"><span class="dl">Location</span><span class="dv">${loc || '—'}</span></div>
    <div class="dr"><span class="dl">Allocated</span><span class="dv">$${Number(allocated).toLocaleString()}</span></div>
    <div class="dr"><span class="dl">Spent</span><span class="dv" style="color:${used > 85 ? 'var(--red)' : 'var(--t1)'}">$${Number(spent).toLocaleString()}</span></div>
    <div class="dr"><span class="dl">Remaining</span><span class="dv" style="color:#4DB6AC">$${Number(remaining).toLocaleString()}</span></div>
    <div class="dr"><span class="dl">Start</span><span class="dv">${start || '—'}</span></div>
    <div class="dr"><span class="dl">Deadline</span><span class="dv">${end || '—'}</span></div>
    <div class="dr"><span class="dl">Tasks</span><span class="dv"><span style="color:#81C784">${doneTasks}</span> / ${totalTasks} completed</span></div>`,
        `<button class="btn btn-g" onclick="closeModal()">Close</button>`
    );
}

// ══════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════
function filterStatus(s) { window.location = '/projects?status=' + encodeURIComponent(s); }
function showErr(id, msg) { const e = document.getElementById(id); if (e) { e.textContent = msg; e.style.display = 'block'; } }

// ESC key closes any open popup
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeCreatePopup();
        closeEditPopup();
        closeDeletePopup();
        closeModal();
    }
});

// Spin animation for submit loading
const style = document.createElement('style');
style.textContent = '@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}';
document.head.appendChild(style);
