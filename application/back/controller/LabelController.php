<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Label;

class LabelController extends BackController
{
    /**
     * @description 显示资源列表
     * @param int $pageNumber
     * @param string $name
     * @param string $type
     * @return \think\Response
     */
    public function indexAction($pageNumber = 1,$name = null, $type = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 10;
        $param = ['name'=>'','type'=>''];
        if ($name && $name != ''){
            $param['name'] = trim($name);
            $where =  array_merge($where, ['name'=>['like','%'.$name.'%']]);
        }
        $lists = Label::getTypeList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($type && $type != ''){
            $param['type'] = trim($type);
            if (in_array($type,array_keys($lists))){
                $where =  array_merge($where, ['type'=>$type]);
            }
        }

        $query = Label::load();
        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "标签清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('label/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Label();
        $lists = Label::getTypeList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Label']) ? $_POST['Label'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            $where = ['name'=>$data['name'],'type'=>$data['type']];
            $result = Label::load()->where($where)->find();
            if ($data){
                if ($result){
                    $error = isset($lists[$data['type']]) ? $lists[$data['type']].'类型已存在此标签：'.$data['name'] : '无效标签';
                    $this->error($error , 'create','',1);
                }else{
                    $validate = Label::getValidate();
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
        }
        return view('label/create',[
            'meta_title'=>'添加标签',
            'meta_util'=>'false',
            'lists'=>$lists
        ]);
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
        $lists = Label::getTypeList();
        $model = Label::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Label']) ? $_POST['Label'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            $type = $model->type;
            $newWhere = ['name'=>$data['name'],'type'=>$type];
            $result = Label::load()->where($newWhere)->where($where)->find();
            if ($data){
                if ($result){
                    $error = isset($lists[$model->type]) ? $lists[$model->type].'类型已存在此标签：'.$data['name'] : '无效标签';
                    $this->error($error , 'create','',1);
                }else{
                    $validate = Label::getValidate();
                    $validate->scene('update');
                    if ($validate->check($data) && Label::update($data,['id'=>$id])){
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
        }
        return view('label/update',[
            'meta_title'=>'编辑标签',
            'meta_util'=>'false',
            'lists'=>$lists,
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
        if ( $this->getRequest()->isAjax()){
            $result = Label::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
