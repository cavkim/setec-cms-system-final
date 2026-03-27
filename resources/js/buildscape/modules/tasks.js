function switchView(v) {
    const isList = v === 'list';
    document.getElementById('list-view').style.display = isList ? 'block' : 'none';
    document.getElementById('kanban-view').style.display = isList ? 'none' : 'block';
    document.getElementById('btn-list').classList.toggle('on', isList);
    document.getElementById('btn-kanban').classList.toggle('on', !isList);
    if (!isList) initKanban();
}

function initKanban() {
    ['pending', 'in_progress', 'completed'].forEach(status => {
        const col = document.getElementById('col-' + status);
        if (!col || col._sortable) return;
        col._sortable = Sortable.create(col, {
            group: 'tasks', animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd(evt) {
                const taskId = evt.item.dataset.id;
                const newStatus = evt.to.dataset.status;
                updateCounts();
                fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ status: newStatus })
                }).then(() => toast('Moved to ' + newStatus.replace('_', ' '), 'success'))
                    .catch(() => toast('Could not update status', 'danger'));
            }
        });
    });
    updateCounts();
}

function updateCounts() {
    ['pending', 'in_progress', 'completed'].forEach(s => {
        const col = document.getElementById('col-' + s);
        const cnt = document.getElementById('count-' + s);
        if (col && cnt) cnt.textContent = col.querySelectorAll('.k-card').length;
    });
}

function toggleTaskDone(cb, id) {
    const isDone = cb.style.background !== 'var(--green)';
    cb.style.background = isDone ? 'var(--green)' : 'transparent';
    cb.style.borderColor = isDone ? 'var(--green)' : 'var(--t3)';
    cb.innerHTML = isDone
        ? '<svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>'
        : '';
    const nameCell = cb.closest('tr').querySelectorAll('td')[1].querySelector('div');
    if (nameCell) {
        nameCell.style.textDecoration = isDone ? 'line-through' : 'none';
        nameCell.style.color = isDone ? 'var(--t3)' : 'var(--t1)';
    }
    fetch(`/tasks/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status: isDone ? 'completed' : 'pending' })
    }).then(() => toast(isDone ? 'Task marked complete ✓' : 'Task reopened', isDone ? 'success' : 'warn'));
}

function openCreateTaskPopup() {
    document.getElementById('create-task-form').reset();
    document.getElementById('ct-error').style.display = 'none';
    document.getElementById('create-task-popup').classList.add('show');
    setTimeout(() => document.getElementById('ct-name').focus(), 200);
}
function closeCreateTaskPopup() { document.getElementById('create-task-popup').classList.remove('show'); }

function openEditTaskPopup(id, name, projectId, priority, status, dueDate, assignedTo, desc) {
    document.getElementById('edit-task-title').textContent = 'Edit — ' + name;
    document.getElementById('edit-task-form').action = '/tasks/' + id;
    document.getElementById('et-name').value = name;
    document.getElementById('et-project').value = projectId;
    document.getElementById('et-priority').value = priority;
    document.getElementById('et-status').value = status;
    document.getElementById('et-due').value = dueDate || '';
    document.getElementById('et-desc').value = desc || '';
    if (assignedTo) document.getElementById('et-assigned').value = assignedTo;
    document.getElementById('et-error').style.display = 'none';
    document.getElementById('edit-task-popup').classList.add('show');
    setTimeout(() => document.getElementById('et-name').focus(), 200);
}
function closeEditTaskPopup() { document.getElementById('edit-task-popup').classList.remove('show'); }

function confirmDeleteTask(id, name) {
    document.getElementById('delete-task-name').textContent = '"' + name + '"';
    document.getElementById('delete-task-form').action = '/tasks/' + id;
    document.getElementById('delete-task-popup').classList.add('show');
}
function closeDeleteTaskPopup() { document.getElementById('delete-task-popup').classList.remove('show'); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeCreateTaskPopup();
        closeEditTaskPopup();
        closeDeleteTaskPopup();
    }
});
