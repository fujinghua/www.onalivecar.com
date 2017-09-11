<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Cate;
use app\common\model\CateProp;

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
            $unique = 'car';
            $model->unique = $unique;
            $data = $model->filter($_POST);
            if (isset($data['level'])){
                $level = $data['level'];
                $validate = Cate::getValidate();
                $data['order'] = (Cate::load()->where(['level'=>$data['level']])->max('`order`')+1);
                if ($level == '3'){
                    $validate->scene('createCar');
                    $data['pid'] = $this->getRequest()->request('series_id');
                    $data['isParent'] = '1';
                    $data['title'] = isset($data['title']) ? $data['title'] : $data['name'].'车款类目';
                }elseif ($level=='2'){
                    $validate->scene('createSeries');
                    $data['pid'] = $this->getRequest()->request('brand_id');
                    $data['isParent'] = '1';
                    $data['title'] = isset($data['title']) ? $data['title'] : $data['name'].'车型类目';
                }else{
                    $data['level'] = '1';
                    $validate->scene('createBrand');
                    $brandModel = new Brand();
                    $pinyin = \app\common\components\ChineseToPinyin::encode($data['name'],'all');
                    $letter = substr($pinyin,0,1);
                    $order = Brand::load()->where(['letter'=>'a'])->max('`order`');
                    $logo = $this->getRequest()->request('logo');
                    $tmp = [
                        'name'=>$data['name'],
                        'letter'=>$letter,
                        'pinyin'=>implode('',explode(' ',$pinyin)),
                        'icon'=>$logo,
                        'order'=>($order+1),
                    ];
                    $brandModel->save($tmp);
                    $prefix = ROOT_PATH.'public';
                    $path = $prefix.'/static/uploads/brand/'.$letter.'/'.$brandModel->id.'/';
                    $to = $path.pathinfo($logo,PATHINFO_BASENAME);
                    $from = $logo;
                    $brandModel->icon = $to;
                    $this->copy($from,$to);
                    $icon = pathinfo($from,PATHINFO_DIRNAME).'/'.pathinfo($from,PATHINFO_FILENAME).'_icon.'.pathinfo($from,PATHINFO_EXTENSION);
                    $to = $path.pathinfo($icon,PATHINFO_BASENAME);
                    $this->copy($icon,$to);
                    $brandModel->isUpdate(true)->save();
                    $data['unique_id'] = $brandModel->id;
                    $data['pid'] = '0';
                    $data['isParent'] = '1';
                    $data['title'] = isset($data['title']) ? $data['title'] : $data['name'].'品牌类目';
                }
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
