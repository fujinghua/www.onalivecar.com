<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%cate_prop}}".
 *
 * @property string $id
 * @property integer $is_delete
 * @property string $name
 * @property string $cate_id
 * @property string $pid
 * @property string $cid
 * @property integer $isAlias
 * @property integer $isColor
 * @property integer $isEnum
 * @property integer $isAlive
 * @property integer $isUnique
 * @property integer $isSeller
 * @property string $order
 * @property integer $level
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CarSell[] $carSells
 * @property Cate $cate
 * @property CatePropValue[] $catePropValues
 */
class CateProp extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%cate_prop}}';

    protected $field = [
        'id' => '特征量ID',
        'is_delete' => '时效;0=失效,1=有效;默认1;',
        'name' => '属性名',
        'cate_id' => '所属类目ID',
        'pid' => '父级特征量ID',
        'cid' => '下级特征量ID',
        'isAlias' => '是否允许别名;0=否，1=是，默认是0',
        'isColor' => '是否颜色属性;0=否，1=是，默认是0',
        'isEnum' => '是否枚举属性;0=否，1=是，默认是0',
        'isAlive' => '是否输入属性;0=否，1=是，默认是1',
        'isUnique' => '是否关键属性;0=否，1=是，默认是1',
        'isSeller' => '是否销售属性;0=否，1=是，默认是1',
        'order' => '排序',
        'level' => '分类深度',
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
            'id' => '特征量ID',
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'name' => '属性名',
            'cate_id' => '所属类目ID',
            'pid' => '父级特征量ID',
            'cid' => '下级特征量ID',
            'isAlias' => '是否允许别名;0=否，1=是，默认是0',
            'isColor' => '是否颜色属性;0=否，1=是，默认是0',
            'isEnum' => '是否枚举属性;0=否，1=是，默认是0',
            'isAlive' => '是否输入属性;0=否，1=是，默认是1',
            'isUnique' => '是否关键属性;0=否，1=是，默认是1',
            'isSeller' => '是否销售属性;0=否，1=是，默认是1',
            'order' => '排序',
            'level' => '分类深度',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarSells()
    {
        return $this->hasMany(CarSell::className(), ['cate_prop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Cate::className(), ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatePropValues()
    {
        return $this->hasMany(CatePropValue::className(), ['cate_prop_id' => 'id']);
    }

}
