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
        'id' => '特征值ID',
        'is_delete' => '时效;0=失效,1=有效;默认1;',
        'value' => '特征值',
        'cate_prop_id' => '特征量表ID',
        'order' => '排序',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCarProps()
    {
        return $this->hasMany(CarProp::className(), ['cate_prop_value_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarSells()
    {
        return $this->hasMany(CarSell::className(), ['cate_prop_value_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCateProp()
    {
        return $this->hasOne(CateProp::className(), ['id' => 'cate_prop_id']);
    }

}
