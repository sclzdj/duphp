<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 17:02
 */

namespace App\Servers;

use App\Model\Admin\SystemConfig;
use App\Model\Admin\SystemFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileServer {

    public $save_filename = null;

    public $save_watermark_filename = null;

    public $save_thumb_filename = null;

    public $save_watermark_thumb_filename = null;

    /**
     * @param string $filetype 上传类型，file，image
     * @param string $filename 保存的文件名
     * @param string $path     保存目录
     * @param string $key      表单名称
     *
     * @return false|string
     */
    public function upload($filetype, $filename, $path = '', $file, $fileInfo = []) {
        if (config('filesystems.default') == 'local') {
            //先删除原来的缩略图和水印图
            if ($fileInfo['extension'] !== '') {
                $save_filename = $filename.'.'.$fileInfo['extension'];
                if ($filetype == 'image') {
                    $save_watermark_filename = $filename.'_watermark.'.$fileInfo['extension'];
                    $save_thumb_filename = $filename.'_thumb.'.$fileInfo['extension'];
                    $save_watermark_thumb_filename = $filename.'_watermark_thumb.'.$fileInfo['extension'];
                }
            } else {
                $save_filename = $filename;
                if ($filetype == 'image') {
                    $save_watermark_filename = $filename.'_watermark';
                    $save_thumb_filename = $filename.'_thumb';
                    $save_watermark_thumb_filename = $filename.'_watermark_thumb';
                }
            }
            $this->save_filename = $save_filename;
            if ($filetype == 'image') {
                $this->save_watermark_filename = $save_watermark_filename;
                $this->save_thumb_filename = $save_thumb_filename;
                $this->save_watermark_thumb_filename = $save_watermark_thumb_filename;
            }
            $systemFile = SystemFile::where(['filename' => $filename])->first();
            if (!$systemFile) {
                $systemFile = SystemFile::create();
            } else {
                if ($systemFile->extension !== '') {
                    Storage::delete('public/'.$path.'/'.$filename.'.'.$systemFile->extension);
                } else {
                    Storage::delete('public/'.$path.'/'.$filename);
                }
                if (strpos($systemFile->mimeType, 'image/') !== false) {
                    if ($systemFile->extension !== '') {
                        Storage::delete('public/'.$path.'/'.$filename.'_watermark.'.$systemFile->extension);
                        Storage::delete('public/'.$path.'/'.$filename.'_thumb.'.$systemFile->extension);
                        Storage::delete('public/'.$path.'/'.$filename.'_watermark_thumb.'.$systemFile->extension);
                    } else {
                        Storage::delete('public/'.$path.'/'.$filename.'_watermark');
                        Storage::delete('public/'.$path.'/'.$filename.'_thumb');
                        Storage::delete('public/'.$path.'/'.$filename.'_watermark_thumb');
                    }
                }
            }
            $object = $file->storeAs('public/'.$path, $save_filename);
            if ($filetype == 'image') {
                if (!is_dir('storage')) {
                    return false;
                }
                $newpath = 'storage/'.$path;
                if ($fileInfo['scene'] == 'set_admin_logo') {
                    $url = asset(Storage::url($object));//原始文件
                } elseif ($fileInfo['scene'] == 'set_admin_logo_text') {
                    $url = asset(Storage::url($object));//原始文件
                } elseif ($fileInfo['scene'] == 'set_upload_image_watermark') {
                    $url = asset(Storage::url($object));//原始文件
                    $img = Image::make($file);
                    if ($img->width() > 100 || $img->height() > 100) {//把上传的水印图片等比缩小到100px以下
                        $img->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        }
                        )->save($newpath.'/'.$save_filename);
                    }
                } else {
                    $gbImg = Image::make($file);
                    //生成水印
                    $upload_image_watermark_on = SystemConfig::where(['type' => 'upload', 'name' => 'upload_image_watermark_on'])->value('value');//水印开关
                    $upload_image_watermark_pic = SystemConfig::where(['type' => 'upload', 'name' => 'upload_image_watermark_pic'])->value('value');//水印图片
                    if ($upload_image_watermark_pic === '') {
                        $upload_image_watermark_pic = 'static/admin/img/watermark.png';
                    }
                    $upload_image_watermark_position = SystemConfig::where(['type' => 'upload', 'name' => 'upload_image_watermark_position'])->value('value');//水印位置
                    $upload_image_watermark_position = $upload_image_watermark_position === '' ? 'bottom-right' : $upload_image_watermark_position;
                    $img = Image::make($file);
                    $marginX = 5;
                    $marginY = 5;
                    $water = Image::make($upload_image_watermark_pic);
                    $bwWidth = 2 * ($water->width() + $marginX * 2);
                    $bwHeight = 2 * ($water->height() + $marginY * 2);
                    $watermark_status = $img->width() >= $bwWidth && $img->height() >= $bwHeight;
                    if ($upload_image_watermark_on == 1 && $watermark_status) {//true为预留的全局配置
                        $img->insert($upload_image_watermark_pic, $upload_image_watermark_position, $marginX, $marginY)->save($newpath.'/'.$save_watermark_filename);
                        $gbImg->insert($upload_image_watermark_pic, $upload_image_watermark_position, $marginX, $marginY);
                    } else {
                        $img->save($newpath.'/'.$save_watermark_filename);
                    }
                    //生成缩略图
                    $upload_image_thumb_on = SystemConfig::where(['type' => 'upload', 'name' => 'upload_image_thumb_on'])->value('value');//水印开关
                    $upload_image_thumb_size = SystemConfig::where(['type' => 'upload', 'name' => 'upload_image_thumb_size'])->value('value');//水印图片
                    if ($upload_image_thumb_size !== '') {
                        $upload_image_thumb_size = explode('*', $upload_image_thumb_size);
                    } else {
                        $upload_image_thumb_size = [200, 200];
                    }
                    $img = Image::make($file);
                    $thumb_status = $img->width() > $upload_image_thumb_size[0] || $img->height() > $upload_image_thumb_size[1];
                    if ($upload_image_thumb_on == 1 && $thumb_status) {//true为预留的全局配置
                        $img->resize($upload_image_thumb_size[0], $upload_image_thumb_size[1], function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        }
                        )->save($newpath.'/'.$save_thumb_filename);
                        $gbImg->resize($upload_image_thumb_size[0], $upload_image_thumb_size[1], function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        }
                        );
                    } else {
                        $img->save($newpath.'/'.$save_thumb_filename);
                    }
                    $gbImg->save($newpath.'/'.$save_watermark_thumb_filename);
                    $url = asset('image_storage?filename='.urlencode('storage/'.$path.'/'.$filename).'&extension='.$fileInfo['extension']);
                }
            } else {
                $url = asset(Storage::url($object));//原始文件
            }
            $update = [
              'url'      => $url,
              'disk'     => config('filesystems.default'),
              'driver'   => config('filesystems.disks.'.
                config('filesystems.default').'.driver'
              ),
              'object'   => $object,
              'filename' => $filename,
            ];
            $update = array_merge($update, $fileInfo);
            $systemFile->update($update);

            return $url;
        } else {
            return false;
        }
    }
}
