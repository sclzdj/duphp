<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_files', function (Blueprint $table) {
            $table->engine = 'innodb';
            $table->increments('id');
            $table->string('url',1000)->default('')->comment('文件地址');
            $table->string('filesystem_driver')->default('')->comment('驱动');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `system_files` COMMENT '后台:系统文件'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_files');
    }
}
