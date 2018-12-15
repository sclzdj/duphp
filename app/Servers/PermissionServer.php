<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/15
 * Time: 23:01
 */

namespace App\Servers;


class PermissionServer
{
    /**
     * 是否为站长
     *
     * @return bool
     */
    public static function website()
    {
        return auth('admin')->id() == 1 ?
            true :
            false;
    }

    /**
     * 是否为超级管理员
     *
     * @return bool
     */
    public static function superAdmin()
    {
        return auth('admin')->user()->type == 1 ?
            true :
            false;
    }

    public static function allowNodes()
    {
        if (self::website()){

        }else{
            return true;
        }
    }
}
