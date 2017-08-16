<?php

namespace app\common\components\decode;

use app\common\components\decode\SocketSwitch;
use \app\common\components\decode\Decode;

/**
 * Class Socket
 * @package app\common\components\decode
 */
class SocketConnect{

    private $host = '0.0.0.0';
    private $port = 8010;
    private $maxUser = 10;
    public  $accept = array(); //连接的客户端
    private $cycle = array(); //循环连接池
    private $isHand = array();//握手信息

    private $socket = null;

    /**
     * @var SocketConnect|null
     */
    private static $_instance = null;

    /**
     * Socket constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param null $host
     * @param null $port
     * @param null $maxUser
     */
    public function set($host = null, $port = null,$maxUser = null)
    {
        if($host !== null){
            $this->host = $host;
        }
        if($port !== null){
            $this->port = $port;
        }
        if($maxUser !== null){
            $this->maxUser = $maxUser;
        }
    }

    /**
     * @param null $host
     * @param null $port
     * @param null $maxUser
     * @return SocketConnect|null
     */
    public static function getInstance($host = null, $port = null,$maxUser = null)
    {
        if (self::$_instance === null){
            self::$_instance = new SocketConnect();
            self::$_instance->set($host,$port,$maxUser);
        }
        return self::$_instance;
    }

    //挂起socket
    public function start_server() {

        //创建一个socket
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        //配置socket
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, TRUE);

        //绑定接口，最多10个人连接，超过的客户端连接会返回WSAECONNREFUSED错误
        $res = socket_bind($this->socket, $this->host, $this->port);
        if ( $res === false ){
            //判断绑定监听地址是否成功，失败则记录绑定监听失败原因,且退出程序
            $errorInt = socket_last_error($this->socket);
            $error = socket_strerror ($errorInt);
            $this->log($error,'error.log');
            return; //因监听失败，只能中断程序执行
        }

        //监听接口
        socket_listen($this->socket, $this->maxUser);

        //无限循环，使用sleep()机制
        $continue = true; //初始条件执行循环
        set_time_limit(0);//永不超时
        while($continue) {
            //获取所有socket连接
            $this->cycle = $this->accept;
            $this->cycle[] = $this->socket;

            //阻塞用，有新连接时才会结束
            socket_select($this->cycle, $write, $except, null);
            foreach ($this->cycle as $k => $v) {
                //当socket运行到连接池最后一位时，开始添加连接
                if($v === $this->socket) {
                    //如果socket连接失败，跳出本次循环
                    if (($accept = socket_accept($v)) < 0) {
                        continue;
                    }
                    //连接成功加入连接池
                    $this->add_accept($accept);
                    continue;
                }

                //在连接池中搜索socket连接ID
                $acceptId = array_search($v, $this->accept);
                //如果连接没有保存至连接池，跳出本次循环
                if ($acceptId === NULL) {
                    continue;
                }
                //没消息的socket就断开
                if (!@socket_recv($v, $data, 1024, 0) || !$data) {
                    $this->close($v);
                    continue;
                }

//              //检查是否握手
//                if (!$this->isHand[$acceptId]) {
//                    //进行握手
//                    $this->upgrade($v, $data, $acceptId);
//                    continue;
//                }

                //执行到此处,表示有数据
                $this->log('于 '.date('Y-m-d H:i:s').' 成功获取到一组原始数据','log.log');
                //存入原始数据
                file_put_contents(ROOT_PATH.'public/static/socket/data.log',$data,FILE_APPEND);

                //将数据进行解码，且存入文件
                $hex = \app\common\components\decode\DecodeHelper::StrToBin($data);
                file_put_contents(ROOT_PATH.'public/static/socket/hexData.log',$hex,FILE_APPEND);

                //数据解析后操作
                Decode::run($hex);
            }
            sleep(1);
            $continue = SocketSwitch::getSwitch(); //获取是否继续循环条件，此项是为了手动关闭循环
            if (!$continue){
                break;
            }
        }
        //如是手动关闭条件符合，则中断
        if (!$continue){
            set_time_limit(1);//取消永不超时
            exit();
        }
    }

    /**
     * 新建一个连接
     * @param $accept //套接号
     */
    private function add_accept($accept) {
        $this->accept[] = $accept;
        $accept = array_keys($this->accept);
        //获取新连接的key值，用于跟握手信息绑定
        $acceptId = end($accept);
        //添加初次连接用户握手信息
        $this->isHand[$acceptId] = FALSE;
    }

    /**
     * 关闭一个连接
     * @param $accept //套接号
     */
    private function close($accept) {
        //在连接池中搜索套接号
        $acceptId = array_search($accept, $this->accept);
        //断开socket连接
        socket_close($accept);
        //销毁变量
        unset($this->cycle[$acceptId]);
        unset($this->accept[$acceptId]);
        unset($this->isHand[$acceptId]);
    }


    /**
     * 响应升级协议，与websocket进行握手
     * @param $accept //套接号
     * @param $data //websocket发送的数据
     * @param $acceptId //与套接号绑定的Id
     */
    private function upgrade($accept, $data, $acceptId) {
        //用正则表达式获取websocket传输过来的key值
        if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$data,$match)) {
            //服务端生成对应key值返回
            $key = base64_encode(sha1($match[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
            $upgrade  = "HTTP/1.1 101 Switching Protocol\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "Sec-WebSocket-Accept: " . $key . "\r\n\r\n";  //必须以两个回车结尾
            //向套接口写入数据
            socket_write($accept, $upgrade, strlen($upgrade));
            $this->isHand[$acceptId] = TRUE;
        }
    }

    /**
     * 按照websocket协议进行解码
     * @param $buffer //需要解码的数据
     * @param $buffer
     * @return null|string
     */
    private function decode($buffer) {
        $len = $masks = $data = $decoded = null;
        //获取传递过来数据长度
        $len = ord($buffer[1]) & 127;
        if ($len === 126) {
            $masks = substr($buffer, 4, 4);
            $data = substr($buffer, 8);

        }
        else if ($len === 127) {
            $masks = substr($buffer, 10, 4);
            $data = substr($buffer, 14);
        }
        else {
            $masks = substr($buffer, 2, 4);
            $data = substr($buffer, 6);
        }
        //
        for ($index = 0; $index < strlen($data); $index++) {
            $decoded .= $data[$index] ^ $masks[$index % 4];
        }
        return $decoded;
    }


    /**
     * @description 记录
     * @param $msg
     * @param string $fileName
     */
    private function log($msg,$fileName = null){
        file_put_contents(ROOT_PATH.'public/static/socket/'.($fileName ? $fileName : 'log.log'),'于: '.date('Y-m-d H:i:s').'; 记录信息=>( '.$msg.' )'.PHP_EOL.PHP_EOL,FILE_APPEND);
    }
}
