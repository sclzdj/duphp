<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    public $timestamps = false;//关闭时间维护
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'value',
        'type',
        'genre',
        'tips',
        'options',
        'required',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 获取配置项值
     *
     * @param        $name
     * @param string $type
     *
     * @return null
     */
    public static function getVal($name, $type = 'basic')
    {
        $systemConfig =
            SystemConfig::where('type', $type)->where('name', $name)->first();

        return $systemConfig ?
            $systemConfig->value :
            null;
    }

    /**
     * 获取前台链接地址
     *
     * @return null|string
     */
    public static function indexUrl()
    {
        $systemConfig = SystemConfig::where('type', 'basic')
            ->where('name', 'basic_index_url')->first();

        $val = $systemConfig ?
            $systemConfig->value :
            null;
        if (strpos($val, 'http://') !== false ||
            strpos($val, 'https://') !== false
        ) {
            return $val;
        } else {
            return action($val);
        }
    }
}
