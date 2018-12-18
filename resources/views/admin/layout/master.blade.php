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
        @yield('page_title','后台 | '.($admin_name!==''?$admin_name:config('app.name')))
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
    <!--本页面专属顶部css-->
@yield('pre_css')
<!-- Bootstrap与ONEUI CSS框架 -->
    <link rel="stylesheet" href="{{asset('static/admin/css/bootstrap.min.css').'?'.$SFV}}">
    <link rel="stylesheet" href="{{asset('static/admin/css/oneui.css').'?'.$SFV}}">
    <link rel="stylesheet" href="{{asset('static/admin/css/dolphin.css').'?'.$SFV}}">
    <!--自定义css-->
    <link rel="stylesheet" href="{{asset('/static/admin/css/custom.css').'?'.$SFV}}">
    <!--本页面专属底部css-->
@yield('css')
<!-- END Stylesheets -->
</head>
<body>
<!-- Page Container -->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed ">
    <!-- Side Overlay-->
@include('admin.layout.side-overlay')
<!-- END Side Overlay -->
    <!-- Sidebar -->
@include('admin.layout.sidebar')
<!-- END Sidebar -->
    <!-- Header -->
@include('admin.layout.header-navbar')
<!-- END Header -->
    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Header -->
    @include('admin.layout.location-navbar')
    <!-- END Page Header -->
        <!-- Page Content -->
        <div class="content" id="app">
            @yield('content','')
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
    <!-- Footer -->
@include('admin.layout.page-footer')
<!-- END Footer -->
</div>
<!-- END Page Container -->
<!-- Apps Modal -->
<!-- Opens from the button in the header -->
@include('admin.layout.apps-modal')
<!-- END Apps Modal -->
<!-- Page JS Plugins -->
<script src="{{asset('/static/admin/js/core/jquery.min.js').'?'.$SFV}}"></script>
<!--vuejs引入和相关代码-->
@yield('vuejs','')
<script src="{{asset('/static/admin/js/core/bootstrap.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/jquery.slimscroll.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/jquery.scrollLock.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/core/jquery.placeholder.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/app.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/admin/js/dolphin.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/libs/bootstrap-notify/bootstrap-notify.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/libs/js-xss/xss.min.js').'?'.$SFV}}"></script>
<script src="{{asset('/static/libs/layer/layer.js').'?'.$SFV}}"></script>
<!-- 程序启动 -->
<script>
    jQuery(function () {
        App.initHelpers(['appear', 'slimscroll', 'magnific-popup', 'table-tools']);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    //上传全局配置
    var server_upload_image_url = "{{action('Admin\System\FileController@upload')}}";//上传地址
    var server_image_host = "";//图片显示前缀域名，上传成功后返回的是完整图片地址就留空
    var server_upload_file_url = "{{action('Admin\System\FileController@upload')}}";//上传地址
    var server_file_host = "";//文件显示前缀域名，上传成功后返回的是完整文件地址就留空
</script>
<!--自定义js-->
<script src="{{asset('/static/admin/js/custom.js').'?'.$SFV}}"></script>
<script>
    $(function () {
        $(document).on('click', '#admin-logout', function () {
            Dolphin.loading('退出中...');
            $.ajax({
                type: 'POST',
                url: '/admin/logout',
                dataType: 'JSON',
                success: function (response) {
                    if (response.status_code >= 200 && response.status_code < 300) {
                        Dolphin.jNotify(response.message, 'success', response.data.url);
                    } else {
                        Dolphin.loading('hide');
                        Dolphin.notify(response.message, 'danger');
                    }
                },
                error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    Dolphin.loading('hide');
                    if (xhr.status == 419) { // csrf错误，错误码固定为419
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
        //侧栏开关
        $(document).on('change', 'input.side-switch', function () {
            var name = $(this).prop('name');
            var checked = $(this).is(':checked');
            if (checked) {
                var value = 1;
            } else {
                var value = 0;
            }
            var _this = $(this);
            Dolphin.loading('设置中');
            $.ajax({
                type: 'PUT',
                url: '{{action('Admin\System\IndexController@config')}}',
                data: {name: name, value: value},
                dataType: 'JSON',
                success: function (response) {
                    Dolphin.loading('hide');
                    if (response.status_code >= 200 && response.status_code < 300) {
                        Dolphin.rNotify(response.message, 'success');
                    } else {
                        _this.prop('checked', !checked);
                        Dolphin.notify(response.message, 'danger');
                    }
                },
                error: function (xhr, status, error) {
                    _this.prop('checked', !checked);
                    var response = JSON.parse(xhr.responseText);
                    Dolphin.loading('hide');
                    if (xhr.status == 419) { // csrf错误，错误码固定为419
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
            return false;
        });
    });
</script>
<!--本页面专属js-->
@yield('javascript','')
</body>
</html>
