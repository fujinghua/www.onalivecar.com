<?php

namespace app\back\validate;

use app\common\validate\Validate;

class BuildingContentValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'building_base_id|楼盘ID','require',
    ];

    /**
     * @var array
     */
    protected $message = [
        'building_base_id.require'  =>  ':attribute 不能为空',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['building_base_id'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}