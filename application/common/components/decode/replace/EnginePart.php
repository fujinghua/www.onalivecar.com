<?php
/*
	汽车发动机部分数据
*/
namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class EnginePart
 * @package app\common\components\decode\replace
 */
class EnginePart extends Decipher {

    private $config = [
        'state'=>'2',
        'crankshaftSpeed'=>'4',
        'consumptionRate'=>'4',
    ];

    public static $description = [
        'state'=>'发动机状态',
        'crankshaftSpeed'=>'曲轴转速',
        'consumptionRate'=>'燃料消耗率',
    ];

    private $func = [];

    /**
     * @description 读取 解析
     */
    protected function getPack(){
        if ($this->codeBody && is_string($this->codeBody)){
            foreach ($this->config as $key => $value){
                $this->pack[$key] = substr($this->codeBody,$this->startLength,$value);
                $this->startLength += $value;
            }
        }
    }

    /**
     * @description 解析信息包
     */
    protected function getPackTrans(){
        $arr = [];
        if (is_array($this->pack)){
            foreach ($this->pack as $k => $v) {
                if (isset($this->func[$k])){
                    $function = $this->func[$k];
                    if (method_exists($this,$function)){
                        $arr[$k] = $this->$function($v);
                    }
                }else{
                    $arr[$k] = $this->getValue($v);
                }
            }
        }
        $this->packTrans = $arr;
    }

    protected function beforeAction(){}

    protected function afterAction(){}

    protected function getValue($value){
        return base_convert($value, 16, 10);
    }

    public static function getDescription(){
        return self::$description;
    }
}