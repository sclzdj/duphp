<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/15
 * Time: 23:01
 */

namespace App\Servers;


use App\Model\Admin\SystemNode;
use App\Model\Admin\SystemRole;
use App\Model\Admin\SystemUser;

class PermissionServer
{
    protected static $guard = 'admin';

    /**
     * 账号是否为站长
     *
     * @param      $id
     *
     * @return bool
     */
    public static function website($id = 0)
    {
        $id = $id > 0 ?
            $id :
            auth(self::$guard)->id();

        return $id == 1 ?
            true :
            false;
    }

    /**
     * 账号是否为超级管理员
     *
     * @param      $id
     *
     * @return bool
     */
    public static function superAdmin($id = 0)
    {
        $id = $id > 0 ?
            $id :
            auth(self::$guard)->id();
        $systemUser = SystemUser::find($id);
        if (!$systemUser) {
            return false;
        }

        return SystemUser::find($id)->type == 0 ?
            true :
            false;
    }

    /**
     * 账号是否允许登录
     *
     * @param      $id
     * @param      $validateNode  是否验证节点，如果验证，账号允许的节点都被禁用的话照样不能登录
     * @param bool $type          传true会返回拒绝登录提示信息
     *
     * @return array|bool
     */
    public static function allowLogin($id = 0, $type = false,
        $validateNode = false
    ) {
        $id = $id > 0 ?
            $id :
            auth(self::$guard)->id();
        $systemUser = SystemUser::find($id);
        if (!$systemUser) {
            return false;
        }
        if ($id == 1) {
            if ($type) {
                return ['status' => true];
            } else {
                return true;
            }
        } else {
            if (!$systemUser) {
                if ($type) {
                    return ['status' => false, 'message' => '账号不存在'];
                } else {
                    return false;
                }
            }
            if ($systemUser->status == 0) {
                if ($type) {
                    return ['status' => false, 'message' => '账号被禁用'];
                } else {
                    return false;
                }
            }
            if ($systemUser->type == 0) {

            } elseif ($systemUser->type == 1) {
                $systemRoles = SystemRole::whereIn('id',
                                                   ArrServer::ids($systemUser->systemRoles->toArray()))
                    ->where('status', 1)->get();
                if (!count($systemRoles)) {
                    if ($type) {
                        return ['status' => false, 'message' => '账号所属角色全被禁用'];
                    } else {
                        return false;
                    }
                }
                if (!$validateNode) {
                    if ($type) {
                        return ['status' => true];
                    } else {
                        return true;
                    }
                }
                $ids = [];
                foreach ($systemRoles as $systemRole) {
                    $ids = array_merge($ids,
                                       ArrServer::ids($systemRole->systemNodes->toArray()));
                }
                $systemNodes =
                    SystemNode::whereIn('id', $ids)->where('status', 1)->get();
                if (!count($systemNodes)) {
                    if ($type) {
                        return [
                            'status' => false,
                            'message' => '账号所属角色分配节点全被禁用'
                        ];
                    } else {
                        return false;
                    }
                }
            } elseif ($systemUser->type == 2) {
                if (!$validateNode) {
                    if ($type) {
                        return ['status' => true];
                    } else {
                        return true;
                    }
                }
                $systemNodes = SystemNode::whereIn('id',
                                                   ArrServer::ids($systemUser->systemNodes->toArray()))
                    ->where('status', 1)->get();
                if (!count($systemNodes)) {
                    if ($type) {
                        return ['status' => false, 'message' => '账号直赋节点全被禁用'];
                    } else {
                        return false;
                    }
                }
            } else {
                if ($type) {
                    return ['status' => false, 'message' => '未知错误'];
                } else {
                    return false;
                }
            }
            if ($type) {
                return ['status' => true];
            } else {
                return true;
            }
        }
    }

