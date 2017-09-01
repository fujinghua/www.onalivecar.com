<?php

namespace app\common\controller;

use app\common\controller\BaseController;

class ApiController extends BaseController
{

    //模块身份标识
    protected $identity = 'api';

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

        // 获取当前用户属性

    }

    /**
     * @param null $key
     * @return string|array|null
     */
    protected function getUser($key = null)
    {
        return $this->getIdentity($key);
    }

    /**
     * 执行此方法时，需要确定对应模块配置相应的登陆配置不然读取默认。默认\app\api\model\User::isGuest 验证
     */
    protected function isUser()
    {
        if (!$this->isGuest()) {
            //还没登录跳转到登录页面
            $this->throwHttp(['code'=>'801','msg'=>'未登录']);
        }
    }

    /**
     * @description before action function
     * if is a client return true, or return false;
     * @return bool
     */
    protected function isGuest()
    {
        $ret = true;
        //用户登录检测
        $identity = $this->identity;
        $model = config($identity . '.default_model');
        if (class_exists($model)){
            $uid = $model::isGuest();
            return $uid ? $uid : false;
        }
        return $ret;
    }

    /**
     * 使用返回数据且中断程序
     * @param array $options
     */
    protected function throwHttp($options = []){
        $ret =  json_encode($options);
        exit($ret);
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
