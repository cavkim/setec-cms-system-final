@extends('layouts.app')
@section('title', 'Budget')
@section('page-title', 'Budget')

@section('styles')
    <style>
        #expense-drawer {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #expense-drawer.open {
            transform: translateX(0);
        }

        #expense-drawer-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #expense-drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .row-active {
            background-color: rgba(173, 198, 255, 0.08) !important;
        }
    </style>
@endsection

@section('content')

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'))</script>
    @endif

    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
        {{-- Action Links --}}
        <div class="flex gap-2 p-1 bg-surface-container-low rounded-xl flex-wrap">
            <a href="{{ route('budget.categories.index') }}" class="px-6 py-2 text-slate-400 hover:text-on-surface font-semibold rounded-lg text-sm transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1;">category</span>
                Manage Categories
            </a>
            <a href="#" class="px-6 py-2 text-slate-400 hover:text-on-surface font-semibold rounded-lg text-sm transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-base">description</span>
                Export Excel
            </a>
            <a href="#" class="px-6 py-2 text-slate-400 hover:text-on-surface font-semibold rounded-lg text-sm transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                Export PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 ">
        <div class="bg-surface-container rounded-2xl p-7 relative overflow-hidden shadow-lg group">
            <span
                class="material-symbols-outlined absolute top-4 right-4 text-6xl text-white/5 group-hover:text-white/10 transition-colors">account_balance</span>
            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Total Allocated</p>
            <p class="text-4xl font-extrabold text-white font-headline">
                ${{ number_format($stats['total_allocated'] / 1000000, 1) }}M</p>
            <p class="text-xs text-on-surface-variant mt-3 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm text-primary">trending_up</span> Across all projects
            </p>
        </div>
        <div class="bg-surface-container-high rounded-2xl p-7 shadow-lg">
            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Total Spent</p>
            <div class="flex items-end gap-3 mt-1">
                <p class="text-4xl font-extrabold text-white font-headline">
                    ${{ number_format($stats['total_spent'] / 1000000, 1) }}M</p>
                <p class="text-xl font-bold text-primary pb-1">{{ $stats['pct_used'] }}%</p>
            </div>
            <div class="w-full h-2 bg-surface-container-lowest rounded-full overflow-hidden mt-4">
                <div class="h-full bg-primary rounded-full" style="width:{{ $stats['pct_used'] }}%"></div>
            </div>
            <p class="text-xs text-on-surface-variant mt-2">Utilization tracking as projected</p>
        </div>
        <div class="bg-surface-container rounded-2xl p-7 shadow-lg border-l-4 border-secondary">
            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">Remaining Funds</p>
            <p class="text-4xl font-extrabold text-secondary font-headline mt-1">
                ${{ number_format($stats['total_remaining'] / 1000000, 1) }}M</p>
            <p class="text-xs text-on-surface-variant mt-3 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">calendar_month</span> {{ 100 - $stats['pct_used'] }}% still
                available
            </p>
        </div>
    </div>


        @if($alerts->count() > 0)
        <div class="space-y-2">
            @foreach($alerts->take(2) as $alert)
                <div
                    class="flex items-center gap-3 px-5 py-3 rounded-xl border {{ $alert->budget_pct >= 90 ? 'bg-error/10 border-error/20' : 'bg-secondary/10 border-secondary/25' }}">
                    <span class="material-symbols-outlined text-sm {{ $alert->budget_pct >= 90 ? 'text-error' : 'text-secondary' }}"
                        style="font-variation-settings:'FILL' 1;">warning</span>
                    <p class="font-semibold text-sm {{ $alert->budget_pct >= 90 ? 'text-error' : 'text-secondary' }} flex-1">
                        {{ $alert->budget_pct >= 90 ? 'CRITICAL' : 'WARNING' }} — {{ $alert->project_name }} at
                        {{ $alert->budget_pct }}% budget used
                    </p>
                    <span
                        class="text-xs text-on-surface-variant">${{ number_format($alert->budget_allocated - $alert->budget_spent) }}
                        remaining</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="bg-surface-container rounded-2xl p-8 shadow-lg">
            <h3 class="text-xl font-bold font-headline text-white mb-7">Budget by Project</h3>
            <div class="space-y-6">
                @foreach($projects as $p)
                    @php
                        $pct = $p->budget_pct ?? 0;
                        $barClass = $pct >= 90 ? 'bg-error' : ($pct >= 70 ? 'bg-secondary' : 'bg-primary');
                        $textClass = $pct >= 90 ? 'text-error' : ($pct >= 70 ? 'text-secondary' : 'text-primary');
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-on-surface">{{ $p->project_name }}</span>
                            <span class="text-on-surface-variant">${{ number_format($p->budget_allocated / 1000000, 1) }}M <span
                                    class="{{ $textClass }} font-bold">({{ $pct }}%{{ $pct >= 90 ? ' ⚠' : '' }})</span></span>
                        </div>
                        <div class="w-full h-2 bg-surface-container-lowest rounded-full overflow-hidden">
                            <div class="{{ $barClass }} h-full rounded-full" style="width:{{ min($pct, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-on-surface-variant mt-1">
                            <span>Spent: ${{ number_format($p->budget_spent) }}</span>
                            <span>Remaining: ${{ number_format($p->budget_allocated - $p->budget_spent) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-surface-container rounded-2xl p-8 shadow-lg">
            <h3 class="text-xl font-bold font-headline text-white mb-7">Category Breakdown</h3>
            @if($hasCategories && $budgetByCategory->count() > 0)
                <div class="grid grid-cols-2 gap-x-10 gap-y-5">
                    @foreach($budgetByCategory as $cat)
                        @php $pct2 = $stats['total_spent'] > 0 ? round(($cat->total_spent / $stats['total_spent']) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs font-bold uppercase text-on-surface-variant">{{ $cat->category_name }}</span>
                                <span class="text-xs font-bold text-on-surface">{{ $pct2 }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-surface-container-lowest rounded-full overflow-hidden">
                                <div class="h-full rounded-full"
                                    style="width:{{ $pct2 }}%;background:{{ $cat->color_hex ?? '#adc6ff' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-40 text-on-surface-variant gap-3">
                    <span class="material-symbols-outlined text-4xl opacity-30">pie_chart</span>
                    <p class="text-sm">No category data yet</p>
                    @if(!$hasExpenses)
                        <code
                            class="text-xs text-primary bg-surface-container-highest px-3 py-1.5 rounded-lg">php artisan migrate</code>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($hasExpenses)
        <div class="bg-surface-container rounded-2xl overflow-hidden shadow-2xl mb-6">
            <div
                class="px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-surface-container-high/50 border-b border-white/5">
                <h3 class="text-xl font-bold font-headline text-white">Recent Expenses</h3>
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $v => $l)
                        <a href="{{ route('budget.index', ['status' => $v, 'project_id' => request('project_id')]) }}"
                            class="px-3 py-1 text-xs font-bold rounded-full transition-all {{ request('status', 'all') === $v ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant hover:text-on-surface' }}">{{ $l }}</a>
                    @endforeach
                </div>
            </div>
            <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 500px);">
                <table class="w-full text-left border-collapse" id="expense-table">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-white/5">
                            @foreach(['Description', 'Project', 'Category', 'Submitted By', 'Amount', 'Date', 'Status'] as $h)
                                <th class="px-6 py-4 text-[0.6875rem] font-bold uppercase tracking-wider text-on-surface-variant">
                                    {{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-white/[0.03] cursor-pointer transition-colors"
                                onclick="openExpenseDrawer(this,{{ $exp->id }},'{{ addslashes($exp->description) }}','{{ addslashes($exp->project_name) }}','{{ isset($exp->category_name) ? addslashes($exp->category_name) : '' }}','{{ addslashes($exp->submitter ?? '') }}',{{ $exp->amount }},'{{ \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') }}','{{ $exp->status }}')">
                                <td class="px-6 py-4 text-sm font-semibold text-on-surface">
                                    {{ \Illuminate\Support\Str::limit($exp->description, 45) }}</td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $exp->project_name }}</td>
                                <td class="px-6 py-4">
                                    @if(isset($exp->category_name))
                                        <span
                                            class="px-3 py-1 bg-surface-container-highest text-primary text-[10px] font-bold uppercase rounded-full">{{ $exp->category_name }}</span>
                                    @else<span class="text-on-surface-variant text-xs">—</span>@endif
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $exp->submitter ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-on-surface">${{ number_format($exp->amount) }}</td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ $exp->status === 'approved' ? 'bg-tertiary-container/30 text-tertiary border border-tertiary/20' : ($exp->status === 'rejected' ? 'bg-error-container/20 text-error border border-error/20' : 'bg-secondary-container/20 text-secondary border border-secondary/20') }}">{{ ucfirst($exp->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <p class="text-on-surface-variant text-sm mb-4">No expenses found.</p>
                                    @can('create expenses')
                                        <button onclick="openAddExpenseDrawer()"
                                            class="px-6 py-3 bg-primary text-on-primary text-sm font-bold rounded-xl active:scale-95 transition-transform">+
                                            Add first expense</button>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses instanceof \Illuminate\Pagination\LengthAwarePaginator && $expenses->hasPages())
                <div class="px-6 py-4 bg-surface-container-high/30 border-t border-white/5 flex justify-between items-center">
                    <p class="text-xs text-on-surface-variant">Showing {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} of
                        {{ $expenses->total() }}</p>
                    <div class="flex gap-2">
                        @if(!$expenses->onFirstPage())
                            <a class="px-3 py-1 text-xs font-bold bg-surface-container-highest text-on-surface-variant rounded-md border border-outline-variant/20"
                                href="{{ $expenses->previousPageUrl() }}">Previous</a>
                        @endif
                        @if($expenses->hasMorePages())
                            <a class="px-3 py-1 text-xs font-bold bg-primary text-on-primary rounded-md"
                                href="{{ $expenses->nextPageUrl() }}">Next</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-surface-container rounded-2xl p-14 text-center shadow-lg">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 mb-4 block"
                style="font-variation-settings:'FILL' 1;">payments</span>
            <p class="text-base font-bold text-on-surface mb-2">Expenses module needs setup</p>
            <p class="text-sm text-on-surface-variant mb-6">Run the migration to activate the full Budget module.</p>
            <code class="bg-surface-container-highest px-4 py-2 rounded-lg text-sm text-primary">php artisan migrate</code>
        </div>
    @endif

    {{-- FAB (only for users who can create expenses) --}}
    @can('create expenses')
        <div class="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 group">
            <span
                class="pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0 bg-surface-container-highest text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap border border-white/10">Add
                Expense</span>
            <button onclick="openAddExpenseDrawer()"
                class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-[0_4px_24px_rgba(77,142,255,0.45)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200"
                aria-label="Add Expense">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">add</span>
            </button>
        </div>
    @endcan

    {{-- Overlay --}}
    <div id="expense-drawer-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]"
        onclick="closeExpenseDrawer()"></div>

    {{-- Drawer --}}
    <div id="expense-drawer"
        class="fixed top-0 right-0 h-full w-full max-w-lg bg-surface-container-low shadow-[-10px_0_30px_rgba(0,0,0,0.5)] z-[70] flex flex-col border-l border-white/5">
        <div class="p-6 border-b border-white/10 flex justify-between items-start">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Expense Details</span>
                <h2 class="text-2xl font-bold text-white font-headline mt-1" id="ed-title">Add Expense</h2>
                <p class="text-sm text-on-surface-variant mt-0.5" id="ed-subtitle">Record a new project expense</p>
            </div>
            <button class="p-2 text-on-surface-variant hover:text-white hover:bg-white/5 rounded-full transition-all"
                onclick="closeExpenseDrawer()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- View body --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6" id="ed-view-body">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">Amount</p>
                    <p class="text-2xl font-bold text-on-surface" id="ed-amount">—</p>
                </div>
                <div>
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">Date</p>
                    <p class="text-sm font-semibold text-on-surface" id="ed-date">—</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">Submitted By</p>
                    <p class="text-sm font-semibold text-on-surface" id="ed-submitter">—</p>
                </div>
            </div>
            <div class="bg-surface-container p-5 rounded-xl border border-white/5 space-y-3">
                <div class="flex justify-between"><span class="text-xs text-on-surface-variant">Project</span><span
                        class="text-xs font-bold text-on-surface" id="ed-project">—</span></div>
                <div class="flex justify-between"><span class="text-xs text-on-surface-variant">Category</span><span
                        class="text-xs font-bold text-on-surface" id="ed-category">—</span></div>
                <div class="flex justify-between"><span class="text-xs text-on-surface-variant">Status</span><span
                        class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full" id="ed-status-badge">—</span></div>
            </div>
        </div>

        {{-- Create body --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-4 hidden" id="ed-create-body">
            <form id="ed-create-form" method="POST" action="{{ route('budget.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Project
                            <span class="text-red-400">*</span></label>
                        <select name="project_id" required
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary">
                            <option value="">— Select project —</option>
                            @foreach($projects as $p)<option value="{{ $p->id }}">{{ $p->project_name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Description
                            <span class="text-red-400">*</span></label>
                        <input type="text" name="description" required placeholder="e.g. Steel beams floors 1-5"
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                    </div>
                    @if($hasCategories)
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Category</label>
                            <select name="category_id"
                                class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary">
                                <option value="">— Select category —</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Amount
                                (USD) <span class="text-red-400">*</span></label>
                            <input type="number" name="amount" min="0" step="0.01" required placeholder="95000"
                                class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Expense
                                Date <span class="text-red-400">*</span></label>
                            <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" required
                                class="w-full bg-surface-container border border-white/10 rounded-xl px-3 py-3 text-sm text-white focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Notes</label>
                        <textarea name="notes" rows="2" placeholder="Additional notes..."
                            class="w-full bg-surface-container border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-primary placeholder:text-slate-500 resize-none"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-white/5 bg-surface-container-high flex gap-3" id="ed-footer-view">
            @can('approve expenses')
                <button class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl active:scale-95 transition-transform"
                    id="ed-approve-btn">Approve Expense</button>

                    <button class="flex-1 bg-error text-white font-bold py-3 rounded-xl active:scale-95 transition-transform" id="ed-reject-btn">Reject Expense</button>
            @endcan
            <button onclick="closeExpenseDrawer()"
                class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors">Close</button>
        </div>
        @can('create expenses')
            <div class="p-6 border-t border-white/5 bg-surface-container-high hidden" id="ed-footer-create">
                <div class="flex gap-3">
                    <button type="submit" form="ed-create-form"
                        class="flex-1 bg-primary text-on-primary font-bold py-3 rounded-xl active:scale-95 transition-transform">Add
                        Expense</button>
                    <button type="button" onclick="closeExpenseDrawer()"
                        class="px-6 py-3 text-on-surface-variant font-bold hover:bg-white/5 rounded-xl transition-colors">Cancel</button>
                </div>
            </div>
        @endcan
    </div>

    {{-- Approve forms (only when expenses exist) --}}
    @if($hasExpenses)
        <div class="hidden">
            @foreach($expenses as $exp)
                @if($exp->status === 'pending')
                    <form id="approve-form-{{ $exp->id }}" method="POST" action="{{ route('budget.approve', $exp->id) }}">@csrf
                       @method('PATCH')</form>
                    <form id="reject-form-{{ $exp->id }}" method="POST" action="{{ route('budget.reject', $exp->id) }}">@csrf
                        @method('PATCH')</form>

                        
                @endif
            @endforeach
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        window.__edMode = null;
        window.__edExpenseId = null;

        function openExpenseDrawer(row, id, desc, project, category, submitter, amount, date, status) {
            window.__edMode = 'view'; window.__edExpenseId = id;
            document.querySelectorAll('#expense-table tbody tr').forEach(r => r.classList.remove('row-active'));
            if (row) row.classList.add('row-active');
            document.getElementById('ed-title').textContent = desc;
            document.getElementById('ed-subtitle').textContent = project;
            document.getElementById('ed-amount').textContent = '$' + Number(amount).toLocaleString();
            document.getElementById('ed-date').textContent = date;
            document.getElementById('ed-submitter').textContent = submitter || '—';
            document.getElementById('ed-project').textContent = project;
            document.getElementById('ed-category').textContent = category || '—';
            const badge = document.getElementById('ed-status-badge');
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            badge.className = 'text-[10px] font-bold uppercase px-2 py-0.5 rounded-full ' + (status === 'approved' ? 'bg-tertiary-container/30 text-tertiary' : (status === 'rejected' ? 'bg-error-container/20 text-error' : 'bg-secondary-container/20 text-secondary'));
            document.getElementById('ed-view-body').classList.remove('hidden');
            document.getElementById('ed-create-body').classList.add('hidden');
            document.getElementById('ed-footer-view').classList.remove('hidden');
            document.getElementById('ed-footer-create').classList.add('hidden');
            const btn = document.getElementById('ed-approve-btn');
            if (status === 'pending') { btn.style.display = ''; btn.onclick = () => { const f = document.getElementById('approve-form-' + id); if (f) f.submit(); }; }
           else { btn.style.display = 'none'; }
let rejectBtn = document.getElementById('ed-reject-btn');
if (rejectBtn) {
    rejectBtn.style.display = status === 'pending' ? '' : 'none';
    rejectBtn.onclick = () => { const f = document.getElementById('reject-form-' + id); if (f) f.submit(); };
}
            _openExpDrw();
        }
        function openAddExpenseDrawer() {
            window.__edMode = 'create'; window.__edExpenseId = null;
            document.querySelectorAll('#expense-table tbody tr').forEach(r => r.classList.remove('row-active'));
            document.getElementById('ed-title').textContent = 'Add Expense';
            document.getElementById('ed-subtitle').textContent = 'Record a new project expense';
            document.getElementById('ed-view-body').classList.add('hidden');
            document.getElementById('ed-create-body').classList.remove('hidden');
            document.getElementById('ed-footer-view').classList.add('hidden');
            document.getElementById('ed-footer-create').classList.remove('hidden');
            _openExpDrw();
        }
        function _openExpDrw() {
            document.getElementById('expense-drawer').classList.add('open');
            document.getElementById('expense-drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeExpenseDrawer() {
            document.getElementById('expense-drawer').classList.remove('open');
            document.getElementById('expense-drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
            document.querySelectorAll('#expense-table tbody tr').forEach(r => r.classList.remove('row-active'));
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeExpenseDrawer(); });
    </script>
@endsection