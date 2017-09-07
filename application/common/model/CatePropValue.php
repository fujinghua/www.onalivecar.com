<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%cate_prop_value}}".
 *
 * @property string $id
 * @property integer $is_delete
 * @property string $value
 * @property string $cate_prop_id
 * @property string $order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CarProp[] $carProps
 * @property CarSell[] $carSells
 * @property CateProp $cateProp
 */
class CatePropValue extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%cate_prop_value}}';

    protected $field = [
        'id',
        'is_delete',
        'value',
        'cate_prop_id',
        'order',
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
                ['value', 'max:150'],
            ],
            'msg' => [

            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '特征值ID',
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'value' => '特征值',
            'cate_prop_id' => '特征量表ID',
            'order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCarProps()
    {
        return $this->hasMany(ucfirst(CarProp::tableNameSuffix()), 'id','cate_prop_value_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCarSells()
    {
        return $this->hasMany(ucfirst(CarSell::tableNameSuffix()), 'id', 'cate_prop_value_id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCateProp()
    {
        return $this->hasOne(ucfirst(CateProp::tableNameSuffix()), 'cate_prop_id','id');
    }

}
