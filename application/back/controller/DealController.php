<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Client;
use app\common\model\Deal;


class DealController extends BackController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function superAction()
    {
        $where = ['t.is_delete'=>'1'];
        $each = 12;
        $model = Deal::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ( $key != ''){
            $where[] = ['exp',"`c`.`userName` like '%".$key."%' "];
        }
        $lang = Deal::Lang();
        $HouseType = Deal::T('HouseType');
        $type = trim($request->request('type'));
        if ( $type != ''){
            if (in_array($type,array_keys($HouseType))){
                $where = array_merge($where,['t.house_type'=>$type]);
            }
        }
        $status = trim($request->request('status'));
        if ( $status != ''){
            if (in_array($type,array_keys($HouseType))){
                $where = array_merge($where,['t.status'=>$status]);
            }
        }

        $join = [];
        $join[] = [Client::tableName().' c','t.client_id = c.id','left'];

        $list = $model->alias('t')
            ->join($join)
            ->where($where)
            ->field('t.*,c.id,c.userName')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "我的交易清单");
        $this->assign('lang', $lang);
        $this->assign('list', $list);
        return view('deal/index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function importAction()
    {
        //
    }

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['t.is_delete'=>'1','t.belongUserId'=>$this->getIdentity('id')];
        $each = 12;
        $model = Deal::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ( $key != ''){
            $where[] = ['exp',"`c`.`userName` like '%".$key."%' "];
        }
        $lang = Deal::Lang();
        $HouseType = Deal::T('HouseType');
        $type = trim($request->request('type'));
        if ( $type != ''){
            if (in_array($type,array_keys($HouseType))){
                $where = array_merge($where,['t.house_type'=>$type]);
            }
        }
        $status = trim($request->request('status'));
        if ( $status != ''){
            if (in_array($type,array_keys($HouseType))){
                $where = array_merge($where,['t.status'=>$status]);
            }
        }

        $join = [];
        $join[] = [Client::tableName().' c','t.client_id = c.id','left'];

        $list = $model->alias('t')
            ->join($join)
            ->where($where)
            ->field('t.*,c.id,c.userName')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "我的交易清单");
        $this->assign('lang', $lang);
        $this->assign('list', $list);
        return view('deal/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Deal();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if ($data){
                $data['belongUserId'] = $this->getIdentity('id');
                $data['createdBy'] = $this->getIdentity('id');
                $data['createdAt'] = date('Y-m-d H:i:s');
                $validate = Deal::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model->save($data)){
                    $prefix = '/static/uploads/deal/'.$model->id.'/';
                    $to = $prefix.pathinfo($data['url'],PATHINFO_BASENAME);
                    $from = $data['url'];
                    $model->url = $to;
                    $this->copy($from,$to);
                    $icon = pathinfo($from,PATHINFO_DIRNAME).'/'.pathinfo($from,PATHINFO_FILENAME).'_icon.'.pathinfo($from,PATHINFO_EXTENSION);
                    $to = $prefix.pathinfo($icon,PATHINFO_BASENAME);
                    $model->url_icon = $to;
                    $this->copy($icon,$to);
                    $model->isUpdate(true)->save();
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

        return view('deal/create',[
            'meta_title'=>'添加一个交易记录',
            'meta_util'=>'false',
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
        $model = Ban::load()->where(['id'=>$id])->find();
        return view('order/view',['model'=>$model]);
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
        $model = new Ban();
        $modelList = Ban::getTypeList();
        $appList = Ban::getAppList();
        $model = Ban::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Ban']) ? $_POST['Ban'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Ban::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Ban::update($data,['id'=>$id])){
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
        return view('order/update',['meta_title'=>'编辑标签','model'=>$model,'appList'=>$appList,'orderList'=>$modelList]);
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
            $result = Deal::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
