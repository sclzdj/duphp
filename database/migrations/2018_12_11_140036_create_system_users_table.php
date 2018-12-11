<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_users', function (Blueprint $table) {
            $table->engine = 'innodb';
            $table->increments('id');
            $table->string('username')->unique()->default('')->comment('账号');
            $table->string('password')->default('')->comment('密码');
            $table->string('nickname')->default('')->comment('昵称');
            $table->unsignedTinyInteger('type')->default(0)
                ->comment('类型:0=>超级管理员 1=>角色权限 2=>直赋权限');
            $table->unsignedTinyInteger('status')->default(1)
                ->comment('状态:0=>禁用 1=>启用');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_users');
    }
}
