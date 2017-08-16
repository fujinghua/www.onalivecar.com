<?php
namespace app\common\components\rbac;

use think\Db;
use think\Loader;
use think\db\Query;
use think\Config;

/**
 * 公共模型(基于TP5新版Model)
 * @author Dinner
 */
class Model extends \think\Model
{

    /**
     * @return array
     */
    public function rules(){
        return [];
    }

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {

        if (is_callable([$this,'rules'])){
            $rules = $this->rules();
            if (is_array($rules)){
                $this->validate = $rules;
            }
        }

        parent::__construct($data);
    }

    /**
     * 获取当前模型的数据库查询对象
     * @access public
     * @param bool $baseQuery 是否调用全局查询范围
     * @return Query
     */
    public function db($baseQuery = true)
    {
        $model = $this->class;
        if (!isset(self::$links[$model])) {
            // 合并数据库配置
            if (!empty($this->connection)) {
                if (is_array($this->connection)) {
                    $connection = array_merge(Config::get('database'), $this->connection);
                } else {
                    $connection = $this->connection;
                }
            } else {
                $connection = [];
            }
            // 设置当前模型 确保查询返回模型对象
            $query = Db::connect($connection)->getQuery($model, $this->query);

            // 设置当前数据表和模型名
            if (!empty($this->table)) {
                $pattern = '{{%(.*?)}}';
                if (preg_match($pattern,$this->table)){
                    $this->table = ltrim($this->table,'{{%');
                    $this->table = rtrim($this->table,'}}');
                    if ($prefix = Config('database.prefix')){
                        $this->table = $prefix.$this->table;
                    }
                }
                $query->setTable($this->table);
            } else {
                $query->name($this->name);
            }

            if (!empty($this->pk)) {
                $query->pk($this->pk);
            }

            self::$links[$model] = $query;
        }
        // 全局作用域
        if ($baseQuery && method_exists($this, 'base')) {
            call_user_func_array([$this, 'base'], [& self::$links[$model]]);
        }
        // 返回当前模型的数据库查询对象
        return self::$links[$model];
    }

    /**
     * 实例化（分层）模型
     * @param string $name         Model名称
     * @param string $layer        业务层名称
     * @param bool   $appendSuffix 是否添加类名后缀
     * @param string $common       公共模块名
     * @return Object | \think\Model
     * @throws \think\exception\ClassNotFoundException
     */
    public static function load($name = '', $layer = 'model', $appendSuffix = false, $common = 'common')
    {
        if ($name === ''){
            $name = get_called_class();
        }
        return Loader::model($name,$layer,$appendSuffix,$common);
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        /**
         * @var $className self;
         */
        $className = get_called_class();
        /**
         * @var $model \think\Model;
         */
        $model = $className::load();
        return $model->getTable();
    }

    /**
     * @return string
     */
    public static function tableNameSuffix()
    {
        $table = self::tableName();
        if ($prefix = Config('database.prefix')){
            $table = str_replace($prefix,'',$table);
        }
        return $table;
    }
}
