<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $parent
 * @property integer $level
 * @property integer $order
 * @property string $name_en
 * @property string $short_name_en
 * @property string $data
 *
 */
class Region extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%region}}';

    protected $field = [
        'id',
        'code',
        'name',
        'parent',
        'level',
        'order',
        'name_en',
        'short_name_en',
        'data',
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
            [['code', 'name', 'parent', 'level', 'order', 'name_en', 'short_name_en'], 'required'],
            [['parent', 'level', 'order'], 'number'],
            [['data'], 'string'],
            [['code', 'name', 'name_en'], 'string', 'max' => 100],
            [['short_name_en'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'parent' => 'Parent',
            'level' => 'Level',
            'order' => 'Order',
            'name_en' => 'Name En',
            'short_name_en' => 'Short Name En',
            'data' => 'Data',
        ];
    }
}
