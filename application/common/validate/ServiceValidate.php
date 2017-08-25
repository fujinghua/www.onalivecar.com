<?php

namespace app\common\validate;

use app\common\validate\Validate;

class ServiceValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'is_delete|标识','require|in:0,1',
    ];

    /**
     * @var array
     */
    protected $message = [
        'is_delete.require'  =>  ':attribute 不能为空',
        'is_delete.in'  =>  ':attribute 无效',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['is_delete'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}