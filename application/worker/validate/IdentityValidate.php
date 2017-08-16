<?php

namespace app\home\validate;

use app\common\validate\Validate;

class IdentityValidate extends Validate
{
    protected $password;
    protected $rePassword;

    /**
     * @var array
     */
    protected $rule = [
        '__token__|校验数据' =>  ['token'],
        'username|用户名'  =>  ['require','max'=>32,'min'=>1,'regex:/^(?!_)(?!\d)(?!.*?_$)[\w]+$/','unique:back_user,username'],
        'department_id|部门'  =>  ['require','number','exist:department,id'],
        'phone|手机' =>  ['regex:/^(1)([\d]){10}$/','unique:back_user,phone'],
        'email|邮箱' =>  ['email','unique:back_user,email'],
//        'password|登录密码' =>  ['require','max'=>32,'min'=>6,'regex:/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/'],
        'password|登录密码' =>  ['require','max'=>32,'min'=>6,],
        'rePassword|确认密码' =>  ['require','max'=>32,'min'=>6, 'confirm:password'],
        'code'=>['unique:back_user,code'],
    ];

    /**
     * @var array
     */
    protected $message = [
        '__token__.token'  =>  ':attribute 无效',
        'department_id.require'  =>  ':attribute 不能为空',
        'department_id.number'  =>  ':attribute 无效',
        'department_id.exist'  =>  ':attribute 不存在',
        'username.require'  =>  ':attribute 不能为空',
        'username.regex'  =>  ':attribute 只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！',
        'username.exist'  =>  ':attribute 不存在',
        'username.usernameExist'  =>  ':attribute 不存在',
        'password.require'  =>  ':attribute 不能为空',
        'password.regex'  =>  ':attribute 至少由数字、字符、特殊字符三种中的两种组成',
        'rePassword.require'  =>  ':attribute 不能为空',
        'rePassword.comparePassword'  =>  '两次密码 不一致',
        'email' =>  ':attribute 不合法',
        'phone' =>  ':attribute 不合法',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['username','password'],
        'update'  =>  ['department_id','username','phone','password','rePassword'],
        'save'  =>  [],
        'loginAjax'   =>  ['username'=> 'require|usernameExist:back_user,username'],
        'login'  =>  ['username','password','__token__'],
        'signUp'  =>  ['department_id','username','phone','password'],
        'register'  =>  ['department_id','username','phone','password','rePassword','__token__'],
    ];

    /**
     * @description 自定义验证规则 用户名是否存在
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @param string    $param  字段
     * @return bool|string
     */
    protected function usernameExist($value, $rule, $data,$param)
    {
        $ret = !$this->unique($value, $rule, $data, $param);
        return $ret;
    }

    /**
     * @description 自定义验证规则
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool|string
     */
    protected function comparePassword($value, $rule, $data)
    {
        $rule = !empty($rule) ? $rule : 'password';
        if (!isset($data[$rule]) || empty($value)) {
            // 两次密码 不一致
            return false;
        }

        // 密码对比
        if (isset($data[$rule]) && $value === $data[$rule]) {
            return true;
        }
        return false;
    }

}