<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%car}}".
 *
 * @property string $id
 * @property string $name
 * @property string $name_alias
 * @property string $cate_id
 * @property string $total
 * @property integer $price
 * @property string $title
 * @property string $code
 * @property string $images_id
 * @property string $images_unique
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cate $cate
 * @property CarProp[] $carProps
 * @property CarSku[] $carSkus
 */
class Car extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%car}}';

    protected $field = [
        'id',
        'name',
        'name_alias',
        'cate_id',
        'total',
        'price',
        'title',
        'code',
        'images_id',
        'images_unique',
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
                ['name', 'max:32'],
                ['name_alias', 'max:150'],
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
            'id' => '车辆ID',
            'name' => '车名称',
            'name_alias' => '车别名或扩充名称',
            'cate_id' => '类目表ID',
            'total' => '总量',
            'price' => '基本价格',
            'title' => '描述信息',
            'code' => '编码',
            'images_id' => '默认图',
            'images_unique' => '详细图片识别字符',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCate()
    {
        return $this->hasOne(ucfirst(Cate::tableNameSuffix()), 'cate_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCarProps()
    {
        return $this->hasMany(ucfirst(CarProp::tableNameSuffix()), 'id','car_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCarSkus()
    {
        return $this->hasMany(ucfirst(CarSku::tableNameSuffix()), 'id','car_id');
    }

}
