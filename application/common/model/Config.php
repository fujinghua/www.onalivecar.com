<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $app
 * @property string $title
 * @property string $name
 * @property string $value
 * @property integer $group
 * @property string $type
 * @property string $options
 * @property string $tip
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order
 * @property integer $status
 */
class Config extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%config}}';

    protected $field = [
        'id',
        'is_delete',
        'app',
        'title',
        'name',
        'value',
        'group',
        'type',
        'options',
        'tip',
        'created_at',
        'updated_at',
        'order',
        'status',
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
                ['group','number','分组 不是数值'],
                ['order','number','排序 不是数值'],
                ['status','in:0,1','状态标识 无效'],
                ['is_delete','in:0,1','时效标识 无效'],
                ['app','in:0,1','应用标识 无效'],
                ['title','max:32'],
                ['name','max:32'],
                ['type','max:16'],
                ['options','max:255'],
                ['tip','max:100'],
            ],
            'msg'=>[
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '配置ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'app' => '应用;0=后台;1=前台;',
            'title' => '配置标题',
            'name' => '配置名称',
            'value' => '配置值',
            'group' => '配置分组',
            'type' => '配置类型',
            'options' => '配置额外值',
            'tip' => '配置说明',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'order' => '排序',
            'status' => '状态',
        ];
    }


    //所有应用类型
    public static function getAppList(){
        return self::T('app');
    }

    //所有标签类型
    public static function getTypeList(){
        return self::T('type');
    }

}
