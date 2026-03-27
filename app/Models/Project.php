<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'project_name',
        'description',
        'location',
        'latitude',
        'longitude',
        'pr_manager_id',
        'client_id',
        'status',
        'progress_percent',
        'start_date',
        'end_date',
        'actual_end_date',
        'budget_allocated',
        'budget_spent',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'progress_percent' => 'decimal:2',
        'budget_allocated' => 'decimal:2',
        'budget_spent' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'pr_manager_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

