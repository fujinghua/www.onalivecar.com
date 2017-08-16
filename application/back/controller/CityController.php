<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\manage\model\City;
use think\Request;

class CityController extends BackController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        /**
         * @var $model \app\manage\model\Client
         */
        $model = City::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"name like '%".$key."%' "];
        }
        $levelLists = City::T('level');
        $level = trim($request->request('level'));
        if ($levelLists != ''){
            if (in_array($level,array_keys($levelLists))){
                $where =  array_merge($where, ['level'=>$level]);
            }
        }
        $list = $model->where($where)->order('parent DESC')->paginate($each);

        $this->assign('meta_title', "客户清单");
        $this->assign('lang', City::Lang());
        $this->assign('list', $list);
        return view('city/index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $config = new City();
        $cityList = City::getCityList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['City']) ? $_POST['City'] : []);
            if (empty($data['parent'])){
                $data['parent'] = null;
            }
            $region = City::getRegionByName($data['name'],$data['data']);
            if (is_object($region)){
                $helper = $this->getHelper();
                $region = $helper::toArray($region);
                foreach ($region as $key => $value){
                    switch ($key){
                        case 'id':{$data['region_id'] = $value;}break;
                        case 'level':{$data['level'] = $value == '0'? '2': $value;}break;
                        case 'order':{$data['order'] = $value;}break;
                        case 'code':{$data['code'] = $value;}break;
                        case 'name_en':{$data['name_en'] = $value;}break;
                        case 'short_name_en':{$data['short_name_en'] = $value;}break;
                        case 'data':{$data['data'] = !empty($data['data']) ? $data['data'] : $value;}break;
                        default:{}
                    }
                }
            }else{
                $data['region_id'] = '1';
                $data['level'] = '2';
                $data['order'] = '1';
                $data['code'] = '1';
                $data['name_en'] = 'NOT';
                $data['short_name_en'] = 'NOT';
            }

            if ($data){
                $validate = City::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $config->save($data)){
                    $this->success('添加成功','create','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $config->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('city/create',['meta_title'=>'添加城市','cityList'=>$cityList]);
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
        $model = Config::load()->where(['id'=>$id])->find();
        return view('config/view',['model'=>$model]);
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
        $model = City::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }
        $cityList = City::getCityList();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = City::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && $model->save($data)){
                    $this->success('更新成功','update','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'update','',1);
                }
            }
        }
        return view('city/update',[
            'meta_title'=>'编辑城市',
            'model'=>$model,
            'cityList'=>$cityList,
        ]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function deleteAction($id)
    {
        $ret = ['code'=>0,'msg'=>'删除失败','delete_id'=>$id];
        if ($this->getRequest()->isAjax()){
            $result = Config::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
