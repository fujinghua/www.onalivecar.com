<?php

namespace app\common\components\decode;

use app\common\components\decode\DecodeHelper;
use app\common\components\decode\Switcher;
use app\common\components\decode\Save;

/**
 * Class Decode
 * @package app\common\components\decode
 */
class Decode{

    //可实例化时配置的属性
    private $separator = '7e';//一个信息 开始和结束 的标志
    private $escape = ['7d02'=>'7e','7d01'=>'7d']; //转义处理定义

    private $data; //本次解析的原始数据串，十六进制
    private $code; //每条信息的原始数据串，十六进制
    private $codeBody; //信息体，十六进制

    //程序执行后后有值得属性
    private $startLength = 0;//读取字符串的开始位置，随着程序执行，该值会有变化
    private $checkCode;//校验码，十六进制
    private $checkStatus; //校验是否成功，bool
    private $ID; //信息ID
    private $attr = []; //信息属性
    private $SIM; //信息SIM
    private $serial;  // 信息流水号
    private $messageType;  // 0，长信息 或 1，短信息
    private $className;  // 属于该数据包类型类名
    private $sum; // 信息包总数
    private $sequence; // 信息包序号
    private $result = [];//整个解析数据
    private $pack = []; //十六进制的解析数据
    private $packTrans = []; //最后的解析数据

    private $type; //数据类型

    public static $_allType = [];

    /**
     * Decode constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (is_array($config)){
            foreach ($config as $key => $value){
                switch ($key){
                    case 'separator';{ $this->separator = $value; }break;
                    case 'escape';{ $this->escape = $value; }break;
                    default:{}
                }
            }
        }
    }

    /**
     * @description 解码成可用十六进制数组
     * @param $data
     * @return array
     */
    protected function decryptData($data)
    {
        $ret = [];
        if (!is_string($data) || $data == ''){
            return $ret;
        }
        $data = ltrim($data,$this->separator);
        $data = rtrim($data,$this->separator);
        $data = explode($this->separator.$this->separator,$data);
        if (is_array($data)){
            $ret = $data;
        }
        return $ret;
    }

    /**
     * @param $hex
     * @param bool $trace
     * @param bool $break
     * @return array
     */
    public static function run($hex,$trace = false,$break = false){
        $ret = ['success'=>0,'error'=>0];
        $model = new Decode();
        foreach ($model->decryptData($hex) as $item){
            $result = $model->decrypt($item);
            if ($result !== false && is_array($result)){
                $ret['success']++;
                $className = isset($result['className']) ? $result['className'] : false;
                $pack = isset($result['packTrans']) ? $result['packTrans'] : [];
                $model->save($className,$pack,$result);
                if ($trace){
                    var_dump($result);
                    if ($break){
                        break;
                    }
                }
            }else{
                $ret['error']++;
            }
        }
        return $ret;
    }

    /**
     * @description 存入数据库
     * @param string $className
     * @param array $pack
     * @param array $source
     * @return bool
     */
    protected function save($className,$pack,$source){
        $ret = false;
        Save::run($className,$pack,$source);
        return $ret;
    }

    /**
     * @description 解码
     * @param string $str
     * @return bool | array
     */
    protected function decrypt($str){

        if (!is_string($str) || $str == ''){
            return false;
        }

        $this->startLength = 0;
        $this->data = $str;
        $this->code = $str;

        //第一步
        $this->remove();

        //第二步
        $this->escape();

        //第三步
        $this->getCheckCode();

        //第五步
        if (DecodeHelper::BCC_Check_Xor($this->code) !== $this->checkCode){
            $this->checkStatus = false;
            return false;
        }else{
            $this->checkStatus = true;
        }

        //第六步
        $this->getId();

        //第七步
        $this->getAttr();

        //第八步
        $this->getSim();

        //第九步
        $this->getSerial();

        //第十步
        $this->getSumAndSequence();

        //第十一步
        $this->getCodeBody();

        //第十二步
        //解析信息
        $this->getPack();

        $this->result['checkCode'] = $this->checkCode;
        $this->result['checkStatus'] = $this->checkStatus;
        $this->result['ID'] = $this->ID;
        $this->result['SIM'] = $this->SIM;
        $this->result['serial'] = $this->serial;
        $this->result['sum'] = $this->sum;
        $this->result['sequence'] = $this->sequence;
        $this->result['messageType'] = $this->messageType;
        $this->result['className'] = $this->className;
        $this->result['type'] = $this->type;
        $this->result['attr'] = $this->attr;
        $this->result['code'] = $this->data;
        $this->result['codeBody'] = $this->codeBody;
        $this->result['pack'] = $this->pack;
        $this->result['packTrans'] = $this->packTrans;

        if (!isset(self::$_allType[$this->SIM])){
            self::$_allType[$this->SIM] = $this->SIM;
        }

        if (isset(self::$_allType[$this->SIM.'_Num'])){
            self::$_allType[$this->SIM.'_Num']++;
        }else{
            self::$_allType[$this->SIM.'_Num']=1;
        }

        return $this->result;
    }

