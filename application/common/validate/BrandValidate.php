<?php

namespace app\common\validate;

use app\common\validate\Validate;

class BrandValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'name|品牌名称','require',
    ];

    /**
     * @var array
     */
    protected $message = [
        'name.require'  =>  ':attribute 不能为空',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['name'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}