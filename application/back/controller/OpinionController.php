<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Opinion;

class OpinionController extends BackController
{
    /**
     * @description 显示资源列表
     * @param int $pageNumber
     * @param string $name
     * @param string $type
     * @param string $app
     * @return \think\Response
     */
    public function indexAction($pageNumber = 1,$name = null, $type = null,$app = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $param = ['name'=>'','type'=>'','app'=>''];
        $query = Opinion::load();
        if ($name && $name != ''){
            $param['name'] = trim($name);
            $nameWhere = ' `name` like '.' \'%'.$name.'%\''.' or `title` like '.' \'%'.$name.'%\' ';
            $query = $query->where($nameWhere);
        }
        $lists = Opinion::getReadList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($type && $type != ''){
            $param['type'] = trim($type);
            if (in_array($type,array_keys($lists))){
                $where =  array_merge($where, ['type'=>$type]);
            }
        }
        $dataProvider =$query->where($where)->page($pageNumber,$each)->select();
        $count = Opinion::load()->where($where)->count();

        $this->assign('meta_title', "标签清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('opinion/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Opinion();
        $lists = Opinion::getReadList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Opinion']) ? $_POST['Opinion'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Opinion::getValidate();
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
        return view('opinion/create',['meta_title'=>'添加配置','lists'=>$lists]);
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
        $model = Opinion::load()->where(['id'=>$id])->find();
        return view('opinion/view',['model'=>$model]);
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
        $model = new Opinion();
        $lists = Opinion::getReadList();
        $model = Opinion::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Opinion']) ? $_POST['Opinion'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Opinion::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Opinion::update($data,['id'=>$id])){
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
        return view('opinion/update',['meta_title'=>'编辑标签','model'=>$model,'lists'=>$lists]);
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
            $result = Opinion::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
