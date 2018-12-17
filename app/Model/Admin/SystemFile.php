<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    protected $fillable = [
        'url',
        'disk',
        'driver',
        'object',
        'extension',
        'mimeType',
        'size',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
