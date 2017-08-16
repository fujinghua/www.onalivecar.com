<?php

// 前端用户登录扩展配置定义文件
return [
    // 当前SESSION登录默认标识
    'unique'                       => 'user',
    // Identity 位置
    'default_model'            => '\app\home\model\User',
    // 登录路由
    'loginUrl'                        => 'home/user/login',
    // 退出路由
    'logoutUrl'                       => 'home/user/logout',
    // 注册路由
    'registerUrl'                     => 'home/user/register',
    // 注册路由
    'resetUrl'                        => 'home/user/reset',
    // 当前用户对象SESSION　key
    '_user'                       => '__USER__',
    // 当前用户 自动登录 SESSION　key
    '_auth_key'                       => '__AUTH_KEY_USER__',
    // 当前用户 登录有效期 SESSION　key
    '_duration'                       => '__DURATION_USER__',
    // 当前用户 登录 默认有效期时间
    '_default_duration'               => '1440',
    // 记住我 当前用户 登录 默认有效期时间
    '_rememberMe_duration'            => 60*60*24*7,
    // 重置密码 默认有效期时间
    '_passwordResetTokenExpire'       => 60*60*2,
    // 登录成功 返回URL
    '_home_url'                     => '__USER_URL__',
    // 是否自动登录
    '_auto_login'                     => true,
];
