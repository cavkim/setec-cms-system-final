<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $table = config('activitylog.table_name', 'activity_log');

        $query = DB::table($table)
            ->leftJoin('users', function ($j) use ($table) {
                $j->on('users.id', '=', $table.'.causer_id')
                    ->where($table.'.causer_type', '=', 'App\\Models\\User');
            })
            ->select($table.'.*', 'users.name as causer_name');

        if ($request->event && $request->event !== 'all') {
            $query->where($table.'.event', $request->event);
        }
        if ($request->search) {
            $query->where($table.'.description', 'like', '%'.$request->search.'%');
        }

        $logs = $query->orderByDesc($table.'.created_at')->paginate(15);

        $stats = [
            'today' => DB::table($table)->whereDate('created_at', today())->count(),
            'week' => DB::table($table)->whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'total' => DB::table($table)->count(),
            'users' => DB::table($table)->whereNotNull('causer_id')->distinct()->count('causer_id'),
        ];

        return view('audit.index', compact('logs', 'stats'));
    }
}
