<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'project_id',
        'inspection_date',
        'inspector_name',
        'status',
        'remarks',
    ];
}
