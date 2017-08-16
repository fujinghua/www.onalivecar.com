<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthItem;

/**
 * This is the model class for table "{{%auth_rule}}".
 *
 * @property string $name
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuthItem[] $AuthItems
 */
class AuthRule extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%auth_rule}}';

    protected $field = [
        'name',
        'data',
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
            'rule'=>[
                ['name','max:64',],
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
            'name' => '规则名',
            'data' => '规则位置',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::tableNameSuffix(), ['rule_name' => 'name']);
    }
}
