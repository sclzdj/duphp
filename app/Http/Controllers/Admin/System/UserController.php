<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemUserRequest;
use App\Model\Admin\SystemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageInfo = [
            'pageSize' => $request['pageSize'] !== null ?
                $request['pageSize'] :
                10,
            'page' => $request['page'] !== null ?
                $request['page'] :
                1
        ];

        $filter = [
            'id' => $request['id'] !== null ?
                $request['id'] :
                '',
            'username' => $request['username'] !== null ?
                $request['username'] :
                '',
            'nickname' => $request['nickname'] !== null ?
                $request['nickname'] :
                '',
            'type' => $request['type'] !== null ?
                $request['type'] :
                '',
            'status' => $request['status'] !== null ?
                $request['status'] :
                '',
            'created_at_start' => $request['created_at_start'] !== null ?
                $request['created_at_start'] :
                '',
            'created_at_end' => $request['created_at_end'] !== null ?
                $request['created_at_end'] :
                '',
        ];
        $orderBy = [
            'order_field' => $request['order_field'] !== null ?
                $request['order_field'] :
                'id',
            'order_type' => $request['order_type'] !== null ?
                $request['order_type'] :
                'asc',
        ];
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id', 'like', '%' . $filter['id'] . '%'];
        }
        if ($filter['username'] !== '') {
            $where[] = ['username', 'like', '%' . $filter['username'] . '%'];
        }
        if ($filter['nickname'] !== '') {
            $where[] = ['nickname', 'like', '%' . $filter['nickname'] . '%'];
        }
        if ($filter['type'] !== '') {
            $where[] = ['type', '=', $filter['type']];
        }
        if ($filter['status'] !== '') {
            $where[] = ['status', '=', $filter['status']];
        }
        if ($filter['created_at_start'] !== '' &&
            $filter['created_at_end'] !== ''
        ) {
            $where[] = [
                'created_at',
                '>=',
                $filter['created_at_start'] . " 00:00:00"
            ];
            $where[] = [
                'created_at',
                '<=',
                $filter['created_at_end'] . " 23:59:59"
            ];
        } elseif ($filter['created_at_start'] === '' &&
            $filter['created_at_end'] !== ''
        ) {
            $where[] = [
                'created_at',
                '<=',
                $filter['created_at_end'] . " 23:59:59"
            ];
        } elseif ($filter['created_at_start'] !== '' &&
            $filter['created_at_end'] === ''
        ) {
            $where[] = [
                'created_at',
                '>=',
                $filter['created_at_start'] . " 00:00:00"
            ];
        }
        $systemUsers = SystemUser::where($where)
            ->orderBy($orderBy['order_field'], $orderBy['order_type'])
            ->paginate($pageInfo['pageSize']);

        return view('/admin/system/user/index',
                    compact('systemUsers', 'pageInfo', 'orderBy', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/admin/system/user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SystemUserRequest $systemUserRequest)
    {
        \DB::beginTransaction();//开启事务
        try {
            $data = $systemUserRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            $data['password'] = bcrypt($data['password']);
            $data['remember_token'] = str_random(64);
            $data['status'] = $data['status'] ?? 0;
            $systemUser = SystemUser::create($data);
            $response = [
                'url' => action('Admin\System\UserController@index'),
                'id' => $systemUser->id
            ];
            \DB::commit();//提交事务

            return $this->response('添加成功', 201, $response);

        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $systemUser = SystemUser::find($id);
        if (!$systemUser || $systemUser->id == 1) {
            abort(403, '参数无效');
        }

        return view('/admin/system/user/edit', compact('systemUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SystemUserRequest $systemUserRequest, $id)
    {
        $systemUser = SystemUser::find($id);
        if (!$systemUser || $systemUser->id == 1) {
            return $this->response('参数无效', 403);
        }
        \DB::beginTransaction();//开启事务
        try {
            $data = $systemUserRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            if ($data['password'] !== '') {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            $data['status'] = $data['status'] ?? 0;
            $systemUser->update($data);
            $response = [
                'url' => action('Admin\System\UserController@index')
            ];
            \DB::commit();//提交事务

            return $this->response('修改成功', 200, $response);

        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 0) {
                if ($id > 1) {
                    SystemUser::where('id', $id)->delete();
                    //                    \DB::table('bs_personates')->where('bs_admin_id', $id)
                    //                        ->delete();
                    //                    \DB::table('bs_belongs')->where('bs_admin_id', $id)
                    //                        ->delete();
                    \DB::commit();//提交事务

                    return $this->response('删除成功', 200);
                } else {
                    \DB::rollback();//回滚事务

                    return $this->Response('系统专属账号不可操作', 400);
                }
            } else {
                $ids = is_array($request->ids) ?
                    $request->ids :
                    explode(',', $request->ids);
                SystemUser::where('id', '<>', '1')->whereIn('id', $ids)
                    ->delete();
                //                \DB::table('bs_personates')->where('bs_admin_id', '<>', '1')
                //                    ->whereIn('bs_admin_id', $ids)->delete();
                //                \DB::table('bs_belongs')->where('bs_admin_id', '<>', '1')
                //                    ->whereIn('bs_admin_id', $ids)->delete();
                \DB::commit();//提交事务

                return $this->response('批量删除成功', 200);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable($id, Request $request)
    {
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 0) {
                if ($id > 1) {
                    SystemUser::where('id', $id)->update(['status' => '1']);
                    \DB::commit();//提交事务

                    return $this->response('启用成功', 200);
                } else {
                    \DB::rollback();//回滚事务

                    return $this->Response('非法操作', 400);
                }
            } else {
                $ids = is_array($request->ids) ?
                    $request->ids :
                    explode(',', $request->ids);
                SystemUser::where('id', '<>', '1')->whereIn('id', $ids)
                    ->update(['status' => '1']);
                \DB::commit();//提交事务

                return $this->response('批量启用成功', 200);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable($id, Request $request)
    {
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 0) {
                if ($id > 1) {
                    SystemUser::where('id', $id)->update(['status' => '0']);
                    \DB::commit();//提交事务

                    return $this->response('禁用成功', 200);
                } else {
                    \DB::rollback();//回滚事务

                    return $this->Response('系统专属账号不可禁用', 400);
                }
            } else {
                $ids = is_array($request->ids) ?
                    $request->ids :
                    explode(',', $request->ids);
                SystemUser::where('id', '<>', '1')->whereIn('id', $ids)
                    ->update(['status' => '0']);
                \DB::commit();//提交事务

                return $this->response('批量禁用成功', 200);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

}
