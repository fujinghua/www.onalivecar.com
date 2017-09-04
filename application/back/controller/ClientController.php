<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\City;
use app\common\model\Client;
use app\common\model\BackUser;

use app\common\model\ClientServer;
use app\common\model\Deal;
use app\common\model\Walk;

class ClientController extends BackController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function importAction()
    {
        //
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function assignAction()
    {
//        return view('client/assign');
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
    public function onServicesAction()
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
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function logAction()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function logDeleteAction()
    {
        //
    }

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['t.is_delete'=>'1'];
        $each = 12;
        /**
         * @var $model \app\back\model\Client
         */
        $model = Client::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"t.userName like '%".$key."%' "];
        }
        $createdBy = trim($request->request('createdBy'));
        if ($createdBy != ''){
            $where[] = ['exp',"b.createdBy like '%".$createdBy."%' "];
        }
        $lists = Client::T('requireType');
        $requireType = trim($request->request('requireType'));
        if ($requireType != ''){
            if (in_array($requireType,array_keys($lists))){
                $where =  array_merge($where, ['requireType'=>$requireType]);
            }
        }

        $list = $model->alias('t')
            ->join(BackUser::tableName().' b','t.createdBy = b.id','left')
            ->where($where)
            ->field('t.*,b.id,b.username as createdBy')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "用户清单");
        $this->assign('lang', Client::Lang());
        $this->assign('list', $list);
        return view('client/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Client();
        $city = City::getCityList();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = $model::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model->save($data)){
                    $this->success('添加成功','create','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('client/create',[
            'meta_title'=>'添加用户',
            'meta_util'=>'false',
            'model'=>$model,
            'city'=>$city,
        ]);
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
        $model = Client::load()->where(['id'=>$id])->find();
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
        $model = Client::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Building']) ? $_POST['Building'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Client::getValidate();
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
        return view('client/update',[
            'meta_title'=>'添加用户',
            'model'=>$model,
            'lang'=>Client::Lang(),
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
            $result = Client::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }

}
