<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'url',
      'disk',
      'driver',
      'object',
      'extension',
      'mimeType',
      'size',
      'scene',
      'filename',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
