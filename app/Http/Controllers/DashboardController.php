<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\SafetyIncident;
use App\Models\TeamMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ── KPI NUMBERS ───────────────────────────────────────────
        $activeProjects = Project::where('status', 'in_progress')->count();
        $totalProjects = Project::count();
        $completedToday = Task::where('status', 'completed')
            ->whereDate('updated_at', today())->count();
        $openTasks = Task::whereIn('status', ['pending', 'in_progress'])->count();

        // Weekly spend (with fallback if expenses table doesn't exist)
        $weeklySpend = 0;
        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('expenses')) {
                $weeklySpend = DB::table('expenses')
                    ->where('status', 'approved')
                    ->whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('amount');
            }
        } catch (\Exception $e) {
            $weeklySpend = 0;
        }

        $activeWorkers = TeamMember::count() * 8; // estimate

        // ── BUDGET ALERTS (projects over 70%) ─────────────────────
        $budgetAlerts = collect();
        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('vw_project_summary')) {
                $budgetAlerts = DB::table('vw_project_summary')
                    ->where('budget_used_percent', '>=', 70)
                    ->orderByDesc('budget_used_percent')
                    ->get();
            } else {
                // Fallback: calculate from projects table
                $budgetAlerts = Project::all()->filter(function ($p) {
                    $percent = $p->budget_allocated > 0 ? ($p->budget_spent / $p->budget_allocated) * 100 : 0;
                    return $percent >= 70;
                })->map(function ($p) {
                    $percent = $p->budget_allocated > 0 ? round(($p->budget_spent / $p->budget_allocated) * 100) : 0;
                    return (object) [
                        'project_name' => $p->project_name,
                        'budget_used_percent' => $percent,
                        'budget_spent' => $p->budget_spent,
                        'budget_allocated' => $p->budget_allocated,
                    ];
                });
            }
        } catch (\Exception $e) {
            $budgetAlerts = collect();
        }

        // ── DEADLINE TASKS (due in 7 days, not completed) ─────────
        $deadlineTasks = DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->select('tasks.*', 'projects.project_name')
            ->where('tasks.status', '!=', 'completed')
            ->whereBetween('tasks.due_date', [now(), now()->addDays(7)])
            ->orderBy('tasks.due_date')
            ->limit(3)
            ->get();

        // ── EXPIRING CERTIFICATIONS (within 60 days) ──────────────
        $expiringCerts = DB::table('team_members')
            ->join('users', 'users.id', '=', 'team_members.user_id')
            ->select('users.name', 'team_members.certification_number', 'team_members.certification_expiry')
            ->whereNotNull('team_members.certification_expiry')
            ->whereBetween('team_members.certification_expiry', [now(), now()->addDays(60)])
            ->orderBy('team_members.certification_expiry')
            ->get();

        // ── RECENT PROJECTS ────────────────────────────────────────
        $recentProjects = Project::orderBy('updated_at', 'desc')->limit(4)->get();

        // ── PENDING TASKS ──────────────────────────────────────────
        $pendingTasks = DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.assigned_to')
            ->select('tasks.*', 'projects.project_name', 'users.name as assignee')
            ->whereIn('tasks.status', ['pending', 'in_progress', 'completed'])
            ->orderByRaw("FIELD(tasks.status, 'in_progress', 'pending', 'completed')")
            ->orderBy('tasks.due_date')
            ->limit(5)
            ->get()
            ->map(fn($t) => (object) $t);

        // ── TEAM WORKLOAD ──────────────────────────────────────────
        $teamWorkload = DB::table('users')
            ->join('team_members', 'team_members.user_id', '=', 'users.id')
            ->leftJoin('tasks', 'tasks.assigned_to', '=', 'users.id')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.name', 'roles.name as role', DB::raw('COUNT(tasks.id) as task_count'))
            ->groupBy('users.id', 'users.name', 'roles.name')
            ->orderByDesc('task_count')
            ->limit(4)
            ->get();

        // ── RECENT ACTIVITY (spatie activitylog) ──────────────────
        $recentActivity = collect();
        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('activity_log')) {
                $recentActivity = DB::table('activity_log')
                    ->leftJoin('users', function ($join) {
                        $join->on('users.id', '=', 'activity_log.causer_id')
                            ->where('activity_log.causer_type', '=', 'App\\Models\\User');
                    })
                    ->select(
                        'activity_log.id',
                        'activity_log.description',
                        'activity_log.event',
                        'activity_log.created_at',
                        'users.name as causer_name'
                    )
                    ->orderByDesc('activity_log.created_at')
                    ->limit(5)
                    ->get()
                    ->map(function ($a) {
                        $a->causer = $a->causer_name ? (object) ['name' => $a->causer_name] : null;
                        $a->created_at = \Carbon\Carbon::parse($a->created_at);
                        return $a;
                    });
            }
        } catch (\Exception $e) {
            $recentActivity = collect();
        }

        // ── SAFETY ────────────────────────────────────────────────
        $lastIncident = SafetyIncident::latest('incident_date')->first();
        $daysSafe = $lastIncident
            ? \Carbon\Carbon::parse($lastIncident->incident_date)->diffInDays(now())
            : 0;
        $openIncidents = SafetyIncident::where('status', 'open')->count();
        $investigatingIncidents = SafetyIncident::where('status', 'investigating')->count();
        $resolvedIncidents = SafetyIncident::whereIn('status', ['resolved', 'closed'])->count();
        $recentIncidents = SafetyIncident::orderByDesc('incident_date')->limit(3)->get();

        // ── BUDGET BY CATEGORY (for modal) ────────────────────────
        $budgetByCategory = collect();
        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('vw_budget_by_category')) {
                $budgetByCategory = DB::table('vw_budget_by_category')
                    ->orderByDesc('total_spent')
                    ->get();
            }
        } catch (\Exception $e) {
            $budgetByCategory = collect();
        }

        // ── BUDGET CHART DATA (last 6 months) ─────────────────────
        $chartMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $chartMonths->push(now()->subMonths($i));
        }
        $chartLabels = $chartMonths->map(fn($m) => $m->format('M'))->toArray();

        $chartAllocated = array_fill(0, 6, 0);
        $chartSpent = array_fill(0, 6, 0);

        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('expenses')) {
                $chartAllocated = $chartMonths->map(
                    fn($m) =>
                    round(DB::table('expenses')
                        ->whereYear('expense_date', $m->year)
                        ->whereMonth('expense_date', $m->month)
                        ->whereIn('status', ['approved', 'pending'])
                        ->sum('amount') / 1000)
                )->toArray();
                $chartSpent = $chartMonths->map(
                    fn($m) =>
                    round(DB::table('expenses')
                        ->whereYear('expense_date', $m->year)
                        ->whereMonth('expense_date', $m->month)
                        ->where('status', 'approved')
                        ->sum('amount') / 1000)
                )->toArray();
            }
        } catch (\Exception $e) {
            $chartAllocated = array_fill(0, 6, 0);
            $chartSpent = array_fill(0, 6, 0);
        }

        return view('dashboard.index_v2', compact(
            'activeProjects',
            'totalProjects',
            'completedToday',
            'openTasks',
            'weeklySpend',
            'activeWorkers',
            'budgetAlerts',
            'deadlineTasks',
            'expiringCerts',
            'recentProjects',
            'pendingTasks',
            'teamWorkload',
            'recentActivity',
            'daysSafe',
            'openIncidents',
            'investigatingIncidents',
            'resolvedIncidents',
            'recentIncidents',
            'budgetByCategory',
            'chartLabels',
            'chartAllocated',
            'chartSpent',
        ));
    }
}
