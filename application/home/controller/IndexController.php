<?php

namespace app\home\controller;

use app\common\controller\HomeController;

/**
 * 默认控制器
 * @author Sir Fu
 */
class IndexController extends HomeController
{
    /**
     * 首页
     * @author Sir Fu
     */
    //测试下
    public function indexAction()
    {
        return view('index/index');
    }

    /**
     * view
     * @author Sir Fu
     */
    public function viewAction()
    {
        return view('index/view');
    }

    /**
     * 用户
     * @author Sir Fu
     */
    public function userAction()
    {
        return view('index/user');
    }

    /**
     * @description The APP 全局MISS路由，一个父级操作.
     * @return string
     */
    public function missAction()
    {
        return json(['status'=>'1','info'=>'无效请求']);
    }

    /**
     * @description The APP 全局MISS路由，一个父级操作.
     * @return string
     */
    public function missLogin()
    {
        return json([]);
    }
}
