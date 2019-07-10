<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Model\Admin\SystemDemo;
use App\Servers\ArrServer;
use Illuminate\Http\Request;

class DemoController extends BaseController {

    public function ueditor() {
        $ueditor1=(string)SystemDemo::where('name','demo_ueditor_1')->value('value');
        $ueditor2=(string)SystemDemo::where('name','demo_ueditor_2')->value('value');
        return view('/admin/system/demo/ueditor',compact('ueditor1','ueditor2'));
    }
    public function ueditorSave(Request $request) {
        \DB::beginTransaction();//开启事务
        try {
            $data = $request->all();
            $data = ArrServer::null2strData($data);
            if(isset($data['demo_ueditor_1'])){
                $ueditor1=SystemDemo::where('name','demo_ueditor_1')->first();
                if(!$ueditor1){
                    SystemDemo::create(['name'=>'demo_ueditor_1','value'=>$data['demo_ueditor_1']]);
                }else{
                    $ueditor1->value=$data['demo_ueditor_1'];
                    $ueditor1->save();
                }
            }
            if(isset($data['demo_ueditor_2'])){
                $ueditor2=SystemDemo::where('name','demo_ueditor_2')->first();
                if(!$ueditor2){
                    SystemDemo::create(['name'=>'demo_ueditor_2','value'=>$data['demo_ueditor_2']]);
                }else{
                    $ueditor2->value=$data['demo_ueditor_2'];
                    $ueditor2->save();
                }
            }
            \DB::commit();//提交事务

            return $this->response('保存成功', 200);

        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }
}
