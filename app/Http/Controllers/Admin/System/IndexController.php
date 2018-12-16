<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Model\Admin\SystemNode;
use App\Servers\NavigationServer;

class IndexController extends BaseController
{
    public function index()
    {
        //        $homeUrl=NavigationServer::homeUrl();
        //        $modules=NavigationServer::modules();
//        $menus = SystemNode::elderNodes(11,1);
//        $menus =NavigationServer::location();
//        dd($menus);

        return view('/admin/system/index/index');
    }
}
