<?php

namespace app\common\components\decode\validate;

use app\common\validate\Validate;

class PowerElectricalValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'power_voltage|动力蓄电池电压'  =>  ['max'=>255],
        'power_current|动力蓄电池电流'  =>  ['max'=>255],
        'start_connect|本帧起始电池序号'  =>  ['max'=>255],
        'total_connect|本帧单体电池总数'  =>  ['max'=>255],
        'item_voltage|单体蓄电池电压值'  =>  ['max'=>255],
        'sum_power|动力蓄电池总成个数'  =>  ['number','between:1,99999999999'],
        'total|单体蓄电池总数'  =>  ['number','between:1,99999999999'],
        'is_delete|备注'  =>  ['number','between:1,99999999999'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'power_voltage.max'  =>  ':attribute 长度不能超过255位',
        'power_current.max'  =>  ':attribute 长度不能超过255位',
        'start_connect.max'  =>  ':attribute 长度不能超过255位',
        'total_connect.max'  =>  ':attribute 长度不能超过255位',
        'item_voltage.max'  =>  ':attribute 长度不能超过255位',
        'sum_power.between'  =>  ':attribute 需要在 1-99999999999 之间',
        'total.between'  =>  ':attribute 需要在 1-99999999999 之间',
        'is_delete.between'  =>  ':attribute 需要在 1-99999999999 之间',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['power_voltage','power_current','start_connect','total_connect','item_voltage','sum_power','total','is_delete'],
        'save'  =>  [],
        'default'  =>  [],
    ];

}