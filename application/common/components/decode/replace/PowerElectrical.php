<?php

namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * @description 动力蓄电池电气数据
 * Class PowerElectrical
 * @package app\common\components\decode\replace
 */
class PowerElectrical extends Decipher {

    private $config = [
        'sum'=>'2',
        'serial '=>'2',
        'PowerVoltage'=>'4',
        'PowerCurrent'=>'4',
        'total'=>'4',
        'startConnect'=>'4',
        'totalConnect'=>'2',
        'itemVoltage'=>'n',
    ];

    private static $description = [
        'sum'=>'动力蓄电池总成个数',
        'serial'=>'电池总成号',
        'PowerVoltage'=>'动力蓄电池电压',
        'PowerCurrent'=>'动力蓄电池电流',
        'total'=>'单体蓄电池总数',
        'startConnect'=>'本帧起始电池序号',
        'totalConnect'=>'本帧单体电池总数',
        'itemVoltage'=>'单体蓄电池电压值',
    ];

    private $func = [
    ];

    /**
     * @description 读取 解析 //第二份协议
     */
    protected function getPack(){
        if ($this->codeBody && is_string($this->codeBody)){
            foreach ($this->config as $key => $value){
                if (stristr($value,'n') !== false){
                    $this->pack[$key] = substr($this->codeBody,$this->startLength);
                    break;
                }
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

    protected function lat($value){
        return base_convert($value, 16, 10)/1000000;
    }

}