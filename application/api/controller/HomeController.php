<?php

namespace app\api\controller;

use app\common\controller\ApiController;

/**
 * 默认控制器
 * @author Sir Fu
 */
class HomeController extends ApiController
{
    /**
     * 默认方法
     * @author Sir Fu
     */
    //测试下
    public function indexAction()
    {
        $userId = $this->context->userId;
        $data = [
            'banner' => []
        ];
        return json(['status' => '1', 'info' => 'API']);
    }


}
