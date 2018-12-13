@extends('admin.layout.master')
@section('pre_css')
    <link rel="stylesheet" href="/static/libs/viewer/viewer.min.css?v=20180327">
    <link rel="stylesheet" href="/static/libs/webuploader/webuploader.css?v=20180327">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header bg-gray-lighter">
                    <ul class="block-options">
                        <li>
                            <button type="button" class="page-reload"><i class="si si-refresh"></i></button>
                        </li>
                        <li>
                            <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">添加账号</h3>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="block-content">
                            <form class="form-horizontal form-builder row" id="create-form">

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-username">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        账号
                                    </label>
                                    <div class="col-md-6 form-option-line">
                                        <input class="form-control" type="text" name="username" value="" placeholder="请输入账号">
                                    </div>
                                    <div class="col-md-5 form-control-static form-option-line">
                                        <div class="help-block help-block-line">2-10个字符，不包含中文</div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-password">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        密码
                                    </label>
                                    <div class="col-md-6 form-option-line">
                                        <input class="form-control" type="password" name="password" value="" placeholder="请输入密码">
                                    </div>
                                    <div class="col-md-5 form-control-static form-option-line">
                                        <div class="help-block help-block-line">5-18个字符，不包含中文，只支持部分特殊字符</div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-nickname">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        昵称
                                    </label>
                                    <div class="col-md-6 form-option-line">
                                        <input class="form-control" type="text" name="nickname" value="" placeholder="请输入昵称">
                                    </div>
                                    <div class="col-md-5 form-control-static form-option-line">
                                        <div class="help-block help-block-line">2-10个字符</div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-avatar">
                                    <label class="col-md-1 control-label form-option-line">
                                        头像
                                    </label>
                                    <div class="col-md-11 form-option-line">
                                        <div class="webuploader-box js-upload-image" upload-type="image">
                                            <input type="hidden" name="avatar" value="">
                                            <div class="uploader-list"></div>
                                            <div class="clearfix"></div>
                                            <div class="filePicker">上传单张图片</div>
                                            <span class="form-control-static form-option-line help-line form-option-webuploader-line">若不上传，将使用系统默认头像</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-type">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        类型
                                    </label>
                                    <div class="col-md-11 form-option-line">
                                        <label class="css-input css-radio css-radio-primary css-radio-sm push-10-r">
                                            <input type="radio" name="type" value="0" checked>
                                            <span></span> 超级管理员 </label>
                                        <label class="css-input css-radio css-radio-primary css-radio-sm push-10-">
                                            <input type="radio" name="type" value="1">
                                            <span></span> 角色权限 </label>
                                        <label class="css-input css-radio css-radio-primary css-radio-sm push-10-r">
                                            <input type="radio" name="type" value="2">
                                            <span></span> 直赋权限 </label>
                                        <span class="form-control-static form-option-line help-line">超级管理员拥有最高权限</span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-status">
                                    <label class="col-md-1 control-label form-option-line">
                                        状态
                                    </label>
                                    <div class="col-md-11 form-option-line">
                                        <label class="css-input switch switch-sm switch-primary switch-rounded " title="启用/禁用">
                                            <input type="checkbox" name="status" value="1" checked><span></span>
                                        </label>
                                        <span class="form-control-static form-option-line help-line">关闭后账号会被禁用，无法登录后台</span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-username">
                                    <div class="col-md-offset-2 col-md-9">
                                        <button class="btn btn-minw btn-primary ajax-post" type="button" id="create-submit">
                                            提交
                                        </button>
                                        <button class="btn btn-default" type="button" onclick="javascript:history.back(-1);return false;">
                                            返回
                                        </button>
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
    <script src="/static/libs/viewer/viewer.min.js?v=20180327"></script>
    <script src="/static/libs/webuploader/webuploader.min.js?v=20180327"></script>
    <script src="/static/admin/js/webuploader-image.js?v=20180327"></script>
    <script>
        $(function () {
            $(document).on('click', '#create-submit', function () {
                $('#create-form').find('.form-validate-msg').remove();//清空该表单的验证错误信息
                var data = $('#create-form').serialize();//表单数据
                Dolphin.loading('提交中...');
                $.ajax({
                    type: 'POST',
                    url: '{{action('Admin\System\UserController@store')}}',
                    dataType: 'JSON',
                    data: data,
                    success: function (response) {
                        Dolphin.loading('hide');
                        if (response.status_code >= 200 && response.status_code < 300) {
                            Dolphin.notify(response.message, 'success');
                            setTimeout(function () {
                                location.href = response.data.url;
                            }, 1500);
                        } else {
                            Dolphin.notify(response.message, 'danger');
                        }
                    },
                    error: function (xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        Dolphin.loading('hide');
                        if (xhr.status == 422) { //数据指定错误，错误码固定为422
                            var validate_notify = '';
                            $.each(response.errors, function (k, v) {
                                var validate_tips = '';
                                for (var i in v) {
                                    validate_tips += '<div class="col-md-11 col-md-offset-1 form-validate-msg form-option-line"><i class="fa fa-fw fa-warning text-warning"></i>' + v[i] + '</div>';
                                    validate_notify += '<li>' + v[i] + '</li>';
                                }
                                $('#create-' + k).append(validate_tips); // 页面表单项下方提示，错误验证信息
                            });
                            Dolphin.notify(validate_notify, 'danger'); //页面顶部浮窗提示，错误验证信息
                        } else if (xhr.status == 419) { // csrf错误，错误码固定为419
                            Dolphin.notify('请勿重复请求~', 'danger');
                        } else {
                            Dolphin.notify('服务器错误~', 'danger');
                        }
                    }
                });
            });
        });
    </script>
@endsection
