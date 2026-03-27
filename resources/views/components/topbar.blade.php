{{-- resources/views/components/topbar.blade.php --}}
<div class="topbar">

    {{-- Title + date --}}
    <div>
        <div class="tb-title" id="page-title">@yield('page-title', 'Dashboard')</div>
        <div style="font-size:10px;color:var(--t3);margin-top:2px">
            {{ now()->format('l, d F Y') }} —
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ auth()->user()->name }}
        </div>
    </div>

    <div class="tb-r">

        {{-- Quarter pill --}}
        <div class="dpill">Q{{ ceil(now()->month / 3) }} {{ now()->year }}</div>

        {{-- Notification bell --}}
        <div class="ib" id="notif-bell" onclick="toggleNotif()">
            <svg width="15" height="15" viewBox="0 0 20 20" fill="#8BAABF">
                <path
                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
            </svg>
            <div class="ndot" id="notif-dot"></div>
        </div>

        {{-- Settings --}}
        <div class="ib" onclick="openSettingsModal()">
            <svg width="15" height="15" viewBox="0 0 20 20" fill="#8BAABF">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" />
            </svg>
        </div>

        {{-- User avatar with dropdown --}}
        <div style="position:relative">
            <div class="av" id="avatar-btn"
                style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#1565C0,#42A5F5);cursor:pointer"
                onclick="toggleUserMenu()">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div id="user-menu"
                style="position:absolute;top:100%;right:0;margin-top:8px;background:var(--bg);border:1px solid var(--bd);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.15);min-width:160px;z-index:1000;display:none">
                <button onclick="openProfileModal();closeUserMenu()"
                    style="width:100%;text-align:left;padding:10px 14px;font-size:12px;background:none;border:none;cursor:pointer;color:var(--t1);transition:all .2s;border-bottom:1px solid var(--bd)">
                    👤 View Profile
                </button>
                <button onclick="window.location.href='{{ route('profile.edit') }}';closeUserMenu()"
                    style="width:100%;text-align:left;padding:10px 14px;font-size:12px;background:none;border:none;cursor:pointer;color:var(--t1);transition:all .2s;border-bottom:1px solid var(--bd)">
                    ✏️ Edit Profile
                </button>
                <button onclick="openSettingsModal();closeUserMenu()"
                    style="width:100%;text-align:left;padding:10px 14px;font-size:12px;background:none;border:none;cursor:pointer;color:var(--t1);transition:all .2s;border-bottom:1px solid var(--bd)">
                    ⚙️ Settings
                </button>
                <form method="POST" action="{{ route('logout') }}" style="width:100%">
                    @csrf
                    <button type="submit"
                        style="width:100%;text-align:left;padding:10px 14px;font-size:12px;background:none;border:none;cursor:pointer;color:#EF5350;transition:all .2s">
                        🚪 Log out
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- NOTIFICATION DROPDOWN PANEL --}}
    <div class="np" id="notif-panel">
        <div class="nph">
            <span class="npt">Notifications</span>
            <button class="npc" onclick="markAllRead()">Mark all read</button>
        </div>

        <div class="npi" style="cursor:pointer" onclick="closeNotif();toast('Budget alert opened','warn')">
            <div class="npd" style="background:var(--red)"></div>
            <div>
                <div class="nptx"><b>Budget critical</b> — Skyline Tower at 91%</div>
                <div class="nptm">2 min ago</div>
            </div>
        </div>
        <div class="npi" onclick="closeNotif();toast('Task deadline opened','warn')">
            <div class="npd" style="background:var(--amber)"></div>
            <div>
                <div class="nptx"><b>Deadline in 3 days</b> — Steel Frame Installation</div>
                <div class="nptm">18 min ago</div>
            </div>
        </div>
        <div class="npi" onclick="closeNotif();toast('Safety incident opened','danger')">
            <div class="npd" style="background:var(--red)"></div>
            <div>
                <div class="nptx">New <b>safety incident</b> filed on Floor 7</div>
                <div class="nptm">1 hr ago</div>
            </div>
        </div>
        <div class="npi" onclick="closeNotif();toast('Document opened','info')">
            <div class="npd" style="background:var(--sky)"></div>
            <div>
                <div class="nptx"><b>Blueprint v3</b> uploaded — Skyline Tower</div>
                <div class="nptm">2 hr ago</div>
            </div>
        </div>
        <div class="npi" onclick="closeNotif();toast('Team member opened','info')">
            <div class="npd" style="background:var(--amber)"></div>
            <div>
                <div class="nptx"><b>Certification expiring</b> — Sarah Johnson, 30d</div>
                <div class="nptm">3 hr ago</div>
            </div>
        </div>
    </div>

