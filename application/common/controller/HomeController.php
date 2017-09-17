<?php

namespace app\common\controller;

use app\common\controller\BaseController;

class HomeController extends BaseController
{

    //模块身份标识
    protected $identity = 'home';

    /**
     * 初始化方法
     * @author Sir Fu
     */
    protected function _initialize()
    {
        parent::_initialize();
        if ($this->getRequest()->ip() != '127.0.0.1') {
            config('app_debug', false);
        }

        // 初始化
        $this->init();

        // 设置SESSION
        $this->setSession();

        // 登录检测,未登录，跳转到登录
        $this->isUser();

        // 默认使用布局
        $this->useLayoutIndex();
    }


    public function useLayoutIndex(){
        // 要使用布局
        $this->view->engine->layout('common@layouts/home-index');
    }

    public function useLayoutMain(){
        // 不使用布局
        $this->view->engine->layout('common@layouts/home-main');
    }

    /**
     * @param null $key
     * @return string|array|null
     */
    protected function getUser($key = null)
    {
        return $this->getIdentity($key);
    }
}
