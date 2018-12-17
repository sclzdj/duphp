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
            $table->string('url', 1000)->index()->default('')->comment('文件链接');
            $table->string('extension')->default('')->comment('后缀名');
            $table->string('mimeType')->default('')->comment('mime类型');
            $table->unsignedInteger('size')->default(0)->comment('大小');
            $table->string('object')->default('')->comment('文件对象名');
            $table->string('disk')->default('')->comment('磁盘');
            $table->string('driver')->default('')->comment('驱动');
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
