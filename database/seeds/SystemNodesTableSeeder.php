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
        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 2)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = 0;
            $systemNode->action = '';
            $systemNode->relate_actions = '';
            $systemNode->level = 1;
            $systemNode->save();
        }
        $systemNode = $systemNodes[0];
        $systemNode->name = '系统';
        $systemNode->pid = 0;
        $systemNode->action = '';
        $systemNode->relate_actions = '';
        $systemNode->status = 1;
        $systemNode->sort = 0;
        $systemNode->save();

        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 4)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(1, 2);
            $systemNode->level = 2;
            $systemNode->save();
        }
        $systemNode = $systemNodes[0];
        $systemNode->name = '系统主页';
        $systemNode->pid = 1;
        $systemNode->action = 'Admin\System\IndexController@index';
        $systemNode->relate_actions = '';
        $systemNode->status = 1;
        $systemNode->sort = 0;
        $systemNode->save();

        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 8)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(4, 6);
            $systemNode->level = 3;
            $systemNode->save();
        }

        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 16)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(7, 14);
            $systemNode->level = 4;
            $systemNode->save();
        }
    }
}
