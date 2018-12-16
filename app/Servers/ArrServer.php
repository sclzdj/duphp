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
     *
     * @param array  $data
     * @param string $field
     * @param int    $type 0返回数组  1返回字符串
     *
     * @return array|string
     */
    public static function ids($data = [], $field = 'id', $type = 0)
    {
        $ids = [];
        foreach ($data as $d) {
            $ids[] = $d[$field];
        }
        if ($type != 1) {
            return $ids;
        } else {
            return implode(',', $ids);
        }
    }

    /**
     * 递归解析数组
     *
     * @param array $data  数据
     * @param int   $pid   上级节点id
     * @param model $model 模型名称
     *
     * @return array 返回入库后的所有对象合成一个数组
     */
    public static function parseData($data = [],
        $model = 'App\Model\Admin\SystemNode', $pid = 0, $level = 1
    ) {
        $sort = 1;
        $result = [];

        foreach ($data as $d) {
            $pix = [
                'pid' => (int)$pid,
                'sort' => $sort,
                'level' => $level,
            ];
            $tmp = $d;
            if (isset($tmp['children'])) {
                unset($tmp['children']);
            }
            $new = $model::create(array_merge($tmp, $pix));
            $result[] = $new;
            if (isset($d['children'])) {
                $result = array_merge($result,
                                      self::parseData($d['children'], $model,
                                                      $new->id, $level + 1));
            }
            $sort++;
        }

        return $result;
    }

    public static function grMaxData($data = [], $pid = 0,
        $html = '&nbsp;│&nbsp;', $max_level = 0, $level = 1
    ) {
        static $return = [];
        foreach ($data as $key => $value) {
            if ($value['pid'] == $pid) {
                $pix = [];
                $pix['_html'] = str_repeat($html, $level - 1);
                $pix['_level'] = $level;
                if ($max_level == 0 || $level != $max_level) {
                    $pix['_data'] =
                        self::grMaxData($data, $value['id'], $html, $max_level,
                                        $level + 1);
                }
                $return[] = $pix;
            }

        }

        return $return;
    }
}
