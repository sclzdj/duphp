<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 17:02
 */

namespace App\Servers;

use App\Model\Admin\SystemFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileServer
{
    public $seccess_path = null;//成功上传文件路径

    /**
     * @param Request $request
     * @param string  $path 保存目录
     * @param string  $key  表单名称
     *
     * @return false|string
     */
    public function upload($path = 'public', $file, $fileInfo = [])
    {
        if (config('filesystems.default') == 'local') {
            $path = $path . '/uploads/' . date('Ymd');
            $path = $file->store($path);
            $this->seccess_path = $path;
            $url = asset(Storage::url($path));
            $create = [
                'url' => $url,
                'disk' => config('filesystems.default'),
                'driver' => config('filesystems.disks.' .
                                   config('filesystems.default') . '.driver'),
                'object' => $path
            ];
            $create = array_merge($create, $fileInfo);
            SystemFile::create($create);

            return $url;
        } else {
            return false;
        }
    }
}
