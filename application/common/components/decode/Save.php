<?php

namespace app\common\components\decode;

/**
 * Class Save
 * @package app\common\components\decode
 */
class Save{

    private static $_classNameConfig = [
        'Gps'=>'Gps', //GPS 数据包
        'PowerElectrical'=>'PowerElectrical', //动力蓄电池电气数据
        'PowerTemperature'=>'PowerTemperature', //动力蓄电池包温度数据
        'Vehicle'=>'Vehicle', //整车数据
        'VehiclePart'=>'VehiclePart', //汽车电机部分数据
        'FuelCell'=>'FuelCell', //燃料电池数据
        'EnginePart'=>'EnginePart', //汽车发动机部分数据
        'Extreme'=>'Extreme', //极值数据
        'FuelTruckCAN'=>'FuelTruckCAN ', //燃油车CAN静态数据
        'TerminalAlarm'=>'TerminalAlarm', //终端报警上报
    ];

    private static $_ModelNamespace = 'app\common\components\decode\model\\';

    /**
     * @description 存储运行
     * @param string $className
     * @param array $pack
     * @param array $source
     * @param string $modelNamespace
     * @return bool
     */
    public static function run($className, $pack, $source, $modelNamespace = ''){
        $ret = false;
        if (isset(self::$_classNameConfig[$className])){
            if ($modelNamespace === ''){
                $modelNamespace = self::$_ModelNamespace;
            }

            $modelClass = $modelNamespace.self::$_classNameConfig[$className];
            if (class_exists($modelClass)){
                /**
                 * @var \think\Model $model
                 */
                $model = new $modelClass();
                if (method_exists($model,'newPack')){
                    $ret = $model->newPack($pack,$source);
                    if ($ret){
                        file_put_contents(ROOT_PATH.'public/static/socket/save.log','于 '.date('Y-m-d H:i:s').' 存入一组数据成功'.PHP_EOL.PHP_EOL,FILE_APPEND);
                    }
                }
           }
        }
        return $ret;
    }
}
