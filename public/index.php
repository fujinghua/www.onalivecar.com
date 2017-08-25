<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//可以绑定模块处理
if($_SERVER['HTTP_HOST'] == '47.94.105.213'){ //绑定三级域名 cms 到后台
    define('BIND_MODULE','back');
}elseif ($_SERVER['HTTP_HOST'] == 'm.onalivecar.com'){ //绑定三级域名 m 到手机端
    define('BIND_MODULE','phone');
}elseif ($_SERVER['HTTP_HOST'] == '47.94.105.213:88'){ //绑定三级域名 api 到接口
    define('BIND_MODULE','api');
}else{ // 其他所有的请求绑定到后台
    define('BIND_MODULE','back');
}
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
