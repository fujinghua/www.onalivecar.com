<?php

namespace app\common\validate;

use app\common\validate\Validate;

class ContactValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'is_delete|标签','require|in:0,1',
        'name|您的称呼','require',
        'contact|联系方式','require',
    ];

    /**
     * @var array
     */
    protected $message = [
        'is_delete.require'  =>  ':attribute不能为空',
        'is_delete.in'  =>  ':attribute无效',
        'name.require'  =>  ':attribute不能为空',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['is_delete','name'],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}