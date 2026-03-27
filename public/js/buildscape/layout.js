(function () {
    function byId(id) {
        return document.getElementById(id);
    }

    window.openModal = function (title, bodyHtml, footerHtml) {
        var overlay = byId('overlay');
        var titleEl = byId('mtitle');
        var bodyEl = byId('mbody');
        var footerEl = byId('mfoot');
        if (!overlay || !titleEl || !bodyEl || !footerEl) return;

        titleEl.textContent = title || 'Details';
        bodyEl.innerHTML = bodyHtml || '';
        footerEl.innerHTML = footerHtml || '<button class="btn btn-g" onclick="closeModal()">Close</button>';
        overlay.classList.add('show');
    };

    window.closeModal = function () {
        var overlay = byId('overlay');
        if (overlay) overlay.classList.remove('show');
    };

    window.toast = function (message, type) {
        if (!message) return;
        var wrap = byId('toastwrap');
        if (!wrap) return;

        var t = document.createElement('div');
        var kind = type || 'info';
        t.className = 'toast ' + kind;
        t.innerHTML = '<div class="tic">' + (kind === 'success' ? '✓' : kind === 'danger' ? '!' : kind === 'warn' ? '!' : 'i') + '</div><div>' + String(message) + '</div>';
        wrap.appendChild(t);

        requestAnimationFrame(function () {
            t.classList.add('in');
        });

        setTimeout(function () {
            t.classList.remove('in');
            setTimeout(function () {
                t.remove();
            }, 280);
        }, 2600);
    };

    window.dismissAlert = function (id) {
        var el = byId(id);
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateX(8px)';
        setTimeout(function () {
            el.remove();
        }, 180);
    };

    window.dismissAllAlerts = function () {
        document.querySelectorAll('.aw .al').forEach(function (el) {
            el.remove();
        });
        window.toast('All alerts dismissed', 'success');
    };

    window.tick = function (box) {
        if (!box) return;
        var done = box.classList.toggle('done');
        box.innerHTML = done
            ? '<svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            : '';
        var taskName = box.closest('.ti') ? box.closest('.ti').querySelector('.tn') : null;
        if (taskName) taskName.classList.toggle('done', done);
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.closeModal();
    });
})();
