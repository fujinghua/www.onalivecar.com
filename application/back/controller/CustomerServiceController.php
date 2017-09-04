<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Service;

class CustomerServiceController extends BackController
{
    /**
     * @description 显示资源列表
     * @param int $pageNumber
     * @param string $key
     * @param string $level
     * @return \think\Response
     */
    public function indexAction($pageNumber = 1,$key = null, $level = null)
    {
        $where = ['is_delete'=>'1'];
        $each = 12;
        $param = ['key'=>'','level'=>''];
        $query = Service::load();
        if ($key && ($key = trim($key)) != ''){
            $param['key'] = $key;
            $where[] = ['exp',"`username` like '%".$key."%' or `contact` like '%".$key."%' or `email` like '%".$key."%' or `address` like '%".$key."%'"];
        }
        $lists = Service::getLevelList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($level && ($level = trim($level)) != ''){
            $param['level'] = $level;
            if (in_array($level,array_keys($lists))){
                $where = array_merge($where,['level'=>$level]);
            }
        }

        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "服务清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('customerService/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Service();
        $modelList = Service::getTypeList();
        $appList = Service::getAppList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Service']) ? $_POST['Service'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
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
        return view('customerService/create',['meta_title'=>'添加配置','appList'=>$appList,'configList'=>$modelList]);
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
        $model = Service::load()->where(['id'=>$id])->find();
        return view('customerService/view',['model'=>$model]);
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
        $model = new Service();
        $modelList = Service::getTypeList();
        $appList = Service::getAppList();
        $model = Service::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Service']) ? $_POST['Service'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Service::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Service::update($data,['id'=>$id])){
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
        return view('customerService/update',['meta_title'=>'编辑标签','model'=>$model,'appList'=>$appList,'configList'=>$modelList]);
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
            $result = Service::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }

}
