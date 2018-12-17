<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemUserRequest;
use App\Model\Admin\SystemUser;
use App\Servers\ArrServer;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index()
    {
        return view('/admin/system/index/index');
    }

    public function config()
    {
        return view('/admin/system/index/config');
    }

    public function updatePassword(SystemUserRequest $systemUserRequest)
    {
        if ($systemUserRequest->method() == 'PATCH') {
            $id = auth()->id();
            $systemUser = SystemUser::find($id);
            if (!$systemUser) {
                return $this->response('参数无效', 403);
            }
            \DB::beginTransaction();//开启事务
            try {
                $data = $systemUserRequest->all();
                $data = ArrServer::null2strData($data);
                $data = ArrServer::inData($data, ['password']);
                if ($data['password'] !== '') {
                    $data['password'] = bcrypt($data['password']);
                } else {
                    unset($data['password']);
                }
                $systemUser->update($data);
                \DB::commit();//提交事务

                return $this->response('修改成功', 200);

            } catch (\Exception $e) {
                \DB::rollback();//回滚事务

                return $this->eResponse($e->getMessage(), $e->getCode());
            }
        }

        return view('/admin/system/index/update_password');
    }

    public function setInfo(SystemUserRequest $systemUserRequest)
    {
        if ($systemUserRequest->method() == 'PUT') {
            $id = auth()->id();
            $systemUser = SystemUser::find($id);
            if (!$systemUser) {
                return $this->response('参数无效', 403);
            }
            \DB::beginTransaction();//开启事务
            try {
                $data = $systemUserRequest->all();
                $data = ArrServer::null2strData($data);
                $data = ArrServer::inData($data,
                                          ['password', 'nickname', 'avatar']);
                if ($data['password'] !== '') {
                    $data['password'] = bcrypt($data['password']);
                } else {
                    unset($data['password']);
                }
                $systemUser->update($data);
                \DB::commit();//提交事务

                return $this->response('设置成功', 200);

            } catch (\Exception $e) {
                \DB::rollback();//回滚事务

                return $this->eResponse($e->getMessage(), $e->getCode());
            }
        }
        $systemUser = auth('admin')->user();

        return view('/admin/system/index/set_info', compact('systemUser'));
    }
}
