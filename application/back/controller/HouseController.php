<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\manage\model\BuildingBase;
use app\manage\model\House;
use app\manage\model\HandHouse;
use app\manage\model\Images;
use app\manage\model\City;

class HouseController extends BackController
{

    /**
     * @description 显示资源列表 //房源清单
     * @param string $name
     * @param string $type
     * @param string $app
     * @return \think\Response
     */
    public function superAction($name = null, $type = null,$app = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $param = ['name'=>'','type'=>'','app'=>''];
        $model = House::load();
        if ($name && $name != ''){
            $param['name'] = trim($name);
            $nameWhere = ' `name` like '.' \'%'.$name.'%\''.' or `title` like '.' \'%'.$name.'%\' ';
            $model->where($nameWhere);
        }
        $cityLists = \app\manage\model\City::getCityList();

        $list = $model->where($where)->order('id DESC')->paginate($each);

        $this->assign('param', $param);
        $this->assign('cityLists', $cityLists);
        $this->assign('list', $list);
        return view('house/super');
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function servicesAction()
    {
        //
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function transferAction()
    {
        //
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function receiveAction()
    {
        //
    }

    /**
     * @description 显示资源列表
     * @param string $name
     * @param string $type
     * @param string $app
     * @return \think\Response
     */
    public function indexAction($name = null, $type = null,$app = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $model = House::load();
        $name = trim($this->getRequest()->request('name'));
        if ($name != ''){
            $where[] = ['exp',"name like '%".$name."%' or `title` like '%".$name."%' "];
        }
        $cityLists = \app\manage\model\City::getCityList();

        $houseType = trim($this->getRequest()->request('houseType'));
        if ($houseType != ''){
            $where['houseType'] = ['in',$houseType];
        }

        $list = $model->where($where)->order('id DESC')->paginate($each);

        $this->assign('lang', House::Lang());
        $this->assign('cityLists', $cityLists);
        $this->assign('list', $list);
        return view('house/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new House();
        if ($this->getRequest()->isPost()){
            //
            $base = $model->filter($_POST);
            $base['is_delete'] = '1';
            $base['created_by'] = $this->getIdentity('id');
            $base['updated_at'] = date('Y-m-d H:i:s');
            $base['created_at'] = date('Y-m-d H:i:s');
            $base['building_base_id'] = isset($base['building_base_id']) ? trim($base['building_base_id']) : '0';
            if ($base){
                $validate = House::getValidate();
                $validate->scene('create');
                if ($validate->check($base) && $model->save($base)){
                    $prefix = '/static/uploads/house/'.$model->id.'/';
                    //
                    $to = $prefix.pathinfo($base['url'],PATHINFO_BASENAME);
                    $from = $base['url'];
                    $model->url = $to;
                    $this->copy($from,$to);
                    $icon = pathinfo($from,PATHINFO_DIRNAME).'/'.pathinfo($from,PATHINFO_FILENAME).'_icon.'.pathinfo($from,PATHINFO_EXTENSION);
                    $to = $prefix.pathinfo($icon,PATHINFO_BASENAME);
                    $model->url_icon = $to;
                    $this->copy($icon,$to);
                    $model->isUpdate(true)->save();

                    $ImagesModel = new Images();
                    $urls = isset($_POST['detail']) ? explode('|',$_POST['detail']) : [];
                    $images = [];
                    foreach ($urls as $url){
                        $item = [];
                        $item['target_id'] = $model->id;
                        $item['type'] = '2';
                        $to = $prefix.pathinfo($url,PATHINFO_BASENAME);
                        $item['url'] = $to;
                        $this->copy($url,$to);
                        $icon = pathinfo($url,PATHINFO_DIRNAME).'/'.pathinfo($url,PATHINFO_FILENAME).'_icon.'.pathinfo($url,PATHINFO_EXTENSION);
                        $to = $prefix.pathinfo($icon,PATHINFO_BASENAME);
                        $item['url_icon'] = $to;
                        $this->copy($icon,$to);
                        $item['url_title'] = $model->title;
                        $item['created_at'] = $model->created_at;
                        $images[] = $item;
                    }
                    $ImagesModel->saveAll($images);
                    $this->success('添加成功','create',$model,1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        $cityLists = City::getCityList();
        return view('house/create',['meta_title'=>'添加房源','model'=>$model,'lang'=>House::Lang(),'cityLists'=>$cityLists]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function viewAction($id)
    {
        $this->assign('meta_title', "详情");
        $model = House::load()->where(['id'=>$id])->find();
        return view('house/view',['model'=>$model]);
    }

    /**
     * 保存更新的资源
     *
     * @param  int  $id
     * @return \think\Response|string
     */
    public function updateAction($id)
    {
        $where = ['is_delete'=>'1'];
        $model = new House();
        $lists = House::getTypeList();
        $model = House::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['House']) ? $_POST['House'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = House::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && House::update($data,['id'=>$id])){
                    $this->success('更新成功','create','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('house/update',['meta_title'=>'编辑标签','model'=>$model,'lists'=>$lists]);
    }

    /**
     * 删除指定资源
     * @return \think\response\Json
     */
    public function deleteAction()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : [];
        $ret = ['status'=>0,'info'=>'删除失败'];
        if ($this->getRequest()->isAjax()){
            $where['id'] = ['in',implode(',',$id)];
            $result = House::update(['is_delete'=>'0'],$where);
            if ($result){
                $ret = ['status'=>1,'info'=>'删除成功'];
            }
        }
        if (empty($id)){
            $ret = ['status'=>1,'info'=>'删除成功'];
        }
        return json($ret);
    }
}
