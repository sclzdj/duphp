<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_nodes', function (Blueprint $table) {
            $table->engine = 'innodb';
            $table->increments('id');
            $table->string('name')->unique()->default('')->comment('名称');
            $table->unsignedInteger('pid')->default(0)->comment('所属父级');
            $table->string('action')->index()->default('')->comment('动作方法');
            $table->text('relate_actions', '1000')->comment('关联的其它动作方法，多个换行隔开');
            $table->unsignedTinyInteger('status')->default(1)
                ->comment('状态:0=>禁用 1=>启用');
            $table->integer('sort')->default(0)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_nodes');
    }
}