<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamMember extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'specialization',
        'certification_number',
        'certification_expiry',
        'hourly_rate',
        'hire_date',
        'role',
    ];

    protected $casts = [
        'certification_expiry' => 'date',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
