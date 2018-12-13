<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Admin\SystemUser::class, function (Faker $faker) {
    return [
        'username' => str_random(mt_rand(2, 20)),
        'password' => bcrypt('admin888'),
        'nickname' => str_random(mt_rand(2, 20)),
        'avatar' => 'http://finecar.dj/uploads/images/20181212/cb504ce774f37d15ce93d58faec4e8f9.jpg',
        'type' => 0,
        'status' => mt_rand(0, 1),
        'remember_token' => str_random(64),
    ];
});
