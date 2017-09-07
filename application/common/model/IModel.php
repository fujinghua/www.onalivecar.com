<?php
namespace app\common\model;


/**
 * Interface IModel
 * @package app\common\model
 */
interface IModel
{

    /**
     * @return \app\common\components\Helper
     */
    public static function getHelper();

    /**
     * @return array
     */
    public function rules();

    /**
     * @return array
     */
    public function attributeLabels();


    //自动日期格式
    public function setDate();

    //自动时间戳
    public function setTime();

    /**
     * @param array $data
     * @return array
     */
    public function filter($data = []);

    /**
     * @param null|array|\think\Model $resultSet
     * @return array
     */
    public function asArray($resultSet = null);

    /**
     * 实例化（分层）模型
     * @param string $name         Model名称
     * @param string $layer        业务层名称
     * @param bool   $appendSuffix 是否添加类名后缀
     * @param string $common       公共模块名
     * @return Object | \think\Model | \app\common\model\Model
     * @throws \think\exception\ClassNotFoundException
     */
    public static function load($name = '', $layer = 'model', $appendSuffix = false, $common = 'common');

    /**
     * @return string
     */
    public static function tableName();

    /**
     * @return string
     */
    public static function tableNameSuffix();

    /**
     * @return string
     */
    public function getTableSuffix();

    /**
     * @return Object|\think\Validate | \app\common\validate\Validate | null
     */
    public static function getValidate();

    /**
     * @param array $data
     * @param string $scene
     * @return bool
     */
    public static function check($data,$scene = '');

    /**
     * @description 获取 模型 数据包助手
     * @return \app\common\lang\LangHelper
     */
    public static function getLangHelper();

    /**
     * @description 获取 当前 模型 的数据包 与实例 getLang 方法同样效果，一个静态方法，一个实例方法
     * @return array
     */
    public static function Lang($field=null);

    /**
     * @description 获取 当前 模型 的数据包 与静态 Lang 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @return array
     */
    public function getLang($field=null);

    /**
     *
     * @param null $field
     * @param null $key
     * @param null $default
     * @return array|string
     */
    public static function T($field=null,$key=null,$default=null);

    /**
     * @description 获取当前模型数据包值所有值 与 静态 T 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @return array
     */
    public function getLists($field=null);

    /**
     * @description 获取当前模型数据包值  与 静态 T 方法同样效果，一个静态方法，一个实例方法
     * @param null $field
     * @param null $key
     * @param null $default
     * @return string
     */
    public function getValue($field=null,$key=null,$default=null);
}
