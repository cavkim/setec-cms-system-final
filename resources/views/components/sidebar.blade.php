{{-- resources/views/components/sidebar.blade.php --}}
{{-- This version hides nav items based on user permissions --}}
<div class="sb">

    {{-- LOGO --}}
    <div class="logo">
        <div class="logo-box">BS</div>
        <div>
            <div class="logo-name">BuildScape</div>
            <div class="logo-sub">CONSTRUCTION PRO</div>
        </div>
    </div>

    <nav style="flex:1;padding:8px 0;overflow-y:auto">

        <div class="nav-sec">Main</div>

        @can('view dashboard')
        <a href="{{ route('dashboard') }}" class="ni {{ request()->routeIs('dashboard') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><rect x="2" y="2" width="7" height="7" rx="1.5"/><rect x="11" y="2" width="7" height="7" rx="1.5"/><rect x="2" y="11" width="7" height="7" rx="1.5"/><rect x="11" y="11" width="7" height="7" rx="1.5"/></svg>
            Dashboard
        </a>
        @endcan

        @can('view projects')
        <a href="{{ route('projects.index') }}" class="ni {{ request()->routeIs('projects.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4z"/></svg>
            Projects
            <span class="nb nb-g">{{ \Illuminate\Support\Facades\DB::table('projects')->where('status','in_progress')->count() }}</span>
        </a>
        @endcan

        @can('view tasks')
        <a href="{{ route('tasks.index') }}" class="ni {{ request()->routeIs('tasks.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/></svg>
            Tasks
            <span class="nb nb-r">{{ \Illuminate\Support\Facades\DB::table('tasks')->whereIn('status',['pending','in_progress'])->count() }}</span>
        </a>
        @endcan

        @can('view team')
        <a href="{{ route('team.index') }}" class="ni {{ request()->routeIs('team.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
            Team
        </a>
        @endcan

        <div class="nav-sec">Finance</div>

        @can('view budget')
        <a href="{{ route('budget.index') }}" class="ni {{ request()->routeIs('budget.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/></svg>
            Budget
        </a>
        @endcan

        @can('view documents')
        <a href="{{ route('documents.index') }}" class="ni {{ request()->routeIs('documents.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
            Documents
        </a>
        @endcan

        <div class="nav-sec">Operations</div>

        @can('view safety')
        <a href="{{ route('safety.index') }}" class="ni {{ request()->routeIs('safety.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/></svg>
            Safety
            @php $openInc = \Illuminate\Support\Facades\DB::table('safety_incidents')->where('status','open')->count(); @endphp
            @if($openInc > 0)<span class="nb nb-r">{{ $openInc }}</span>@endif
        </a>
        @endcan

        @can('view reports')
        <a href="{{ route('reports.index') }}" class="ni {{ request()->routeIs('reports.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3z"/></svg>
            Reports
        </a>
        @endcan

        @can('view notifications')
        <a href="{{ route('notifications.index') }}" class="ni {{ request()->routeIs('notifications.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
            Notifications
        </a>
        @endcan

        @can('view audit log')
        <a href="{{ route('audit.index') }}" class="ni {{ request()->routeIs('audit.*') ? 'on' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"/></svg>
            Audit Log
        </a>
        @endcan

    </nav>

    {{-- User at bottom --}}
    <div class="sb-foot">
        <div class="u-card">
            <div class="av" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#1565C0,#42A5F5)">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:12px;font-weight:500;color:var(--t1)">{{ auth()->user()->name }}</div>
                <div style="font-size:10px;color:var(--t3)">
                    {{ ucfirst(str_replace('_',' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}
                </div>
            </div>
        </div>
    </div>

</div>