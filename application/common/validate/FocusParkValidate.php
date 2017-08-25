<?php

namespace app\common\validate;

use app\common\validate\Validate;

class FocusParkValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'name|配置标识','require|unique:config,name',
        'value|配置值','require',
    ];

    /**
     * @var array
     */
    protected $message = [
        'name.require'  =>  ':attribute 不能为空',
        'name.unique'  =>  ':attribute 已存在',
        'value.require'  =>  ':attribute 无效',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['name','value'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}