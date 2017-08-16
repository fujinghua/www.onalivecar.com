<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthAssignment;
use app\common\model\BackUser;
use app\common\model\AuthRule;
use app\common\model\AuthItemChild;
use app\common\model\Ban;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuthAssignment[] $AuthAssignments
 * @property BackUser[] $backUsers
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $AuthItemChildren
 * @property AuthItemChild[] $AuthItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 * @property Ban[] $Bans
 * @property BackUser[] $backUsers0
 */
class AuthItem extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%auth_item}}';

    protected $field = [
        'name',
        'type',
        'description',
        'rule_name',
        'data',
        'created_at',
        'updated_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule' => [
                ['type', 'in:1,2', '类型 无效'],
                ['name', 'max:64',],
                ['rule_name', 'max:64',],
            ],
            'msg' => []
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '权限名',
            'type' => '类型',
            'description' => '权限描述',
            'rule_name' => '使用规则',
            'data' => '补充说明',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param $route
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     */
    public static function findByRoute($route){
        return AuthItem::load()->where(['name' => $route,'type'=>'2'])->find();
    }

    /**
     * @param $role
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     */
    public static function findByRole($role){
        return AuthItem::load()->where(['name' => $role,'type'=>'1'])->find();
    }

    /**
     * 获取一个权限的所有下属权限
     * @param $name
     * @return array
     */
    public static function getHasAssign($name){
        $ret = [];
        $helper = self::getHelper();
        $child = $helper::toArray(AuthItemChild::load()->where(['parent'=>$name])->field('child')->select());
        if ($child){
            foreach ($child as $item){
                $ret[] = ['name'=>$item['child']];
                $res = self::getHasAssign($item['child']);
                $ret = $ret + $res;
            }
        }
        return $ret;
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(ucfirst(AuthAssignment::tableNameSuffix()), 'item_name', 'name');
    }

    /**
     * @return \think\model\relation\HasManyThrough
     */
    public function getBackUsers()
    {
        return $this->hasManyThrough(ucfirst(BackUser::tableNameSuffix()),ucfirst(AuthAssignment::tableNameSuffix()));
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getRuleName()
    {
        return $this->hasOne(ucfirst(AuthRule::tableNameSuffix()), 'name', 'rule_name');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getParent()
    {
        return $this->hasOne(ucfirst(AuthItemChild::tableNameSuffix()), 'child', 'name');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getChildren()
    {
        return $this->hasMany(ucfirst(AuthItemChild::tableNameSuffix()), 'parent', 'name');
    }

    /**
     * @return \think\model\relation\HasManyThrough
     */
    public function topics()
    {
        return $this->hasManyThrough('Topic','User');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBans()
    {
        return $this->hasMany(ucfirst(Ban::tableNameSuffix()), 'name', 'item_name');
    }

}
