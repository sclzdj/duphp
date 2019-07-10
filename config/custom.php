<?php

return [
  'upload_scenes' => [
    'set_admin_avatar'           => [
      'system_users' => [
        'where' => ['avatar' => '='],
      ],
    ],
    'set_admin_logo'             => [
      'system_configs' => [
        'whereRaw' => "`name` = 'admin_logo'",
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