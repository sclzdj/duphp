<?php

use Illuminate\Database\Seeder;

class SystemNodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 4)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = 0;
            $systemNode->action = '';
            $systemNode->level = 1;
            $systemNode->save();
        }
        $systemNode = $systemNodes[0];
        $systemNode->name = '系统';
        $systemNode->pid = 0;
        $systemNode->action = '';
        $systemNode->status = 1;
        $systemNode->sort = 0;
        $systemNode->save();
        $systemNode = $systemNodes[1];
        $systemNode->name = '系统主页';
        $systemNode->pid = 1;
        $systemNode->action = 'Admin\System\IndexController@index';
        $systemNode->status = 1;
        $systemNode->sort = 0;
        $systemNode->level = 2;
        $systemNode->save();

        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 9)->create();
        foreach ($systemNodes as $systemNode) {
            $pid = mt_rand(1, 4);
            $systemNode->pid = $pid == 2 ?
                1 :
                $pid;
            $systemNode->level = 2;
            $systemNode->save();
        }

        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 27)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(5, 13);
            $systemNode->level = 3;
            $systemNode->save();
        }

        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 81)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(14, 41);
            $systemNode->level = 4;
            $systemNode->status = mt_rand(0, 1);
            $systemNode->save();
        }
    }
}
