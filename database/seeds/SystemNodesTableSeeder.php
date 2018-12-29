<?php

use Illuminate\Database\Seeder;

class SystemNodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 4)->create();
        //        foreach ($systemNodes as $systemNode) {
        //            $systemNode->pid = 0;
        //            $systemNode->action = '';
        //            $systemNode->level = 1;
        //            $systemNode->save();
        //        }
        //        $systemNode = $systemNodes[0];
        //        $systemNode->name = '系统';
        //        $systemNode->pid = 0;
        //        $systemNode->action = '';
        //        $systemNode->status = 1;
        //        $systemNode->sort = 0;
        //        $systemNode->save();
        //        $systemNode = $systemNodes[1];
        //        $systemNode->name = '系统主页';
        //        $systemNode->pid = 1;
        //        $systemNode->action = 'Admin\System\IndexController@index';
        //        $systemNode->status = 1;
        //        $systemNode->sort = 0;
        //        $systemNode->level = 2;
        //        $systemNode->save();
        //
        //        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 9)->create();
        //        foreach ($systemNodes as $systemNode) {
        //            $pid = mt_rand(1, 4);
        //            $systemNode->pid = $pid == 2 ?
        //                1 :
        //                $pid;
        //            $systemNode->level = 2;
        //            $systemNode->save();
        //        }
        //
        //        $systemNodes =
        //            factory(\App\Model\Admin\SystemNode::class, 27)->create();
        //        foreach ($systemNodes as $systemNode) {
        //            $systemNode->pid = mt_rand(5, 13);
        //            $systemNode->level = 3;
        //            $systemNode->save();
        //        }
        //
        //        $systemNodes =
        //            factory(\App\Model\Admin\SystemNode::class, 81)->create();
        //        foreach ($systemNodes as $systemNode) {
        //            $systemNode->pid = mt_rand(14, 41);
        //            $systemNode->level = 4;
        //            $systemNode->status = mt_rand(0, 1);
        //            $systemNode->save();
        //        }

        $systemNodes = [
            [
                'name' => '系统',
                'icon' => 'fa fa-fw fa-laptop',
                'children' => [
                    [
                        'name' => '系统首页',
                        'icon' => 'fa fa-fw fa-laptop',
                        'action' => 'Admin\System\IndexController@index',
                    ],
                    [
                        'name' => '配置中心',
                        'icon' => 'fa fa-fw fa-laptop',
                        'children' => [
                            [
                                'name' => '系统配置',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\IndexController@config',
                            ],
                            [
                                'name' => '修改密码',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\IndexController@updatePassword',
                            ],
                            [
                                'name' => '资料设置',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\IndexController@setInfo',
                            ]
                        ]
                    ],
                    [
                        'name' => '权限功能',
                        'icon' => 'fa fa-fw fa-laptop',
                        'children' => [
                            [
                                'name' => '账号管理',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\UserController@index',
                                'children' => [
                                    [
                                        'name' => '添加',
                                        'action' => 'Admin\System\UserController@create',
                                    ],
                                    [
                                        'name' => '修改',
                                        'action' => 'Admin\System\UserController@edit',
                                    ],
                                    [
                                        'name' => '启用',
                                        'action' => 'Admin\System\UserController@enable',
                                    ],
                                    [
                                        'name' => '禁用',
                                        'action' => 'Admin\System\UserController@disable',
                                    ],
                                    [
                                        'name' => '删除',
                                        'action' => 'Admin\System\UserController@destroy',
                                    ],
                                ]
                            ],
                            [
                                'name' => '角色管理',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\RoleController@index',
                                'children' => [
                                    [
                                        'name' => '添加',
                                        'action' => 'Admin\System\RoleController@create',
                                    ],
                                    [
                                        'name' => '修改',
                                        'action' => 'Admin\System\RoleController@edit',
                                    ],
                                    [
                                        'name' => '启用',
                                        'action' => 'Admin\System\RoleController@enable',
                                    ],
                                    [
                                        'name' => '禁用',
                                        'action' => 'Admin\System\RoleController@disable',
                                    ],
                                    [
                                        'name' => '删除',
                                        'action' => 'Admin\System\RoleController@destroy',
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => '地区管理',
                        'icon' => 'fa fa-fw fa-laptop',
                        'action' => 'Admin\System\AreaController@index',
                        'children' => [
                            [
                                'name' => '排序',
                                'action' => 'Admin\System\AreaController@sort',
                            ],
                        ]
                    ],
                    [
                        'name' => '开发中心',
                        'icon' => 'fa fa-fw fa-laptop',
                        'children' => [
                            [
                                'name' => '文件管理',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\FileController@index',
                                'children' => [
                                    [
                                        'name' => '上传配置',
                                        'action' => 'Admin\System\FileController@config',
                                    ],
                                    [
                                        'name' => '删除',
                                        'action' => 'Admin\System\FileController@destroy',
                                    ],
                                ]
                            ],
                            [
                                'name' => '节点管理',
                                'icon' => 'fa fa-fw fa-laptop',
                                'action' => 'Admin\System\NodeController@index',
                                'children' => [
                                    [
                                        'name' => '添加',
                                        'action' => 'Admin\System\NodeController@create',
                                    ],
                                    [
                                        'name' => '修改',
                                        'action' => 'Admin\System\NodeController@edit',
                                    ],
                                    [
                                        'name' => '启用',
                                        'action' => 'Admin\System\NodeController@enable',
                                    ],
                                    [
                                        'name' => '禁用',
                                        'action' => 'Admin\System\NodeController@disable',
                                    ],
                                    [
                                        'name' => '排序',
                                        'action' => 'Admin\System\NodeController@sort',
                                    ],
                                    [
                                        'name' => '模块排序',
                                        'action' => 'Admin\System\NodeController@moduleSort',
                                    ],
                                    [
                                        'name' => '删除',
                                        'action' => 'Admin\System\NodeController@destroy',
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => '微信',
                'icon' => 'fa fa-fw fa-laptop',
                'children' => [
                    [
                        'name' => '公众号板块',
                        'icon' => 'fa fa-fw fa-laptop',
                    ],
                    [
                        'name' => '小程序板块',
                        'icon' => 'fa fa-fw fa-laptop',
                    ]
                ]
            ],
        ];
        \App\Servers\ArrServer::parseData($systemNodes);
    }
}
