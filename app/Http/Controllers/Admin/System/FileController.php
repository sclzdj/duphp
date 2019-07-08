<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Model\Admin\SystemConfig;
use App\Model\Admin\SystemFile;
use App\Servers\ArrServer;
use App\Servers\FileServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController {

    public function index(Request $request) {
        $scenes = SystemFile::select('scene')->where('scene', '<>', '')->distinct()->get()->toArray();//查出所有场景
        $pageInfo = [
          'pageSize' => $request['pageSize'] !== null ?
            $request['pageSize'] :
            SystemConfig::getVal('basic_page_size'),
          'page'     => $request['page'] !== null ?
            $request['page'] :
            1,
        ];

        $filter = [
          'url'              => $request['url'] !== null ?
            $request['url'] :
            '',
          'driver'           => $request['driver'] !== null ?
            $request['driver'] :
            '',
          'disk'             => $request['disk'] !== null ?
            $request['disk'] :
            '',
          'scene'            => $request['scene'] !== null ?
            $request['scene'] :
            '',
          'mimeType'         => $request['mimeType'] !== null ?
            $request['mimeType'] :
            '',
          'created_at_start' => $request['created_at_start'] !== null ?
            $request['created_at_start'] :
            '',
          'created_at_end'   => $request['created_at_end'] !== null ?
            $request['created_at_end'] :
            '',
        ];
        $orderBy = [
          'order_field' => $request['order_field'] !== null ?
            $request['order_field'] :
            'id',
          'order_type'  => $request['order_type'] !== null ?
            $request['order_type'] :
            'desc',
        ];
        $where = [];
        if ($filter['url'] !== '') {
            $where[] = ['url', 'like', '%'.$filter['url'].'%'];
        }
        if ($filter['driver'] !== '') {
            $where[] = ['driver', '=', $filter['driver']];
        }
        if ($filter['disk'] !== '') {
            $where[] = ['disk', '=', $filter['disk']];
        }
        if ($filter['scene'] !== '') {
            $where[] = ['scene', '=', $filter['scene']];
        }
        if ($filter['mimeType'] !== '') {
            $where[] = ['mimeType', 'like', '%'.$filter['mimeType'].'%'];
        }
        if ($filter['created_at_start'] !== ''
          && $filter['created_at_end'] !== ''
        ) {
            $where[] = [
              'created_at',
              '>=',
              $filter['created_at_start']." 00:00:00",
            ];
            $where[] = [
              'created_at',
              '<=',
              $filter['created_at_end']." 23:59:59",
            ];
        } elseif ($filter['created_at_start'] === ''
          && $filter['created_at_end'] !== ''
        ) {
            $where[] = [
              'created_at',
              '<=',
              $filter['created_at_end']." 23:59:59",
            ];
        } elseif ($filter['created_at_start'] !== ''
          && $filter['created_at_end'] === ''
        ) {
            $where[] = [
              'created_at',
              '>=',
              $filter['created_at_start']." 00:00:00",
            ];
        }
        $systemFiles = SystemFile::where($where)
                                 ->orderBy($orderBy['order_field'], $orderBy['order_type'])
                                 ->paginate($pageInfo['pageSize']);

        return view('/admin/system/file/index',
          compact('systemFiles', 'pageInfo', 'orderBy', 'filter', 'scenes')
        );
    }

    public function destroy(Request $request) {
        $id = $request->id;
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 0) {
                $result = SystemFile::delFileAndRow($id);
                \DB::commit();//提交事务
                if ($result) {
                    return $this->response('<div>有关联数据，不能删除！关联数据：<li>表:'.$result['table'].'，字段:'.$result['field'].'，关联记录:'.$result['id_str'].'</li></div>', 400);
                }

                return $this->response('删除成功', 200);
            } else {
                $ids = is_array($request->ids) ?
                  $request->ids :
                  explode(',', $request->ids);
                $result = SystemFile::delFileAndRow($ids);
                \DB::commit();//提交事务
                if ($result) {
                    $message = '<div><span style="color: #c54736;">以下记录有关联数据，不能删除</span>，其它已批量删除成功！<div><span style="color: #c54736;">有关联数据记录：</span>';
                    foreach ($result as $r) {
                        $message .= '<li style="color: #c54736;">记录:'.$r['id'].'，表'.$r['table'].'，字段:'.$r['field'].'，关联记录:'.$r['id_str'].'</li>';
                    }
                    $message .= '</div>';
                } else {
                    $message = '批量删除成功';
                }

                return $this->response($message, 200);
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
    public function upload(Request $request, $path = 'uploads', $key = 'file') {
        $upload_type = (string)$request->instance()->post('upload_type', 'file');
        $filename = (string)$request->instance()->post('filename', '');
        $scene = (string)$request->instance()->post('scene', '');
        $filename = ltrim(str_replace('\\', '/', $filename), '/');
        $upload_scenes = config('custom.upload_scenes');
        if (!isset($upload_scenes[$scene])) {
            return $this->uploadResponse('该场景值没有被配置预设，无效', 400);
        }
        if ($filename === '' || in_array($upload_type, ['images', 'files'])) {
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
            return $this->uploadResponse('php.ini最大限制上传'.
              number_format($fileInfo['iniSize'] /
                1024 / 1024, 2, '.',
                ''
              ).'M的文件', 400
            );
        }
        if ($scene == '这里写你要判断的场景') {//这里是上传场景可以根据这个做一些特殊判断，下面写出对应的限制即可
            $upload_image_limit_size = '';
            $upload_image_allow_extension = '';
            $upload_file_limit_size = '';
            $upload_file_allow_extension = '';
        }
        $filetype = 'file';
        if (strpos($fileInfo['mimeType'], 'image/') !== false) {
            $filetype = 'image';
            $upload_image_limit_size = $upload_image_limit_size ?? SystemConfig::getVal('upload_image_limit_size', 'upload');
            if ($upload_image_limit_size > 0
              && $fileInfo['size'] > $upload_image_limit_size * 1000
            ) {
                return $this->uploadResponse('最大允许上传'.
                  $upload_image_limit_size.'K的图片',
                  400
                );
            }
            $upload_image_allow_extension = $upload_image_allow_extension ?? SystemConfig::getVal('upload_image_allow_extension', 'upload');
            if ($upload_image_allow_extension !== '') {
                $upload_image_allow_extension_arr =
                  explode(',', $upload_image_allow_extension);
                if (!in_array($fileInfo['extension'],
                  $upload_image_allow_extension_arr
                )
                ) {
                    return $this->uploadResponse('只允许上传图片的后缀类型：'.
                      $upload_image_allow_extension,
                      400
                    );
                }
            }
        } else {
            $upload_file_limit_size = $upload_file_limit_size ?? SystemConfig::getVal('upload_file_limit_size', 'upload');
            if ($upload_file_limit_size > 0
              && $fileInfo['size'] > $upload_file_limit_size * 1000
            ) {
                return $this->uploadResponse('最大允许上传'.
                  $upload_file_limit_size.'K的文件',
                  400
                );
            }
            $upload_file_allow_extension = $upload_file_allow_extension ?? SystemConfig::getVal('upload_file_allow_extension', 'upload');
            if ($upload_file_allow_extension !== '') {
                $upload_file_allow_extension_arr =
                  explode(',', $upload_file_allow_extension);
                if (!in_array($fileInfo['extension'],
                  $upload_file_allow_extension_arr
                )
                ) {
                    return $this->uploadResponse('只允许上传文件的后缀类型：'.
                      $upload_file_allow_extension,
                      400
                    );
                }
            }
        }
        $fileInfo['scene'] = $scene;
        \DB::beginTransaction();//开启事务
        $FileServer = new FileServer();
        try {
            if (request()->method() == 'OPTIONS') {
                return $this->response([]);
            }

            $url = $FileServer->upload($filetype, $filename, $path, $request->file($key), $fileInfo);
            if ($url !== false) {
                \DB::commit();//提交事务

                return $this->uploadResponse('上传成功', 201, ['url' => $url]);
            } else {
                \DB::rollback();//回滚事务
                $FileServer->delete($FileServer->objects);

                return $this->uploadResponse('上传失败', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务
            $FileServer->delete($FileServer->objects);

            return $this->eResponse($e->getMessage(), $e->getCode());
        }

    }

    /**
     * 图片显示专属方法
     *
     * @param \Illuminate\Http\Request $request
     */
    public function image(Request $request) {
        $filename = urldecode($request->instance()->get('filename', ''));
        $extension = $request->instance()->get('extension', '');
        if ($filename === '') {
            abort(422, '缺少图片名');
        }
        $type = $request->instance()->get('type', 0);//1:水印 2:缩略图 3:缩略图加水印 其它:原图
        if ($extension !== '') {
            $save_filename = $filename.'.'.$extension;
            $save_watermark_filename = $filename.'_watermark.'.$extension;
            $save_thumb_filename = $filename.'_thumb.'.$extension;
            $save_watermark_thumb_filename = $filename.'_watermark_thumb.'.$extension;
        } else {
            $save_filename = $filename;
            $save_watermark_filename = $filename.'_watermark';
            $save_thumb_filename = $filename.'_thumb';
            $save_watermark_thumb_filename = $filename.'_watermark_thumb';
        }
        $img = $save_filename;
        if ($type == 1) {
            if (is_file($save_watermark_filename)) {
                $img = $save_watermark_filename;
            }
        } elseif ($type == 2) {
            if (is_file($save_thumb_filename)) {
                $img = $save_thumb_filename;
            }
        } elseif ($type == 3) {
            if (is_file($save_watermark_thumb_filename)) {
                $img = $save_watermark_thumb_filename;
            }
        }
        if (!is_file($img)) {
            abort(404, '未找到图片');
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mime = finfo_file($finfo, $img);
        finfo_close($finfo);
        if (strpos($mime, 'image/') === false) {
            abort(404, '未找到图片');
        }
        header('Content-Type:'.$mime);
        echo file_get_contents($img);
    }

}
