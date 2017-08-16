<?php

namespace app\worker\controller;

use \Workerman\Worker;

/**
 * Worker 控制器扩展类
 */
abstract class WorkerController
{
    protected $worker;
    protected $socket    = '';
    protected $protocol  = 'http'; //应用层协议
    protected $host      = '0.0.0.0'; // IP
    protected $port      = '2346'; // 端口
    protected $processes = 4; // 设置当前worker实例的进程数

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        // 实例化 Websocket 服务
        $this->worker = new Worker($this->socket ?: $this->protocol . '://' . $this->host . ':' . $this->port);
        // 设置进程数
        $this->worker->count = $this->processes;
        // 初始化
        $this->init();

        // 设置回调
        foreach (['onWorkerStart', 'onConnect', 'onMessage', 'onClose', 'onError', 'onBufferFull', 'onBufferDrain', 'onWorkerStop', 'onWorkerReload'] as $event) {
            if (method_exists($this, $event)) {
                $this->worker->$event = [$this, $event];
            }
        }
        // Run worker
        Worker::runAll();
    }

    protected function init()
    {
    }

}
