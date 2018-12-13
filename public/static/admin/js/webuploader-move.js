$(function () {
    //多图上传拖拽排序
    $('.ui-sortable').sortable({
        placeholder: "ui-sortable-images-state-highlight",
        handle: ".move-picture"
    });
    $(".ui-sortable").disableSelection();

    //多文件上传拖拽排序
    $('.ui-sortable').sortable({
        handle: ".move-file"
    });
    $(".ui-sortable").disableSelection();
});
