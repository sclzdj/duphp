<?php

namespace App\Model\Admin;

use App\Servers\FileServer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SystemFile extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'url',
      'original_url',
      'disk',
      'driver',
      'object',
      'objects',
      'extension',
      'mimeType',
      'size',
      'scene',
      'filename',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 删除文件和数据库记录
     *
     * @param string $ids 文件id集合
     *
     * @return array
     */
    static public function delFileAndRow($ids) {
        $upload_scenes = config('custom.upload_scenes');
        $result = [];
        $objects = [];
        if (is_numeric($ids)) {
            $systemFile = self::where('id', $ids)->first();
            if (isset($upload_scenes[$systemFile->scene])) {
                $where = [];
                foreach ($upload_scenes[$systemFile->scene]['where'] as $k => $v) {
                    if($v=='like'){
                        $where[] = "`{$k}` {$v} '%{$systemFile->url}%'";
                    }else{
                        $where[] = "`{$k}` {$v} '{$systemFile->url}'";
                    }
                }
                $where = implode(' OR ', $where);
                $id_str = \DB::table($upload_scenes[$systemFile->scene]['table'])->selectRaw('GROUP_CONCAT(`id`) AS `id_str`')->whereRaw($where)->first()->id_str;
                if (!$id_str) {
                    $systemFile->delete();
                    $objects[] = $systemFile->objects;
                } else {
                    $result = [
                      'table'  => $upload_scenes[$systemFile->scene]['table'],
                      'field'  => implode('|',array_keys($upload_scenes[$systemFile->scene]['where'])),
                      'id_str' => $id_str,
                    ];
                }
            }
        } else {
            $systemFiles = self::whereIn('id', is_array($ids) ? $ids : explode(',', $ids))->get();
            foreach ($systemFiles as $systemFile) {
                if (isset($upload_scenes[$systemFile->scene])) {
                    $where = [];
                    foreach ($upload_scenes[$systemFile->scene]['where'] as $k => $v) {
                        if($v=='like'){
                            $where[] = "`{$k}` {$v} '%{$systemFile->url}%'";
                        }else{
                            $where[] = "`{$k}` {$v} '{$systemFile->url}'";
                        }
                    }
                    $where = implode(' OR ', $where);
                    $id_str = \DB::table($upload_scenes[$systemFile->scene]['table'])->selectRaw('GROUP_CONCAT(`id`) AS `id_str`')->whereRaw($where)->first()->id_str;
                    if (!$id_str) {
                        $systemFile->delete();
                        $objects[] = $systemFile->objects;
                    } else {
                        $result[] = [
                          'id'     => $systemFile->id,
                          'table'  => $upload_scenes[$systemFile->scene]['table'],
                          'field'  => implode('|',array_keys($upload_scenes[$systemFile->scene]['where'])),
                          'id_str' => $id_str,
                        ];
                    }
                }
            }
        }
        $FileServer = new FileServer();
        foreach ($objects as $object) {
            $FileServer->delete($object);
        }

        return $result;
    }
}
