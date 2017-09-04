<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Service;
use app\common\model\BackUser;

class ServiceController extends BackController
{
    public function onServicesAction(){}

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['t.is_delete'=>'1'];
        $each = 12;
        $model = Service::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"`b`.`username` like '%".$key."%' "];
        }
        $levelList = Service::lang('level');
        $level = trim($request->request('level'));
        if ($level != ''){
            if (in_array($level,array_keys($levelList))){
                $where = array_merge($where,['t.level'=>$level]);
            }
        }

        $join = [
            [BackUser::tableName().' b','t.back_user_id = b.id','left'],
        ];

        $list = $model->alias('t')
            ->join($join)
            ->where($where)
            ->field('t.*,b.id,b.username')
            ->order('t.order ASC')->paginate($each);

        $this->assign('meta_title', "客服清单");
        $this->assign('lang', Service::Lang());
        $this->assign('list', $list);
        return view('service/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Service();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Service::getValidate();
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
        return view('service/create',['meta_title'=>'客服添加','model'=>$model]);
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
        $model = Ban::load()->where(['id'=>$id])->find();
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
        $where = ['is_delete'=>'1','id'=>$id];
        $model = Service::load()->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Service::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Service::update($data,['id'=>$id])){
                    $this->success('更新成功','create','',1);
                }else{
                    $error = $validate->getError();
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('service/update',['meta_title'=>'更新客服','model'=>$model]);
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
            $result = Ban::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
