<?php

namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class Vehicle
 * @package app\common\components\decode\replace
 */
class Vehicle extends Decipher {

    private $config = [
        'warning'=>'8',
        'status'=>'8',
        'lat'=>'8',
        'lng'=>'8',
        'height'=>'4',
        'speed'=>'4',
        'direction'=>'4',
        'time'=>'12',
    ];

    public static $description = [
        'warning'=>'报警标志',
        'status'=>'状态',
        'lat'=>'纬度',
        'lng'=>'经度',
        'height'=>'高程',
        'speed'=>'速度',
        'direction'=>'方向',
        'time'=>'时间',
    ];

    private $func = [
//        'warning'=>'warning',
//        'status'=>'status',
        'lat'=>'lat',
        'lng'=>'lng',
        'height'=>'height',
        'speed'=>'speed',
        'direction'=>'direction',
        'time'=>'time',
    ];

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

    protected function lat($value){
        return base_convert($value, 16, 10)/1000000;
    }

    protected function lng($value){
        return base_convert($value, 16, 10)/1000000;
    }

    protected function height($value){
        return base_convert($value, 16, 10)/10;
    }

    protected function speed($value){
        return base_convert($value, 16, 10)/10;
    }

    protected function direction($value){
        return base_convert($value, 16, 10)/10;
    }

    protected function time($value){
        $time = str_split($value,1);
        $newTime = '';
        foreach ($time as $tv){
            $newTime .= base_convert($tv, 16, 10);
        }
        $newTime = str_split($newTime,2);
        return join('-',$newTime);
    }
}