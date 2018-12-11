<header id="header-navbar" class="content-mini content-mini-full">
    <!-- Header Navigation Right -->
    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">
                    <img src="/static/admin/img/avatar.jpg" alt="用户头像">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">用户名称 (角色名称)</li>
                    <li>
                        <a tabindex="-1" href="修改密码链接">
                            <i class="si si-wrench pull-right"></i>修改密码
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a tabindex="-1" href="javascript:void(0);" id="admin-logout">
                            <i class="si si-logout pull-right"></i>退出帐号
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a class="btn btn-default" href="前台链接" target="_blank" data-toggle="tooltip" data-placement="bottom" data-original-title="打开前台">
                <i class="fa fa-external-link-square"></i>
            </a>
        </li>
        <li>
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default" data-toggle="layout" data-action="side_overlay_toggle" title="侧边栏" type="button">
                <i class="fa fa-tasks"></i>
            </button>
        </li>
        <li></li>
    </ul>
    <!-- END Header Navigation Right -->
    <!-- Header Navigation Left -->
    <ul class="nav nav-pills pull-left">
        <li class="hidden-md hidden-lg">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <a href="javascript:void(0)" data-toggle="layout" data-action="sidebar_toggle"><i class="fa fa-navicon"></i></a>
        </li>
        <li class="hidden-xs hidden-sm">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <a href="javascript:void(0)" title="打开/关闭左侧导航" data-toggle="layout" data-action="sidebar_mini_toggle"><i class="fa fa-bars"></i></a>
        </li>
        <li class="hidden-xs hidden-sm active">
            <a href="javascript:void(0);" target="_self" class="top-menu"><i class="fa fa-fw fa-cab"></i>模块一</a>
        </li>
        <li class="hidden-xs hidden-sm">
            <a href="javascript:void(0);" target="_self" class="top-menu"><i class="fa fa-fw fa-cab"></i>模块二</a>
        </li>
        <li>
            <!-- Opens the Apps modal found at the bottom of the page, before including JS code -->
            <a href="#" data-toggle="modal" data-target="#apps-modal"><i class="si si-grid"></i></a>
        </li>
    </ul>
    <!-- END Header Navigation Left -->
</header>


