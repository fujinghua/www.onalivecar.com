<?php

namespace app\common\components\decode;

/**
 * Class Switcher
 * @package app\common\components\decode
 */
class Switcher{

    private static $_type;
    private static $_parentType = ['0200'=>'0', '0f01'=>'1'];
    private static $_classNameConfig = [
        '0200'=>'Gps', //GPS 数据包
        '01'=>'PowerElectrical', //动力蓄电池电气数据
        '02'=>'PowerTemperature', //动力蓄电池包温度数据
        '03'=>'Vehicle', //整车数据
        '04'=>'VehiclePart', //汽车电机部分数据
        '05'=>'FuelCell', //燃料电池数据
        '06'=>'EnginePart', //汽车发动机部分数据
        '08'=>'Extreme', //极值数据
        '0a'=>'FuelTruckCAN ', //燃油车CAN静态数据
        '0b'=>'TerminalAlarm', //终端报警上报
    ];

    /**
     * @description 转换器
     * @param $id
     * @param $codeBody
     * @param $sum
     * @param string $namespace
     * @return array
     */
    public static function run($id, $codeBody,$sum,$namespace = 'app\common\components\decode\replace\\'){
        $ret = [];
        $id = strtolower($id);
        if (isset(self::$_parentType[$id])){
            self::$_type = self::$_parentType[$id];
        }
        switch (self::$_type){
            case '0':{
                $key = $id;
            }break;
            case '1':{
                $key = strtolower(substr($codeBody,0,2));
                $codeBody = substr($codeBody,2);
            }break;
            default:{ return $ret; }break;
        }
        if (isset(self::$_classNameConfig[$key])){
            $class = $namespace.self::$_classNameConfig[$key];
            if (class_exists($class)){
                /**
                 * @var \app\common\components\decode\IDecipher $model
                 */
                $model = new $class();
                if (method_exists($model,'getData')){
                    $result = $model->getData($codeBody,$sum);
                    $result['className'] = self::$_classNameConfig[$key];
                    $result['type'] = $key;
                    $ret = $result;
                }
            }
        }
        return $ret;
    }
}
