<?php

namespace app\home\controller;

use app\common\controller\HomeController;

class AjaxController extends HomeController
{

    /**
     * @description 获取城市
     * @param null $level
     * @param null $name
     * @return \think\response\Json
     */
    public function getCityAction($level=null,$name=null)
    {
        $ret = [];
        $where = [];
        if ($name|| ($name = $this->getRequest()->get('name'))){
            $where = array_merge($where,['name'=>$name]);
        }
        if ($level|| ($level = $this->getRequest()->get('level'))){
            $where = array_merge($where,['level'=>$level]);
        }
        $cityList = \app\common\model\City::getCityList($where);
        if (!empty($cityList)){
            foreach ($cityList as $key => $value){
                $ret[] = ['id'=>$key,'name'=>$value];
            }
        }
        return json($ret);
    }

    /**
     * @return \think\response\Json
     */
    public function getServiceAction(){
        $ret = [];
        $where = ['t.is_delete'=>'1', 'b.is_delete'=>'1',];
        $model = \app\common\model\Service::load();
        $name = trim($this->getRequest()->request('name'));
        if ($name != ''){
            $where[] = ['exp'," `b`.`username` like '%".$name."%' "];
        }

        $list = $model->alias('t')
            ->join(\app\common\model\BackUser::tableName().' b','t.back_user_id = b.id','left')
            ->where($where)
            ->field('t.*,b.id,b.id as serviceId,b.username as serviceName')
            ->order('t.order')
            ->limit(20)
            ->select();
        if (!empty($list)){
            foreach ($list as $item){
                $ret[] = ['id'=>$item['serviceId'],'name'=>$item['serviceName']];
            }
        }
        return json($ret);
    }

    /**
     * @return \think\response\Json
     */
    public function getClientAction(){
        $ret = [];
        $where = ['is_delete'=>'1'];
        $model = \app\common\model\Client::load();
        $name = trim($this->getRequest()->request('name'));
        if ($name != ''){
            $where[] = ['exp'," `userName` like '%".$name."%' "];
        }
        $list = $model->where($where)->limit(20)->select();
        if (!empty($list)){
            foreach ($list as $item){
                $ret[] = ['id'=>$item['id'],'name'=>$item['userName']];
            }
        }
        return json($ret);
    }

    /**
     * @description 获取楼盘
     * @param null $name
     * @return \think\response\Json
     */
    public function getBuildingBaseAction($name=null)
    {
        $ret = [];
        $where = ['is_delete'=>'1'];
        $model = \app\common\model\BuildingBase::load();
        if ($name || ($name = $this->getRequest()->request('name'))){
            if ($name != ''){
                $nameWhere = " `title` like '%".$name."%' or `titlePinyin` like '%".$name."%'";
                $model->where($nameWhere);
            }
        }
        $list = $model->where($where)->limit(20)->select();
        if (!empty($list)){
            foreach ($list as $item){
                $ret[] = ['id'=>$item['id'],'name'=>$item['title']];
            }
        }
        return json($ret);
    }

    /**
     * @description 上传器
     * @return \think\response\Json
     */
    public function uploaderAction()
    {
        $config = ['format'=>'200*200'];
        if (isset($_REQUEST['file'])){
            $config['fileField'] = $_REQUEST['file'];
        }
        $ret = \app\common\components\Uploader::action($config);
        return json($ret);
    }

}