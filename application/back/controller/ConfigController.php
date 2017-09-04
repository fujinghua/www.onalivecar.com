<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Config;

class ConfigController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        /**
         * @var $model \app\back\model\Config
         */
        $model = Config::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"name like '%".$key."%' or  `title` like '.' '%'.$key.'%' "];
        }
        $typeLists = Config::T('type');
        $type = trim($request->request('type'));
        if ($type != ''){
            if (in_array($type,array_keys($typeLists))){
                $where =  array_merge($where, ['type'=>$type]);
            }
        }
        $appLists = Config::T('app');
        $app = trim($request->request('app'));
        if ($app != ''){
            if (in_array($app,array_keys($appLists))){
                $where =  array_merge($where, ['app'=>$app]);
            }
        }

        $list = $model->where($where)->order('id DESC')->paginate($each);

        $this->assign('meta_title', "配置清单");
        $this->assign('lang', Config::Lang());
        $this->assign('list', $list);
        return view('config/index');

    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Config();
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
        return view('config/create',[
            'meta_title'=>'添加配置',
            'model'=>$model,
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
        $model = Config::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Config::getValidate();
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
        return view('config/update',[
            'meta_title'=>'编辑标签',
            'model'=>$model,
            'lang'=>Config::Lang(),
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
