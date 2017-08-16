<?php
/*
	极值数据
*/
namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class Extreme
 * @package app\common\components\decode\replace
 */
class Extreme extends Decipher {

    private $config = [
        'highAssemblyNumber'=>'2',
        'highMonomerCode'=>'2',
        'highMonomerVoltage'=>'4',
        'lowAssemblyNumber'=>'2',
        'lowMonomerCode'=>'2',
        'lowMonomerVoltage'=>'4',
        'temperatureAssemblyNumber'=>'2',
        'high_temperature_in_assembly'=>'2',
		'high_temperature'=>'2',
		'low_temperature_in_number'=>'2',
		'lowtemperatureassembly'=>'2',
		'low_temperature_in_assembly'=>'2',
    ];

    public static $description = [
        'highAssemblyNumber'=>'最高电压电池总成号',
        'highMonomerCode'=>'最高电压电池单体代号',
        'highMonomerVoltage'=>'电池单体电压最高值',
        'lowAssemblyNumber'=>'最低电压电池总成号',
        'lowMonomerCode'=>'最低电压电池单体代号',
        'lowMonomerVoltage'=>'电池单体电压最低值',
        'temperatureAssemblyNumber'=>'蓄电池中最高温度总成号',
        'high_temperature_in_assembly'=>'蓄电池中最高温度探针在总成中代号',
		'high_temperature'=>'蓄电池中最高温度值',
		'low_temperature_in_number'=>'蓄电池中最低温度探针序号',
		'lowtemperatureassembly'=>'蓄电池中最低温度总成号',
		'low_temperature_in_assembly'=>'蓄电池中最低温度探针在总成中代号',
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