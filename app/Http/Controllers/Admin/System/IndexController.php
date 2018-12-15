<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Servers\ArrServer;
use App\Servers\PermissionServer;

class IndexController extends BaseController
{
    public function index()
    {
        $dd=ArrServer::parseData([]);
        dd($dd);
        return view('/admin/system/index/index');
    }
}
