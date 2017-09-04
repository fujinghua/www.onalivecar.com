<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\BackUser;
use app\common\model\Client;
use app\common\model\Contact;
use app\common\model\Service;

class ContactController extends BackController
{

    /**
     *
     */
    public function servicesAction(){}

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function superAction()
    {
        $where = ['t.is_delete'=>'1'];
        $each = 12;
        $model = Contact::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"`t`.`name` like '%".$key."%' or `t`.`contact` like '%".$key."%' or `t`.`email` like '%".$key."%' or `t`.`address` like '%".$key."%'"];
        }
        $readType = Contact::lang('readed');
        $readed = trim($request->request('readed'));
        if ($readed != ''){
            if (in_array($readed,array_keys($readType))){
                $where = array_merge($where,['readed'=>$readed]);
            }
        }

        $join = [
            [BackUser::tableName().' b','t.back_user_id = b.id','left'],
            [Client::tableName().' c','t.home_user_id = c.id','left'],
        ];

        $list = $model->alias('t')
            ->join($join)
            ->where($where)
            ->field('t.*,b.id,b.username as belongUser,c.id,c.username as clientName')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "我的求购清单");
        $this->assign('lang', Contact::Lang());
        $this->assign('list', $list);
        return view('contact/index');
    }

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['t.is_delete'=>'1','b.id'=>$this->getIdentity('id')];
        $each = 12;
        $model = Contact::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"`t`.`name` like '%".$key."%' or `t`.`contact` like '%".$key."%' or `t`.`email` like '%".$key."%' or `t`.`address` like '%".$key."%'"];
        }
        $readType = Contact::lang('readed');
        $readed = trim($request->request('readed'));
        if ($readed != ''){
            if (in_array($readed,array_keys($readType))){
                $where = array_merge($where,['readed'=>$readed]);
            }
        }

        $join = [
            [BackUser::tableName().' b','t.back_user_id = b.id','left'],
            [Client::tableName().' c','t.home_user_id = c.id','left'],
        ];

        $list = $model->alias('t')
            ->join($join)
            ->where($where)
            ->field('t.*,b.id,b.username as belongUser,c.id,c.username as clientName')
            ->order('t.id DESC')->paginate($each);

        $this->assign('meta_title', "我的求购清单");
        $this->assign('lang', Contact::Lang());
        $this->assign('list', $list);
        return view('contact/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Contact();
        $lists = Service::getService();
        $hotLists = Service::getService(true);
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Building']) ? $_POST['Building'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Contact::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model->save($data)){
                    $this->success('提交成功','create','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('contact/create',[
            'meta_title'=>'联系我们',
            'meta_util'=>'false',
            'lists'=>$lists,
            'hotLists'=>$hotLists,
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
        $model = Contact::load()->where(['id'=>$id])->find();
        return view('config/view',[
            'meta_title'=>'详情',
            'model'=>$model
        ]);
    }

    /**
     * 设置状态
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function readAction($id)
    {
        $ret = ['code'=>0,'msg'=>'设置失败','delete_id'=>$id];
        if ($this->getRequest()->isAjax()){
            $where = ['id'=>$id];
            $model = Contact::load()->where($where)->find();
            $read = $model->readed == '2' ? '1' : '2' ;
            $result = Contact::update(['readed'=>$read],$where);
            if ($result){
                $ret = ['code'=>1,'msg'=>'设置成功','readed'=>$read];
            }
        }
        return json($ret);
    }

    /**
     * @description 求购分配
     * @param $id $pageNumber
     * @return \think\Response
     */
    public function assignAction($id = null)
    {
        return '';
        $model = new Contact();
        $lists = Service::getService();
        $hotLists = Service::getService(true);
        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Building']) ? $_POST['Building'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Contact::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model->save($data)){
                    $this->success('提交成功','create','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'create','',1);
                }
            }
        }
        return view('contact/create',[
            'meta_title'=>'求购分配',
            'meta_util'=>'false',
            'lists'=>$lists,
            'hotLists'=>$hotLists,
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
            $result = Contact::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }


}
