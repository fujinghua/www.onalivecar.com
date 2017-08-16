<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace app\common\controller;

use app\common\controller\BaseController;

class BackController extends BaseController
{
    /**
     * 初始化方法
     * @author Sir Fu
     */
    protected function _initialize()
    {
        parent::_initialize();

        $this->setSession();

        // 登录检测,未登录，跳转到登录
        $this->isUser();

        // 获取当前访问地址
        $currentUrl = $this->getCurrentUrl();

        //模板转换
        if ('back/index/index' === strtolower($currentUrl)){
            $this->view->engine->layout('common@layouts/back-index');
        }

        //兼容iframe
        $url = $this->getUrl();
        // 权限检测，首页不需要权限
        if (!$this->accessCheck()) {
            if(!('back/index/index' === strtolower($currentUrl) || $url === '/back')){
                $this->error('拒绝访问', url('back/Index/index'),[],'1');
            }
        }
    }
}
