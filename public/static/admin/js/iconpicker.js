$(function () {
    function iconpicker(obj) {
        obj.iconpicker({
            title: '请选择图标',
            placement: 'bottomLeft',
            hideOnSelect: true,//选择后隐藏
            selectedCustomClass: '',//选中的图标添加样式
            //icons: [],
            fullClassFormatter: function (val) {
                return 'fa ' + val;
            },
            inputSearch: true,//输入可搜索
        });
    }

    iconpicker($('.js-icon-picke input'));
    $('.js-icon-picke input').focus(function () {
        iconpicker($(this));
    });
});
