<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\Type;

/**
 * This is the model class for table "{{%type_park}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $type_id
 * @property integer $target_id
 * @property integer $group
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Type $type
 */
class TypePark extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%type_park}}';

    protected $field = [
        'id',
        'is_delete',
        'type_id',
        'target_id',
        'type',
        'name',
        'description',
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
            'rule'=>[
                ['type','in:0,1,2,3,4,5,6,7'],
                ['type_id','number'],
                ['target_id','number'],
                ['is_delete','in:0,1','时效 无效'],
                ['name','max:32'],
                ['description','max:255'],
            ],
            'msg'=>[
                'group.in'=> '此类型不允许',
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
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'type_id' => '标签表ID',
            'target_id' => '目标表ID;根据group值外联',
            'type' => '父级类型:0=失效,1=预定,2=客户,3=房东,4=新房,5=二手房,6=楼房,7=客服,8=出租;默认1;',
            'name' => '标签',
            'description' => '详细描述',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getType()
    {
        return $this->hasOne(ucfirst(Type::tableNameSuffix()), 'type_id', 'id');
    }
}
