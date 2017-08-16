<?php

namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class PowerTemperature
 * @package app\common\components\decode\replace
 */
class PowerTemperature extends Decipher {

    private $config = [
        'batteryNum'=>'2',
        'TemperatureInfo'=>'1',
        'batteryCode'=>'2',
        'batteryProbe'=>'4',
        'TemperatureTotal'=>'1',
    ];

    public static $description = [
        'batteryNum'=>'动力蓄电池总成个数',
        'TemperatureInfo'=>'动力蓄电池温度信息',
        'batteryCode'=>'电池总成号',
        'batteryProbe'=>'电池温度探针个数',
        'TemperatureTotal'=>'电池总各温度探针检测到的温度值',
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