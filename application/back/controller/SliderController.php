<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Slider;

class SliderController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = ['is_delete'=>'1'];
        $each = 20;
        $model = Slider::load();
        $request = $this->getRequest();
        $lang = Slider::Lang();
        $type = trim($request->request('type'));
        if ($type != ''){
            if (in_array($type,array_keys($lang['type']))){
                $where =  array_merge($where, ['type'=>$type]);
            }
        }
        $app = trim($request->request('app'));
        if ($app != ''){
            if (in_array($app,array_keys($lang['app']))){
                $where =  array_merge($where, ['app'=>$app]);
            }
        }
        $status = trim($request->request('status'));
        if ($status != ''){
            if (in_array($status,array_keys($lang['status']))){
                $where =  array_merge($where, ['status'=>$status]);
            }
        }
        $list = $model->where($where)->order('`order` ASC')->paginate($each);
        $this->assign('meta_title', "广告清单");
        $this->assign('model', $model);
        $this->assign('list', $list);
        return view('slider/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Slider();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if ($data){
                $validate = Slider::getValidate();
                $validate->scene('create');
                $data['back_user_id'] = $this->getIdentity('id');
                $data['created_at'] = date('Y-m-d H:i:s');
                if ($validate->check($data) && $model->save($data)){
                    $type = $model->getValue('typeName',$data['type'],'default');
                    $prefix = '/static/uploads/slider/'.$type.'/'.$model->id.'/';
                    //
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
        return view('slider/create',['meta_title'=>'添加广告','model'=>$model]);
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
        $config = new Slider();
        $model = Slider::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Ban']) ? $_POST['Ban'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Slider::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Slider::update($data,['id'=>$id])){
                    $this->success('更新成功','update',['id'=>$id],1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $config->getError();
                    }
                    $this->error($error, 'update',['id'=>$id],1);
                }
            }
        }
        return view('slider/update',['meta_title'=>'编辑广告','model'=>$model]);
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
            $result = Slider::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
