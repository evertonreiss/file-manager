<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'file_name',
        'file_path',
        'description',
        'mime_type',
        'file_size',
        'is_visible',
        'is_downloadable',
        'file_hash'
    ];

    protected $hidden = [
        'file_hash'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_downloadable' => 'boolean',
    ];
}
