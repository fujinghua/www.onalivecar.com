<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Brand;
use app\common\model\Cate;

class CateController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = [];
        $each = 20;
        $model = Cate::load();
        $lang = Cate::Lang();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"t.name like '%".$key."%' "];
        }
        $brand = trim($request->request('brand'));
        if ($brand != ''){
            if (in_array($brand,array_keys($lang['brand']))){
                $where =  array_merge($where, ['brand'=>$brand]);
            }
        }
        $list = $model->alias('t')
            ->join(Brand::tableName().' b','t.id = b.id','left')
            ->where($where)
            ->field('t.*,b.name as brand')
            ->order(['`level`'=>'ASC','`order`'=>'ASC'])->paginate($each);
        $this->assign('meta_title', "类目清单");
        $this->assign('model', $model);
        $this->assign('list', $list);
        return view('cate/index');
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @return \think\Response
     */
    public function createAction()
    {
        $model = new Cate();
        $brand = Brand::load()->where(['is_delete'=>'1'])->order(['letter'=>'ASC','id'=>'ASC'])->column('name','id');
        if ($this->getRequest()->isPost()){
            $data = $model->filter($_POST);
            $data =  [
                'name' => '奥迪',
                'pid' => '0',
                'isParent' => '1',
                'order' => '1',
                'level' => '1',
                'unique_id' => '1',
                'title' => '奥迪品牌类目',
            ];
            $res = \app\common\model\Cate::load()->save($data);
            dump($res);
            exit();
            if ($data){
                $validate = Cate::getValidate();
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
        return view('cate/create',['meta_title'=>'添加品牌','model'=>$model,'brand'=>$brand]);
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
