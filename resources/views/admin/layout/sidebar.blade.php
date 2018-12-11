<nav id="sidebar">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="side-header side-content bg-white-op dolphin-header">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times"></i>
                </button>
                <a class="h5 text-white" href="首页链接">
                    <img src="/static/admin/img/logo.png" class="logo" alt="项目LOGO">
                    <img src="/static/admin/img/logo-text.png" class="logo-text sidebar-mini-hide" alt="项目文字LOGO">
                </a>
            </div>
            <!-- END Side Header -->
            <!-- Side Content -->
            <div class="side-content" id="sidebar-menu">
                <ul class="nav-main" id="nav-236">
                    <li class="open">
                        <a class="active" href="一级菜单链接" target="_self"><i class="fa fa-fw fa-home"></i><span class="sidebar-mini-hide">一级菜单</span></a>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0);"><i class="fa fa-fw fa-user"></i><span class="sidebar-mini-hide">一级菜单</span></a>
                        <ul>
                            <li>
                                <a href="二级菜单链接" target="_self"><i class="fa fa-fw fa-list"></i>二级菜单</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- END Side Content -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