    /**
     * @description 去掉第一个信息和最后一个信息的标识 和 转成全部小写
     */
    private function remove(){
        $this->code = strtolower($this->code);
        $this->code = str_replace($this->separator,'',$this->code);
    }

    /**
     * @description 转义处理
     */
    private function escape(){
        if (!is_array($this->escape)){
            return;
        }
        foreach ($this->escape as $key => $value){
            $this->code = str_replace($key,$value,$this->code);
        }
    }

    /**
     * @description 读取校验码
     */
    private function getCheckCode(){
        $this->checkCode = substr($this->code,-2); //校验码
        $this->code = substr($this->code,-strlen($this->code),-2); //BBC校验体,即信息数据，到这一步后属性 $this->code 不再变化
    }

    /**
     * @description 读取信息ID 标识
     */
    private function getId(){
        $this->ID = substr($this->code,$this->startLength,4);
        $this->startLength +=4;
    }

    /**
     * @description 读取信息体属性
     */
    private function getAttr(){
        $this->attr['parent'] = substr($this->code,$this->startLength,4); //信息属性
        $this->startLength +=4;
        $this->attr['bin'] = str_pad(base_convert($this->attr['parent'], 16, 2), 16, '0', STR_PAD_LEFT); //信息体属性转成二进制
        $this->attr['codeBodyLength'] = substr($this->attr['bin'],0,10); //表示消息长度
        $this->attr['decrypt'] = substr($this->attr['bin'],10,3); //表示数据加密方式（3位都是0表示不加密）
        $this->attr['messageType'] = substr($this->attr['bin'],13,1); //长消息、短消息标识（1表示短消息，0表示长消息需分包）。短消息时，消息头中则不出现包总数、包序号两个数据项、为长消息时消息头中出现包总数、包序号项
        $this->attr['rest'] = substr($this->attr['bin'],14); //保留预留
        $this->messageType = $this->attr['messageType'];
    }

    /**
     * @description 读取信息SIM
     */
    private function getSim(){
        $this->SIM = substr($this->code,$this->startLength,12); // 信息SIM
        $this->startLength += 12;
    }

    /**
     * @description 读取信息流水号
     */
    private function getSerial(){
        $this->serial = substr($this->code,$this->startLength,4);
        $this->startLength += 4;
    }

    /**
     * @description 读取 信息包总数 和 信息包序号
     */
    private function getSumAndSequence(){
        if ($this->messageType == '0'){
            $this->sum = substr($this->code,$this->startLength,4); // 信息包总数
            $this->startLength += 4;
            $this->sequence = substr($this->code,$this->startLength,4); // 信息包序号
            $this->startLength += 4;
        }
    }

    /**
     * @description 读取 信息体
     */
    private function getCodeBody(){

        $this->codeBody = substr($this->code,$this->startLength); // 信息体，before that，we run code just for it.
        $this->startLength = 0;
    }

    /**
     * @description 读取 解析信息
     */
    private function getPack(){

        if (!$this->codeBody){
            return;
        }

        //解析
        $result = Switcher::run($this->ID,$this->codeBody,$this->sum);

        $this->pack = isset($result['pack']) ? $result['pack'] : [];
        $this->packTrans = isset($result['packTrans']) ? $result['packTrans'] : [];
        $this->className = isset($result['className']) ? $result['className'] : '00';
        $this->type = isset($result['type']) ? $result['type'] : '00';
        if (isset(self::$_allType[$this->type])){
            self::$_allType[$this->type]++;
        }else{
            self::$_allType[$this->type] = 1;
        }
    }

}
