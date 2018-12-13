@extends('admin.layout.master')
@section('pre_css')
    <link rel="stylesheet" href="/static/libs/jquery-nestable/jquery.nestable.css?v=20180327" type="text/css"/>
@endsection
@section('content')
    <div class="alert alert-info alert-dismissable">
        @if($maxLevel==4)
            <p><strong><i class="fa fa-fw fa-hand-o-right"></i> 说明：</strong>第一层节点服务顶部导航模块，第二层节点服务左侧一级菜单，第三层节点服务左侧二级菜单，第四层节点服务页面操作方法；<strong class="text-danger">五级节点（即本页面第五层节点）之后无效</strong></p>
        @elseif($maxLevel==3)
            <p><strong><i class="fa fa-fw fa-hand-o-right"></i> 说明：</strong>第一层节点服务左侧一级菜单，第二层节点服务左侧二级菜单，第三层节点服务页面操作方法；<strong class="text-danger">第四层节点（实际为五级节点）之后无效</strong></p>
        @else
            <p><strong><i class="fa fa-fw fa-hand-o-right"></i> 说明：</strong><strong class="text-danger">该页面已失效</strong></p>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <ul class="nav nav-tabs">
                    <li class="@if($pid==0) active @endif">
                        <a href="{{action('Admin\System\NodeController@index')}}">全部</a>
                    </li>
                    @foreach($modules as $module)
                        <li class="@if($pid==$module->id) active @endif">
                            <a href="{{action('Admin\System\NodeController@index',['pid'=>$module->id])}}">{{$module->name}}</a>
                        </li>
                    @endforeach
                    <li>
                        <a href="">模块排序</a>
                    </li>
                    <li class="pull-right">
                        <ul class="block-options push-10-t push-10-r">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                            </li>
                            <li>
                                <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                            </li>
                            <li>
                                <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                            </li>
                            <li>
                                <button type="button" data-toggle="block-option" data-action="close"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p><strong><i class="fa fa-fw fa-info-circle"></i> 提示：</strong>按住表头可拖动节点，调整后点击【保存节点】。</p>
                    </div>
                    <div class="tab-pane active">
                        <div class="row data-table-toolbar">
                            <div class="col-sm-12">
                                <form action="/admin.php/admin/menu/index/group/user.html" method="get">
                                    <div class="toolbar-btn-action">
                                        <a title="新增" class="btn btn-primary" href="{{action('Admin\System\NodeController@create',['pid'=>$pid])}}"><i class="fa fa-plus-circle"></i> 新增</a>
                                        <button title="保存" type="button" class="btn btn-default disabled" id="save" disabled=""><i class="fa fa-check-circle-o"></i> 保存节点</button>
                                        <button title="隐藏禁用节点" type="button" class="btn btn-danger" id="hide_disable"><i class="fa fa-eye-slash"></i> 隐藏禁用节点</button>
                                        <button title="显示禁用节点" type="button" class="btn btn-info" id="show_disable"><i class="fa fa-eye"></i> 显示禁用节点</button>
                                        <button title="展开所有节点" type="button" class="btn btn-success" id="expand-all"><i class="fa fa-plus"></i> 展开所有节点</button>
                                        <button title="收起所有节点" type="button" class="btn btn-warning" id="collapse-all"><i class="fa fa-minus"></i> 收起所有节点</button>
                                        <span class="form-inline">
                                                    <input class="form-control" type="text" name="max" value="" placeholder="显示层数">
                                                </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="dd" id="menu_list">
                            <ol class="dd-list">
                                <!--1级-->
                                @foreach($tree as $v1)
                                    <li class="dd-item dd3-item @if($v1['status']==0) dd-disable @endif" data-id="{{$v1['id']}}">
                                        <div class="dd-handle dd3-handle">拖拽</div>
                                        <div class="dd3-content"><i class="fa fa-fw fa-user"></i> {{$v1['name']}}
                                            <div class="action">
                                                <a href="{{action('Admin\System\NodeController@create',['pid'=>$v1['id']])}}" data-toggle="tooltip" data-original-title="添加子节点">
                                                    <i class="list-icon fa fa-plus fa-fw"></i>
                                                </a>
                                                <a href="{{action('Admin\System\NodeController@edit',['id'=>$v1['id']])}}" data-toggle="tooltip" data-original-title="编辑">
                                                    <i class="list-icon fa fa-pencil fa-fw"></i>
                                                </a>
                                                @if($v1['status'])
                                                    <a href="javascript:void(0);" data-id="{{$v1['id']}}" class="disable" data-toggle="tooltip" data-original-title="禁用">
                                                        <i class="list-icon fa fa-ban fa-fw"></i>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);" data-id="{{$v1['id']}}" class="enable" data-toggle="tooltip" data-original-title="启用">
                                                        <i class="list-icon fa fa-check-circle-o fa-fw"></i>
                                                    </a>
                                                @endif
                                                <a href="javascript:void(0);" data-id="{{$v1['id']}}" data-toggle="tooltip" data-original-title="删除" class="">
                                                    <i class="list-icon fa fa-times fa-fw"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <ol class="dd-list">
                                            <!--2级-->
                                            @foreach($v1['_data'] as $v2)
                                                <li class="dd-item dd3-item @if($v2['status']==0) dd-disable @endif" data-id="{{$v2['id']}}">
                                                    <div class="dd-handle dd3-handle">拖拽</div>
                                                    <div class="dd3-content"><i class="fa fa-fw fa-key"></i> {{$v2['name']}}
                                                        <div class="action">
                                                            <a href="{{action('Admin\System\NodeController@create',['pid'=>$v2['id']])}}" data-toggle="tooltip" data-original-title="添加子节点">
                                                                <i class="list-icon fa fa-plus fa-fw"></i>
                                                            </a>
                                                            <a href="{{action('Admin\System\NodeController@edit',['id'=>$v2['id']])}}" data-toggle="tooltip" data-original-title="编辑">
                                                                <i class="list-icon fa fa-pencil fa-fw"></i>
                                                            </a>
                                                            @if($v2['status'])
                                                                <a href="javascript:void(0);" data-id="{{$v2['id']}}" class="disable" data-toggle="tooltip" data-original-title="禁用">
                                                                    <i class="list-icon fa fa-ban fa-fw"></i>
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0);" data-id="{{$v2['id']}}" class="enable" data-toggle="tooltip" data-original-title="启用">
                                                                    <i class="list-icon fa fa-check-circle-o fa-fw"></i>
                                                                </a>
                                                            @endif
                                                            <a href="javascript:void(0);" data-id="{{$v2['id']}}" class="disable" data-toggle="tooltip" data-original-title="禁用">
                                                                <i class="list-icon fa fa-ban fa-fw"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" data-id="{{$v2['id']}}" data-toggle="tooltip" data-original-title="删除" class="">
                                                                <i class="list-icon fa fa-times fa-fw"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <ol class="dd-list">
                                                        <!--3级-->
                                                        @foreach($v2['_data'] as $v3)
                                                            <li class="dd-item dd3-item @if($v3['status']==0) dd-disable @endif" data-id="{{$v3['id']}}">
                                                                <div class="dd-handle dd3-handle">拖拽</div>
                                                                <div class="dd3-content"><i class="fa fa-fw fa-user"></i> {{$v3['name']}}<span class="link"><i class="fa fa-link"></i> {{$v3['action']}}</span>
                                                                    <div class="action">
                                                                        <a href="{{action('Admin\System\NodeController@create',['pid'=>$v3['id']])}}" data-toggle="tooltip" data-original-title="添加子节点">
                                                                            <i class="list-icon fa fa-plus fa-fw"></i>
                                                                        </a>
                                                                        <a href="{{action('Admin\System\NodeController@edit',['id'=>$v3['id']])}}" data-toggle="tooltip" data-original-title="编辑">
                                                                            <i class="list-icon fa fa-pencil fa-fw"></i>
                                                                        </a>
                                                                        @if($v3['status'])
                                                                            <a href="javascript:void(0);" data-id="{{$v3['id']}}" class="disable" data-toggle="tooltip" data-original-title="禁用">
                                                                                <i class="list-icon fa fa-ban fa-fw"></i>
                                                                            </a>
                                                                        @else
                                                                            <a href="javascript:void(0);" data-id="{{$v3['id']}}" class="enable" data-toggle="tooltip" data-original-title="启用">
                                                                                <i class="list-icon fa fa-check-circle-o fa-fw"></i>
                                                                            </a>
                                                                        @endif
                                                                        <a href="javascript:void(0);" data-id="{{$v3['id']}}" data-toggle="tooltip" data-original-title="删除" class="">
                                                                            <i class="list-icon fa fa-times fa-fw"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <ol class="dd-list">
                                                                    <!--4级-->
                                                                    @foreach($v3['_data'] as $v4)
                                                                        <li class="dd-item dd3-item @if($v4['status']==0) dd-disable @endif" data-id="{{$v4['id']}}">
                                                                            <div class="dd-handle dd3-handle">拖拽</div>
                                                                            <div class="dd3-content"><i class="fa fa-fw fa-user"></i> {{$v4['name']}}<span class="link"><i class="fa fa-link"></i> {{$v4['action']}}</span>
                                                                                <div class="action">
                                                                                    <a href="{{action('Admin\System\NodeController@create',['pid'=>$v4['id']])}}" data-toggle="tooltip" data-original-title="添加子节点">
                                                                                        <i class="list-icon fa fa-plus fa-fw"></i>
                                                                                    </a>
                                                                                    <a href="{{action('Admin\System\NodeController@edit',['id'=>$v4['id']])}}" data-toggle="tooltip" data-original-title="编辑">
                                                                                        <i class="list-icon fa fa-pencil fa-fw"></i>
                                                                                    </a>
                                                                                    @if($v4['status'])
                                                                                        <a href="javascript:void(0);" data-id="{{$v4['id']}}" class="disable" data-toggle="tooltip" data-original-title="禁用">
                                                                                            <i class="list-icon fa fa-ban fa-fw"></i>
                                                                                        </a>
                                                                                    @else
                                                                                        <a href="javascript:void(0);" data-id="{{$v4['id']}}" class="enable" data-toggle="tooltip" data-original-title="启用">
                                                                                            <i class="list-icon fa fa-check-circle-o fa-fw"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                    <a href="javascript:void(0);" data-id="{{$v4['id']}}" data-toggle="tooltip" data-original-title="删除" class="">
                                                                                        <i class="list-icon fa fa-times fa-fw"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                @endforeach
                                                                <!--4级end-->
                                                                </ol>
                                                            </li>
                                                    @endforeach
                                                    <!--3级end-->
                                                    </ol>
                                                </li>
                                        @endforeach
                                        <!--2级end-->
                                        </ol>
                                    </li>
                            @endforeach
                            <!--1级end-->
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="/static/libs/jstree/jstree.min.js?v=20180327"></script>
    <script src="/static/libs/jquery-nestable/jquery.nestable.js?v=20180327"></script>
    <script src="/static/libs/jquery-ui/jquery-ui.min.js?v=20180327"></script>
    <script>
        $(function () {
            // 模块拖拽
            // $("#sortable").sortable({
            //     connectWith: ".connectedSortable"
            // }).disableSelection();

            // 保存节点
            $('#save').click(function () {
                Dolphin.loading();
                $.post("/admin.php/admin/menu/save.html", {menus: $('#menu_list').nestable('serialize')}, function (data) {
                    Dolphin.loading('hide');
                    if (data.code) {
                        $('#save').removeClass('btn-success').addClass('btn-default disabled');
                        Dolphin.notify(data.msg, 'success');
                    } else {
                        Dolphin.notify(data.msg, 'danger');
                    }
                });
            });

            // 初始化节点拖拽
            $('#menu_list').nestable({maxDepth: 4}).on('change', function () {
                $('#save').removeAttr("disabled").removeClass('btn-default disabled').addClass('btn-success');
            });

            // 隐藏禁用节点
            $('#hide_disable').click(function () {
                $('.dd-disable').hide();
            });

            // 显示禁用节点
            $('#show_disable').click(function () {
                $('.dd-disable').show();
            });

            // 展开所有节点
            $('#expand-all').click(function () {
                $('#menu_list').nestable('expandAll');
            });

            // 收起所有节点
            $('#collapse-all').click(function () {
                $('#menu_list').nestable('collapseAll');
            });

            // 禁用节点
            $('.dd3-content').delegate('.disable', 'click', function () {
                var self = $(this);
                var ids = self.data('ids');
                var ajax_url = '/admin.php/admin/menu/disable/table/admin_menu.html';
                Dolphin.loading();
                $.post(ajax_url, {ids: ids}, function (data) {
                    Dolphin.loading('hide');
                    if (data.code) {
                        self.attr('data-original-title', '启用').removeClass('disable').addClass('enable')
                            .children().removeClass('fa-ban').addClass('fa-check-circle-o')
                            .closest('.dd-item')
                            .addClass('dd-disable');
                    } else {
                        Dolphin.notify(data.msg, 'danger');
                    }
                });
                return false;
            });

            // 启用节点
            $('.dd3-content').delegate('.enable', 'click', function () {
                var self = $(this);
                var ids = self.data('ids');
                var ajax_url = '/admin.php/admin/menu/enable/table/admin_menu.html';
                Dolphin.loading();
                $.post(ajax_url, {ids: ids}, function (data) {
                    Dolphin.loading('hide');
                    if (data.code) {
                        self.attr('data-original-title', '禁用').removeClass('enable').addClass('disable')
                            .children().removeClass('fa-check-circle-o').addClass('fa-ban')
                            .closest('.dd-item')
                            .removeClass('dd-disable');
                    } else {
                        Dolphin.notify(data.msg, 'danger');
                    }
                });
                return false;
            });
        });
    </script>
@endsection
