<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\AuthAssignment;
use app\common\model\AuthItem;
use app\common\model\AuthItemChild;
use app\common\model\AuthRule;
use app\common\model\BackUser;

class AuthController extends BackController
{

    /**
     * @description 显示清单
     * @return \think\Response
     */
    public function indexAction()
    {
        $where = [];
        $each = 12;
        $model = AuthItem::load();
        $key = trim($this->getRequest()->request('keyword'));
        if ($key != '') {
            $nameWhere = ' `name` like ' . ' \'%' . $key . '%\' ';
            $model->where($nameWhere);
        }
        $type = trim($this->getRequest()->request('type'));
        if ($type != '') {
            if (in_array($type, array_keys($model->getLists('type')))) {
                $where = array_merge($where, ['type' => $type]);
            }
        }
        $list = $model->where($where)->order('created_at DESC')->paginate($each);

        $this->assign('list', $list);
        return view('auth/index', ['model' => $model]);
    }

    /**
     * 显示创建资源表单页.| 保存新建的资源
     *
     * @param $type int
     * @return \think\Response
     */
    public function createAction($type = null)
    {
        if (!$type) {
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '2';
        }
        $model = new AuthItem();
        if ($this->getRequest()->isPost()) {
            $data = $model->filter($_POST);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($data) {
                $type = isset($data['type']) ? $data['type'] : '2';
                if (!in_array($type, array_keys($model->getLists('type')))) {
                    $this->error('无效类型', 'create', '', 1);
                }
                if ($type == '2' && isset($data['name'])) {
                    $data['name'] = preg_replace("/(\/)+/", "/", $data['name']);
                    if (!($data['name'] == '' || $data['name'] == '/')) {
                        $tmpData = trim($data['name'], '/');
                        $tmp = explode('/', $tmpData);
                        if (count($tmp) < 3) {
                            array_push($tmp, '*');
                            $data['name'] = '/' . implode('/', $tmp);
                        }
                    }
                    if (!($data['name'] == '' || $data['name'] == '/')) {
                        $tmp = explode('/', $data['name']);
                        if (count($tmp) <= 3) {

                        }
                    } else {
                        $this->error('无效路由', 'create', '', 1);
                    }
                    if (substr($data['name'], 0, 1) != '/') {
                        $data['name'] = '/' . $data['name'];
                    }
                }
                if (AuthItem::get($data['name'])) {
                    $this->error('已存在此权限', 'create', '', 1);
                }
                if ($model->save($data)) {
                    $this->success('添加成功', 'create', $model, 1);
                } else {
                    $error = $model->getError();
                    $this->error($error, 'create', '', 1);
                }
            }
        }
        $model->type = $type;
        return view('auth/create', ['meta_title' => '添加权限', 'model' => $model]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function viewAction($id)
    {
        $this->assign('meta_title', "详情");
        $model = AuthItem::load()->where(['id' => $id])->find();
        return view('auth/view', ['model' => $model]);
    }

    /**
     * @description 编辑
     * @param $id
     * @return string
     */
    public function updateAction($id = null)
    {
        $model = AuthItem::get(['name' => $id]);
        if (!$model) {
            return '';
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = $model->filter($_POST);
            if (!in_array($data['type'], array_keys($model->getLists('type')))) {
                $this->error('无效类型', url('update', ['id' => $id]), [], 1);
            }
            if ($data['type'] == '2' && $data['name'] != $model->name) {
                $name = preg_replace("/(\/)+/", "/", $data['name']);
                if (!($name == '' || $name == '/')) {
                    $tmpData = trim($name, '/');
                    $tmp = explode('/', $tmpData);
                    if (count($tmp) < 3) {
                        array_push($tmp, '*');
                        $name = '/' . implode('/', $tmp);
                    }
                }
                if (!($name == '' || $name == '/')) {
                    $tmp = explode('/', $name);
                    if (count($tmp) <= 3) {

                    }
                } else {
                    $this->error('无效路由', url('update', ['id' => $id]), [], 1);
                }
                if (substr($name, 0, 1) != '/') {
                    $name = '/' . $name;
                }
                $data['name'] = $name;
            }
            if ($data['name'] == $model->name) {
                unset($data['name']);
            } else {
                if (AuthItem::get($data['name'])) {
                    $this->error('已存在此权限', 'create', '', 1);
                }
            }
            if ($data['type'] == $model->type) {
                unset($data['type']);
            }
            if ($data) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                //更新
                $where['name'] = $model->name;
                $res = AuthItem::update($data, $where);
                if (isset($data['name'])) {
                    $id = $data['name'];
                    AuthItem::load()->where($where)->setField('name', $data['name']);
                }
                if ($res) {
                    $this->success('更新成功', url('update', ['id' => $id]), 1);
                } else {
                    $this->error('更新失败', url('update', ['id' => $id]), [], 1);
                }
            } else {
                $this->success('更新成功', url('update', ['id' => $id]), [], 1);
            }
        }
        return view('auth/update', ['meta_title' => '更新权限', 'model' => $model]);
    }

    /**
     * 分配权限
     * @param null $id
     * @return string|\think\response\View
     */
    public function assignAction($id = null)
    {
        $where = ['name' => $id];
        $model = AuthItem::get($where);
        if (!$model) {
            return '';
        }
        $helper = self::getHelper();
        $hasAssign = AuthItem::getHasAssign($id);
        $unAssign = $helper::toArray(AuthItem::load()->field('name')->select());
        foreach ($unAssign as $key => $item) {
            if (in_array($item, $hasAssign) || $item['name'] == $id) {
                unset($unAssign[$key]);
            }
        }
        return view('auth/assign',
            [
                'meta_title' => '分配权限',
                'model' => $model,
                'unAssign' => $unAssign,
                'hasAssign' => $hasAssign,
            ]
        );
    }

    /**
     * 设置角色
     * @param null $id
     * @param null $role
     * @return array|\think\response\Json
     */
    public function setRoleAction($id = null, $role = null)
    {
        $ret = ['status' => 0, 'info' => '分配角色失败'];
        if (!(empty($id) || empty($role)) && $this->getRequest()->isAjax()) {
            if (!AuthItem::get(['name' => $role])) {
                $ret = '该角色不存在';
            } elseif (!BackUser::get(['id' => $id])) {
                $ret = '该账号不存在';
            } elseif (!AuthAssignment::load()->where(['item_name' => $role, 'user_id' => $id])->find()) {
                $ret = '该账号已属于该角色';
            }else{
                $data['item_name'] = $role;
                $data['user_id'] = $id;
                $data['create_at'] = date('Y-m-d H:i:s');
                AuthAssignment::load()->save($data);
                $ret = ['status' => 1, 'info' => '分配角色成功'];
            }
        }
        return json($ret);
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
            $names = isset($_POST['name']) ? $_POST['name'] : [];
            $data = [];
            foreach ($names as $name) {
                if ($name == $id) {
                    continue;
                }
                $data[] = ['parent' => $id, 'child' => $name];
            }
            AuthItemChild::load()->saveAll($data);
            $ret = ['status' => 1, 'info' => '分配成功'];
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
            $names = isset($_POST['name']) ? $_POST['name'] : [];
            $where['parent'] = $id;
            $where['child'] = ['in', implode(',', $names)];
            $result = AuthItemChild::load()->where($where)->delete();
            if ($result || empty($names)) {
                $ret = ['status' => 1, 'info' => '移除成功'];
            }
        }
        return json($ret);
    }

    /**
     * 删除指定资源
     * @return \think\response\Json
     */
    public function deleteAction()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : [];
        $ret = ['status' => 0, 'info' => '删除失败'];
        if ($this->getRequest()->isAjax()) {
            $where['name'] = ['in', implode(',', $id)];
            $result = AuthItem::load()->where($where)->delete();
            if ($result) {
                $ret = ['status' => 1, 'info' => '删除成功'];
            }
        }
        if (empty($id)) {
            $ret = ['status' => 1, 'info' => '删除成功'];
        }
        return json($ret);
    }

}