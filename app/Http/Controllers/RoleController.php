<?php
// app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();

        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode(' ', $p->name)[1] ?? 'system';
        });

        $stats = [
            'total_roles' => $roles->count(),
            'total_permissions' => Permission::count(),
            'total_users' => DB::table('users')->count(),
            'custom_roles' => $roles->whereNotIn('name', ['super_admin', 'admin', 'project_manager', 'site_supervisor', 'team_member', 'client'])->count(),
        ];

        return view('roles.index', compact('roles', 'allPermissions', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $slug = strtolower(str_replace(' ', '_', trim($request->name)));
        $role = Role::create(['name' => $slug, 'guard_name' => 'web']);

        if ($request->permissions)
            $role->syncPermissions($request->permissions);

        activity()->log('created role: ' . $slug);

        return redirect()->route('roles.index')->with('success', 'Role "' . $slug . '" created!');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['permissions' => 'nullable|array']);

        $role->syncPermissions($request->permissions ?? []);
        activity()->log('updated permissions for role: ' . $role->name);

        return redirect()->route('roles.index')->with('success', 'Role "' . $role->name . '" updated!');
    }

    public function destroy(Role $role)
    {
        $protected = ['super_admin', 'admin', 'project_manager', 'site_supervisor', 'team_member', 'client'];
        if (in_array($role->name, $protected))
            return redirect()->route('roles.index')->with('error', 'Cannot delete a built-in role.');

        if ($role->users()->count() > 0)
            return redirect()->route('roles.index')->with('error', 'Cannot delete a role that has users assigned to it.');

        $name = $role->name;
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role "' . $name . '" deleted.');
    }
}