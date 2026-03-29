<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $fillable = [
        'category_name',
        'color_hex',
        'description',
        'is_active',
    ];
}

