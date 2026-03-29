<?php
// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Get task IDs and metadata via query builder
        $query = DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.assigned_to')
            ->select(
                'tasks.id',
                'tasks.task_name',
                'tasks.description',
                'tasks.project_id',
                'tasks.assigned_to',
                'tasks.status',
                'tasks.priority',
                'tasks.due_date',
                'tasks.progress_percent',
                'tasks.created_at',
                'tasks.updated_at',
                'projects.project_name',
                'users.name as assignee'
            );

        // if ($request->status && $request->status !== 'all')
        //     $query->where('tasks.status', $request->status);

        // With this:
        if ($request->status === 'high_priority') {
            $query->where('tasks.priority', 'high');
        } elseif ($request->status && $request->status !== 'all') {
            $query->where('tasks.status', $request->status);
        }
        if ($request->priority && $request->priority !== 'all')
            $query->where('tasks.priority', $request->priority);
        if ($request->search)
            $query->where(function ($q) use ($request) {
                $q->where('tasks.task_name', 'like', '%' . $request->search . '%')
                    ->orWhere('projects.project_name', 'like', '%' . $request->search . '%');
            });

        $paginated = $query
            ->orderByRaw("FIELD(tasks.status,'in_progress','pending','completed','cancelled')")
            ->orderByRaw("FIELD(tasks.priority,'high','medium','low')")
            ->paginate(12);

        // Load actual Task models for authorization - just for the current page
        $taskIds = $paginated->pluck('id')->toArray();
        $taskModels = Task::whereIn('id', $taskIds)->get()->keyBy('id');

        // Add models to each item in the paginated collection
        foreach ($paginated->items() as $item) {
            $item->_model = $taskModels[$item->id] ?? null;
        }

        $tasks = $paginated;

        $stats = [
            'total' => DB::table('tasks')->count(),
            'pending' => DB::table('tasks')->where('status', 'pending')->count(),
            'in_progress' => DB::table('tasks')->where('status', 'in_progress')->count(),
            'completed' => DB::table('tasks')->where('status', 'completed')->count(),
        ];

        $projects = DB::table('projects')->orderBy('project_name')->get();
        $users = DB::table('users')->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'stats', 'projects', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:200',
            'project_id' => 'required|exists:projects,id',
            'priority' => 'required|in:low,medium,high',
            // 'status' => 'required|in:pending,in_progress,completed,cancelled',
            'status' => 'required|in:pending,in_progress,completed,blocked,cancelled',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        Task::create([
            'task_name' => $request->task_name,
            'project_id' => $request->project_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date ?: null,
            'assigned_to' => $request->assigned_to ?: null,
            'description' => $request->description,
            'progress_percent' => $request->progress_percent ?? 0,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'task_name' => 'required|string|max:200',
            'project_id' => 'required|exists:projects,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,blocked,cancelled',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $task->update([
            'task_name' => $request->task_name,
            'project_id' => $request->project_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date ?: null,
            'assigned_to' => $request->assigned_to ?: null,
            'description' => $request->description,
            'progress_percent' => $request->progress_percent ?? $task->progress_percent,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update(['status' => $request->status]);
        return response()->json(['ok' => true]);
    }
}