<?php
//配置文件
return [

    // 异常页面的模板文件
    'exception_tmpl'         => ROOT_PATH . DS . 'application' . DS . 'common' . DS . 'view' . DS . 'layouts' . DS . 'api-error.html',

    // 抛出了HttpException异常，可以支持定义单独的异常页面的模板地址
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 =>  'common@layouts/api-404',
        // 定义 402
        402 =>  'common@layouts/api-402',
    ],
];