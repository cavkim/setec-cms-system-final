<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'project_id',
        'equipment_name',
        'status',
        'last_maintenance_date',
    ];
}
