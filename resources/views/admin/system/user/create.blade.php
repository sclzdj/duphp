@php
    $SFV=\App\Model\Admin\SystemConfig::getVal('basic_static_file_version');
@endphp
@extends('admin.layout.master')
@section('pre_css')
    <link rel="stylesheet" href="{{asset('/static/libs/viewer/viewer.min.css').'?'.$SFV}}">
    <link rel="stylesheet" href="{{asset('/static/libs/webuploader/webuploader.css').'?'.$SFV}}">
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
                                            <input type="radio" name="type" value="0" checked v-model="form.type">
                                            <span></span> 超级管理员 </label>
                                        <label class="css-input css-radio css-radio-primary css-radio-sm push-10-r">
                                            <input type="radio" name="type" value="1" v-model="form.type">
                                            <span></span> 角色权限 </label>
                                        <label class="css-input css-radio css-radio-primary css-radio-sm push-10-r">
                                            <input type="radio" name="type" value="2" v-model="form.type">
                                            <span></span> 直赋权限 </label>
                                        <span class="form-control-static form-option-line help-line">超级管理员拥有最高权限</span>
                                    </div>
                                </div>

                                <div v-show="form.type==1" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-system_role_ids">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        所属角色
                                    </label>
                                    <div class="col-md-11 form-option-line">
                                        <div class="row">
                                            @foreach($systemRoles as $systemRole)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <label class="css-input css-checkbox css-checkbox-primary css-checkbox-sm css-checkbox-rounded">
                                                        <input type="checkbox" name="system_role_ids[]" value="{{$systemRole->id}}">
                                                        <span></span> {{$systemRole->name}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1 form-control-static form-option-line">
                                        <div class="help-block help-block-line">至少选择一位角色</div>
                                    </div>
                                </div>

                                <div v-show="form.type==2" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="create-system_node_ids">
                                    <label class="col-md-1 control-label form-option-line">
                                        <span class="form-option-require"></span>
                                        直赋节点
                                    </label>
                                    <div class="col-md-11 form-option-line">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                @php $html=''; @endphp
                                                @foreach($treeNodes as $node)
                                                    @if($node['level']<4)
                                                        <div class="small-input-checkbox" style="margin-left: 60px;">{!! $html !!}</div>
                                                        @php
                                                            $html='';
                                                            $marginLeft=($node['level']-1)*20;
                                                        @endphp
                                                        <div class="small-input-checkbox" style="margin-left: {{$marginLeft}}px;">
                                                            <label style="@if($node['level']==1)font-weight: bold;@endif" title="@if($node['level']==1)模块@else{{$node['level']}}级节点@endif" class="css-input css-checkbox css-checkbox-primary css-checkbox-sm css-checkbox-rounded">
                                                                <input level="{{$node['level']}}" type="checkbox" name="system_node_ids[]" value="{{$node['id']}}">
                                                                <span></span> {{$node['name']}}
                                                                @if($node['level']==1)
                                                                    <i style="font-weight: normal;color: #888;">----模块</i>
                                                                @elseif($node['level']==2)
                                                                    <i style="font-weight: normal;color: #aaa;">----2级节点</i>
                                                                @elseif($node['level']==3)
                                                                    <i style="font-weight: normal;color: #ccc;">----3级节点</i>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @else
                                                        @php
                                                            $html.='<label class="css-input css-checkbox css-checkbox-primary css-checkbox-sm css-checkbox-rounded">
                                                                <input level="'.$node['level'].'" type="checkbox" name="system_node_ids[]" value="'.$node['id'].'">
                                                                <span></span> '.$node['name'].'</label>';
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if($html!=='')
                                                    <div class="small-input-checkbox" style="margin-left: 60px;">{!! $html !!}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1 form-control-static form-option-line">
                                        <div class="help-block help-block-line">至少选择一个节点</div>
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
@section('vuejs')
    <script src="{{asset('/static/libs/vue/vue.min.js').'?'.$SFV}}"></script>
    <script>
        $(function () {
            new Vue({
                el: '#app',
                data: {
                    form: {
                        type: 0
                    }
                }
            });
        });
    </script>
@endsection
@section('javascript')
    <script src="{{asset('/static/libs/viewer/viewer.min.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/libs/webuploader/webuploader.min.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/admin/js/webuploader-image.js').'?'.$SFV}}"></script>
    <script src="{{asset('/static/admin/js/change-node.js').'?'.$SFV}}"></script>
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
@endsection
