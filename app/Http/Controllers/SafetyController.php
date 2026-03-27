<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SafetyIncident;

class SafetyController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('safety_incidents')
            ->leftJoin('users', 'users.id', '=', 'safety_incidents.reported_by')
            ->select('safety_incidents.*', 'users.name as reporter');

        if ($request->status && $request->status !== 'all') {
            $query->where('safety_incidents.status', $request->status);
        }
        if ($request->severity && $request->severity !== 'all') {
            $query->where('safety_incidents.severity', $request->severity);
        }

        $incidents = $query->orderByDesc('safety_incidents.incident_date')->paginate(10);

        $last = DB::table('safety_incidents')->orderByDesc('incident_date')->first();
        $daysSafe = $last ? \Carbon\Carbon::parse($last->incident_date)->diffInDays(now()) : 0;

        $stats = [
            'total' => DB::table('safety_incidents')->count(),
            'open' => DB::table('safety_incidents')->where('status', 'open')->count(),
            'investigating' => DB::table('safety_incidents')->where('status', 'investigating')->count(),
            'resolved' => DB::table('safety_incidents')->whereIn('status', ['resolved', 'closed'])->count(),
            'days_safe' => $daysSafe,
        ];

        return view('safety.index', compact('incidents', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', SafetyIncident::class);

        $request->validate([
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'incident_date' => 'required|date',
        ]);

        DB::table('safety_incidents')->insert([
            'description' => $request->description,
            'severity' => $request->severity,
            'status' => 'open',
            'location' => $request->location,
            'incident_date' => $request->incident_date,
            'reported_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('safety.index')->with('success', 'Incident reported!');
    }

    public function update(Request $request, $id)
    {
        $incident = SafetyIncident::findOrFail($id);
        $this->authorize('resolve', $incident);

        $request->validate([
            'status' => 'required|in:open,investigating,resolved,closed',
        ]);

        DB::table('safety_incidents')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('safety.index')->with('success', 'Incident updated!');
    }
}

