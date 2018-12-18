<?php

use Illuminate\Database\Seeder;

class SystemConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemConfigs = [
            [
                'name' => 'basic_admin_run',
                'title' => '后台开关',
                'value' => 1,
                'type' => 'basic',
                'genre' => 'switch',
                'tips' => '关闭后只有站长能登录后台',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'basic_wxapp_run',
                'title' => '小程序开关',
                'value' => 1,
                'type' => 'basic',
                'genre' => 'switch',
                'tips' => '关闭后小程序所有接口失效',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'basic_page_size',
                'title' => '默认分页数量',
                'value' => 20,
                'type' => 'basic',
                'genre' => 'text',
                'tips' => '含有分页的接口或列表页面默认数量都从这里取值',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'basic_index_url',
                'title' => '前台链接',
                'value' => '',
                'type' => 'basic',
                'genre' => 'text',
                'tips' => '如含有http://或https://则走外网，否则走动作方法，动作方法格式必须有效且不能携带参数',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'basic_static_file_version',
                'title' => '静态文件版本',
                'value' => mt_rand(100, 1000),
                'type' => 'basic',
                'genre' => 'text',
                'tips' => '如果静态文件有改动，只需要把这里的值+1即可，客户端就不走缓存将重新请求静态文件',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_name',
                'title' => '名称',
                'value' => '',
                'type' => 'admin',
                'genre' => 'text',
                'tips' => '后台名称',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_logo',
                'title' => 'LOGO',
                'value' => '',
                'type' => 'admin',
                'genre' => 'image',
                'tips' => '后台左上角的正方形logo',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_logo_text',
                'title' => 'LOGO文字',
                'value' => '',
                'type' => 'admin',
                'genre' => 'image',
                'tips' => '后台左上角的长方形logo文字',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_login_captcha',
                'title' => '登录验证码',
                'value' => 0,
                'type' => 'admin',
                'genre' => 'switch',
                'tips' => '关闭后后台登录不需要输入验证码',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_keywords',
                'title' => '关键词',
                'value' => '',
                'type' => 'admin',
                'genre' => 'text',
                'tips' => '后台搜索引擎关键字',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_describe',
                'title' => '描述',
                'value' => '',
                'type' => 'admin',
                'genre' => 'textarea',
                'tips' => '后台描述，有利于搜索引擎抓取相关信息',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_icp',
                'title' => '备案',
                'value' => '',
                'type' => 'admin',
                'genre' => 'text',
                'tips' => '后台备案号',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'admin_copyright',
                'title' => '版权',
                'value' => '',
                'type' => 'admin',
                'genre' => 'text',
                'tips' => '后台版权信息',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'upload_image_limit_size',
                'title' => '图片大小限制',
                'value' => '',
                'type' => 'upload',
                'genre' => 'text',
                'tips' => '留空或填0为不限制大小，单位：kb',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'upload_image_allow_extension',
                'title' => '允许的图片后缀',
                'value' => '',
                'type' => 'upload',
                'genre' => 'tags',
                'tips' => '多个后缀用英文逗号隔开，不填写则不限制类型',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'upload_file_limit_size',
                'title' => '文件大小限制',
                'value' => '',
                'type' => 'upload',
                'genre' => 'text',
                'tips' => '留空或填0为不限制大小，单位：kb',
                'options' => '',
                'required' => 0,
            ],
            [
                'name' => 'upload_file_allow_extension',
                'title' => '允许的文件后缀',
                'value' => '',
                'type' => 'upload',
                'genre' => 'tags',
                'tips' => '多个后缀用英文逗号隔开，不填写则不限制类型',
                'options' => '',
                'required' => 0,
            ],
        ];
        foreach ($systemConfigs as $systemConfig) {
            \App\Model\Admin\SystemConfig::create($systemConfig);
        }
    }
}