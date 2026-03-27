<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyIncident extends Model
{
    use HasFactory;

    protected $table = 'safety_incidents';

    protected $fillable = [
        'description',
        'severity',
        'status',
        'location',
        'incident_date',
        'reported_by',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
    ];
}