</div>

<script>
    // ── NOTIFICATION PANEL ────────────────────────
    let notifOpen = false;
    function toggleNotif() {
        notifOpen = !notifOpen;
        document.getElementById('notif-panel').classList.toggle('open', notifOpen);
    }
    function closeNotif() {
        notifOpen = false;
        document.getElementById('notif-panel').classList.remove('open');
    }
    function markAllRead() {
        document.getElementById('notif-dot').style.display = 'none';
        document.querySelectorAll('.npi').forEach(i => i.style.opacity = '.35');
        toast('All notifications marked as read', 'success');
        closeNotif();
    }
    // close on outside click
    document.addEventListener('click', e => {
        const bell = document.getElementById('notif-bell');
        const panel = document.getElementById('notif-panel');
        if (panel && bell && !panel.contains(e.target) && !bell.contains(e.target)) closeNotif();
    });

    // ── SETTINGS MODAL ────────────────────────────
    function openSettingsModal() {
        openModal('System settings',
            `<div style="font-size:11px;font-weight:600;color:var(--t2);margin-bottom:10px">Budget alert thresholds</div>
        ${[['Alert at 70% budget used', true], ['Alert at 85% budget used', true], ['Alert at 95% budget used', true], ['Email notifications', true], ['In-app notifications', true]]
                .map(([l, on]) => `<div class="dr"><span class="dl">${l}</span>
        <div style="width:34px;height:20px;background:${on ? 'var(--green)' : 'rgba(255,255,255,.1)'};border-radius:10px;cursor:pointer;position:relative;transition:background .2s"
             onclick="this.style.background=this.style.background.includes('green')?'rgba(255,255,255,.1)':'var(--green)';toast('Setting toggled','info')">
            <div style="position:absolute;top:3px;${on ? 'right:3px' : 'left:3px'};width:14px;height:14px;background:#fff;border-radius:50%;transition:all .2s"></div>
        </div></div>`).join('')}
        <div style="font-size:11px;font-weight:600;color:var(--t2);margin:14px 0 8px">System</div>
        <div class="dr"><span class="dl">Currency</span><span class="dv">USD</span></div>
        <div class="dr"><span class="dl">Date format</span><span class="dv">Y-m-d</span></div>
        <div class="dr"><span class="dl">Session timeout</span><span class="dv">60 minutes</span></div>`,
            `<button class="btn btn-p" onclick="toast('Settings saved','success');closeModal()">Save settings</button>`
        );
    }

    // ── PROFILE MODAL ─────────────────────────────
    function openProfileModal() {
        openModal('My profile',
            `<div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--bd)">
            <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#1565C0,#42A5F5);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--t1)">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:var(--t3)">{{ auth()->user()->getRoleNames()->first() ?? 'User' }} · Active</div>
            </div>
        </div>
        <div class="dr"><span class="dl">Email</span><span class="dv">{{ auth()->user()->email }}</span></div>
        <div class="dr"><span class="dl">Role</span><span class="dv">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span></div>
        <div class="dr"><span class="dl">Member since</span><span class="dv">{{ auth()->user()->created_at->format('M d, Y') }}</span></div>
        <div class="dr"><span class="dl">Last login</span><span class="dv">{{ now()->format('M d, Y \\a\\t H:i') }}</span></div>
        <div class="dr"><span class="dl">2FA Status</span><span class="dv" style="color:#81C784">Active ✓</span></div>`,
            `<a href="{{ route('profile.edit') }}" class="btn btn-p" style="text-decoration:none;display:inline-block;text-align:center">Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-d">Log out</button>
        </form>`
        );
    }

    // ── USER MENU DROPDOWN ────────────────────────
    let userMenuOpen = false;
    function toggleUserMenu() {
        userMenuOpen = !userMenuOpen;
        document.getElementById('user-menu').style.display = userMenuOpen ? 'block' : 'none';
    }
    function closeUserMenu() {
        userMenuOpen = false;
        document.getElementById('user-menu').style.display = 'none';
    }
    // close user menu on outside click
    document.addEventListener('click', e => {
        const avatar = document.getElementById('avatar-btn');
        const menu = document.getElementById('user-menu');
        if (menu && avatar && !menu.contains(e.target) && !avatar.contains(e.target)) closeUserMenu();
    });
</script>