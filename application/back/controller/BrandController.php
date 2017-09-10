<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Brand;

class BrandController extends BackController
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

        $query = Brand::load();
        $providerModel = clone $query;
        $count = $query->where($where)->count();
        $dataProvider = $providerModel->where($where)->page($pageNumber,$each)->select();

        $this->assign('meta_title', "标签清单");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        return view('label/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Brand();
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $path = ROOT_PATH.'public/static/uploads/brand/';
            $pinyin = \app\common\components\ChineseToPinyin::encode($data['name'],'all');
            $data['pinyin'] = implode('',explode(' ',$pinyin));
            $data['icon'] = ' ';
            $res = $model->save($data);
            if ($res){
                $to =$path .$data['letter'].'/'. $model->id . '/' . md5(date("YmdHis") . rand(10000, 99999)) . '.png';
                $this->getFolder(pathinfo($to,PATHINFO_DIRNAME));
                $this->copy(ROOT_PATH.$data['icon'],$to);
                $to = str_replace(ROOT_PATH.'public/','/',$to);
                $order = Brand::load()->where(['letter'=>$data['letter']])->count()+1;
                $model->isUpdate(true)->save(['icon'=>$to.'?t='.date('Ymd'),'order'=>$order]);
            }
//            if ($data){
//                if ($res){
//                    $error = isset($lists[$data['type']]) ? $lists[$data['type']].'类型已存在此标签：'.$data['name'] : '无效标签';
//                    $this->error($error , 'create','',1);
//                }else{
//                    $validate = Brand::getValidate();
//                    $validate->scene('create');
//                    if ($validate->check($data) && $model->save($data)){
//                        $this->success('添加成功','create','',1);
//                    }else{
//                        $error = $validate->getError();
//                        if (empty($error)){
//                            $error = $model->getError();
//                        }
//                        $this->error($error, 'create','',1);
//                    }
//                }
//            }
        }
        return view('label/create',[
            'meta_title'=>'添加标签',
            'meta_util'=>'false',
            'model'=>$model,
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
        $model = Brand::load()->where(['id'=>$id])->where($where)->find();
        if (!$model){
            return '';
        }

        if ($this->getRequest()->isPost()){
            $data = (isset($_POST['Label']) ? $_POST['Label'] : []);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            $type = $model->type;
            $newWhere = ['name'=>$data['name'],'type'=>$type];
            $result = Brand::load()->where($newWhere)->where($where)->find();
            if ($data){
                if ($result){
                    $error = isset($lists[$model->type]) ? $lists[$model->type].'类型已存在此标签：'.$data['name'] : '无效标签';
                    $this->error($error , 'create','',1);
                }else{
                    $validate = Brand::getValidate();
                    $validate->scene('update');
                    if ($validate->check($data) && Brand::update($data,['id'=>$id])){
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
        }
        return view('label/update',[
            'meta_title'=>'编辑标签',
            'meta_util'=>'false',
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
            $result = Brand::update(['is_delete'=>'0'],['id'=>$id]);
            if ($result){
                $ret = ['code'=>1,'msg'=>'删除成功','delete_id'=>$id];
            }
        }
        return json($ret);
    }
}
