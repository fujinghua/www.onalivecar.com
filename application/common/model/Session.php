<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%session}}".
 *
 * @property string $session_id
 * @property integer $session_expire
 * @property resource $session_data
 * @property string $uid
 * @property string $update_time
 */
class Session extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%session}}';

    protected $field = [
        'session_id',
        'session_expire',
        'session_data',
        'uid',
        'update_time',
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
            [['session_id', 'session_expire', 'uid', 'update_time'], 'required'],
            [['session_expire', 'uid'], 'integer'],
            [['session_data'], 'string'],
            [['update_time'], 'safe'],
            [['session_id'], 'string', 'max' => 255],
            [['session_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_id' => 'ID',
            'session_expire' => 'SESSION_ID',
            'session_data' => '数据',
            'uid' => '用户ID',
            'update_time' => '更新时间',
        ];
    }
}
