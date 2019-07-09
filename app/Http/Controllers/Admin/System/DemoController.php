<?php

namespace App\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DemoController extends Controller {

    public function ueditor() {
        return view('/admin/system/demo/ueditor');
    }
    public function ueditorSave() {
    }
}
