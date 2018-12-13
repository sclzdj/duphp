<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    protected $fillable = [
        'url',
        'filesystem_driver',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
