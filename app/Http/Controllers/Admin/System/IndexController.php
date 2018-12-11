<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('/admin/system/user/index');
    }
}
