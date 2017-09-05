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
namespace app\api\controller;


use app\common\controller\ApiController;
use app\api\model\User;

/**
 * 用户控制器 因为不需要检查登陆状态所以继承基础控制器 BaseController, 而不是继承ApiController
 * @author Sir Fu
 */
class UserController extends ApiController
{
    /**
     * 初始化方法
     * @author Sir Fu
     */
    protected function _initialize()
    {

        parent::_initialize();
        if ($this->getRequest()->ip() != '127.0.0.1') {
            config('app_debug', false);
        }
        // 初始化
        $this->init('user');

        $this->setSession('user');
    }

    /**
     * @description 数据
     * @return mixed
     */
    public function indexAction()
    {
        return json([]);
    }

    /**
     * @description back login
     * @author Sir Fu
     */
    public function loginAction()
    {
        //$this->getRequest()->isAjax() &&
        if ($this->getRequest()->isPost()) {
            $username = trim($this->getRequest()->request('username'));
            $password = $this->getRequest()->request('password');
//        // 图片验证码校验
//        if (!$this->checkVerify(input('post.verify')) && 'localhost' !== request()->host() && '127.0.0.1' !== request()->host()) {
//            $this->error('验证码输入错误');
//        }
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = [
                'username' => $username,
                'password' => $password,
            ];

            $validate = User::getValidate();
            $validate->scene('loginAjax');

            if ($validate->check($data)) {
                //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $identity = new User();
                $identity->username = $username;
                $identity->password = $password;
                $res = $identity->login();
                if ($res instanceof User) {
                    $info = ['token' => $res->token];
                    $this->ajaxReturn(['code' => 0000, 'code_str' => '登录成功', 'info' => $info]);
                } else {
                    $this->ajaxReturn(['code' => 1006, 'code_str' => $res]);
                }
            } else {
                $this->ajaxReturn(['code' => 1004, 'code_str' => $validate->getError()]);

            }
        }
        if ($this->isGuest()) {
            $this->goHome();
        }
    }

    /**
     * @description Logout action.
     */
    public function logoutAction()
    {
        User::logout();
        return json([]);
    }

    /**
     * @description 足迹
     * @return string
     */
    public function logAction()
    {
        return json([]);
    }

    /**
     * @description 新增
     * @return string
     */
    public function resetPasswordAction()
    {
        return json([]);
    }

    /**
     * @description 新增
     * @param $id
     * @return string
     */
    public function resetAction($id = 0)
    {
        if (empty($id)) {
            throw new \think\Exception\HttpException(404, '该账号不存在', null, ['code' => '404', 'msg' => '该账号不存在', 'info' => '该账号不存在'], '404');
        }
        $find = false;

        if ($model = User::load()->where(['id' => $id])->find()) {
            $find = true;
        } else if ($model = User::findByUsername($id)) {
            $find = true;
        } else if ($model = User::findByPhone($id)) {
            $find = true;
        } else if ($model = User::findByPasswordResetToken($id)) {
            $find = true;
        }

        if (!$find) {
            throw new \think\Exception\HttpException(404, '该账号不存在', null, ['code' => '404', 'msg' => '该账号不存在', 'info' => '该账号不存在'], '404');
        }

        $request = $this->getRequest();
        if ($request->isPost() || $request->isAjax()) {
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = [];
            $data['oldPassword'] = $request->post('newPassword');
            $data['password'] = $request->post('password');
            $data['rePassword'] = $request->post('rePassword');
            $validate = User::getValidate();
            $validate->scene('reset');
            if ($validate->check($data)) { //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = User::load()->resetUser($id, $data);
                if ($res) {
                    $this->success('更新成功', url('reset', ['id' => $id]), [], 1);
                } else {
                    $this->error('原密码不正确', url('reset', ['id' => $id]), [], 1);
                }
            } else {
                $this->error($validate->getError(), url('reset', ['id' => $id]), [], 1);
            }
        }

        return json([]);
    }


    public function registerAction()
    {
        $identity = new User();
        $request = $this->getRequest();
        // $token = $request->request('__token__');
        if ($request->isPost()) {
            // 调用当前模型对应的Identity验证器类进行数据验证
            $data = [];
            $data['username'] = $request->post('phone');
            $data['phone'] = $request->post('phone');
            $data['password'] = $request->post('password');
            $data['rePassword'] = $request->post('rePassword');
            $validate = User::getValidate();
            $validate->scene('register');
            if ($validate->check($data)) { //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = $identity->register($data);
                if ($res instanceof User) {
                    $this->ajaxReturn(array("code" => "0000", 'code_str' => "注册成功"));
                } else {
                    $this->ajaxReturn(array("code" => "0001", 'code_str' => "注册失败"));
                }
            } else {
                $this->ajaxReturn(array("code" => 1004, 'code_str' => "信息验证失败"));
            }
        } else {
            exit(json_encode(['code' => 1008, 'msg' => '参数错误']));
        }
    }

    /**
     * @description 删除
     * @param $id
     * @return string
     */
    public function deleteAction($id = 0)
    {
        return json(['code' => 1, 'msg' => '删除成功', 'delete_id' => $id]);
    }
}
