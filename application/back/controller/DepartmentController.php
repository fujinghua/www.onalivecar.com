<?php

namespace app\back\controller;

use app\common\components\rbac\DbManager;
use app\common\controller\BackController;
use app\common\model\Department;

class DepartmentController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $model = Department::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"`name` like '%".$key."%' "];
        }
        $departmentLists = Department::getLevelList();
        $level = trim($request->request('level'));
        if ($level != ''){
            if (in_array($level,array_keys($departmentLists))){
                $where = array_merge($where,['level'=>$level]);
            }
        }

        $list = $model->where($where)->order('id DESC')->paginate($each);

        $this->assign('meta_title', "部门清单");
        $this->assign('departmentLists', $departmentLists);
        $this->assign('list', $list);
        return view('department/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Department();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Department::getValidate();
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
        return view('department/create',[
            'meta_title'=>'添加部门',
            'meta_util'=>'false',
            'model'=>$model
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
        $model = Department::load()->where(['id'=>$id])->find();
        return view('department/view',['model'=>$model]);
    }

    /**
     * 保存更新的资源
     *
     * @param  int  $id
     * @return \think\Response|string
     */
    public function updateAction($id)
    {
        $model = Department::load()->where(['id'=>$id])->find();
        if (!$model){
            $this->error('不存在此部门', 'create','',1);
        }
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Department::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model::update($data,['id'=>$id])){
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
        return view('department/update',[
            'meta_title'=>'编辑部门',
            'meta_util'=>'false',
            'model'=>$model
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
            $result = Department::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }

}
