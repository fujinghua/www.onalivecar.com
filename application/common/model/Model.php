<?php
namespace app\common\model;

use think\Db;
use think\Loader;
use think\db\Query;
use think\Config;
use app\common\components\LangHelper;
use think\Request;

/**
 * @property array $rules
 * @property array $attributeLabels
 */
class Model extends \think\Model
{

    /**
     * @return \app\common\components\Helper
     */
    public static function getHelper(){
        return \app\common\components\Helper::getInstance();
    }

    public function getTableInformation($path = '',$namespace = ''){
        if (!file_exists($path) || !is_dir($path)){
            return;
        }

        if ($od = opendir($path)){
            $str = '<?php'.PHP_EOL.PHP_EOL;
            $str .= 'class ClassModel {'.PHP_EOL;
            while (($file = readdir($od)) !== false)  //读取该目录内文件
            {
                $tmp = explode('.',$file);
                $className = $tmp[0];
                if (!empty($className) && $className != 'Model'){
                    $str .= '/**'.$className.'**/'.PHP_EOL;
                    $str .= 'protected $field = ['.PHP_EOL;
                    $className = $namespace.$className;
                    /**
                     * @var $model \think\Model
                     */
                    $model = new $className;
                    $info = $model->getTableInfo();
                    foreach ($info['fields'] as $field){
                        $str .= "'".$field."',".PHP_EOL;
                    }
                    $str .= "];".PHP_EOL.PHP_EOL;
                }
            }
            $str .= "}".PHP_EOL.PHP_EOL;
            @file_put_contents($path.'/ClassModel.php',$str);
            closedir($od);
        }
    }

    public function getTableInfoAll(){
        $sql = "select * from information_schema.columns where table_name='wf_soap_detail' ";
    }

    /**
     * @return array
     */
    public function rules(){
        return [];
    }

    /**
     * @return array
     */
    public function attributeLabels(){
        return [];
    }


    //自动日期格式
    public function setDate(){
        return date('Y-m-d H:i:s');
    }

    //自动时间戳
    public function setTime(){
        return time();
    }

    /**
     * @param array $data
     * @return array
     */
    public function filter($data = []){
        $ret = [];
        if (!empty($data) && is_array($data)){
            $field = $this->field;
            if (!$field){
                $field = $this->getTableInfo()['fields'];
            }
            foreach ($field as $value){
                $ret[$value] = isset($data[$value]) ? (is_array($data[$value]) ? (implode(',',$data[$value])) : $data[$value]) : null;
                if ($ret[$value] === null){
                    unset($ret[$value]);
                }
            }
        }
        return $ret;
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

        // 设置当前数据表和模型名
        if (!empty($this->table)) {
            $pattern = '{{%(.*?)}}';
            if (preg_match($pattern,$this->table)){
                $this->table = ltrim($this->table,'{{%');
                $this->table = rtrim($this->table,'}}');
            }
            if ($prefix = Config('database.prefix')){
                if (strpos($this->table,$prefix) !== 0){
                    $this->table = $prefix.$this->table;
                }
            }
            $this->setTable($this->table);
        }
    }

    /**
     * @param null|array|\think\Model $resultSet
     * @return array
     */
    public function asArray($resultSet = null){
        $ret = [];
        if (empty($resultSet) || !(is_array($resultSet) || is_object($resultSet))){
            return $ret;
        }
        $isTrueArray = false;
        if ($resultSet instanceof $this){
            $ret = $resultSet->toArray();
        }else if (is_array($resultSet)){
            foreach ($resultSet as $model){
                if ($model instanceof $this){
                    $ret[] = $model->toArray();
                }else{
                    $isTrueArray = true;
                    break;
                }
            }
        }
        if ($isTrueArray){
            $ret = $resultSet;
        }
        return $ret;
    }

    /**
     * 实例化（分层）模型
     * @param string $name         Model名称
     * @param string $layer        业务层名称
     * @param bool   $appendSuffix 是否添加类名后缀
     * @param string $common       公共模块名
     * @return Object | \think\Model | \app\common\model\Model
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

    /**
     * @return string
     */
    public function getTableSuffix()
    {
        $table = $this->getTable();
        if ($prefix = Config('database.prefix')){
            $table = str_replace($prefix,'',$table);
        }
        return $table;
    }

    /**
     * @return Object|\think\Validate | \app\common\validate\Validate | null
     */
    public static function getValidate(){
        $className = pathinfo(get_class(self::load()),PATHINFO_FILENAME);
        $request = Request::instance();
        /**
         * @var $class \app\common\validate\Validate
         */
        $class = '\\app\\'.$request->module().'\\validate\\'.$className.'Validate';
        if (!class_exists($class)){
            $class = '\\app\\common\\validate\\'.$className.'Validate';
        }
        return $class::load();
    }

    /**
     * @param array $data
     * @param string $scene
     * @return bool
     */
    public static function check($data,$scene = ''){
        $validate = self::getValidate();

        //设定场景
        if (is_string($scene) && $scene !== ''){
            $validate->scene($scene);
        }

        return $validate->check($data);
    }

    /**
     * @description 获取 模型 数据包助手
     * @return \app\common\components\LangHelper
     */
    public static function getLangHelper(){
        return LangHelper::getInstance();
    }

    /**
     * @description 获取 当前 模型 的数据包 与实例 getLang 方法同样效果，一个静态方法，一个实例方法
     * @return array
     */
    public static function Lang($field=null){
        $field = (string)($field);
        if (is_string($field) && $field != ''){
            $ret = LangHelper::getInstance()->getField(self::tableNameSuffix(),$field);
        }else{
            $ret = LangHelper::getInstance()->get(self::tableNameSuffix());
        }
        return $ret;
    }

    /**
     * @description 获取 当前 模型 的数据包 与静态 Lang 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @return array
     */
    public function getLang($field=null){
        $field = (string)($field);
        if (is_string($field) && $field != ''){
            $ret = LangHelper::getInstance()->getField($this->getTableSuffix(),$field);
        }else{
            $ret = LangHelper::getInstance()->get($this->getTableSuffix());
        }
        return $ret;
    }

    /**
     *
     * @param null $field
     * @param null $key
     * @param null $default
     * @return array|string
     */
    public static function T($field=null,$key=null,$default=null){
        $ret = '';
        $key = (string)($key);
        if (is_string($field) && $field != '' && is_string($key) && $key != '' ){
            $ret = LangHelper::getInstance()->getValue(self::tableNameSuffix(),$field,$key);
            if (empty($ret)){
                $ret = (string)$default;
            }
        }else if (is_string($field) && $field != ''){
            $ret = LangHelper::getInstance()->getField(self::tableNameSuffix(),$field);
            if (empty($ret)){
                $ret = (array)$default;
            }
        }
        return $ret;
    }

    /**
     * @description 获取当前模型数据包值所有值 与 静态 T 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @return array
     */
    public function getLists($field=null){
        $field = (string)($field);
        $ret = [];
        if (is_string($field) && $field != ''){
            $ret = LangHelper::getInstance()->getField($this->getTableSuffix(),$field);
        }
        return $ret;
    }

    /**
     * @description 获取当前模型数据包值  与 静态 T 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @param null $key
     * @param null $default
     * @return string
     */
    public function getValue($field=null,$key=null,$default=null){
        $ret = '';
        $key = (string)($key);
        if (is_string($field) && $field != '' && is_string($key) && $key != '' ){
            $ret = LangHelper::getInstance()->getValue($this->getTableSuffix(),$field,$key);
            if (empty($ret)){
                $ret = (string)$default;
            }
        }
        return $ret;
    }
}
