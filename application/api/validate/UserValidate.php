<?php

namespace app\api\validate;

use app\common\validate\Validate;

class UserValidate extends Validate
{
    protected $password;
    protected $rePassword;

    /**
     * @var array
     * 'regex:/^(?!_)(?!\d)(?!.*?_$)[\w]+$/',
     */
    protected $rule = [
        '__token__|校验数据' => ['token'],
        'username|账号' => ['max' => 32, 'min' => 1, 'unique:home_user,username'],
        'phone|手机' => ['regex:/^(1)([\d]){10}$/', 'unique:home_user,phone'],
        'email|邮箱' => ['email', 'unique:home_user,email'],
//        'password|登录密码' =>  ['require','max'=>32,'min'=>6,'regex:/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/'],
//        'password|登录密码' =>  ['require','max'=>32,'min'=>6,'regex:/(?!^\\d+$)(?!^[a-zA-Z]+$)(?!^[\~\!@#\$%\^&\*\(\)_\+\/\.,;\'\\\[\]<>\?:"\|\{\}]+$).{6,20}/'],
//        'password|登录密码' =>  ['require','max'=>32,'min'=>6,'regex:/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{6,32}$/'],
        'password|登录密码' => ['require', 'max' => 32, 'min' => 6],
        'rePassword|确认密码' => ['require', 'max' => 32, 'min' => 6, 'confirm:password'],
        'code' => ['unique:home_user,code'],
    ];

    /**
     * @var array
     */
    protected $message = [
        '__token__.token' => ':attribute 无效',
      // 'username.require' => ':attribute 不能为空',
        'username.regex' => ':attribute 只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！',
        'username.exist' => ':attribute 不存在',
        'username.usernameExist' => ':attribute 不存在',
        'password.require' => ':attribute 不能为空',
        'password.regex' => ':attribute 至少由数字、字符、特殊字符三种中的两种组成',
        'rePassword.require' => ':attribute 不能为空',
        'rePassword.comparePassword' => '两次密码 不一致',
        'rePassword.confirm' => '两次密码 不一致',
        'email' => ':attribute 不合法',
        'phone.unique' => '此手机号码已被注册',
        'phone' => ':attribute 不合法',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create' => ['username', 'password'],
        'update' => [
            'username' => ['max' => 32, 'min' => 1,'unique:home_user,username'],
            'phone',
            'email',
            'password' => ['max' => 32, 'min' => 6],
            'rePassword' => ['max' => 32, 'min' => 6, 'confirm:password']
        ],
        'reset' => [
            'password' => ['max' => 32, 'min' => 6],
            'rePassword' => ['max' => 32, 'min' => 6, 'confirm:password']
        ],
        'save' => [],
        'loginAjax' => ['username' => 'require|usernameExist:home_user,username', 'password'],
        'login' => ['username' => 'require|usernameExist:home_user,username', 'password'],
        'signUp' => ['username', 'phone', 'password'],
        'register' => ['username', 'phone', 'password', 'rePassword', '__token__'],
    ];

    /**
     * @description 自定义验证规则 用户名是否存在
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则
     * @param array $data 数据
     * @param string $param 字段
     * @return bool|string
     */
    protected function usernameExist($value, $rule, $data, $param)
    {
        $ret = !$this->unique($value, $rule, $data, $param);
        return $ret;
    }

    /**
     * @description 自定义验证规则
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则
     * @param array $data 数据
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