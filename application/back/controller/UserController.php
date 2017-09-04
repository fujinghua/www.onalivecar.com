<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace app\back\controller;


use app\common\controller\BackController;
use app\common\model\BackUser;
use app\back\model\Identity;
use app\common\model\Department;

/**
 * 用户控制器
 * @author Sir Fu
 */
class UserController extends BackController
{

    /**
     * @description 日志
     * @return string
     */
    public function logAction()
    {
        $this->assign('meta_title', "日志信息");
        return view('user/log');
    }

    /**
     * @description 新增
     * @param $id
     * @return string
     */
    public function resetPasswordAction($id = 0)
    {
        $id = intval($id);
        if (empty($id)) {
            $id = '1';
        }
        $model = BackUser::load()->where(['id' => $id])->find();
        return view('user/reset', ['meta_title' => '修改密码', 'model' => $model]);
    }

    /**
     * @description 浏览
     * @param $id
     * @return string
     */
    public function viewAction($id = 0)
    {
        $id = intval($id);
        if (empty($id)) {
            $id = '1';
        }
        $model = BackUser::load()->where(['id' => $id])->find();
        return view('user/view', ['meta_title' => '个人信息', 'model' => $model]);
    }

    /**
     * @description 清单
     * @param bool $super
     * @return string
     */
    public function indexAction($super = false)
    {
        $where = ['is_delete' => '1','id'=>['not in','1']];
        $each = 12;
        $model = BackUser::load();
        $request = $this->getRequest();
        $key = trim($request->request('keyword'));
        if ($key != '') {
            $nameWhere = ' `real_name` like ' . ' \'%' . $key . '%\'';
            $model->where($nameWhere);
        }
        $typeList = BackUser::getDepartmentList();
        $department_id = trim($request->request('department_id'));
        if ($super == 'true' || $department_id != '') {
            $type = ($super == 'true') ? '1' : $department_id;
            if (in_array($type, array_keys($typeList))) {
                $where = array_merge($where, ['department_id' => $type]);
            }
        }

        $list = $model->where($where)->order('id DESC')->paginate($each);

        $this->assign('meta_title', "管理员清单");
        $this->assign('typeList', $typeList);
        $this->assign('list', $list);
        $this->assign('super', $super);
        return view('user/index');
    }

    /**
     * @description Register Home Page
     * @return \think\response\View
     */
    public function registerAction()
    {
        $identity = new Identity();
        $request = $this->getRequest();
        $token = $request->request('__token__');

        if ($request->isPost() && $token) {
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = [];
            $data['department_id'] = $request->post('department_id');
            $data['username'] = $request->post('username');
            $data['phone'] = $request->post('phone');
            $data['password'] = $request->post('password');
            $data['rePassword'] = $request->post('rePassword');
            $validate = Identity::getValidate();
            $validate->scene('register');
            if ($validate->check($data)) { //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = $identity->signUp($data);
                if ($res instanceof Identity) {
                    $this->success('注册成功', 'login');
                } else {
                    $this->error($res, 'register', '', 1);
                }
            } else {
                $this->error($validate->getError(), 'register', '', 1);
            }
        }
        $typeList = BackUser::getDepartmentList();
        $this->assign('typeList', $typeList);
        return view('user/create', ['meta_title' => '会员注册']);
    }

    /**
     * @description 编辑
     * @param $id
     * @return string
     */
    public function updateAction($id = null)
    {
        $where = ['id' => $id];
        $model = BackUser::load()->where($where)->find();
        if (!$model) {
            return '';
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = [];
            $data['department_id'] = $request->post('department_id');
            $data['phone'] = $request->post('phone');
            $data['password'] = $request->post('password');
            $data['rePassword'] = $request->post('rePassword');
            $old_phone = $request->post('old_phone');
            $old_department_id = $request->post('old_department_id');
            if ($model->phone != $old_phone || $model->department_id != $old_department_id) {
                $this->error('非法操作', url('update', ['id' => $id]), [], 1);
            }
            if ($model->department_id == $data['department_id']) {
                unset($data['department_id']);
            }
            if ($model->phone == $data['phone']) {
                unset($data['phone']);
            }
            $validate = Identity::getValidate();
            $validate->scene('update');
            if ($validate->check($data)) { //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = Identity::load()->updateUser($id, $data);
                if ($res instanceof Identity) {
                    $this->success('更新成功', url('update', ['id' => $id]), [], 1);
                } else {
                    $this->error($res, url('update', ['id' => $id]), [], 1);
                }
            } else {
                $this->error($validate->getError(), url('update', ['id' => $id]), [], 1);
            }
        }
        $typeList = BackUser::getDepartmentList();
        $this->assign('typeList', $typeList);
        return view('user/update', ['meta_title' => '更新信息', 'departmentList' => Identity::getDepartmentList(), 'model' => $model]);
    }

    /**
     * @description 删除
     * @param $id
     * @return string
     */

    /**
     * 删除指定资源
     * @return \think\response\Json
     */
    public function deleteAction()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : [];
        $ret = ['status' => 0, 'info' => '删除失败'];
        if ($this->getRequest()->isAjax()) {
            if (in_array('1', $id)) {
                $id = array_flip($id);
                unset($id[1]);
                $id = array_flip($id);
            }
            $where['id'] = ['in', implode(',', $id)];
            $result = BackUser::load()->where($where)->setField('is_delete', '0');
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
