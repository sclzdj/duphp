<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemConfigRequest;
use App\Http\Requests\Admin\SystemUserRequest;
use App\Model\Admin\SystemConfig;
use App\Model\Admin\SystemUser;
use App\Servers\ArrServer;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index()
    {
        return view('/admin/system/index/index');
    }

    /**
     * ***添加图片这种配置时，要注意webuploader-image.js和文件管理模板页面的修改
     * @param \App\Http\Requests\Admin\SystemConfigRequest $systemConfigRequest
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function config(SystemConfigRequest $systemConfigRequest)
    {
        $type = $systemConfigRequest->type ?:
            'basic';
        if ($systemConfigRequest->method() == 'POST') {
            \DB::beginTransaction();//开启事务
            try {
                $data = $systemConfigRequest->all();
                $data = ArrServer::null2strData($data);
                $systemConfigs = SystemConfig::where('type', $type)->get();
                foreach ($systemConfigs as $systemConfig) {
                    if ($systemConfig['genre'] == 'switch') {
                        $data[$systemConfig['name']] =
                            $data[$systemConfig['name']] ?? 0;
                    } elseif ($systemConfig['genre'] == 'checkbox') {
                        $data[$systemConfig['name']] =
                            implode(',', $data[$systemConfig['name']]);
                    } elseif ($systemConfig['genre'] == 'icon') {
                        $data[$systemConfig['name']] =
                            'fa ' . $data[$systemConfig['name']];
                    }
                    SystemConfig::where('type', $type)
                        ->where('name', $systemConfig['name'])
                        ->update(['value' => $data[$systemConfig['name']]]);
                }
                \DB::commit();//提交事务

                return $this->response('保存成功', 200);

            } catch (\Exception $e) {
                \DB::rollback();//回滚事务

                return $this->eResponse($e->getMessage(), $e->getCode());
            }
        }
        if ($systemConfigRequest->method() == 'PUT') {
            \DB::beginTransaction();//开启事务
            try {
                $data = $systemConfigRequest->all();
                $data = ArrServer::null2strData($data);
                $systemConfig = SystemConfig::where('type', 'basic')
                    ->where('name', $data['name'])->first();
                if (!$systemConfig) {
                    \DB::rollback();//回滚事务

                    return $this->response('无效请求', 400);
                }
                $systemConfig->value=$data['value'];
                $systemConfig->save();

                \DB::commit();//提交事务

                return $this->response('设置成功', 200);

            } catch (\Exception $e) {
                \DB::rollback();//回滚事务

                return $this->eResponse($e->getMessage(), $e->getCode());
            }
        }
        $types = [
            'basic' => '基本',
            'admin' => '后台',
            'upload' => '上传',
            'index' => '前台',
            'wap' => '移动端',
            'weixin' => '微信',
            'wechat' => '微信公众号',
            'wxapp' => '微信小程序',
            'ali' => '阿里',
        ];
        foreach ($types as $key => $t) {
            $data = SystemConfig::where('type', $key)->get();
            if ($type == $key) {
                $systemConfigs = $data;
            }
            if (count($data) == 0) {
                unset($types[$key]);
            }
        }
        $genres = ArrServer::ids($systemConfigs, 'genre');

        return view('/admin/system/index/config',
                    compact('types', 'type', 'systemConfigs', 'genres'));
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
