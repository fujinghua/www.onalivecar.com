<?php

namespace app\common\validate;

use app\common\validate\Validate;

class CarValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
    ];

    /**
     * @var array
     */
    protected $message = [
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