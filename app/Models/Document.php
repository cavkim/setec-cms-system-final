<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'project_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'document_type',
    ];
}
