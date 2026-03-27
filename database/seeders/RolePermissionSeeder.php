<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── DEFINE ALL PERMISSIONS ─────────────────────────────
        $permissions = [
            // Dashboard
            'view dashboard',

            // Projects
            'view projects', 'create projects', 'edit projects', 'delete projects',

            // Tasks
            'view tasks', 'create tasks', 'edit tasks', 'delete tasks',

            // Team
            'view team', 'create team', 'edit team', 'delete team',

            // Budget
            'view budget', 'create expenses', 'approve expenses',

            // Documents
            'view documents', 'upload documents', 'delete documents',

            // Safety
            'view safety', 'create incidents', 'resolve incidents',

            // Reports
            'view reports', 'export reports',

            // Notifications
            'view notifications',

            // Audit Log
            'view audit log',

            // Settings
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── CREATE ROLES & ASSIGN PERMISSIONS ─────────────────

        // 1. Super Admin — everything
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin — everything except audit log
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'view dashboard',
            'view projects','create projects','edit projects','delete projects',
            'view tasks','create tasks','edit tasks','delete tasks',
            'view team','create team','edit team','delete team',
            'view budget','create expenses','approve expenses',
            'view documents','upload documents','delete documents',
            'view safety','create incidents','resolve incidents',
            'view reports','export reports',
            'view notifications',
            'manage settings',
        ]);

        // 3. Project Manager — manage projects/tasks/budget, view team/docs/safety
        $pm = Role::firstOrCreate(['name' => 'project_manager', 'guard_name' => 'web']);
        $pm->syncPermissions([
            'view dashboard',
            'view projects','create projects','edit projects',
            'view tasks','create tasks','edit tasks',
            'view team',
            'view budget','create expenses',
            'view documents','upload documents',
            'view safety','create incidents',
            'view notifications',
        ]);

        // 4. Site Supervisor — manage tasks/safety, view projects/docs
        $supervisor = Role::firstOrCreate(['name' => 'site_supervisor', 'guard_name' => 'web']);
        $supervisor->syncPermissions([
            'view dashboard',
            'view projects',
            'view tasks','create tasks','edit tasks',
            'view team',
            'view budget',
            'view documents','upload documents',
            'view safety','create incidents','resolve incidents',
            'view notifications',
        ]);

        // 5. Team Member — view & update own tasks, view projects/docs
        $member = Role::firstOrCreate(['name' => 'team_member', 'guard_name' => 'web']);
        $member->syncPermissions([
            'view dashboard',
            'view projects',
            'view tasks','edit tasks',
            'view documents',
            'view safety','create incidents',
            'view notifications',
        ]);

        // 6. Client — read-only portal
        $client = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $client->syncPermissions([
            'view dashboard',
            'view projects',
            'view documents',
            'view notifications',
        ]);

        // ── ASSIGN ROLES TO EXISTING USERS ────────────────────
        $roleMap = [
            'superadmin@buildscape.com' => 'super_admin',
            'admin@buildscape.com'      => 'admin',
            'pm@buildscape.com'         => 'project_manager',
            'supervisor@buildscape.com' => 'site_supervisor',
            'member@buildscape.com'     => 'team_member',
            'client@buildscape.com'     => 'client',
        ];

        foreach ($roleMap as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$role]);
                echo "Assigned {$role} to {$email}\n";
            }
        }

        echo "✓ All roles and permissions seeded!\n";
    }
}
