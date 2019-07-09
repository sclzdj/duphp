<?php

return [
  'upload_scenes' => [
    'set_admin_avatar'           => [
      'table' => 'system_users',
      'where' => ['avatar'=>'='],
    ],
    'set_admin_logo'             => [
      'table' => 'system_configs',
      'where' => ['value'=>'='],
    ],
    'set_admin_logo_text'        => [
      'table' => 'system_configs',
      'where' => ['value'=>'='],
    ],
    'set_admin_logo_signin'      => [
      'table' => 'system_configs',
      'where' => ['value'=>'='],
    ],
    'set_upload_image_watermark' => [
      'table' => 'system_configs',
      'where' =>['value'=>'='],
    ],
    'ueditor_upload' => [
      'table' => 'system_demos',
      'where' =>['value'=>'like'],
    ],
    'ueditor_catch_upload' => [
      'table' => 'system_demos',
      'where' =>['value'=>'like'],
    ],
  ],
];