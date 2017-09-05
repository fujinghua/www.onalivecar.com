<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $name
 * @property string $letter
 * @property string $Pinyin
 * @property string $icon
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 */
class Brand extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%brand}}';

    protected $field = [
        'id',
        'is_delete',
        'name',
        'letter',
        'pinyin',
        'icon',
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
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'name' => '品牌名',
            'letter' => '首字母',
            'pinyin' => '中文拼音',
            'icon' => '品牌商标',
            'order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

}
