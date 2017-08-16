<?php
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\common\components\decode\SocketConnect;

set_time_limit(0);//永不超时

class Socket extends Command
{
    protected function configure()
    {
        $this->setName('Socket')->setDescription('Command Socket');
    }

    protected function execute(Input $input, Output $output)
    {
        file_put_contents(ROOT_PATH.'public/static/socket/run.log', '于时间:'.date('Y-m-d H:i:s').'执行了一次获取数据操作'.PHP_EOL.PHP_EOL,FILE_APPEND);
        //设置连接信息
        $serviceSocket = SocketConnect::getInstance();
        //开启socket服务端
        $serviceSocket->start_server();

    }
}
