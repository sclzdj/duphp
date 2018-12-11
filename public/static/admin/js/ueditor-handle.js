$(function () {
    // **百度编辑器单图上传不支持跨域，等待官方更新
    new UE.ui.Editor();
    for (var index = 0; index < $('.js-ueditor').length; index++) {
        $('.js-ueditor:eq(' + index + ')').prop('id', 'js-ueditor' + index);
        UE.getEditor('js-ueditor' + index, {
            initialFrameHeight: 800 //设置编辑器高度
        });
    }
    // 重写文件件上传方法
    UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
    UE.Editor.prototype.getActionUrl = function (action) {
        switch (action) {
            case 'uploadimage':
            case 'uploadscrawl':
            case 'uploadvideo':
            case 'uploadfile':
                return 'http://dt5.dj/index/upload/index/type/ueditor'; //这就是自定义的上传地址
                break;
            default:
                return this._bkGetActionUrl.call(this, action);
        }
    }
});
