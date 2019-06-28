<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 17:02
 */

namespace App\Servers;

use App\Model\Admin\SystemFile;
use Illuminate\Support\Facades\Storage;

class FileServer
{
    public $object = null;//成功上传的文件

    /**
     * @param string $filename 保存的文件名
     * @param string  $path 保存目录
     * @param string  $key  表单名称
     *
     * @return false|string
     */
    public function upload($filename,$path = 'public/uploads', $file, $fileInfo = [])
    {
        if (config('filesystems.default') == 'local') {
            $object = $file->storeAs($path,$filename);
            $this->object = $object;
            $url = asset(Storage::url($object));
            $systemFile = SystemFile::where(['url' => $url])->first();
            if (!$systemFile) {
                $systemFile = SystemFile::create();
            }
            $update = [
                'url' => $url,
                'disk' => config('filesystems.default'),
                'driver' => config('filesystems.disks.' .
                                   config('filesystems.default') . '.driver'),
                'object' => $object
            ];
            $update = array_merge($update, $fileInfo);
            $systemFile->update($update);

            return $url;
        } else {
            return false;
        }
    }
}
