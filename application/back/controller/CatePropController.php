<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Cate;
use app\common\model\CateProp;
use app\common\model\CatePropValue;

class CatePropController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = [];
        $request = $this->getRequest();
        $limit = $request->request('limit') ? : 20;
        $model = CateProp::load();
        $lang = CateProp::Lang();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"t.name like '%".$key."%' "];
        }
        $cate = trim($request->request('cate'));
        if ($cate != ''){
            if (in_array($cate,array_keys($lang['car']))){
                $where =  array_merge($where, ['level'=>$cate]);
            }
        }
        if ($this->getRequest()->request('isAjax')){
            $list = $model->alias('t')
                ->join(CateProp::tableName().' b','t.id = b.id','left')
                ->where($where)
                ->field('t.*,b.name as brand,b.icon as icon')
                ->order(['`level`'=>'ASC','`order`'=>'ASC'])->paginate($limit)->toArray();
            $ret = ['code'=>'0','msg'=>'','count'=>$list['total'],'data'=>$list['data']];
            return json($ret);
        }else{
            $this->assign('meta_title', "汽车配置");
            $this->assign('model', $model);
            return view('cateProp/index');
        }
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new CateProp();
        $cate = Cate::load()->where(['level'=>'1'])->order(['order'=>'ASC','id'=>'ASC'])->column('name,unique_id','id');
        $cateProp = CateProp::load()->where(['level'=>'1'])->order(['order'=>'ASC','id'=>'ASC'])->column('name,cate_id','id');
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if (!empty($_REQUEST['isPublic'])){
                $data['cate_id'] = $_REQUEST['brand_id'];
            }
            if (!empty($data)){
                $validate = CateProp::getValidate();
                if ($validate->check($data)){
                    if ($_REQUEST['pName'] != $data['name']){
                        $data['level'] ++;
                        if ($data['level'] > 3){
                            $data['level'] = 3;
                        }
                        $model->save($data);
                    }else{
                        $model = CateProp::load()->where(['name'=>$data['name']])->find();
                    }
                    if (!empty($_REQUEST['value'])){
                        if (is_array($_REQUEST['value'])){
                            foreach ($_REQUEST['value'] as $item){
                                $catePropValue = new CatePropValue();
                                $value = str_replace('（','(',str_replace('）',')',$item));
                                $extra = '';
                                if (strstr($value,'(') !==false && strstr($value,')') !==false){
                                    $value = str_replace(')','',$value);
                                    $tmp = explode('(',$value);
                                    $value = $tmp[0];
                                    $extra = $tmp[1];
                                }
                                $catePropValue->save(['value' =>$value, 'cate_prop_id' => $model->id,'extra'=>$extra]);
                            }
                        }
                    }
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
        return view('cateProp/create',['meta_title'=>'添加汽车配置','model'=>$model,'cateProp'=>$cateProp,'cate'=>$cate]);
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
        $config = new Cate();
        $model = Cate::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Ban']) ? $_POST['Ban'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data){
                $validate = Cate::getValidate();
                $validate->scene('update');
                if ($validate->check($data) && Cate::update($data,['id'=>$id])){
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
        return view('cate/update',['meta_title'=>'编辑广告','model'=>$model]);
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
            $result = Cate::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
