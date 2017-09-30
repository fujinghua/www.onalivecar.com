<?php

namespace app\common\lang;


/**
 * Class LangHelper
 * @package app\common\lang
 */
class LangHelper
{
    /**
     * 单例容器
     * @var
     */
    private static $_instance;

    /**
     * 单例接口
     * @return \app\common\lang\LangHelper
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /**
     * 单例
     * LangHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * 模型语言包
     * @var array
     */
    private static $lang = [
        'auth_assignment' => [],
        'auth_item' => [
            // 权限类别
            'type' => ['1' => '角色', '2' => '路由'],
        ],
        'auth_item_child' => [],
        'auth_rule' => [],
        'back_user' => [
            // 账号类型
            'department' => ['1' => '超级权限', '2' => '主管权限', '3' => '普通权限C1', '4' => '普通权限C2'],
            'permission' => ['1' => '超级权限', '2' => '主管权限', '3' => '普通权限C1', '4' => '普通权限C2'],
        ],
        'back_user_log' => [],
        'ban' => [
            // 禁止类别
            'ban' => ['1' => '允许', '2' => '禁止'],
        ],
        'brand' => [],
        'cate' => [
            'type' => ['1' => '汽车分类', '2' => '汽车配置'], //分类类型
            'typeName' => ['1' => 'car', '2' => 'carConfig'], //类别识别
            'car' => ['1' => '品牌分类', '2' => '系列分类', '3' => '车款分类'], //车分类深度
            'carConfig' => ['1' => '一级配置', '2' => '二级配置'], //配置深度
        ],
        'CateProp' => [
        ],
        'cloud' => [
        ],
        'config' => [
            'app' => ['1' => '后台', '2' => '前台'],
            'type' => [
                'array' => '数组',
                'num' => '数值',
                'picture' => '图片',
                'select' => '选择',
                'text' => '短文本',
                'textarea' => '长文本',
                'toggle' => '开关',
            ],
        ],
        'contact' => [
            'readed' => ['1' => '未读', '2' => '已读']
        ],
        'contact_read' => [],
        'deal' => [
            'houseType' => ['1' => '新车', '2' => '二手车'],
            'status' => ['1' => '预定', '2' => '成交', '3' => '取消'],
            'payWay' => ['1' => '一次性', '2' => '按揭', '3' => '分期付款'],
        ],
        'delete_log' => [],
        'department' => [
            // 部门等级
            'level' => ['1' => '超级权限', '2' => '主管权限', '3' => '普通权限C1', '4' => '普通权限C2'],
            'level_en' => ['1' => 'SUP', '2' => 'VIP', '3' => 'COMMON1', '4' => 'COMMON2'],
        ],
        'download' => [],
        'home_user' => [
            // 账号类别
            'type' => ['1' => '普通用户', '2' => '成交用户', '3' => '贵宾用户', '4' => '卖家用户'],
            // 注册方式
            'reg_type' => ['1' => '手机', '2' => '电脑', '3' => '平板'],
        ],
        'home_user_log' => [
            'user_agent_type' => ['1' => 'PC', '2' => 'Android', '3' => 'IOS'],
        ],
        'hot' => [
            'pass' => ['1' => '已通过', '2' => '审核中', '3' => '未通过'],
            'type' => ['2' => '新车', '3' => '二手车', '4' => '', '5' => '交易'],
            'typeName' => ['2' => 'newHouse', '3' => 'handHouse', '4' => 'build', '5' => 'deal'],
            'app' => ['1' => '后台', '2' => '前台'],
            'status' => ['1' => '预定', '2' => '上架', '3' => '下架', '4' => '失效'],
            'createStatus' => ['1' => '预定', '2' => '上架', '3' => '下架'],
        ],
        'images' => [
            // 图片所属类别
            'type' => ['1' => '', '2' => '新车', '3' => '二手车', '4' => '家装'],
        ],
        'label' => [
            // 标签所属类别
            'type' => ['1' => '预定', '2' => '用户', '3' => '用户', '4' => '新车', '5' => '二手车', '6' => '', '7' => '客服'],
        ],
        'label_park' => [
            // 标签所属类别
            'type' => ['1' => '预定', '2' => '用户', '3' => '用户', '4' => '新车', '5' => '二手车', '6' => '', '7' => '客服'],
        ],
        'login_log' => [
            'app' => ['1' => '后台账户', '2' => '前端用户'],
        ],
        'menu' => [],
        'notice' => [
            // 状态
            'pass' => ['1' => '待审核', '2' => '已通过', '3' => '未通过', '4' => '保存', '5' => '已推送'],
            // 是否已读
            'read' => ['1' => '未读', '2' => '已读'],
        ],
        'notice_read' => [],
        'on_links' => [],
        'opinion' => [
            // 是否已读
            'read' => ['1' => '未读', '2' => '已读'],
        ],
        'opinion_read' => [],
        'region' => [],
        'session' => [],
        'slider' => [
            'type' => ['1' => '默认轮播', '2' => '首页轮播'],
            'typeName' => ['1' => 'default', '2' => 'home'],
            'typeMax' => ['1' => '6', '2' => '6'], //轮播上限
            'app' => ['1' => '后台', '2' => '前台'],
            'status' => ['1' => '预定', '2' => '上架', '3' => '下架', '4' => '失效'],
            'createStatus' => ['1' => '预定', '2' => '上架', '3' => '下架'],
        ],
        'type' => [
            // 类型所属类别
            'type' => ['1' => '预定', '2' => '用户', '3' => '用户', '4' => '新车', '5' => '二手车', '6' => '', '7' => '客服'],
        ],
        'type_park' => [
            // 类型所属类别
            'type' => ['1' => '预定', '2' => '用户', '3' => '用户', '4' => '新车', '5' => '二手车', '6' => '', '7' => '客服'],
        ],
        'upload' => [],
        'walk' => [],
    ];

