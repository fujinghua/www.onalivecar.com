<?php

namespace backend\components\region;


use Yii;
use yii\db\Connection;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;

class RegionConfigs extends \yii\base\Object
{

    const CACHE_TAG = 'backend';
    /**
     * @var Connection Database connection.
     */
    public $db = 'db';

    /**
     * @var Cache Cache component.
     */
    public $cache = 'cache';

    /**
     * @var integer Cache duration. Default to a hour.
     */
    public $cacheDuration = 3600;

    /**
     * @var string region table name.
     */
    public $regionTable = '{{%region}}';

    /**
     * @var string Admin table name.
     */
    public $adminLogTable = '{{%admin_log}}';

    /**
     * @var array
     */
    public $options;

    /**
     * @var self Instance of self
     */
    private static $_instance;

    /*
     * @var array  all regions
     */
    public static $regions = [];

    /*
     * @var array  all the repeat regions,alias as regionExtras
     */
    public static $regionExtras = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->db !== null && !($this->db instanceof Connection)) {
            if (is_string($this->db) && strpos($this->db, '\\') === false) {
                $this->db = Yii::$app->get($this->db, false);
            } else {
                $this->db = Yii::createObject($this->db);
            }
        }
        if ($this->cache !== null && !($this->cache instanceof Cache)) {
            if (is_string($this->cache) && strpos($this->cache, '\\') === false) {
                $this->cache = Yii::$app->get($this->cache, false);
            } else {
                $this->cache = Yii::createObject($this->cache);
            }
        }
        parent::init();
    }

    /**
     * get all region
     * @return array|mixed
     */
    public static function getRegions(){
        if (!empty(static::$regions)){
            return static::$regions;
        }
        return static::$regions = RegionData::$region;
    }

    /**
     * get all the repeat regions,alias as regionExtras
     * @return array|mixed
     */
    public static function getRegionExtras(){
        if (!empty(static::$regionExtras)){
            return static::$regionExtras;
        }
        return static::$regionExtras = RegionData::$regionExtras;
    }

    /**
     * Create instance of self
     * @return static
     */
    public static function instance()
    {
        if (self::$_instance === null) {
            $type = ArrayHelper::getValue(Yii::$app->params, 'backend.configs', []);
            if (is_array($type) && !isset($type['class'])) {
                $type['class'] = static::className();
            }

            return self::$_instance = Yii::createObject($type);
        }

        return self::$_instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = static::instance();
        if ($instance->hasProperty($name)) {
            return $instance->$name;
        } else {
            if (count($arguments)) {
                $instance->options[$name] = reset($arguments);
            } else {
                return array_key_exists($name, $instance->options) ? $instance->options[$name] : null;
            }
        }
    }

    /**
     * @return Connection
     */
    public static function db()
    {
        return static::instance()->db;
    }

    /**
     * @return Cache
     */
    public static function cache()
    {
        return static::instance()->cache;
    }

    /**
     * @return integer
     */
    public static function cacheDuration()
    {
        return static::instance()->cacheDuration;
    }

    /**
     * @return string
     */
    public static function RegionTable()
    {
        return static::instance()->regionTable;
    }

}
