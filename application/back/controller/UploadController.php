<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Upload;

class UploadController extends BackController
{
    /**
     * @description 显示资源列表
     * @param int $pageNumber
     * @param string $key
     * @param string $type
     * @return \think\Response
     */
    public function indexAction($pageNumber = 1,$key = null, $type = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $param = ['key'=>'','type'=>''];
        if ($key && ($key = trim($key)) != ''){
            $param['key'] = $key;
            $where[] = ['exp',' `name` like '.' \'%'.$key.'%\''];
            $where =  array_merge($where, ['md5'=>$key]);
        }
        $lists = Upload::getTypeList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($type && $type != ''){
            $param['type'] = trim($type);
            if (in_array($type,array_keys($lists))){
                $where =  array_merge($where, ['ext'=>$type]);
            }
        }

        $query = Upload::load();
        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "上传记录");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('upload/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Upload();
        $lists = Upload::getTypeList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Upload']) ? $_POST['Upload'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Upload::getValidate();
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
        return view('upload/create',['meta_title'=>'添加配置','lists'=>$lists]);
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
        $model = Upload::load()->where(['id'=>$id])->find();
        return view('upload/view',['model'=>$model]);
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
        $model = new Upload();
        $modelList = Upload::getTypeList();
        $appList = Upload::getAppList();
        $model = Upload::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Upload']) ? $_POST['Upload'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Upload::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Upload::update($data,['id'=>$id])){
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
        return view('upload/update',['meta_title'=>'编辑标签','model'=>$model,'appList'=>$appList,'uploadList'=>$modelList]);
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
            $result = Upload::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
