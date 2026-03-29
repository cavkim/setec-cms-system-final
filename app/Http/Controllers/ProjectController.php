<?php
// app/Http/Controllers/ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    // use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = DB::table('projects')
            ->select(
                'projects.*',
                DB::raw('ROUND((projects.budget_spent / NULLIF(projects.budget_allocated,0)) * 100, 1) as budget_pct'),
                DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id) as total_tasks'),
                DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.project_id = projects.id AND tasks.status = "completed") as done_tasks')
            );

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('project_name', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->orderByDesc('updated_at')->paginate(10);

        $stats = [
            'total' => DB::table('projects')->count(),
            'active' => DB::table('projects')->where('status', 'in_progress')->count(),
            'on_hold' => DB::table('projects')->where('status', 'on_hold')->count(),
            'planning' => DB::table('projects')->where('status', 'planning')->count(),
            'completed' => DB::table('projects')->where('status', 'completed')->count(),
        ];

        return view('projects.index_v2', compact('projects', 'stats'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create projects'), 403);

        $request->validate([
            'project_name' => 'required|string|min:3|max:200',
            'location' => 'required|string|min:2|max:200',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'budget_allocated' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'progress_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $project = Project::create([
            'project_name' => $request->project_name,
            'location' => $request->location,
            'description' => $request->description ?? '',
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget_allocated' => $request->budget_allocated,
            'budget_spent' => 0,
            'progress_percent' => $request->progress_percent ?? 0,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project "' . $project->project_name . '" created!');
    }

    public function update(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('edit projects'), 403);

        $request->validate([
            'project_name' => 'required|string|min:3|max:200',
            'location' => 'required|string|min:2|max:200',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'budget_allocated' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'progress_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $project->update([
            'project_name' => $request->project_name,
            'location' => $request->location,
            'description' => $request->description ?? '',
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget_allocated' => $request->budget_allocated,
            'progress_percent' => $request->progress_percent ?? $project->progress_percent,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated!');
    }

    public function destroy(Project $project)
    {
        abort_unless(auth()->user()->can('delete projects'), 403);

        $name = $project->project_name;
        $project->delete();
        return redirect()->route('projects.index')->with('success', '"' . $name . '" deleted.');
    }

    // ── SHOW (detail page) ────────────────────────────────────────
    public function show(Project $project)
    {
        $tasks = DB::table('tasks')
            ->leftJoin('users', 'users.id', '=', 'tasks.assigned_to')
            ->select('tasks.*', 'users.name as assignee')
            ->where('tasks.project_id', $project->id)
            ->orderByRaw("FIELD(tasks.status,'in_progress','pending','completed')")
            ->get();

        $expenses = DB::table('expenses')
            ->join('budget_categories', 'budget_categories.id', '=', 'expenses.category_id')
            ->select('expenses.*', 'budget_categories.category_name', 'budget_categories.color_hex')
            ->where('expenses.project_id', $project->id)
            ->orderByDesc('expenses.expense_date')
            ->get();

        return view('projects.show', compact('project', 'tasks', 'expenses'));
    }
}
