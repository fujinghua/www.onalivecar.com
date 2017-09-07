<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%car_sell}}".
 *
 * @property string $id
 * @property string $cate_prop_value_id
 * @property string $cate_prop_id
 * @property string $prop_value
 * @property string $prop_extend
 * @property string $images_id
 * @property string $images_unique
 * @property string $car_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatePropValue $catePropValue
 * @property CateProp $cateProp
 */
class CarSell extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%car_sell}}';

    protected $field = [
        'id',
        'cate_prop_value_id',
        'cate_prop_id',
        'prop_value',
        'prop_extend',
        'images_id',
        'images_unique',
        'car_id',
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
                ['prop_value', 'max:32'],
                ['prop_extend', 'max:150'],
                ['images_unique', 'max:32'],
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
            'id' => '销售属性ID',
            'cate_prop_value_id' => '特征值ID',
            'cate_prop_id' => '特征量ID',
            'prop_value' => '特征值（允许自定义）',
            'prop_extend' => '扩展属性值',
            'images_id' => '默认图',
            'images_unique' => '详细图片识别字符',
            'car_id' => '车辆表ID',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCatePropValue()
    {
        return $this->hasOne(ucfirst(CatePropValue::tableNameSuffix()), 'cate_prop_value_id','id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCateProp()
    {
        return $this->hasOne(ucfirst(CateProp::tableNameSuffix()), 'cate_prop_id','id');
    }

}
