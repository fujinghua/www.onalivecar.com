<?php

namespace app\back\controller;

use app\common\components\rbac\Assignment;
use app\common\controller\BackController;
use app\common\model\BackUser;
use app\common\model\Ban;
use app\common\model\AuthItem;
use app\common\model\AuthAssignment;

class BanController extends BackController
{
    /**
     * @description 显示资源列表
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = [];
        $each = 12;
        /**
         * @var $model \app\back\model\Ban
         */
        $model = Ban::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != ''){
            $where[] = ['exp',"`item_name` like '%".$key."%' "];
        }
        $id = trim($request->request('id'));
        if ($id != ''){
            $where = array_merge($where,['back_user_id'=>$id]);
        }
        $id = trim($request->request('id'));
        if ($id != ''){
            $where = array_merge($where,['back_user_id'=>$id]);
        }
        $levels = BackUser::getDepartmentList();
        $level = trim($request->request('level'));
        if ($level != ''){
            if (in_array($level,array_keys($levels))){
                $where =  array_merge($where, ['item_name'=>$level]);
            }
        }
        $BanLists = Ban::getBanList();
        $ban = trim($request->request('ban'));
        if ($ban != ''){
            if (in_array($ban,array_keys($BanLists))){
                $where =  array_merge($where, ['ban'=>$ban]);
            }
        }

        $list = $model->where($where)->paginate($each);

        $this->assign('meta_title', "权限清单");
        $this->assign('model', $model);
        $this->assign('list', $list);
        $this->assign('levels', $levels);
        return view('ban/index');
    }

    /**
     * 分配权限
     * @param null $id
     * @return string|\think\response\View
     */
    public function assignAction($id = null)
    {
        $model = AuthAssignment::load()->where(['user_id'=>$id])->find();
        if (!$model) {
            return '';
        }
        $where = ['type' => '1'];
        $helper = self::getHelper();
        $unAssign = $helper::toArray(AuthItem::load()->where($where)->select());
        $hasAssign = AuthItem::getHasAssign($model->item_name);
        foreach ($unAssign as $key => $item) {
            if (in_array($item, $hasAssign) || $item['name'] == $model['item_name'] || substr($item['name'], 0, 1) == '/' ) {
                unset($unAssign[$key]);
            }
        }
        return view('user/assign',
            [
                'meta_title' => '分配权限',
                'model' => $model,
                'unAssign' => $unAssign,
                'hasAssign' => $hasAssign,
            ]
        );
    }
    /**
     * 添加子权限
     * @param null $id
     * @return array|\think\response\Json
     */
    public function addChildAction($id = null)
    {
        $ret = ['status' => 0, 'info' => '分配失败'];
        $model = AuthItem::get(['name' => $id]);
        $request = $this->getRequest();
        if ($model && $request->isAjax()) {
//            $names = isset($_POST['name']) ? $_POST['name'] : [];
//            $data = [];
//            foreach ($names as $name) {
//                if ($name == $id) {
//                    continue;
//                }
//                $data[] = ['parent' => $id, 'child' => $name];
//            }
//            AuthItemChild::load()->saveAll($data);
//            $ret = ['status' => 1, 'info' => '分配成功'];
        }
        return json($ret);
    }

    /**
     * 移除子权限
     * @param null $id
     * @return array|\think\response\Json
     */
    public function removeChildAction($id = null)
    {
        $ret = ['status' => 0, 'info' => '移除失败'];
        $model = AuthItem::get(['name' => $id]);
        $request = $this->getRequest();
        if ($model && $request->isAjax()) {
//            $names = isset($_POST['name']) ? $_POST['name'] : [];
//            $where['parent'] = $id;
//            $where['child'] = ['in', implode(',', $names)];
//            $result = AuthItemChild::load()->where($where)->delete();
//            if ($result || empty($names)) {
//                $ret = ['status' => 1, 'info' => '移除成功'];
//            }
        }
        return json($ret);
    }

}
