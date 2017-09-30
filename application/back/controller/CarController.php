<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Car;
use app\common\model\CarProp;
use app\common\model\Cate;
use app\common\model\CateProp;
use app\common\model\CatePropValue;

class CarController extends BackController
{

    /**
     * @description 汽车配置清单
     * @return \think\Response
     */
    public function indexAction()
    {
        $model = Car::load();
        $cateList = Cate::load()->where(['level'=>'1','type'=>'1'])->order(['order'=>'ASC','id'=>'ASC'])->column('name','id');
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
                    $where =  array_merge($where, ['t.level'=>$cate]);
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
                ->field('t.*,c.name as pName')
                ->order(['`level`'=>'ASC','`order`'=>'ASC'])
                ->with(['getCarProps'=>function($query){$query->field('*');}])
                ->paginate($limit)->toArray();
            $ret = ['code'=>'0','msg'=>'','count'=>$list['total'],'data'=>$list['data']];
            return json($ret);
        }else{
            $this->assign('meta_title', "汽车配置");
            $this->assign('model', $model);
            $this->assign('cateList', $cateList);
            return view('car/index');
        }
    }

    /**
     * @description 汽车配置清单
     * @return \think\Response
     */
    public function listAction()
    {
        $model = Car::load();
        $cateList = Cate::load()->where(['level'=>'1','type'=>'1'])->order(['order'=>'ASC','id'=>'ASC'])->column('name','id');
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
                    $where =  array_merge($where, ['t.level'=>$cate]);
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
                ->field('t.*,c.name as pName')
                ->order(['`level`'=>'ASC','`order`'=>'ASC'])
                ->with(['getCarProps'=>function($query){$query->field('*');}])
                ->paginate($limit)->toArray();
            $ret = ['code'=>'0','msg'=>'','count'=>$list['total'],'data'=>$list['data']];
            return json($ret);
        }else{
            $this->assign('meta_title', "汽车配置");
            $this->assign('model', $model);
            $this->assign('cateList', $cateList);
            return view('car/index');
        }
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Car();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            if (!empty($data)){
                $validate = Car::getValidate();
                $validate->scene('create');
                if ($validate->check($data) && $model->save($data)){
                    $carProps = isset($_REQUEST['CAR_PROP']) ? $_REQUEST['CAR_PROP'] : [];
                    $carPropData = [];
                    $item = [];
                    $item['car_id'] = $model->id;
                    $carPropModel = new CarProp();
                    foreach ($carProps as $key => $value){
                        $tmp = explode(',',$key);
                        $item['cate_prop_value_id'] = $tmp[0];
                        $item['type'] = $tmp[1];
                        $item['prop'] = $value;
                        $carPropData[] = $item;
                    }
                    $carPropModel->saveAll($carPropData);
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
        $cateProp = CateProp::load()->alias('t')
            ->where(['t.is_delete'=>'1','t.is_passed'=>'1'])
            ->join(Cate::tableName().' c','t.cate_id = c.id','left')
            ->order(['t.cate_id'=>'ASC','t.id'=>'ASC'])
            ->field('t.*,c.name as cateName')->select();
        $prop = [];
        $parent = [];
        $cateProp = CateProp::load()->asArray($cateProp);
        $catePropValueModel = CatePropValue::load();
        foreach ($cateProp as $item){
            $parent[$item['cate_id']] = $item['cateName'];
            if ($item['isEnum']){
                $item['isEnumList'] = $catePropValueModel->field('id,value')->where(['is_delete'=>'1','cate_prop_id'=>$item['id']])->select();
            }
            $prop[$item['cate_id']][] = $item;
        }
        $cate = Cate::load();
        $cateList = $cate->asArray($cate->field('id,name')->where(['is_delete'=>'1','level'=>'3'])->select());
        return view('car/create',['meta_title'=>'添加新车','model'=>$model,'cateList'=>$cateList,'prop'=>$prop,'parent'=>$parent]);
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
        return view('car/update',['meta_title'=>'编辑广告','model'=>$model]);
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
        return view('car/view', ['meta_title' => '汽车配置', 'list' => $list]);
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
