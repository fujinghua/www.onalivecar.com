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
     * @return \app\manage\model\Identity
     */
    private static $_manageUser = '\app\manage\model\Identity';

    /**
     * @return \app\manage\model\Menu
     */
    private static $_menu = '\app\manage\model\Menu';

    /**
     * @return \app\manage\model\BackUserLog
     */
    private static $_manageLog = '\app\manage\model\BackUserLog';

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
     * @return \app\manage\model\Identity | null
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
     * @return \app\manage\model\Menu | null
     */
    public static function getMenu(){
        if (!class_exists(self::$_menu)){
            return null;
        }
        return new self::$_menu;
    }

    /**
     * @return \app\manage\model\BackUserLog | null
     */
    public static function getBackUserLog(){
        if (!class_exists(self::$_manageLog)){
            return null;
        }
        return new self::$_manageLog;
    }

}
