<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Model\Admin\SystemConfig;
use App\Model\Admin\SystemFile;
use App\Servers\ArrServer;
use App\Servers\FileServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{
    public function index(Request $request)
    {
        $pageInfo = [
            'pageSize' => $request['pageSize'] !== null ?
                $request['pageSize'] :
                SystemConfig::getVal('basic_page_size'),
            'page' => $request['page'] !== null ?
                $request['page'] :
                1
        ];

        $filter = [
            'url' => $request['url'] !== null ?
                $request['url'] :
                '',
            'driver' => $request['driver'] !== null ?
                $request['driver'] :
                '',
            'disk' => $request['disk'] !== null ?
                $request['disk'] :
                '',
            'mimeType' => $request['mimeType'] !== null ?
                $request['mimeType'] :
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
                'desc',
        ];
        $where = [];
        if ($filter['url'] !== '') {
            $where[] = ['url', 'like', '%' . $filter['url'] . '%'];
        }
        if ($filter['driver'] !== '') {
            $where[] = ['driver', '=', $filter['driver']];
        }
        if ($filter['disk'] !== '') {
            $where[] = ['disk', '=', $filter['disk']];
        }
        if ($filter['mimeType'] !== '') {
            $where[] = ['mimeType', 'like', '%' . $filter['mimeType'] . '%'];
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
        $systemFiles = SystemFile::where($where)
            ->orderBy($orderBy['order_field'], $orderBy['order_type'])
            ->paginate($pageInfo['pageSize']);

        return view('/admin/system/file/index',
                    compact('systemFiles', 'pageInfo', 'orderBy', 'filter'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 0) {
                $systemFile = SystemFile::find($id);
                SystemFile::where('id', $id)->delete();
                /**
                 * 此处应该执行一个钩子，检测该文件有没有其它地方在使用
                 *
                 *
                 *
                 *
                 */
                if ($systemFile->driver == 'local') {
                    Storage::disk($systemFile->disk)
                        ->delete($systemFile->object);
                }
                \DB::commit();//提交事务

                return $this->response('删除成功', 200);
            } else {
                $ids = is_array($request->ids) ?
                    $request->ids :
                    explode(',', $request->ids);
                $systemFiles = SystemFile::whereIn('id', $ids)->get();
                /**
                 * 此处应该执行一个钩子，检测该文件有没有其它地方在使用
                 * 需要返回可以删除的ids
                 *
                 *
                 */
                SystemFile::whereIn('id', $ids)->delete();
                foreach ($systemFiles as $systemFile) {
                    if ($systemFile->driver == 'local') {
                        Storage::disk($systemFile->disk)
                            ->delete($systemFile->object);
                    }
                }
                \DB::commit();//提交事务

                return $this->response('批量删除成功', 200);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }


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
        $filename = $request->instance()->post('filename', '');
        $filename = ltrim(str_replace('\\', '/', $filename), '/');
        if ($filename === '') {
            $filename = date("Ymd/").time().mt_rand(10000, 99999);
        }
        if (!$request->hasFile($key)) {
            return $this->uploadResponse('没有选择上传文件', 400);
        }
        if (!$request->file($key)->isValid()) {
            return $this->uploadResponse('上传过程中出错，请主要检查php.ini是否配置正确', 400);
        }
        $fileInfo = [];
        $fileInfo['extension'] = $request->file->extension();
        $fileInfo['mimeType'] = $request->file->getMimeType();
        $fileInfo['size'] = $request->file->getClientSize();
        $fileInfo['iniSize'] = $request->file->getMaxFilesize();
        if ($fileInfo['size'] > $fileInfo['iniSize']) {
            return $this->uploadResponse('php.ini最大限制上传' .
                                         number_format($fileInfo['iniSize'] /
                                                       1024 / 1024, 2, '.',
                                                       '') . 'M的文件', 400);
        }
        if (strpos($fileInfo['mimeType'], 'image/') !== false) {
            $upload_image_limit_size =
                SystemConfig::getVal('upload_image_limit_size', 'upload');
            if ($upload_image_limit_size > 0 &&
                $fileInfo['size'] > $upload_image_limit_size * 1000
            ) {
                return $this->uploadResponse('最大允许上传' .
                                             $upload_image_limit_size . 'K的图片',
                                             400);
            }
            $upload_image_allow_extension =
                SystemConfig::getVal('upload_image_allow_extension', 'upload');
            if ($upload_image_allow_extension !== '') {
                $upload_image_allow_extension_arr =
                    explode(',', $upload_image_allow_extension);
                if (!in_array($fileInfo['extension'],
                              $upload_image_allow_extension_arr)
                ) {
                    return $this->uploadResponse('只允许上传图片的后缀类型：' .
                                                 $upload_image_allow_extension,
                                                 400);
                }
            }
        } else {
            $upload_file_limit_size =
                SystemConfig::getVal('upload_file_limit_size', 'upload');
            if ($upload_file_limit_size > 0 &&
                $fileInfo['size'] > $upload_file_limit_size * 1000
            ) {
                return $this->uploadResponse('最大允许上传' .
                                             $upload_file_limit_size . 'K的文件',
                                             400);
            }
            $upload_file_allow_extension =
                SystemConfig::getVal('upload_file_allow_extension', 'upload');
            if ($upload_file_allow_extension !== '') {
                $upload_file_allow_extension_arr =
                    explode(',', $upload_file_allow_extension);
                if (!in_array($fileInfo['extension'],
                              $upload_file_allow_extension_arr)
                ) {
                    return $this->uploadResponse('只允许上传文件的后缀类型：' .
                                                 $upload_file_allow_extension,
                                                 400);
                }
            }
        }
        if($fileInfo['extension']!==''){
            $filename .= '.'.$fileInfo['extension'];
        }
        \DB::beginTransaction();//开启事务
        $FileServer = new FileServer();
        try {
            if (request()->method() == 'OPTIONS') {
                return $this->response([]);
            }

            $url = $FileServer->upload($filename,$path, $request->file($key), $fileInfo);
            if ($url !== false) {
                \DB::commit();//提交事务

                return $this->uploadResponse('上传成功', 201, ['url' => $url]);
            } else {
                \DB::rollback();//回滚事务

                return $this->uploadResponse('上传失败', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务
            if ($FileServer->object) {
                Storage::delete($FileServer->object);
            }

            return $this->eResponse($e->getMessage(), $e->getCode());
        }

    }

}
