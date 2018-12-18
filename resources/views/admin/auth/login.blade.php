<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie9 no-focus" lang="zh"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-focus" lang="zh"> <!--<![endif]-->
@php
    $SFV=\App\Model\Admin\SystemConfig::getVal('basic_static_file_version');
@endphp
<head>
    <meta charset="utf-8">
    <title>
        @php
            $admin_name=\App\Model\Admin\SystemConfig::getVal('admin_name','admin');
        @endphp
        后台登录 | {{($admin_name!==''?$admin_name:config('app.name'))}}
    </title>
    <meta name="keywords" content="{{\App\Model\Admin\SystemConfig::getVal('admin_keywords','admin')}}">
    <meta name="description" content="{{\App\Model\Admin\SystemConfig::getVal('admin_describe','admin')}}">
    <meta name="author" content="Dujun">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <!-- 下面的图标可以用自己的图标替换，它们被桌面和移动浏览器所使用 -->
    <link rel="shortcut icon" href="{{asset('/static/admin/img/favicons/favicon.ico'.'?'.$SFV)}}">
    <link rel="icon" type="image/png" href="{{asset('/static/admin/img/favicons/favicon.ico'.'?'.$SFV)}}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{asset('/static/admin/img/favicons/favicon-16x16.png'.'?'.$SFV)}}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{asset('/static/admin/img/favicons/favicon-16x16.png'.'?'.$SFV)}}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{asset('/static/admin/img/favicons/favicon-16x16.png'.'?'.$SFV)}}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{asset('/static/admin/img/favicons/favicon-16x16.png'.'?'.$SFV)}}" sizes="16x16">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/static/admin/img/favicons/apple-touch-icon-57x57.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/static/admin/img/favicons/apple-touch-icon-60x60.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/static/admin/img/favicons/apple-touch-icon-72x72.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/static/admin/img/favicons/apple-touch-icon-76x76.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/static/admin/img/favicons/apple-touch-icon-114x114.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/static/admin/img/favicons/apple-touch-icon-120x120.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/static/admin/img/favicons/apple-touch-icon-144x144.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/static/admin/img/favicons/apple-touch-icon-152x152.png'.'?'.$SFV)}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/static/admin/img/favicons/apple-touch-icon-180x180.png'.'?'.$SFV)}}">
    <!-- END Icons -->
    <!-- Stylesheets -->
    <!-- Bootstrap与ONEUI CSS框架  Page JS Plugins CSS  -->
    <link rel="stylesheet" href="{{asset('/static/admin/css/bootstrap.min.css').'?'.$SFV}}">
    <link rel="stylesheet" href="{{asset('/static/admin/css/oneui.css').'?'.$SFV}}">
    <link rel="stylesheet" href="{{asset('/static/admin/css/dolphin.css').'?'.$SFV}}">
    <!--自定义css-->
    <link rel="stylesheet" href="{{asset('/static/admin/css/custom.css').'?'.$SFV}}">
    <!--本页面专属css-->

    <!-- END Stylesheets -->
