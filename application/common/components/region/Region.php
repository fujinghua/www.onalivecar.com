<?php

namespace app\common\components\region;

use app\common\model\Model;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property double $id
 * @property string $code
 * @property string $name
 * @property double $parent
 * @property double $level
 * @property double $order
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
