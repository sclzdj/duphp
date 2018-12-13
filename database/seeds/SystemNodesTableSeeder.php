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
        $systemNodes = factory(\App\Model\Admin\SystemNode::class, 5)->create();
        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 25)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(1, 5);
            $systemNode->save();
        }
        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 125)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(6, 25);
            $systemNode->save();
        }
        $systemNodes =
            factory(\App\Model\Admin\SystemNode::class, 625)->create();
        foreach ($systemNodes as $systemNode) {
            $systemNode->pid = mt_rand(26, 125);
            $systemNode->save();
        }
    }
}
