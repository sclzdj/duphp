<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemRoleRequest;
use App\Model\Admin\SystemRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
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
            'name' => $request['name'] !== null ?
                $request['name'] :
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
        if ($filter['name'] !== '') {
            $where[] = ['name', 'like', '%' . $filter['name'] . '%'];
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
        $systemRoles = SystemRole::where($where)
            ->orderBy($orderBy['order_field'], $orderBy['order_type'])
            ->paginate($pageInfo['pageSize']);

        return view('/admin/system/role/index',
                    compact('systemRoles', 'pageInfo', 'orderBy', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/admin/system/role/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SystemRoleRequest $systemRoleRequest)
    {
        DB::beginTransaction();//开启事务
        try {
            $data = $systemRoleRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            $data['status'] = $data['status'] ?? 0;
            $systemRole = SystemRole::create($data);
            $response = [
                'url' => action('Admin\System\RoleController@index'),
                'id' => $systemRole->id
            ];
            DB::commit();//提交事务

            return $this->response('添加成功', 201, $response);

        } catch (\Exception $e) {
            DB::rollback();//回滚事务

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
        $systemRole = SystemRole::find($id);
        if (!$systemRole) {
            abort(403, '参数无效');
        }

        return view('/admin/system/role/edit', compact('systemRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SystemRoleRequest $systemRoleRequest, $id)
    {
        $systemRole = SystemRole::find($id);
        if (!$systemRole) {
            return $this->response('参数无效', 403);
        }
        DB::beginTransaction();//开启事务
        try {
            $data = $systemRoleRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            $data['status'] = $data['status'] ?? 0;
            $systemRole->update($data);
            $response = [
                'url' => action('Admin\System\RoleController@edit',
                                ['id' => $id])
            ];
            DB::commit();//提交事务

            return $this->response('修改成功', 200, $response);

        } catch (\Exception $e) {
            DB::rollback();//回滚事务

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
    public function destroy($id)
    {
        //
    }
}
