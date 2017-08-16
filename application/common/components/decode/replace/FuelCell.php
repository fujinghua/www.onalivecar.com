<?php
/*
	燃料电池数据
*/
namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class FuelCell
 * @package app\common\components\decode\replace
 */
class FuelCell extends Decipher {

    private $config = [
        'voltage'=>'4',
        'current'=>'4',
        'consumptionRate'=>'4',
        'probeCount'=>'4',
        'ProbTemperature'=>'2',
    ];

    public static $description = [
        'voltage'=>'燃料电池电压',
        'current'=>'燃料电池电流',
        'consumptionRate'=>'燃料消耗率',
        'probeCount'=>'燃料电池温度探针总数',
        'ProbTemperature'=>'探针温度值',
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