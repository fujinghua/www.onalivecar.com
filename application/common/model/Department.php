<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;


/**
 * This is the model class for table "{{%department}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $remark
 * @property string $name
 * @property integer $parent
 * @property string $code
 * @property integer $order
 * @property integer $level
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser[] $backUsers
 */
class Department extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%department}}';

    protected $field = [
        'id',
        'is_delete',
        'remark',
        'name',
        'parent',
        'code',
        'order',
        'level',
        'created_at',
        'updated_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static function getLevelList(){
        return self::T('level');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['level','number|in:0,1,2,3','类型 无效'],
                ['is_delete','in:0,1','时效 无效'],
                ['name','max:100'],
                ['code','max:100'],
            ],
            'msg'=>[
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'remark' => '备注',
            'name' => '名称',
            'parent' => '上级',
            'code' => '编号',
            'order' => '顺序',
            'level' => '等级;0=无效;1董事会部门2总经理部门3业务员部门',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBackUsers()
    {
        return $this->hasMany(ucfirst(BackUser::tableNameSuffix()), 'department_id', 'id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getParent()
    {
        return $this->hasOne(ucfirst(Department::tableNameSuffix()), 'id', 'parent',[],'LEFT');
    }
}
