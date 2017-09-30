<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Cate;
use app\common\model\CateProp;
use app\common\model\CatePropValue;

class CarconfigController extends BackController
{

    /**
     * @description 汽车配置清单
     * @return \think\Response
     */
    public function indexAction()
    {
        $unique = 'carConfig';
        $model = Cate::load();
        $model->unique = $unique;

        if ($this->getRequest()->request('isAjax')){

            $type = '2';
            $where = ['t.type'=>$type];
            $request = $this->getRequest();
            $limit = $request->request('limit') ? : 20;
            $lang = Cate::Lang();
            $key = trim($request->request('keyword'));
            if ($key != ''){
                $where[] = ['exp',"t.name like '%".$key."%' "];
            }
            $cate = trim($request->request('cate'));
            if ($cate != ''){
                if (in_array($cate,array_keys($lang[$model->unique]))){
                    $where =  array_merge($where, ['level'=>$cate]);
                }
            }

            $list = $model->alias('t')
                ->join(Cate::tableName().' c','t.pid = c.id','left')
                ->where($where)
                ->field('t.*,c.name as pName')
                ->order(['`level`'=>'ASC','`order`'=>'ASC'])->paginate($limit)->toArray();
            $ret = ['code'=>'0','msg'=>'','count'=>$list['total'],'data'=>$list['data']];
            return json($ret);
        }else{
            $this->assign('meta_title', "汽车配置");
            $this->assign('model', $model);
            return view('carconfig/index');
        }
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Cate();
        $unique = 'carConfig';
        $type = '2';
        $model->unique = $unique;
        $parent = Cate::load()->where(['level'=>'1','type'=>$type])->order(['order'=>'ASC','id'=>'ASC'])->column('name,unique_id','id');
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if (isset($data['level'])){
                $data['type'] = $type;
                $data['unique_code'] = $unique;
                $data['isParent'] = '1';
                $validate = Cate::getValidate();
                $data['order'] = (Cate::load()->where(['level'=>$data['level'],'type'=>$type])->max('`order`')+1);
                $validate->scene('createCarConfig');
                $pid = $this->getRequest()->request('parent_id');
                $data['pid'] = empty($pid) ? '0' : $pid;
                $data['title'] = !empty($data['title']) ? $data['title'] : $data['name'].' 配置';
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
        return view('carconfig/create',['meta_title'=>'添加配置','model'=>$model,'parent'=>$parent]);
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
        return view('carconfig/update',['meta_title'=>'编辑广告','model'=>$model]);
    }

    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function propAction()
    {
        $model = CateProp::load();
        $cateList = Cate::load()->where(['level'=>'1','type'=>'2'])->order(['order'=>'ASC','id'=>'ASC'])->column('name','id');
        if ($this->getRequest()->request('isAjax')){
            $where = [];
            $request = $this->getRequest();
            $limit = $request->request('limit') ? : 20;
            $key = trim($request->request('keyword'));
            if ($key != ''){
                $where[] = ['exp',"t.name like '%".$key."%' "];
            }
            $cate = trim($request->request('cate_id'));
            if ($cate != ''){
                if (in_array($cate,array_keys($cateList))){
                    $where =  array_merge($where, ['t.cate_id'=>$cate]);
                }
            }
            $isBase = trim($request->request('isBase'));
            if ($isBase != ''){
                $where =  array_merge($where, ['t.isBase'=>$isBase]);
            }
            $isEnum = trim($request->request('isEnum'));
            if ($isEnum != ''){
                $where =  array_merge($where, ['t.isEnum'=>$isEnum]);
            }
            $list = $model->alias('t')
                ->join(Cate::tableName().' c','t.cate_id = c.id','left')
                ->where($where)
                ->field('t.*,c.name as cateName')
                ->order(['`t`.`id`'=>'ASC','`t`.`order`'=>'ASC'])->paginate($limit)->toArray();
            $ret = ['code'=>'0','msg'=>'','count'=>$list['total'],'data'=>$list['data']];
            return json($ret);
        }else{
            $this->assign('meta_title', "汽车配置");
            $this->assign('model', $model);
            $this->assign('cateList', $cateList);
            return view('carconfig/prop');
        }
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createPropAction()
    {
        $model = new CateProp();
        $type = '2';
        $cateList = Cate::load()->where(['level'=>'1','type'=>$type])->order(['order'=>'ASC','id'=>'ASC'])->column('name','id');
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if (isset($data['isColor'])){
                if ($data['isColor'] == '1'){
                    $data['isEnum'] = '1';
                }
            }
            if (!empty($data)){
                $validate = CateProp::getValidate();
                if ($validate->check($data) && $model->save($data)){
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
                    $this->success('添加成功','createProp','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'createProp','',1);
                }
            }
        }
        return view('carconfig/createProp',['meta_title'=>'添加汽车配置','model'=>$model,'cateList'=>$cateList]);
    }

    /**
     * @param int $id
     * @return string|\think\response\View
     */
    public function viewAction($id = 0)
    {
        $where = ['t.is_delete' => '1', 't.cate_prop_id' => $id];
        $model = CatePropValue::load();
        $list = $model->alias('t')
            ->join(CateProp::tableName() . ' c', 't.cate_prop_id = c.id', 'left')
            ->where($where)
            ->field('t.*,c.name as catePropName')
            ->order(['`c`.`id`' => 'ASC', '`t`.`order`' => 'ASC'])->select();
        $list = $model->asArray($list);
        return view('carconfig/view', ['meta_title' => '汽车配置', 'list' => $list]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return string
     */
    public function updatePropAction($id)
    {
        $where = ['is_delete'=>'1','id'=>$id];
        $model = CateProp::load()->where($where)->find();
        if (!$model){
            return '';
        }
        $type = '2';
        $cateList = Cate::load()->where(['level'=>'1','type'=>$type])->order(['order'=>'ASC','id'=>'ASC'])->column('name','id');
        if ($this->getRequest()->isPost()){
            $data = CateProp::load()->filter($_POST);
            if (!empty($data)){
                $validate = CateProp::getValidate();
                if ($validate->check($data) && CateProp::update($data,['id'=>$id])){
                    if (!empty($_REQUEST['value'])){
                        if (is_array($_REQUEST['value'])){
                            foreach ($_REQUEST['value'] as $key => $item){
                                $value = str_replace('（','(',str_replace('）',')',$item));
                                $extra = '';
                                if (strstr($value,'(') !==false && strstr($value,')') !==false){
                                    $value = str_replace(')','',$value);
                                    $tmp = explode('(',$value);
                                    $value = $tmp[0];
                                    $extra = $tmp[1];
                                }
                                if (!isset($_REQUEST['valueId'][$key])){
                                    $catePropValue = new CatePropValue();
                                    $catePropValue->save(['value' =>$value, 'cate_prop_id' => $id,'extra'=>$extra]);
                                }else{
                                    CatePropValue::update(['value' =>$value, 'cate_prop_id' => $id,'extra'=>$extra],['id'=>$_REQUEST['valueId'][$key]]);
                                }
                            }
                        }
                    }
                    $this->success('更新成功','updateProp','',1);
                }else{
                    $error = $validate->getError();
                    if (empty($error)){
                        $error = $model->getError();
                    }
                    $this->error($error, 'updateProp','',1);
                }
            }
        }
        return view('carconfig/updateProp',['meta_title'=>'更新汽车配置','model'=>$model,'cateList'=>$cateList]);
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

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function deleteValueAction($id)
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
