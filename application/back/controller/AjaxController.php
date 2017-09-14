<?php

namespace app\back\controller;

use app\common\controller\BackController;

class AjaxController extends BackController
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
     * @description 获取部门
     * @param null $name
     * @return \think\response\Json
     */
    public function getDepartmentAction($name=null)
    {
        $ret = [];
        $where = ['is_delete'=>'1'];
        $model = \app\common\model\Department::load();
        if ($name || ($name = $this->getRequest()->request('name'))){
            if ($name != ''){
                $nameWhere = " `name` like '%".$name."%' ";
                $model->where($nameWhere);
            }
        }
        $list = $model->where($where)->limit(20)->select();
        if (!empty($list)){
            foreach ($list as $item){
                $ret[] = ['id'=>$item['id'],'name'=>$item['name']];
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
     * @return \think\response\Json
     */
    public function getBackUserAction(){
        $ret = [];
        $where = ['is_delete'=>'1'];
        $model = \app\common\model\BackUser::load();
        $name = trim($this->getRequest()->request('name'));
        if ($name != ''){
            $where[] = ['exp'," `username` like '%".$name."%' "];
        }
        $list = $model->where($where)->limit(20)->select();
        if (!empty($list)){
            foreach ($list as $item){
                $ret[] = ['id'=>$item['id'],'name'=>$item['username']];
            }
        }
        return json($ret);
    }

    /**
     * @description 获取
     * @param null $name
     * @return \think\response\Json
     */
    public function getCarAction($name=null)
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
     * @description 获取分类
     * @param null $pid
     * @param null $level
     * @return \think\response\Json
     */
    public function getCateAction($pid=null,$level=null)
    {
        $ret = [];
        $where = [];
        $model = \app\common\model\Cate::load();
        if ($pid || ($pid = $this->getRequest()->request('pid'))){
            if ($pid != ''){
                $where['pid'] = $pid;
            }
        }
        if ($level || ($level = $this->getRequest()->request('level'))){
            if ($level != ''){
                $where['level'] = $level;
            }
        }
        $list = $model->where($where)->order(['level'=>'ASC','id'=>'ASC','`order`'=>'ASC'])->column('name,pid,id','id');
        if (!empty($list)){
            foreach ($list as $key => $item){
                $ret[] = ['id'=>$key,'name'=>$item['name']];
            }
        }
        return json($ret);
    }

    /**
     * @description 获取特征量
     * @param null $pid
     * @param null $level
     * @return \think\response\Json
     */
    public function getCatePropAction($pid=null,$level=null)
    {
        $ret = [];
        $where = [];
        $model = \app\common\model\CateProp::load();
        if ($pid || ($pid = $this->getRequest()->request('pid'))){
            if ($pid != ''){
                $where['pid'] = $pid;
            }
        }
        if ($level || ($level = $this->getRequest()->request('level'))){
            if ($level != ''){
                $where['level'] = $level;
            }
        }
        $list = $model->where($where)->order(['level'=>'ASC','id'=>'ASC','`order`'=>'ASC'])->column('name,id,cid,pid','id');
        if (!empty($list)){
            foreach ($list as $key => $item){
                $ret[] = ['id'=>$key,'name'=>$item['name']];
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

    /**
     * @description 手机
     * @return \think\response\Json
     */
    public function phoneAction()
    {
        $ret = ['code'=>'1','param'=>$_REQUEST];
        return json($ret);
    }

}