    /**
     * 排序语言包
     * @var array
     */
    private static $order = [
        // 月
        'mouth' => [
            '1' => '本月',
            '2' => '下月',
            '3' => '三月内',
            '4' => '六月内',
            '5' => '前三月',
            '6' => '前六月',
        ],
        // 周
        'week' => [
            '1' => '本周',
            '2' => '下周',
            '3' => '三周内',
        ],
        // 天
        'day' => [
            '1' => '今日',
            '2' => '明天',
            '3' => '后天',
            '4' => '五天以内',
            '5' => '一周以内',
        ],
    ];

    /**
     * @param null $table
     * @return array
     */
    public function get($table = null)
    {
        $ret = [];
        if (is_string($table) && $table != '') {
            $ret = isset(self::$lang[$table]) ? self::$lang[$table] : [];
            if (!is_array($ret)) {
                $ret = [];
            }
        }
        return $ret;
    }

    /**
     * @param null $table
     * @param null $field
     * @return array
     */
    public function getField($table = null, $field = null)
    {
        $ret = [];
        if (is_string($table) && $table != '' && is_string($field) && $field != '') {
            $ret = isset(self::$lang[$table][$field]) ? self::$lang[$table][$field] : [];
            if (!is_array($ret)) {
                $ret = [];
            }
        }
        return $ret;
    }

    /**
     * @param null $table
     * @param null $field
     * @param null $key
     * @return string
     */
    public function getValue($table = null, $field = null, $key = null)
    {
        $ret = '';
        $key = (string)($key);
        if (is_string($table) && $table != '' && is_string($field) && $field != '' && is_string($key) && $key != '') {
            $ret = isset(self::$lang[$table][$field][$key]) ? self::$lang[$table][$field][$key] : '';
            if (!is_string($ret)) {
                $ret = '';
            }
        }
        return $ret;
    }


    /**
     * @param null $type
     * @return array
     */
    public function getOrder($type = null)
    {
        $ret = [];
        if (is_string($type) && $type != '') {
            $ret = isset(self::$order[$type]) ? self::$order[$type] : [];
            if (!is_array($ret)) {
                $ret = [];
            }
        }
        return $ret;
    }

}

