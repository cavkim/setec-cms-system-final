<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Task extends Model
{
    use LogsActivity;

    protected $fillable = [
        'project_id',
        'task_name',
        'description',
        'assigned_to',
        'priority',
        'status',
        'due_date',
        'progress_percent',
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress_percent' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}