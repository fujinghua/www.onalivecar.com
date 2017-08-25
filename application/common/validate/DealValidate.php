<?php

namespace app\common\validate;

use app\common\validate\Validate;

class DealValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'client_id|客户' => ['require','exist:client,id'],
        'belongUserId|权属人' => ['require','exist:back_user,id'],
        'type|类型' => ['require'],
        'houseNo|房号' => ['require'],
        'area|面积' => ['require'],
        'eachPrice|单价' => ['require'],
        'total|总价' => ['require'],
        'signDate|认购时间' => ['require'],
        'recepter|签约者' => ['require'],
        'receptPhone|签约者手机' => ['require'],
        'status|订单状态' => ['require'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'client_id.require'  =>  ':attribute不能为空',
        'client_id.exist'  =>  ':attribute不存在',
        'belongUserId.require'  =>  ':attribute不能为空',
        'belongUserId.exist'  =>  ':attribute不存在',
        'type.require'  =>  ':attribute不能为空',
        'name.require'  =>  ':attribute不能为空',
        'houseNo.require'  =>  ':attribute不能为空',
        'area.require'  =>  ':attribute不能为空',
        'eachPrice.require'  =>  ':attribute不能为空',
        'total.require'  =>  ':attribute不能为空',
        'signDate.require'  =>  ':attribute不能为空',
        'recepter.require'  =>  ':attribute不能为空',
        'receptPhone.require'  =>  ':attribute不能为空',
        'status.require'  =>  ':attribute不能为空',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  [
            'client_id',
            'type',
            'name',
            'houseNo',
            'area',
            'eachPrice',
            'total',
            'signDate',
            'recepter',
            'receptPhone',
        ],
        'update'  =>  [],
        'save'  =>  [],
        'not'  =>  [],
    ];

}