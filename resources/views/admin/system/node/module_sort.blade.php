@extends('admin.layout.master')
@section('pre_css')
    <link href="/static/libs/jquery-nestable/jquery.nestable.css?v=20180327" type="text/css">
@endsection
@section('content')
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p><strong><i class="fa fa-fw fa-info-circle"></i> 提示：</strong>按住模块即可拖动位置，调整后点击【保存排序】</p>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <ul class="nav nav-tabs">
                    <li>
                        <a href="{{action('Admin\System\NodeController@index')}}">全部</a>
                    </li>
                    @foreach($modules as $module)
                        <li>
                            <a href="{{action('Admin\System\NodeController@index',['pid'=>$module->id])}}">{{$module->name}}</a>
                        </li>
                    @endforeach
                    <li class="active">
                        <a href="{{action('Admin\System\NodeController@moduleSort')}}">模块排序</a>
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
                    <div class="tab-pane active">
                        <form class="sort-form">
                            <button href="{{action('Admin\System\NodeController@moduleSort')}}" submit-type="POST" title="保存" type="button" class="btn btn-success push-10 ids-submit">保存排序</button>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="sortable" class="connectedSortable push-20 ui-sortable">
                                        @foreach($modules as $module)
                                            <div class="sortable-item pull-left ui-sortable-handle">
                                                <input type="hidden" name="ids[]" value="{{$module->id}}">
                                                <i class="{{$module->icon}}"></i> {{$module->name}}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="/static/libs/jquery-nestable/jquery.nestable.js" ?v=20180327></script>
    <script src="/static/libs/jquery-ui/jquery-ui.min.js" ?v=20180327></script>
    <script>
        $(function () {

            //模块拖拽
            $("#sortable").sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();

            //批量提交函数
            function idsSubmit(fn, ids) {
                var url = $(fn).attr('href');
                var type = $(fn).attr('submit-type');
                Dolphin.loading();
                $.ajax({
                    type: type,
                    url: url,
                    dataType: 'JSON',
                    data: {ids: ids},
                    success: function (response) {
                        Dolphin.loading('hide');
                        if (response.status_code >= 200 && response.status_code < 300) {
                            Dolphin.notify(response.message, 'success');
                            setTimeout(function () {
                                if (response.data.url !== undefined) {
                                    location.href = response.data.url;
                                } else {
                                    location.reload();
                                }
                            }, 1500);
                        } else {
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
            }

            //批量提交执行
            $(document).on('click', '.ids-submit', function () {
                var ids = [];
                var fn = this;
                $('input[name="ids[]"]').each(function () {
                    ids.push($(this).val());
                });
                if (ids.length == 0) {
                    Dolphin.notify('请先选择数据', 'warning');
                    return false;
                }
                var confirm = $(this).attr('confirm');
                if (confirm !== undefined) {
                    //询问框
                    layer.confirm(confirm, {
                        title: '警告',
                        btn: ['确定', '取消'] //按钮
                    }, function (index) {
                        layer.close(index);
                        idsSubmit(fn, ids);
                    });
                    return false;
                } else {
                    idsSubmit(fn, ids);
                    return false;
                }
            });
        });
    </script>
@endsection
