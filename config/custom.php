<?php

return [
  'upload_scenes' => [//文件上传场景配置
    'set_admin_avatar'           => [//场景名称
      'system_users' => [//每个场景对应的表，可以多个
        'where' => ['avatar' => '='],//表中对应的字段，可以多个，使用OR查询
      ],
    ],
    'set_admin_logo'             => [
      'system_configs' => [
        'whereRaw' => "`name` = 'admin_logo'",//其它原生查询条件，如果是OR语句，请用()包起来
        'where'    => ['value' => '='],
      ],
    ],
    'set_admin_logo_text'        => [
      'system_configs' => [
        'whereRaw' => "`name` = 'admin_logo_text'",
        'where'    => ['value' => '='],
      ],
    ],
    'set_admin_logo_signin'      => [
      'system_configs' => [
        'whereRaw' => "`name` = 'admin_logo_signin'",
        'where'    => ['value' => '='],
      ],
    ],
    'set_upload_image_watermark' => [
      'system_configs' => [
        'whereRaw' => "`name` = 'upload_image_watermark_pic'",
        'where'    => ['value' => '='],
      ],
    ],
    'ueditor_catch_upload'       => [
      'system_demos' => [
        'whereRaw' => "(`name` = 'demo_ueditor_1' OR `name` = 'demo_ueditor_2')",
        'where'    => ['value' => 'like'],
      ],
    ],
  ],
];