<?php
// routes/web.php — FULL FINAL VERSION with all permissions

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupportController;

// ── AUTH ROUTES (Breeze handles these) ────────────────────
require __DIR__ . '/auth.php';

// ── PROTECTED ROUTES ──────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADD THIS LINE ↓
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    // Dashboard — all authenticated users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')
        ->middleware('permission:view dashboard');

    // Projects
    Route::middleware('permission:view projects')->group(function () {
        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    });
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store')
        ->middleware('permission:create projects');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update')
        ->middleware('permission:edit projects');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy')
        ->middleware('permission:delete projects');

    // Tasks
    Route::middleware('permission:view tasks')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    });
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')
        ->middleware('permission:create tasks');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update')
        ->middleware('permission:edit tasks');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')
        ->middleware('permission:delete tasks');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status')
        ->middleware('permission:edit tasks');

    // Team
    Route::middleware('permission:view team')->group(function () {
        Route::get('team', [TeamController::class, 'index'])->name('team.index');
    });
    Route::post('team', [TeamController::class, 'store'])->name('team.store')
        ->middleware('permission:create team');
    Route::put('team/{id}', [TeamController::class, 'update'])->name('team.update')
        ->middleware('permission:edit team');
    Route::delete('team/{id}', [TeamController::class, 'destroy'])->name('team.destroy')
        ->middleware('permission:delete team');

    // User Management (admin only)
    Route::middleware('role:super_admin|admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{id}/avatar', [UserController::class, 'uploadAvatar'])->name('users.avatar');
    });




    // Role Management (super_admin only)
    Route::middleware('role:super_admin|admin')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });


    // Budget
    Route::middleware('permission:view budget')->group(function () {
        Route::get('budget', [BudgetController::class, 'index'])->name('budget.index');
        Route::get('budget/categories', [BudgetCategoryController::class, 'index'])->name('budget.categories.index');
        Route::post('budget/categories', [BudgetCategoryController::class, 'store'])->name('budget.categories.store')
            ->middleware('role:super_admin|admin|project_manager');
        Route::put('budget/categories/{category}', [BudgetCategoryController::class, 'update'])->name('budget.categories.update')
            ->middleware('role:super_admin|admin|project_manager');
        Route::delete('budget/categories/{category}', [BudgetCategoryController::class, 'destroy'])->name('budget.categories.destroy')
            ->middleware('role:super_admin|admin|project_manager');
        Route::post('budget/categories/api/create', [BudgetCategoryController::class, 'apiCreate'])->name('budget.categories.api')
            ->middleware('role:super_admin|admin|project_manager');
    });
    Route::post('budget/expenses', [BudgetController::class, 'storeExpense'])->name('budget.store')
        ->middleware('permission:create expenses');
    Route::patch('budget/expenses/{id}/approve', [BudgetController::class, 'approveExpense'])->name('budget.approve')
        ->middleware('permission:approve expenses');
    Route::patch('budget/expenses/{id}/reject', [BudgetController::class, 'rejectExpense'])->name('budget.reject')
        ->middleware('permission:approve expenses');

    // Documents
    Route::middleware('permission:view documents')->group(function () {
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    });
    Route::post('documents', [DocumentController::class, 'store'])->name('documents.store')
        ->middleware('permission:upload documents');
    Route::delete('documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy')
        ->middleware('permission:delete documents');

    // Safety
    Route::middleware('permission:view safety')->group(function () {
        Route::get('safety', [SafetyController::class, 'index'])->name('safety.index');
    });
    Route::post('safety', [SafetyController::class, 'store'])->name('safety.store')
        ->middleware('permission:create incidents');
    Route::put('safety/{id}', [SafetyController::class, 'update'])->name('safety.update')
        ->middleware('permission:resolve incidents');

    // Reports
    Route::middleware('permission:view reports')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export')
        ->middleware('permission:export reports');

    // Notifications — all auth users
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index')
        ->middleware('permission:view notifications');
    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll')
        ->middleware('permission:view notifications');

    // Support — all auth users
    Route::get('support', [SupportController::class, 'index'])->name('support.index');

    // Audit Log — super admin + admin only (via role middleware)
    Route::middleware('role:super_admin|admin')->group(function () {
        Route::get('audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::redirect('audit-logs', '/audit')->name('audit-logs.index');
    });

    // Welcome
    Route::get('/welcome', function () {
        return view('welcome');
    });

});
