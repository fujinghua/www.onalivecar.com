<?php

namespace app\back\controller;

use app\common\controller\BackController;
use think\Request;

class MaintainController extends BackController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function indexAction()
    {
        //
    }

    public function companyAction(){}

    /**
     * 网站主题维护
     *
     * @return \think\Response
     */
    public function themeAction()
    {
        //
    }

    /**
     * 数据库维护
     *
     * @return \think\Response
     */
    public function databaseAction()
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @return \think\Response
     */
    public function pageAction()
    {
        //
    }

    /**
     * 删除缓存
     */
    public function cacheAction()
    {
        $ret = ['status'=>'0','info'=>'清楚失败'];
        if ($this->getIdentity('department_id') == '1'){
            $this->clearCache();
            $this->clearTemp();
            $ret = ['status'=>'1','info'=>'清楚成功'];
        }
        return json($ret);
    }
}
