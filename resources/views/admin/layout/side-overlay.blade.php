<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="side-header side-content">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-times"></i>
            </button>
            <span>
                    <img class="img-avatar img-avatar32" src="/static/admin/img/avatar.jpg" alt="用户头像">
                    <span class="font-w600 push-10-l">用户名称</span>
                </span>
        </div>
        <!-- END Side Header -->
        <!--侧栏-->
        <!-- Side Content -->
        <div class="side-content remove-padding-t" id="aside">
            <!-- Side Overlay Tabs -->
            <div class="block pull-r-l border-t">
                <div class="block-content">
                    <div class="block pull-r-l">
                        <div class="block-header bg-gray-lighter">
                            <ul class="block-options">
                                <li>
                                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                </li>
                            </ul>
                            <h3 class="block-title">系统设置</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-bordered">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="font-s13 font-w600">站点开关</div>
                                            <div class="font-s13 font-w400 text-muted">站点关闭后将不能访问</div>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            <label class="css-input switch switch-sm switch-primary push-10-t">
                                                <input type="checkbox" data-table="b50d2a4d" data-id="1" data-field="value" checked=""><span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Side Overlay Tabs -->
        </div>
        <!-- END Side Content -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
