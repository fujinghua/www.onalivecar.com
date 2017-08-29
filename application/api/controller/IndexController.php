<?php

namespace app\api\controller;

use app\common\controller\ApiController;

/**
 * 默认控制器
 * @author Sir Fu
 */
class IndexController extends ApiController
{
    /**
     * 默认方法
     * @author Sir Fu
     */
    public function indexAction()
    {
        return json(['status'=>'1','info'=>'API']);
    }

    /**
     * @description The APP 全局MISS路由，一个父级操作.
     * @return string
     */
    public function missAction()
    {
        return json(['status'=>'1','info'=>'无效请求']);
    }

}
