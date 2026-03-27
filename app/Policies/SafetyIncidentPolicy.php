<?php

namespace App\Policies;

use App\Models\SafetyIncident;
use App\Models\User;

class SafetyIncidentPolicy
{
    /**
     * Determine if the user can view the incident.
     */
    public function view(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('view safety');
    }

    /**
     * Determine if the user can create incidents.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create incidents');
    }

    /**
     * Determine if the user can update/edit the incident.
     */
    public function update(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('create incidents');
    }

    /**
     * Determine if the user can resolve the incident.
     */
    public function resolve(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('resolve incidents');
    }

    /**
     * Determine if the user can delete the incident.
     */
    public function delete(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('resolve incidents');
    }

    /**
     * Determine if the user can restore the incident.
     */
    public function restore(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('resolve incidents');
    }

    /**
     * Determine if the user can permanently delete the incident.
     */
    public function forceDelete(User $user, SafetyIncident $incident): bool
    {
        return $user->hasPermissionTo('resolve incidents');
    }
}
