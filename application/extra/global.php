<?php

// 全局扩展配置定义文件

defined('__URL__') or define('__URL__',(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:''));

return [
    // 当前请求地址
    'url'                   => __URL__,
];
