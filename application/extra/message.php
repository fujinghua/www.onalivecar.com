<?php

// 应用短信扩展配置定义文件
return [
    // 待发送的第三方识别码(appKey)
    'app_key'=>'23427166',
    // API MasterSecret
    'app_master_secret'=>'7033a425886313b7ac2288667c2a0d8b',
    // Extend
    'extend'=>'123456',
    // SmsType
    'sms_type'=>'normal',
    // product  APP产品名
    'product_name'=>'Alive House',
    // 短信有效时间。单位秒，默认为1800秒，即30分钟
    'duration'=>'1800',

    // 注册签名
    'sign'=>[
        // 验证签名
        'name'=>'注册验证',
        // 短信模板编号
        'code'=>'SMS_12980145',
    ],
    // 找回或变更 身份验证
    'change'=>[
        // 验证签名
        'name'=>'变更验证',
        // 短信模板编号
        'code'=>'SMS_12980149',
    ],
    // 身份验证
    'iam'=>[
        // 验证签名
        'name'=>'身份验证',
        // 短信模板编号
        'code'=>'SMS_12980149',
    ],
    // 激活 后台
    'alive'=>[
        // 验证签名
        'name'=>'身份验证',
        // 短信模板编号
        'code'=>'SMS_34000270',
    ],
];