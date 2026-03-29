<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'project_id',
        'uploaded_by',
        'document_name',
        'document_type',
        'file_name',
        'file_path',
        'file_url',
        'file_extension',
        'file_size',
        'version_number',
        'is_latest',
        'description',
    ];
}
