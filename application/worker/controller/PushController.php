<?php

namespace app\worker\controller;

use \Workerman\Worker;
use \Workerman\Lib\Timer;

class PushController
{
    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        $task = new Worker();
        $task->onWorkerStart = function($task)
        {
            // 每2.5秒执行一次
            $time_interval = 2.5;
            Timer::add($time_interval, function()
            {
                echo "Push task run\n";
            });
        };

        // Run worker
        Worker::runAll();
    }

    protected function init()
    {

    }

}
