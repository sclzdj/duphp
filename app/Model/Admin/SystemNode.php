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
        'level',
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

    /**
     * 获取模块
     *
     * @param string $status
     *
     * @return mixed
     */
    public static function modules($status = '')
    {
        $where = ['pid' => 0];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $modules = self::where($where)->orderBy('sort', 'asc')->get();

        return $modules;
    }

    /**
     * 查找某节点的所有后代节点
     *
     * @param int    $pid    节点id
     * @param string $status 条件
     * @param int    $type   返回类型 0=>所有信息的二维数组，1=>只有id的一维数组
     * @param int    $level  这个参数不传递
     *
     * @return array|string
     */
    public static function progenyNodes($id, $status = '', $type = 0, $level = 1
    ) {
        static $data = [];
        $where = ['pid' => $id];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $systemNodes =
            self::where($where)->orderBy('sort', 'asc')->get()->toArray();
        foreach ($systemNodes as $key => $systemNode) {
            $systemNode['_level'] = $level;
            if ($type == 1) {
                $data[] = $systemNode['id'];
            } else {
                $data[] = $systemNode;
            }
            $data = self::progenyNodes($systemNode['id'], $status, $type,
                                       $level + 1);
        }

        return $data;
    }

    /**
     * 查找某节点的所有直属长辈节点
     *
     * @param int $pid  节点id
     * @param int $type 返回类型 0=>所有信息的二维数组，1=>只有id的一维数组
     *
     * @return array|string
     */
    public static function elderNodes($id, $type = 0)
    {
        static $data = [];
        $systemNode = self::find($id);
        if ($systemNode) {
            $systemNode = $systemNode->toArray();
            $pSystemNode = self::find($systemNode['pid']);
            if ($pSystemNode) {
                $pSystemNode = $pSystemNode->toArray();
                if ($type == 1) {
                    array_unshift($data, $pSystemNode['id']);
                } else {
                    array_unshift($data, $pSystemNode);
                }
                $data = self::elderNodes($pSystemNode['id'], $type);
            }
        }

        return $data;
    }

    /**
     * 衍生无限级分类
     *
     * @param int     $pid       父级节点开始查，传0查全部
     * @param string  $status    查询条件
     * @param string  $html      级别文本
     * @param integer $max_level 查出层数
     * @param int     $level     这个参数不传递
     *
     * @return mixed 多维数组
     */
    public static function grMaxNodes($pid = 0, $status = '',
        $html = '&nbsp;│&nbsp;', $max_level = 0, $level = 1
    ) {
        $where = ['pid' => $pid];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $systemNodes =
            self::where($where)->orderBy('sort', 'asc')->get()->toArray();
        foreach ($systemNodes as $key => $systemNode) {
            $systemNodes[$key]['_html'] = str_repeat($html, $level - 1);
            $systemNodes[$key]['_level'] = $level;
            if ($max_level == 0 || $level != $max_level) {
                $systemNodes[$key]['_data'] =
                    self::grMaxNodes($systemNode['id'], $status, $html,
                                     $max_level, $level + 1);
            }
        }

        return $systemNodes;
    }

    /**
     * 树状无限级分类
     *
     * @param int     $pid       父级节点开始查，传0查全部
     * @param string  $status    查询条件
     * @param object  $obj       修改页面的对象（主要用于selected和disabled）
     * @param string  $html      级别文本
     * @param integer $max_level 查出层数
     * @param int     $level     这个参数不传递
     *
     * @return mixed 一维数组
     */
    public static function treeNodes($pid = 0, $status = '', $obj = '',
        $html = '&nbsp;│&nbsp;', $max_level = 0, $level = 1
    ) {
        static $data = [];
        static $disabledLevel = 0;
        static $disabled = false;
        $where = ['pid' => $pid];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $systemNodes =
            self::where($where)->orderBy('sort', 'asc')->get()->toArray();
        foreach ($systemNodes as $key => $systemNode) {
            if ($obj && $disabledLevel < $level && $disabled) {
                $systemNode['_disabled'] = 'disabled';
            } else {
                $systemNode['_disabled'] = '';
            }
            if ($obj && $level <= $disabledLevel) {
                $disabled = false;
            }
            if ($obj && $systemNode['id'] == $obj['id']) {
                $systemNode['_disabled'] = 'disabled';
                $disabledLevel = $level;
                $disabled = true;
            }
            if ($obj && $systemNode['id'] == $obj['pid']) {
                $systemNode['_selected'] = 'selected';
            } else {
                $systemNode['_selected'] = '';
            }
            $systemNode['_html'] = str_repeat($html, $level - 1);
            $systemNode['_level'] = $level;
            $data[] = $systemNode;
            if ($max_level == 0 || $level != $max_level) {
                $data = self::treeNodes($systemNode['id'], $status, $obj, $html,
                                        $max_level, $level + 1);
            }
        }

        return $data;
    }

    /**
     * 衍生无限级分类页面html结构 只用于节点管理页
     *
     * @param int     $pid       父级节点开始查，传0查全部
     * @param string  $status    查询条件
     * @param integer $max_level 显示层数
     *
     * @return mixed 多维数组
     */
    public static function grMaxHtml($pid = 0, $status = '', $max_level = 0,
        $level = 1
    ) {
        $innerHtml = '';
        $where = ['pid' => $pid];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $systemNodes =
            self::where($where)->orderBy('sort', 'asc')->get()->toArray();
        foreach ($systemNodes as $k => $v) {
            if ($v['status']) {
                $disable = '';
            } else {
                $disable = 'dd-disable';
            }
            $innerHtml .= '<li class="dd-item dd3-item ' . $disable .
                '" data-id="' . $v['id'] .
                '"><div class="dd-handle dd3-handle">拖拽</div>';
            $innerHtml .= '<div class="dd3-content"><i class="' . $v['icon'] .
                '"></i> <span class="dd3-level" data-toggle="tooltip" data-original-title="' .
                $v['level'] . '级节点">' . $v['level'] . '</span>' . $v['name'];
            $innerHtml .= '<div class="action"><a href="' .
                action('Admin\System\NodeController@create',
                       ['pid' => $v['id']]) .
                '" data-toggle="tooltip" data-original-title="添加子节点"><i class="list-icon fa fa-plus fa-fw"></i></a>';
            $innerHtml .= '<a href="' .
                action('Admin\System\NodeController@edit', ['id' => $v['id']]) .
                '" data-toggle="tooltip" data-original-title="修改"><i class="list-icon fa fa-pencil fa-fw"></i></a>';
            if ($v['status']) {
                $innerHtml .= '<a href="' .
                    action('Admin\System\NodeController@disable',
                           ['id' => $v['id']]) .
                    '" submit-type="PATCH" submit-status="disable" class="disable id-submit" data-toggle="tooltip" data-original-title="禁用"><i class="list-icon fa fa-ban fa-fw"></i></a>';
            } else {
                $innerHtml .= '<a href="' .
                    action('Admin\System\NodeController@enable',
                           ['id' => $v['id']]) .
                    '" submit-type="PATCH" submit-status="enable" class="enable id-submit" data-toggle="tooltip" data-original-title="启用"><i class="list-icon fa fa-check-circle-o fa-fw"></i></a>';
            }
            $innerHtml .= '<a href="' .
                action('Admin\System\NodeController@destroy',
                       ['id' => $v['id']]) .
                '" submit-type="DELETE" confirm="确定删除?" class="id-submit" data-toggle="tooltip" data-original-title="删除"><i class="list-icon fa fa-times fa-fw"></i></a>';
            $innerHtml .= '</div></div>';
            if ($max_level == 0 || $level != $max_level) {
                unset($systemNodes[$k]);
                $ii =
                    self::grMaxHtml($v['id'], $status, $max_level, $level + 1);
                if ($ii) {
                    $innerHtml .= '<ol class="dd-list">' .
                        self::grMaxHtml($v['id'], $status, $max_level,
                                        $level + 1) . '</ol>';
                }
            }
            $innerHtml .= '</li>';
        }

        return $innerHtml;
    }

    /**
     * 递归解析节点，主要用于排序
     *
     * @param array $menus 节点数据
     * @param int   $pid   上级节点id
     *
     * @return array 解析成可以写入数据库的格式
     */
    public static function parseNodes($data = [], $pid = 0)
    {
        $sort = 1;
        $result = [];
        foreach ($data as $d) {
            $result[] = [
                'id' => (int)$d['id'],
                'pid' => (int)$pid,
                'sort' => $sort,
            ];
            if (isset($d['children'])) {
                $result = array_merge($result, self::parseNodes($d['children'],
                                                                $d['id']));
            }
            $sort++;
        }

        return $result;
    }
}
