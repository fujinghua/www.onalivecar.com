<?php

namespace app\common\controller;

use app\common\controller\BaseController;

class ApiController extends BaseController
{
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
        $this->init('user');

        $this->setSession('user');

//        // 登录检测,未登录，跳转到登录
//        $this->isUser();

        // 获取当前访问地址
        $currentUrl = $this->getCurrentUrl();

        //兼容iframe
        $url = $this->getUrl();
        // 权限检测，首页不需要权限
        if (!$this->accessCheck()) {
            if (!('api/index/index' === strtolower($currentUrl) || $url === '/')) {
                $this->error('拒绝访问', url('api/Index/index'), [], '1');
            }
        }
    }

    /**
     * @param null $key
     * @return string|array|null
     */
    protected function getUser($key = null)
    {
        return $this->getIdentity($key, 'user');
    }

    /**
     * @description 公共上传器
     * @param string $format
     * @param string $fileField
     * @return \think\response\Json
     */
    public function uploaderAction($format = '200*200', $fileField = 'file')
    {
        $config = ['format' => $format, 'fileField' => $fileField];
        $ret = \app\common\components\Uploader::action($config);
        return json($ret);
    }

}
