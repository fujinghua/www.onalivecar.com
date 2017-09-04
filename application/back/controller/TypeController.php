<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Type;

class TypeController extends BackController
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
        $lists = Type::getTypeList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($type && $type != ''){
            $param['type'] = trim($type);
            if (in_array($type,array_keys($lists))){
                $where =  array_merge($where, ['type'=>$type]);
            }
        }

        $query = Type::load();
        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "类型清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('type/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Type();
        $lists = Type::getTypeList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Type']) ? $_POST['Type'] : []);
            $data['is_delete'] = '1';
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            $where = ['name'=>$data['name'],'type'=>$data['type']];
            $result = Type::load()->where($where)->find();
            if ($data){
                if ($result){
                    if ($result->is_delete == '0'){
                        Type::update(['is_delete'=>'1'],$where);
                    }else{
                        $error = isset($lists[$data['type']]) ? '已存在 '.$data['name'] .' 此'.$lists[$data['type']].'类型名' : '无效类型名';
                        $this->error($error , 'create','',1);
                    }
                }else{
                    $validate = Type::getValidate();
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
        return view('type/create',[
            'meta_title'=>'添加一个类型名',
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
        $lists = Type::getTypeList();
        $model = Type::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Type']) ? $_POST['Type'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $type = $model->type;
            $where = array_merge($where,['name'=>$data['name'],'type'=>$type]);
            $result = Type::load()->where($where)->find();
            if ($data){
                if ($result){
                    if ($result->is_delete == '0'){
                        Type::update(['is_delete'=>'1'],$where);
                    }else{
                        $error = isset($lists[$type]) ? '已存在 '.$data['name'] .' 此'.$lists[$type].'类型名' : '无效类型名';
                        $this->error($error , 'update?id='.$id,'',1);
                    }
                }else{
                    $validate = Type::getValidate();
                    $validate->scene('update');
                    if ($validate->check($data) && Type::update($data,['id'=>$id])){
                        $this->success('更新成功','update?id='.$id,'',1);
                    }else{
                        $error = $validate->getError();
                        if (empty($error)){
                            $error = $model->getError();
                        }
                        $this->error($error, 'update?id='.$id,'',1);
                    }
                }
            }
        }
        return view('type/update',[
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
            $result = Type::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
