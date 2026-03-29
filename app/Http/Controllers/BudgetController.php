<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $hasExpenses = Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'project_id');
        $hasCategories = Schema::hasTable('budget_categories') && Schema::hasColumn('budget_categories', 'category_name');

        $projects = DB::table('projects')
            ->select(
                'id',
                'project_name',
                'location',
                'budget_allocated',
                'budget_spent',
                'status',
                DB::raw('ROUND((budget_spent / NULLIF(budget_allocated,0)) * 100, 1) as budget_pct')
            )
            ->orderByDesc('budget_allocated')
            ->get();

        $stats = [
            'total_allocated' => $projects->sum('budget_allocated'),
            'total_spent' => $projects->sum('budget_spent'),
            'total_remaining' => $projects->sum('budget_allocated') - $projects->sum('budget_spent'),
            'pct_used' => $projects->sum('budget_allocated') > 0
                ? round(($projects->sum('budget_spent') / $projects->sum('budget_allocated')) * 100, 1)
                : 0,
        ];

        $alerts = $projects->filter(fn($p) => $p->budget_pct >= 70)
            ->sortByDesc('budget_pct');

        $expenses = collect();
        $categories = collect();
        $budgetByCategory = collect();

        if ($hasExpenses) {
            $query = DB::table('expenses')
                ->join('projects', 'projects.id', '=', 'expenses.project_id')
                ->leftJoin('users', 'users.id', '=', 'expenses.submitted_by')
                ->select('expenses.*', 'projects.project_name', 'users.name as submitter');

            if ($hasCategories) {
                $query->leftJoin('budget_categories', 'budget_categories.id', '=', 'expenses.category_id')
                    ->addSelect('budget_categories.category_name', 'budget_categories.color_hex');
            }

            if ($request->status && $request->status !== 'all') {
                $query->where('expenses.status', $request->status);
            }
            if ($request->project_id) {
                $query->where('expenses.project_id', $request->project_id);
            }

            $expenses = $query->orderByDesc('expenses.expense_date')->paginate(12);

            if ($hasCategories) {
                $budgetByCategory = DB::table('expenses')
                    ->join('budget_categories', 'budget_categories.id', '=', 'expenses.category_id')
                    ->select(
                        'budget_categories.category_name',
                        'budget_categories.color_hex',
                        DB::raw('SUM(expenses.amount) as total_spent'),
                        DB::raw('COUNT(*) as count')
                    )
                    ->where('expenses.status', 'approved')
                    ->groupBy('budget_categories.id', 'budget_categories.category_name', 'budget_categories.color_hex')
                    ->orderByDesc('total_spent')
                    ->get();

                $categories = DB::table('budget_categories')
                    ->where('is_active', true)
                    ->orderBy('category_name')
                    ->get();
            }
        }

        return view('budget.index', compact(
            'projects',
            'stats',
            'alerts',
            'expenses',
            'categories',
            'budgetByCategory',
            'hasExpenses',
            'hasCategories'
        ));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999',
            'expense_date' => 'required|date',
            'category_id' => 'nullable|exists:budget_categories,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::table('expenses')->insert([
            'project_id' => $request->project_id,
            'category_id' => $request->category_id ?: null,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'submitted_by' => auth()->id(),
            'status' => 'pending',
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),


        ]);

        $total = DB::table('expenses')
            ->where('project_id', $request->project_id)
            ->where('status', 'approved')
            ->sum('amount');
        DB::table('projects')->where('id', $request->project_id)
            ->update(['budget_spent' => $total, 'updated_at' => now()]);

        return redirect()->route('budget.index')->with('success', 'Expense added!');
    }


    public function approveExpense(Request $request, $id)
    {
        abort_unless(auth()->user()->can('approve expenses'), 403);

        DB::transaction(function () use ($id) {
            $expense = DB::table('expenses')->where('id', $id)->firstOrFail();

            DB::table('expenses')->where('id', $id)->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'updated_at' => now(),
            ]);

            $total = DB::table('expenses')
                ->where('project_id', $expense->project_id)
                ->where('status', 'approved')
                ->sum('amount');

            DB::table('projects')->where('id', $expense->project_id)
                ->update(['budget_spent' => $total, 'updated_at' => now()]);
        });

        return redirect()->route('budget.index')->with('success', 'Expense approved!');
    }

    public function rejectExpense(Request $request, $id)
    {
        abort_unless(auth()->user()->can('approve expenses'), 403);

        DB::transaction(function () use ($id) {
            $expense = DB::table('expenses')->where('id', $id)->firstOrFail();

            DB::table('expenses')->where('id', $id)->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);

            $total = DB::table('expenses')
                ->where('project_id', $expense->project_id)
                ->where('status', 'approved')
                ->sum('amount');

            DB::table('projects')->where('id', $expense->project_id)
                ->update(['budget_spent' => $total, 'updated_at' => now()]);
        });

        return redirect()->route('budget.index')->with('success', 'Expense rejected.');
    }
}
