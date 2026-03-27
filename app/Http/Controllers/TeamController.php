<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        // team_members has: id, user_id, certification_number, certification_expiry, role
        // users has: id, name, email, email_verified_at, password, remember_token, created_at, updated_at
        // NOTE: role is in team_members — NOT using spatie roles join since team_members.role exists

        $query = DB::table('users')
            ->leftJoin('team_members', 'team_members.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'team_members.id as member_id',
                'team_members.role',
                'team_members.certification_number',
                'team_members.certification_expiry',
                DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.assigned_to = users.id AND tasks.status != "completed") as active_tasks')
            );

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', '%'.$request->search.'%')
                    ->orWhere('users.email', 'like', '%'.$request->search.'%')
                    ->orWhere('team_members.role', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->role && $request->role !== 'all') {
            $query->where('team_members.role', $request->role);
        }

        $members = $query->orderBy('users.name')->paginate(10);

        $stats = [
            'total' => DB::table('users')->count(),
            'active' => DB::table('users')->count(),
            'on_tasks' => DB::table('tasks')
                ->whereIn('status', ['pending', 'in_progress'])
                ->whereNotNull('assigned_to')
                ->distinct()
                ->count('assigned_to'),
            'expiring' => DB::table('team_members')
                ->whereNotNull('certification_expiry')
                ->whereBetween('certification_expiry', [now(), now()->addDays(60)])
                ->count(),
        ];

        $expiringCerts = DB::table('team_members')
            ->join('users', 'users.id', '=', 'team_members.user_id')
            ->select('users.name', 'team_members.certification_number', 'team_members.certification_expiry')
            ->whereNotNull('team_members.certification_expiry')
            ->whereBetween('team_members.certification_expiry', [now(), now()->addDays(30)])
            ->orderBy('team_members.certification_expiry')
            ->get();

        $roles = DB::table('team_members')
            ->whereNotNull('role')
            ->distinct()
            ->orderBy('role')
            ->pluck('role');

        return view('team.index', compact('members', 'stats', 'expiringCerts', 'roles'));
    }

    public function create()
    {
        return redirect()->route('team.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        DB::table('team_members')->insert([
            'user_id' => $user->id,
            'role' => $request->role ?? 'team_member',
            'certification_number' => $request->certification_number,
            'certification_expiry' => $request->certification_expiry ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('team.index')
            ->with('success', 'Team member "'.$user->name.'" added!');
    }

    public function show(string $team)
    {
        return redirect()->route('team.index');
    }

    public function edit(string $team)
    {
        return redirect()->route('team.index');
    }

    public function update(Request $request, string $team)
    {
        $id = (int) $team;

        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        $exists = DB::table('team_members')->where('user_id', $id)->exists();
        $data = [
            'role' => $request->role ?? 'team_member',
            'certification_number' => $request->certification_number,
            'certification_expiry' => $request->certification_expiry ?: null,
            'updated_at' => now(),
        ];
        if ($exists) {
            DB::table('team_members')->where('user_id', $id)->update($data);
        } else {
            DB::table('team_members')->insert(array_merge($data, [
                'user_id' => $id,
                'created_at' => now(),
            ]));
        }

        return redirect()->route('team.index')->with('success', 'Member updated!');
    }

    public function destroy(string $team)
    {
        $id = (int) $team;
        $name = DB::table('users')->where('id', $id)->value('name');
        DB::table('model_has_roles')->where('model_id', $id)->where('model_type', 'App\\Models\\User')->delete();
        DB::table('team_members')->where('user_id', $id)->delete();
        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('team.index')
            ->with('success', '"'.$name.'" removed.');
    }
}
