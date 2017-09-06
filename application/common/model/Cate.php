<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%cate}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $pid
 * @property integer $isParent
 * @property string $order
 * @property integer $level
 * @property string $unique_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Car[] $cars
 * @property CateProp[] $cateProps
 */
class Cate extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%cate}}';

    protected $field = [
        'id' => '类目ID',
        'name' => '类目名称',
        'pid' => '类目父ID',
        'isParent' => '是否作为父级；0否，1是；默认为0',
        'order' => '排序',
        'level' => '分类深度',
        'unique_id' => '扩展识别',
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

            ],
            [
                [['name', 'pid', 'order'], 'required'],
                [['pid', 'isParent', 'order', 'level'], 'integer'],
                [['created_at', 'updated_at'], 'safe'],
                [['name'], 'string', 'max' => 64],
                [['unique_id'], 'string', 'max' => 32],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '类目ID',
            'name' => '类目名称',
            'pid' => '类目父ID',
            'isParent' => '是否作为父级；0否，1是；默认为0',
            'order' => '排序',
            'level' => '分类深度',
            'unique_id' => '扩展识别',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Car::className(), ['cate_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCateProps()
    {
        return $this->hasMany(CateProp::className(), ['cate_id' => 'id']);
    }

}
