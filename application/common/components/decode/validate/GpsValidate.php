<?php

namespace app\common\components\decode\validate;

use app\common\validate\Validate;

class GpsValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'alarm_flag|报警标志'  =>  ['max'=>255],
        'status|状态'  =>  ['max'=>255],
        'lng|经度'  =>  ['max'=>255],
        'lat|纬度'  =>  ['max'=>255],
        'high|高度'  =>  ['max'=>255],
        'speed|速度'  =>  ['float'],
        'orientation|方向'  =>  ['float'],
        'is_delete|备注'  =>  ['number','between:1,99999999999'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'alarm_flag.max'  =>  ':attribute 长度不能超过255位',
        'status.max'  =>  ':attribute 长度不能超过255位',
        'lng.max'  =>  ':attribute 长度不能超过255位',
        'lat.max'  =>  ':attribute 长度不能超过255位',
        'high.max'  =>  ':attribute 长度不能超过255位',
        'is_delete.between'  =>  ':attribute 需要在 1-99999999999 之间',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['alarm_flag','status','lng','lat','high','speed','orientation','is_delete'],
        'save'  =>  [],
        'default'  =>  [],
    ];

}