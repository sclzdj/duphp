<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Model\Admin\SystemNode;
use App\Servers\NavigationServer;
use App\Servers\PermissionServer;

class IndexController extends BaseController
{
    public function index()
    {
//        dd(PermissionServer::website());
//                $homeUrl=NavigationServer::homeUrl();
//                $modules=NavigationServer::modules();
//        $menus = SystemNode::elderNodes(11,1);
//        $menus =NavigationServer::menus();
//        dump($menus);

        return view('/admin/system/index/index');
    }
}
