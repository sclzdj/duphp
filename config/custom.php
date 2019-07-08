<?php

return [
  'upload_scenes' => [
    'set_admin_avatar'           => [
      'table' => 'system_users',
      'field' => 'avatar',
      'type'  => '=',
    ],
    'set_admin_logo'             => [
      'table' => 'system_configs',
      'field' => 'value',
      'type'  => '=',
    ],
    'set_admin_logo_text'        => [
      'table' => 'system_configs',
      'field' => 'value',
      'type'  => '=',
    ],
    'set_admin_logo_signin'      => [
      'table' => 'system_configs',
      'field' => 'value',
      'type'  => '=',
    ],
    'set_upload_image_watermark' => [
      'table' => 'system_configs',
      'field' => 'value',
      'type'  => '=',
    ],
  ],
];