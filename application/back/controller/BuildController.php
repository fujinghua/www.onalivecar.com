<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\back\model\BuildingBase;
use app\back\model\City;
use app\back\model\BuildingDetail;
use app\back\model\BuildingContent;
use app\back\model\Images;

class BuildController extends BackController
{

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['t.is_delete'=>'1'];
        $each = 12;
        /**
         * @var $model \app\back\model\BuildingBase
         */
        $model = BuildingBase::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"t.title like '%".$key."%' "];
        }
        $address = trim($request->request('address'));
        if ($address != ''){
            $where[] = ['exp',"t.address like '%".$address."%' "];
        }
        $cityLists = City::getCityList();
        $city = trim($request->request('city'));
        if ($city != ''){
            if (in_array($city,array_keys($cityLists))){
                $where =  array_merge($where, ['city_id'=>$city]);
            }
        }

        $list = $model->alias('t')
            ->join(BuildingDetail::tableName().' b','t.id = b.building_base_id','left')
            ->where($where)
            ->field('t.*,b.building_base_id')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "楼盘清单");
        $this->assign('lang', BuildingBase::Lang());
        $this->assign('cityLists', $cityLists);
        $this->assign('list', $list);
        return view('build/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $cityLists = City::getCityList();
        $detailModel = new BuildingDetail();
        if ($this->getRequest()->isPost()){
            //
            $model = new BuildingBase();
            $base = $model->filter($_POST);
            $base['is_delete'] = '1';
            $base['updated_at'] = date('Y-m-d H:i:s');
            $base['created_at'] = date('Y-m-d H:i:s');
            $base['created_by'] = $this->getIdentity('id');
            if(empty($base['titlePinyin'])){
                $helper = self::getHelper();
                $pinyin = $helper::getZhPinYin($base['title']);
                $base['titlePinyin'] = implode('',explode(' ',$pinyin));
            }
            $base['titlePinyin'] = strtolower($base['titlePinyin']);
            if ($base){
                $validate = BuildingBase::getValidate();
                $validate->scene('create');
                if ($validate->check($base) && $model->save($base)){
                    $prefix = '/static/uploads/buildingDetail/'.$model->id.'/';
                    //
                    $detail = $detailModel->filter($_POST);
                    $detail['building_base_id'] = $model->id;
                    $to = $prefix.pathinfo($detail['url'],PATHINFO_BASENAME);
                    $from = $detail['url'];
                    $detail['url'] = $to;
                    $this->copy($from,$to);
                    $icon = pathinfo($from,PATHINFO_DIRNAME).'/'.pathinfo($from,PATHINFO_FILENAME).'_icon.'.pathinfo($from,PATHINFO_EXTENSION);
                    $to = $prefix.pathinfo($icon,PATHINFO_BASENAME);
                    $detail['url_icon'] = $to;
                    $this->copy($icon,$to);
                    $detail['updated_at'] = date('Y-m-d H:i:s');
                    $detail['created_at'] = date('Y-m-d H:i:s');
                    //
                    $contentModel = new BuildingContent();
                    $content = $contentModel->filter($_POST);
                    $content['building_base_id'] = $model->id;
                    $ImagesModel = new Images();
                    $urls = isset($_POST['detail']) ? explode('|',$_POST['detail']) : [];
                    $images = [];
                    $item = [];
                    $item['target_id'] = $model->id;
                    $item['type'] = '1';
                    $item['url'] = $detail['url'];
                    $item['url_icon'] = $detail['url_icon'];
                    $item['url_title'] = $model->title;
                    $item['created_at'] = $model->created_at;
                    $images[] = $item;
                    foreach ($urls as $url){
                        $item = [];
                        $item['target_id'] = $model->id;
                        $item['type'] = '1';
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
                    $detailModel->save($detail);
                    $contentModel->save($content);
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
        return view('build/create',['meta_title'=>'添加楼盘','meta_util'=>'false','model'=>$detailModel,'cityLists'=>$cityLists,]);
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
        $model = BuildingBase::load()->where(['id'=>$id])->find();
        return view('build/view',['model'=>$model]);
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
        $cityLists = City::getCityList();
        $model = BuildingBase::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            $this->error('不存在此楼盘', 'create','',1);
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Building']) ? $_POST['Building'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = BuildingBase::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && $model::update($data,['id'=>$id])){
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
        return view('build/update',['meta_title'=>'更新楼盘','model'=>$model,'cityLists'=>$cityLists,]);
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
            $result = BuildingBase::update(['is_delete'=>'0'],$where);
            if ($result){
                $ret = ['status'=>1,'info'=>'删除成功'];
            }
        }
        if (empty($id)){
            $ret = ['status'=>1,'info'=>'删除成功'];
        }
        return json($ret);
    }

    /**
     * 图片上传
     * @return \think\response\Json
     */
    public function uploadAction(){
        $config = [];
        if (isset($_REQUEST['file'])){
            $config['fileField'] = $_REQUEST['file'];
        }
        $ret = \app\common\components\Uploader::action($config);

        return json($ret);
    }

}
