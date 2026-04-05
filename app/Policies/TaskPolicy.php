<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine if the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('view tasks');
    }

    /**
     * Determine if the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create tasks');
    }

    /**
     * Determine if the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->hasRole('team_member')) {
            return $task->assigned_to === $user->id;
        }
        return $user->hasPermissionTo('edit tasks');
    }

    /**
     * Determine if the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('delete tasks');
    }

    /**
     * Determine if the user can restore the task.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('delete tasks');
    }

    /**
     * Determine if the user can permanently delete the task.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('delete tasks');
    }
}