    /**
     * 账号许可节点
     *
     * @param int  $id
     * @param int  $node_status
     * @param int  $role_status
     * @param bool $type 传true返回对象数组，否则返回id数组
     *
     * @return array|bool|object 返回true则许可所有节点
     */
    public static function allowNodes($id = 0, $node_status = 1,
        $role_status = 1, $type = false
    ) {
        if (self::website($id) || self::superAdmin($id)) {
            return true;
        } else {
            $id = $id > 0 ?
                $id :
                auth(self::$guard)->id();
            $systemUser = SystemUser::find($id);
            if (!$systemUser) {
                return [];
            }
            if ($systemUser->type == 1) {
                if ($role_status !== '') {
                    $where = ['status' => $role_status];
                } else {
                    $where = [];
                }
                $systemRoles = SystemRole::where($where)->whereIn('id',
                                                                  ArrServer::ids($systemUser->systemRoles->toArray()))
                    ->get();
                if (!count($systemRoles)) {
                    return [];
                }
                $ids = [];
                foreach ($systemRoles as $systemRole) {
                    $ids = array_merge($ids,
                                       ArrServer::ids($systemRole->systemNodes->toArray()));
                }
                if ($node_status !== '') {
                    $where = ['status' => $node_status];
                } else {
                    $where = [];
                }
                $systemNodes =
                    SystemNode::where($where)->whereIn('id', $ids)->get();
                if (!count($systemNodes)) {
                    return [];
                }
                if ($type) {
                    return $systemNodes;
                } else {
                    return ArrServer::ids($systemNodes->toArray());
                }
            } elseif ($systemUser->type == 2) {
                if ($node_status !== '') {
                    $where = ['status' => $node_status];
                } else {
                    $where = [];
                }
                $systemNodes = SystemNode::where($where)->whereIn('id',
                                                                  ArrServer::ids($systemUser->systemNodes->toArray()))
                    ->get();
                if (!count($systemNodes)) {
                    return [];
                }
                if ($type) {
                    return $systemNodes;
                } else {
                    return ArrServer::ids($systemNodes->toArray());
                }
            } else {
                return [];
            }
        }
    }

    /**
     * 允许访问的所有方法
     *
     * @param int  $id
     * @param bool $type 是否转小写
     *
     * @return array
     */
    public static function allowActions($id = 0, $type = false)
    {
        $nodes = self::allowNodes($id, 1, 1, true);
        if ($nodes === true) {
            $nodes = SystemNode::get();
        }
        $actions = [];
        if (count($nodes) > 0) {
            foreach ($nodes as $node) {
                if ($node->action !== '') {
                    $pix = $node->action;
                    if ($type) {
                        $pix = strtolower($pix);
                    }
                    $actions[] = $pix;
                    if (explode('@', $node->action)[1] == 'create') {
                        $pix = explode('@', $node->action)[0] . '@store';
                        if ($type) {
                            $pix = strtolower($pix);
                        }
                        $actions[] = $pix;
                    } elseif (explode('@', $node->action)[1] == 'store') {
                        $pix = explode('@', $node->action)[0] . '@create';
                        if ($type) {
                            $pix = strtolower($pix);
                        }
                        $actions[] = $pix;
                    } elseif (explode('@', $node->action)[1] == 'edit') {
                        $pix = explode('@', $node->action)[0] . '@update';
                        if ($type) {
                            $pix = strtolower($pix);
                        }
                        $actions[] = $pix;
                    } elseif (explode('@', $node->action)[1] == 'update') {
                        $pix = explode('@', $node->action)[0] . '@edit';
                        if ($type) {
                            $pix = strtolower($pix);
                        }
                        $actions[] = $pix;
                    }
                }
            }
        }

        return $actions;
    }

    /**
     * 判断此方法是否允许
     *
     * @param      $action
     * @param bool $type 是否不区分大小写  true则不区分，false区分
     * @param int  $id
     *
     * @return bool
     */
    public static function allowAction($action, $type = false, $id = 0)
    {
        $actions = self::allowActions($id, $type);
        if ($type) {
            $action = strtolower($action);
        }

        return in_array($action, $actions);
    }
}
