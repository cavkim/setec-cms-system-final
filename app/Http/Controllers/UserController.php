<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    // ── LIST ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::with([
            'tasks' => function ($query) {
                $query->where('status', '!=', 'completed');
            },
            // 'teamMember',
            'roles'
        ]);

        // Apply search filter at query level
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        // Apply role filter at query level
        // if ($request->role && $request->role !== 'all') {
        //     $query->whereHas('teamMember', function ($q) use ($request) {
        //         $q->where('role', $request->role);
        //     });
        // }

        if ($request->role && $request->role !== 'all') {
            $query->role($request->role);
        }

        // Apply status filter at query level
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Paginate results (15 per page)
        $users = $query->orderBy('name')->paginate(15);
        // dd($users->first()->role);

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')
                ->orWhereNull('status')
                ->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'with_photo' => User::whereNotNull('avatar')
                ->where('avatar', '!=', '')
                ->count(),
        ];

        $roles = DB::table('roles')->orderBy('name')->get();

        return view('users.index', compact('users', 'stats', 'roles'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string',
            'role' => 'nullable|string',
            // 'status' => 'nullable|in:active,inactive',
            'status' => 'nullable|in:active,inactive,suspended',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => $request->status ?? 'active',
            'avatar' => $avatarPath,
        ]);

        // Create TeamMember record with role
        // if ($request->role) {
        //     \App\Models\TeamMember::create([
        //         'user_id' => $user->id,
        //         'role' => $request->role,
        //         'hire_date' => now()->toDateString(),
        //     ]);
        // }

        // REPLACE WITH:
        if ($request->role_id) {
            $role = \Spatie\Permission\Models\Role::find($request->role_id);
            if ($role)
                $user->assignRole($role->name);
        }



        activity()->log('created user: ' . $user->name);

        return redirect()->route('users.index')
            ->with('success', 'User "' . $user->name . '" created successfully!');
    }

    // ── UPDATE ────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:50',           // ← add here
            'status' => 'nullable|in:active,inactive,suspended', // ← add here
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ?? 'active',
            'updated_at' => now(),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar)
                Storage::disk('public')->delete($user->avatar);
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle password change
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        // Update role - only if user has manage role permission
        // if ($request->role && auth()->user()->hasPermissionTo('manage roles')) {
        //     $roleModel = \Spatie\Permission\Models\Role::where('name', $request->role)->first();
        //     if ($roleModel) {
        //         $userModel = \App\Models\User::find($id);
        //         $userModel->syncRoles([$request->role]);
        //     }
        // }
        // REPLACE WITH:
        if ($request->role_id) {
            $role = \Spatie\Permission\Models\Role::find($request->role_id);
            if ($role)
                User::find($id)->syncRoles([$role->name]);
        }

        activity()->log('updated user: ' . $request->name);

        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    // ── DESTROY ───────────────────────────────────────────────────
    public function destroy($id)
    {
        if ($id == auth()->id())
            return redirect()->route('users.index')->with('error', 'Cannot delete yourself.');

        $user = DB::table('users')->where('id', $id)->first();
        if ($user && $user->avatar)
            Storage::disk('public')->delete($user->avatar);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('team_members')->where('user_id', $id)->delete();
        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    // ── UPLOAD AVATAR (AJAX) ──────────────────────────────────────
    public function uploadAvatar(Request $request, $id)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);

        $user = DB::table('users')->where('id', $id)->first();
        if ($user && $user->avatar)
            Storage::disk('public')->delete($user->avatar);

        $path = $request->file('avatar')->store('avatars', 'public');
        DB::table('users')->where('id', $id)->update(['avatar' => $path, 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'avatar' => Storage::url($path),
        ]);
    }
}