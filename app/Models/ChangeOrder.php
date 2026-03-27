<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeOrder extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'cost_impact',
        'status',
    ];
}
