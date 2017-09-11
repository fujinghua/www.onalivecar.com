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
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Car[] $cars
 * @property CateProp[] $cateProps
 *
 * @property string $unique
 */
class Cate extends Model
{
    public $unique = 'cate';

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%cate}}';

    protected $field = [
        'id',
        'name',
        'pid',
        'isParent',
        'order',
        'level',
        'unique_code',
        'unique_id',
        'title',
        'created_at',
        'updated_at',
    ];

    /**
     * 新增自动完成列表
     * @var array
     */
    protected $insert = ['created_at','unique_code'];

    /**
     * 插入自动 赋值类目组别识别码
     */
    public function setUniqueCodeAttr()
    {
        return $this->unique;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule' => [
                ['name', 'max:64'],
                ['unique_id', 'max:32'],
                ['title', 'max:255'],
            ],
            'msg' => [

            ],
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
            'unique_code' => '类目组别识别码',
            'unique_id' => '扩展识别ID',
            'title' => '说明',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCars()
    {
        return $this->hasMany(ucfirst(Car::tableNameSuffix()),'id','cate_id' );
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCateProps()
    {
        return $this->hasMany(ucfirst(CateProp::tableNameSuffix()),'id','cate_id' );
    }

}
