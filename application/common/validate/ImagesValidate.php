<?php

namespace app\common\validate;

use app\common\validate\Validate;

class ImagesValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'target_id|外键','require|in:0,1',
        'type|类型','require|in:0,1',
    ];

    /**
     * @var array
     */
    protected $message = [
        'target_id.require'  =>  ':attribute 不能为空',
        'type.require'  =>  ':attribute 不能为空',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['target_id','type'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}