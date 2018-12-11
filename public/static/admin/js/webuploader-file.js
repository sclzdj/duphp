$(function () {
    //上传全局配置
    var server_upload_file_url = "http://dt5.dj/index/upload";//上传地址
    var server_file_host = "http://dt5.dj/";//文件显示前缀域名，上传成功后返回的是完整文件地址就留空


    // 文件上传初始化Web Uploader
    var uploader_file = [];
    for (var index = 0; index < $('.js-upload-file').length; index++) {
        var upload_type = $('.js-upload-file:eq(' + index + ')').attr('upload-type');
        uploader_file[index] = WebUploader.create({
            swf: './static/libs/webuploader/Uploader.swf',// swf文件路径
            server: server_upload_file_url,// 文件接收服务端
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id: '.js-upload-file:eq(' + index + ') .filePicker', // 选择文件的按钮。可选。
                multiple: upload_type == 'files' ? true : false // 是否多选
            },
            // 只允许选择文件文件。
            accept: {
                title: upload_type, //指定接受哪些类型的文件
                extensions: 'gif,jpg,jpeg,bmp,png', //允许的文件后缀，不带点，多个用逗号分割
                mimeTypes: '' //文件mime类型
            },
            //附带参数
            formData: {},
            auto: true, // 选完文件后，是否自动上传
            fileVal: 'file', //设置文件上传域的name
            method: 'POST', //文件上传方式
            fileNumLimit: undefined, //验证文件总数量, 超出则不允许加入队列，默认undefined
            fileSizeLimit: undefined, //验证文件总大小是否超出限制, 超出则不允许加入队列，默认undefined
            fileSingleSizeLimit: undefined, //验证单个文件大小是否超出限制, 超出则不允许加入队列，默认undefined
            duplicate: true //为true允许重复上传同张文件
        });
        //标记这是第几个文件上传
        uploader_file[index].index = index;
        //记录上传文件
        uploader_file[index].files = [];
        if (upload_type == 'files') {
            //标记上传表单名称
            uploader_file[index].inputName = $('.js-upload-file:eq(' + index + ')').attr('input-name');
        }
        //标记上传类型
        uploader_file[index].upload_type = upload_type;
        // 当开始上传流程时触发
        uploader_file[index].on('startUpload', function () {
            Dolphin.loading();
        });
        // 当有文件添加进来的时候
        uploader_file[index].on('fileQueued', function (file) {
            var $li = $('<li id="' + file.id + '" class="list-group-item file-item" style="word-wrap: break-word;">' +
                '<span class="pull-right file-state"></span>' +
                '<i class="fa fa-file"></i>' +
                file.name + '&nbsp;&nbsp;<span class="file-btns"></span></li>');
            // $list为容器jQuery实例
            if (this.upload_type == 'files') {
                $('.js-upload-file:eq(' + this.index + ') .uploader-list').append($li);
            } else {
                $('.js-upload-file:eq(' + this.index + ') .uploader-list').empty();
                $('.js-upload-file:eq(' + this.index + ') .uploader-list').html($li);
            }
            //记录上传文件
            uploader_file[this.index].files[file.id] = file;
        });
        // 文件上传过程中创建进度条实时显示。
        uploader_file[index].on('uploadProgress', function (file, percentage) {
            var $li = $('#' + file.id),
                $percent = $li.find('.progress');
            $li.find('.file-btns,.file-state').empty();
            // 避免重复创建
            if (!$percent.length) {
                $percent = $('<div class="progress file-progress"><div class="progress-run"></div><div class="progress-percent"></div></div>')
                    .appendTo($li);
            }
            $percent.find('.progress-run').css('width', percentage * 100 + '%');
            percentageRate = percentage * 100;
            $percent.find('.progress-percent').text(percentageRate.toFixed(2) + '%');
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader_file[index].on('uploadSuccess', function (file, response) {
            if (response.status_code < 200 || response.status_code >= 300) {
                var $li = $('#' + file.id),
                    $file_state = $li.find('.file-state'),
                    $error = $file_state.find('.text-danger');
                // 避免重复创建
                if (!$error.length) {
                    $error = $('<span class="text-danger"></span>').appendTo($file_state);
                }
                $error.html(response.message + '&nbsp;[<a href="javascript:void(0);" uploader-index="' + this.index + '" file-id="' + file.id + '" class="uploader-retry text-primary">重试上传</a>]');
            } else {
                $('#' + file.id).addClass('upload-state-done');
                if (this.upload_type == 'files') {
                    //将上传的文件地址赋值给隐藏输入框，并添加元素
                    $('#' + file.id).append('<input type="hidden" name="' + this.inputName + '[]" value="' + server_file_host + response.data.path + '">');
                } else {
                    //将上传的文件地址赋值给隐藏输入框
                    $('#' + file.id).parent().parent().find('input[type="hidden"]').val(server_file_host + response.data.path);
                }
                //成功提示
                var $li = $('#' + file.id),
                    $file_state = $li.find('.file-state'),
                    $success = $file_state.find('.text-success');
                // 避免重复创建
                if (!$success.length) {
                    $success = $('<span class="text-success"></span>').appendTo($file_state);
                }
                $success.text('上传成功');
                //下载按钮
                $download = '&nbsp;[<a href="' + server_file_host + response.data.path + '" target="_blank" class="text-success">下载</a>]';
                $li.find('.file-btns').append($download);
                //删除原有提示
                $li.find('.file-state .error').remove();
            }
        });
        // 文件上传失败，显示上传出错
        uploader_file[index].on('uploadError', function (file, reason) {
            var $li = $('#' + file.id),
                $file_state = $li.find('.file-state'),
                $error = $file_state.find('.text-danger');
            // 避免重复创建
            if (!$error.length) {
                $error = $('<span class="text-danger"></span>').appendTo($file_state);
            }
            $error.html('上传失败&nbsp;[<a href="javascript:void(0);" uploader-index="' + this.index + '" file-id="' + file.id + '" class="uploader-retry text-primary">重试上传</a>]');
        });
        // 完成上传完了，成功或者失败，先删除进度条
        uploader_file[index].on('uploadComplete', function (file) {
            $('#' + file.id).find('.progress').remove();
            //添加删除按钮
            $remove = '[<a href="javascript:void(0);" class="remove-file" uploader-index="' + this.index + '" file-id="' + file.id + '">删除</a>]';
            $('#' + file.id).find('.file-btns').prepend($remove);
            if (this.upload_type == 'files') {
                //添加拖拽按钮
                $('#' + file.id).append('<i class="fa fa-fw fa-arrows move-file"></i>');
            }
        });
        // 当所有文件上传结束时触发
        uploader_file[index].on('uploadFinished', function () {
            Dolphin.loading('hide')
        });
    }


    //移除文件
    $(document).on('mouseover', '.js-upload-file .file-item', function () {
        $(this).find('.move-file').show();
    });
    $(document).on('mouseleave', '.js-upload-file .file-item', function () {
        $(this).find('.move-file').hide();
    });
    $(document).on('click', '.remove-file', function () {
        //单文件上传时需重置表单值
        $(this).parent().parent().parent().parent().find('input[type="hidden"]').val('');
        //移除元素
        $(this).parent().parent().remove();
        //移除队列中的对应文件
        var index = $(this).attr('uploader-index');
        var fileId = $(this).attr('file-id');
        uploader_file[index].removeFile(uploader_file[index].files[fileId], true);
    });

    //重试上传
    $(document).on('click', '.uploader-retry', function () {
        var index = $(this).attr('uploader-index');
        var fileId = $(this).attr('file-id');
        uploader_file[index].retry(uploader_file[index].files[fileId]);
    });


    //多图上传拖拽排序
    $('.ui-sortable').sortable({
        handle: ".move-file"
    });
    $(".ui-sortable").disableSelection();
});
