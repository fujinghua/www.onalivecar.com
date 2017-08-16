<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%on_links}}".
 *
 * @property string $id
 * @property integer $is_delete
 * @property integer $app
 * @property string $title
 * @property string $logo
 * @property string $url
 * @property integer $type
 * @property string $create_time
 * @property string $update_time
 * @property string $sort
 * @property integer $status
 */
class OnLinks extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%on_links}}';

    protected $field = [
        'id',
        'back_user_id',
        'route',
        'url',
        'user_agent',
        'gets',
        'posts',
        'target',
        'ip',
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
            [['is_delete', 'app', 'type', 'sort', 'status'], 'integer'],
            [['create_time', 'update_time'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'logo', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'app' => '应用;0=后台;1=前台;',
            'title' => '标题',
            'logo' => 'logo',
            'url' => '链接',
            'type' => '类型',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