</head>
<body>
<!-- Page Container -->
<!-- Login Content -->
<div class="bg-white pulldown" style="margin-top: -15px;">
    <div class="content content-boxed overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="push-30-t push-50 animated fadeIn">
                    <!-- Login Title -->
                    <div class="text-center">
                        <img src="{{asset('/static/admin/img/logo-signin.png').'?'.$SFV}}" alt="{{($admin_name!==''?$admin_name:config('app.name'))}}管理系统" style="max-width: 350px;">
                        <p class="text-muted push-15-t">{{($admin_name!==''?$admin_name:config('app.name'))}}管理系统</p>
                    </div>
                    <!-- END Login Title -->
                    <!-- Login Form -->
                    <form class="form-horizontal push-30-t signin-form" id="login-form">
                        <div class="form-group row" id="login-username">
                            <label class="col-xs-12">账号</label>
                            <div class="col-xs-12">
                                <input class="form-control" type="text" name="username" placeholder="请输入您的账号">
                            </div>
                        </div>
                        <div class="form-group" id="login-password">
                            <label class="col-xs-12">密码</label>
                            <div class="col-xs-12">
                                <input class="form-control" type="password" name="password" placeholder="请输入您的密码">
                            </div>
                        </div>
                        @if(\App\Model\Admin\SystemConfig::getVal('admin_login_captcha','admin'))
                            <div class="form-group" id="login-captcha">
                                <label class="col-xs-12 " for="login-password">验证码</label>
                                <div class="col-md-8 col-xs-7">
                                    <input class="form-control" type="text" name="captcha" placeholder="请输入验证码">
                                </div>
                                <div class="col-md-4 col-xs-5">
                                    <img src="{{captcha_src()}}" class="pull-right" id="captcha" style="cursor: pointer;height: 34px;" onclick="this.src='{{captcha_src()}}'+'?'+Math.random()" title="点击刷新" alt="captcha">
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label class="css-input switch switch-sm switch-primary">
                                    <input type="checkbox" id="login-remember-token"><span></span> 记住您的账号?
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <div class="font-s13 text-right push-5-t">
                                    {{--<a href="">忘记密码?</a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group push-30-t">
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                                <button class="btn btn-block btn-primary" id="login-submit" type="button">登 录</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Login Form -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Login Content -->
<!-- Login Footer -->
<div class="pulldown push-30-t text-center animated fadeInUp">
    @php
        $admin_icp=\App\Model\Admin\SystemConfig::getVal('admin_icp','admin');
    @endphp
    <small class="text-muted"><a class="font-w600" href="http://www.beianbeian.com/" target="_blank">{{($admin_icp!==''?$admin_icp:'Programmer Inn @ sclzdj')}}</a></small>
</div>
<!-- END Login Footer -->
<!-- END Apps Modal -->
<!-- Page JS Plugins -->
<script src="{{asset('/static/admin/js/core/jquery.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/bootstrap.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/jquery.scrollLock.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/jquery.placeholder.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/js.cookie.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/dolphin.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/libs/bootstrap-notify/bootstrap-notify.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/libs/js-xss/xss.min.js').'?'.$SFV}}"></script>
<!-- 程序启动 -->
<script>
    jQuery(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
<!--vuejs引入和相关代码-->
<!--自定义js-->
<script src="{{asset('/static/admin/js/custom.js').'?'.$SFV}}"></script>
<!--本页面专属js-->

<!--页面js脚本-->
<script>
    $(function () {
        var remember = Cookies.get('username');
        if (remember !== undefined) {
            $('#login-form input[name="username"]').val(Cookies.get('username'));
            $('#login-remember-token').prop('checked', true);
        }
        $(document).on('click', '#login-submit', function () {
            $('#login-form').find('.form-validate-msg').remove();//清空该表单的验证错误信息
            var data = $('#login-form').serialize();//表单数据
            Dolphin.loading('登录中...');
            $.ajax({
                type: 'POST',
                url: '/admin/login',
                dataType: 'JSON',
                data: data,
                success: function (response) {
                    if (response.status_code >= 200 && response.status_code < 300) {
                        if ($('#login-remember-token').is(':checked')) {
                            Cookies.set('username', $('#login-form input[name="username"]').val(), {expires: 7, path: ''});
                        } else {
                            Cookies.remove('username', {path: ''});
                        }
                        Dolphin.jNotify(response.message, 'success', response.data.url);
                    } else {
                        Dolphin.loading('hide');
                        $('#captcha').trigger('click');
                        Dolphin.notify(response.message, 'danger');
                    }
                },
                error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    Dolphin.loading('hide');
                    $('#captcha').trigger('click');
                    if (xhr.status == 422) { //数据指定错误，错误码固定为422
                        var validate_notify = '';
                        $.each(response.errors, function (k, v) {
                            var validate_tips = '';
                            for (var i in v) {
                                validate_tips += '<div class="col-md-12 form-validate-msg"><i class="fa fa-fw fa-warning text-warning"></i>' + v[i] + '</div>';
                                validate_notify += '<li>' + v[i] + '</li>';
                            }
                            $('#login-' + k).append(validate_tips); // 页面表单项下方提示，错误验证信息
                        });
                        Dolphin.notify(validate_notify, 'danger'); //页面顶部浮窗提示，错误验证信息
                    } else if (xhr.status == 419) { // csrf错误，错误码固定为419
                        Dolphin.notify('请勿重复请求~', 'danger');
                    } else {
                        if (response.message) {
                            Dolphin.notify(response.message, 'danger');
                        } else {
                            Dolphin.notify('服务器错误~', 'danger');
                        }
                    }
                }
            });
        });
    });
</script>
</body>
</html>
