@php
    $SFV=\App\Model\Admin\SystemConfig::getVal('basic_static_file_version');
@endphp
@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="block-content">
                            <form class="form-horizontal form-builder row" id="demo-form">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="col-md-1 control-label form-option-line">
                                        百度编辑器1
                                    </label>
                                    <div class="col-md-9 form-option-line">
                                        <script class="js-ueditor" name="demo-ueditor-1" type="text/plain"></script>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1 form-validate-msg form-option-line">
                                        <span class="form-control-static help-line">百度编辑器1的提示信息</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="{{asset('/static/libs/ueditor/ueditor.config.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/libs/ueditor/ueditor.all.min.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/libs/ueditor/lang/zh-cn/zh-cn.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/admin/js/ueditor-handle.js').'?'.$SFV}}"></script>
@endsection
