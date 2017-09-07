<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%car_sku}}".
 *
 * @property string $id
 * @property string $sku
 * @property integer $total
 * @property integer $price
 * @property string $car_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Car $car
 */
class CarSku extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%car_sku}}';

    protected $field = [
        'id',
        'sku',
        'total',
        'price',
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
            'sku' => '特征量ID组合',
            'total' => 'Total',
            'price' => 'Price',
            'car_id' => '车辆表ID',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCar()
    {
        return $this->hasOne(ucfirst(Car::tableNameSuffix()), 'car_id','id');
    }

}
