<?php

namespace app\common\lang;


/**
 * Class LangHelper
 * @package app\common\lang
 */
class SiteHelper
{
    /**
     * 单例容器
     * @var
     */
    private static $_instance;

    /**
     * 单例接口
     * @return \app\common\lang\SiteHelper
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
     * SiteHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * 模型语言包
     * @var array
     */
    private static $lang = [
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

}

