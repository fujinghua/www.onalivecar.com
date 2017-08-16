<?php

namespace app\common\components;

/**
 * Class Configs
 * @package app\common\components
 */
class Configs
{
    const CACHE_TAG = 'manage';

    /**
     * @var integer Cache duration. Default to a hour.
     */
    public static $cacheDuration = 3600;

    /**
     * @return \think\Cache
     */
    private static $_cache = '\think\Cache';

    /**
     * @return \app\back\model\Identity
     */
    private static $_manageUser = '\app\back\model\Identity';

    /**
     * @return \app\back\model\Menu
     */
    private static $_menu = '\app\back\model\Menu';

    /**
     * @return \app\back\model\BackUserLog
     */
    private static $_manageLog = '\app\back\model\BackUserLog';

    /**
     * @return \think\Cache | null
     */
    public static function getCache(){
        if (!class_exists(self::$_cache)){
            return null;
        }
        return new self::$_cache();
    }

    /**
     * @return \app\back\model\Identity | null
     */
    public static function getIdentity(){
        if (!class_exists(self::$_manageUser)){
            return null;
        }
        return new self::$_manageUser();
    }

    /**
     * @return \app\common\components\rbac\AuthManager
     */
    public static function getAuthManager(){
        return \app\common\components\rbac\AuthManager::getInstance();
    }

    /**
     * @return \app\back\model\Menu | null
     */
    public static function getMenu(){
        if (!class_exists(self::$_menu)){
            return null;
        }
        return new self::$_menu;
    }

    /**
     * @return \app\back\model\BackUserLog | null
     */
    public static function getBackUserLog(){
        if (!class_exists(self::$_manageLog)){
            return null;
        }
        return new self::$_manageLog;
    }

}
