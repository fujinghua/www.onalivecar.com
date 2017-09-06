<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%car_prop}}".
 *
 * @property string $id
 * @property string $car_id
 * @property string $cate_prop_value_id
 * @property integer $type
 * @property string $prop
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Car $car
 * @property CatePropValue $catePropValue
 */
class CarProp extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%car_prop}}';

    protected $field = [
        'id' => 'ID',
        'car_id' => '车辆表ID',
        'cate_prop_value_id' => '特征量表ID',
        'type' => '是否自定义属性值；0=否，1=是；默认是0；',
        'prop' => '自定义属性值',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
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
                ['is_delete', 'in:0,1', '时效 无效'],
                ['name', 'max:32'],
                ['pinyin', 'max:150'],
                ['icon', 'max:255'],
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
            'id' => 'ID',
            'car_id' => '车辆表ID',
            'cate_prop_value_id' => '特征量表ID',
            'type' => '是否自定义属性值；0=否，1=是；默认是0；',
            'prop' => '自定义属性值',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Car::className(), ['id' => 'car_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatePropValue()
    {
        return $this->hasOne(CatePropValue::className(), ['id' => 'cate_prop_value_id']);
    }

}
