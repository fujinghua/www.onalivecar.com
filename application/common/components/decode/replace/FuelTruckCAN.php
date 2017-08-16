<?php
/*
	燃油车CAN静态数据
*/
namespace app\common\components\decode\replace;

use app\common\components\decode\Decipher;

/**
 * Class FuelTruckCAN
 * @package app\common\components\decode\replace
 */
class FuelTruckCAN extends Decipher {

    private $config = [
        'batteryVoltage'=>'4',
		'totalMileageCategory'=>'2',
		'Cumulative mileage'=>'8',
		'Cumulative total fuel consumption'=>'8',
		'faultLampStatus'=>'2',
		'numberoOfFaultCodes'=>'2',
		'engineSpeed'=>'4',
		'vehicleSpeed'=>'2',
		'inletTemperature'=>'2',
		'coolantTemperature'=>'2',
		'vehicleAmbientTemperature'=>'2',
		'manifoldPressure'=>'2',
		'fuelPressure'=>'4',
		'atmosphericPressure'=>'2',
		'airFlow'=>'4',
		'valvePositionSensor'=>'4',
		'acceleratorPedalPosition'=>'4',
		'engineRunningTime'=>'4',
		'faultMileage'=>'8',
		'remainingOil'=>'4',
		'engineLoad'=>'2',
		'longTermFuelCorrection'=>'4',
		'sparkAdvanceAngle'=>'4',
		'instrumentTotalMileage'=>'8',
		'totalVehicleRunningTime'=>'8',
		'totalFuelConsumption'=>'8',

    ];

    public static $description = [
        'batteryVoltage'=>'电瓶电压',
		'totalMileageCategory'=>'总里程类别',
		'Cumulative mileage'=>'累计里程',
		'Cumulative total fuel consumption'=>'累计总耗油量',
		'faultLampStatus'=>'故障灯状态',
		'numberoOfFaultCodes'=>'故障码个数',
		'engineSpeed'=>'发动机转速',
		'vehicleSpeed'=>'车辆速度',
		'inletTemperature'=>'进气口温度',
		'coolantTemperature'=>'冷却液温度',
		'vehicleAmbientTemperature'=>'车辆环境温度',
		'manifoldPressure'=>'进气歧管压力',
		'fuelPressure'=>'燃油压力',
		'atmosphericPressure'=>'大气压力',
		'airFlow'=>'空气流量',
		'valvePositionSensor'=>'气门位置传感器',
		'acceleratorPedalPosition'=>'油门踏板位置',
		'engineRunningTime'=>'发动机运行时间',
		'faultMileage'=>'故障行驶里程',
		'remainingOil'=>'剩余油量',
		'engineLoad'=>'发动机负荷',
		'longTermFuelCorrection'=>'长期燃油修正',
		'sparkAdvanceAngle'=>'点火提前角',
		'instrumentTotalMileage'=>'仪表总里程',
		'totalVehicleRunningTime'=>'车辆总运行时间',
		'totalFuelConsumption'=>'仪表总耗油量',
    ];

    private $func = [
        'batteryVoltage'=>'batteryVoltage',
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

    protected function batteryVoltage($value){
        return base_convert($value, 16, 10)/100;
    }
    protected function airFlow($value){
        return base_convert($value, 16, 10)/10;
    }

    protected function valvePositionSensor($value){
        return base_convert($value, 16, 10)/10;
    }
    protected function acceleratorPedalPosition($value){
        return base_convert($value, 16, 10)/10;
    }
    protected function remainingOil($value){
        return base_convert($value, 16, 10)/10;
    }
    protected function longTermFuelCorrection($value){
        return base_convert($value, 16, 10)/10;
    }
    protected function sparkAdvanceAngle($value){
        return (base_convert($value, 16, 10)/10)-64;
    }

}