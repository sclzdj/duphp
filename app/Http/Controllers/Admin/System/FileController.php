<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Servers\FileServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{
    /**
     * 文件上传
     *
     * @param string $path 保存目录
     * @param string $key  表单名称
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, $path = 'public', $key = 'file')
    {
        DB::beginTransaction();//开启事务
        $FileServer = new FileServer();
        try {
            if (request()->method() == 'OPTIONS') {
                return $this->response([]);
            }
            $url = $FileServer->upload($request, $path, $key);
            if ($url !== false) {
                DB::commit();//提交事务
                return $this->uploadResponse('上传成功', 201, ['url' => $url]);
            } else {
                DB::rollback();//回滚事务
                return $this->uploadResponse('上传失败', 400);
            }
        } catch (\Exception $e) {
            DB::rollback();//回滚事务
            if ($FileServer->seccess_path) {
                Storage::delete($FileServer->seccess_path);
            }

            return $this->eResponse($e->getMessage(), $e->getCode());
        }

    }
}
