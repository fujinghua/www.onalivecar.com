<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Download;

class DownloadController extends BackController
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
        $where = [];
        $each = 12;
        $param = ['name'=>'','type'=>'','app'=>''];
        if ($key && ($key = trim($key)) != ''){
            $param['name'] = $key;
            $where[] = ['exp',' `title` like '.' \'%'.$key.'%\''.' or `url` like '.' \'%'.$key.'%\' '." or `fileName` like '%".$key."%' "];
        }
        $lists = Download::getTypeList();
        if (isset($lists[0])){
            unset($lists[0]);
        }
        if ($type && ($type = trim($type)) != ''){
            $param['type'] = $type;
            if (in_array($type,array_keys($lists))){
                $where =  array_merge($where, ['tb_category'=>$type]);
            }
        }

        $query = Download::load();
        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "下载清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('param', $param);
        $this->assign('lists', $lists);
        return view('download/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Download();
        $lists = Download::getTypeList();
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Download']) ? $_POST['Download'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Download::getValidate();
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
        return view('download/create',['meta_title'=>'添加配置','lists'=>$lists]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function viewAction($id=0)
    {
        $id = intval($id);
        if (empty($id)){
            $id = '1';
        }
        $model = Download::load()->where(['id'=>$id])->find();
        return view('download/view',[
            'meta_title'=>'下载痕迹',
            'meta_util'=>'false',
            'model'=>$model
        ]);
    }

    /**
     * 保存更新的资源
     *
     * @param  int  $id
     * @return \think\Response|string
     */
    public function downloadAction($id =0)
    {
        $id = intval($id);
        if (empty($id)){
            $id = '1';
        }
        $model = Download::load()->where(['id'=>$id])->find();
        return json(['code'=>'1','result'=>'']);
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
            $result = Download::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }

}
