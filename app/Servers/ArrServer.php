<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/15
 * Time: 22:06
 */

namespace App\Servers;


class ArrServer
{
    /**
     * 获取只有id的数据
     * @param array  $data
     * @param string $field
     * @param int    $type 0返回数组  1返回字符串
     *
     * @return array|string
     */
    public static function ids($data=[],$field='id',$type=0)
    {
        $ids=[];
        foreach ($data as $d){
            $ids[]=$d[$field];
        }
        if($type!=1){
            return $ids;
        }else{
            return implode(',',$ids);
        }
    }
}
