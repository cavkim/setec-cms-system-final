<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => DB::table('projects')->count(),
            'tasks' => DB::table('tasks')->count(),
            'incidents' => DB::table('safety_incidents')->count(),
            'members' => DB::table('users')->count(),
        ];

        $tasksByStatus = DB::table('tasks')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $projects = DB::table('projects')
            ->select('project_name', 'status', 'progress_percent', 'budget_allocated', 'budget_spent')
            ->orderByDesc('budget_allocated')
            ->get();

        return view('reports.index', compact('stats', 'tasksByStatus', 'projects'));
    }

    public function export(string $type)
    {
        switch ($type) {
            case 'projects':
                $data['projects'] = DB::table('projects')->get();
                $view = 'reports.pdf.projects';
                $filename = 'projects-report-'.date('Y-m-d').'.pdf';
                break;
            case 'safety':
                $data['incidents'] = DB::table('safety_incidents')
                    ->leftJoin('users', 'users.id', '=', 'safety_incidents.reported_by')
                    ->select('safety_incidents.*', 'users.name as reporter')
                    ->orderByDesc('incident_date')
                    ->get();
                $view = 'reports.pdf.safety';
                $filename = 'safety-report-'.date('Y-m-d').'.pdf';
                break;
            case 'tasks':
                $driver = DB::connection()->getDriverName();
                $query = DB::table('tasks')
                    ->join('projects', 'projects.id', '=', 'tasks.project_id')
                    ->leftJoin('users', 'users.id', '=', 'tasks.assigned_to')
                    ->select('tasks.*', 'projects.project_name', 'users.name as assignee');
                if ($driver === 'mysql') {
                    $query->orderByRaw("FIELD(tasks.status,'in_progress','pending','completed','blocked')");
                } else {
                    $query->orderByRaw("CASE tasks.status WHEN 'in_progress' THEN 1 WHEN 'pending' THEN 2 WHEN 'completed' THEN 3 WHEN 'blocked' THEN 4 ELSE 5 END");
                }
                $data['tasks'] = $query->get();
                $view = 'reports.pdf.tasks';
                $filename = 'tasks-report-'.date('Y-m-d').'.pdf';
                break;
            default:
                return redirect()->route('reports.index')->with('error', 'Unknown report type');
        }

        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
