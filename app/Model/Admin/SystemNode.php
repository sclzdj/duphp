<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemNode extends Model
{
    public $timestamps = false;//关闭时间维护
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'name',
        'status',
        'action',
        'relate_actions',
        'sort',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'sort'
    ];
}
