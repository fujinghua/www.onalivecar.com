<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthItem;

/**
 * This is the model class for table "{{%auth_item_child}}".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class AuthItemChild extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%auth_item_child}}';

    protected $field = [
        'parent',
        'child',
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
            'rule'=>[
                ['parent','max:64',],
                ['child','max:64',],
            ],
            'msg'=>[]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => '权限上级',
            'child' => '权限下级',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getParent0()
    {
        return $this->hasOne(AuthItem::tableNameSuffix(), ['name' => 'parent']);
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getChild0()
    {
        return $this->hasOne(AuthItem::tableNameSuffix(), ['name' => 'child']);
    }
}
