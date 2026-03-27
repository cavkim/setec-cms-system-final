<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\SafetyIncident;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\SafetyIncidentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
        SafetyIncident::class => SafetyIncidentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
