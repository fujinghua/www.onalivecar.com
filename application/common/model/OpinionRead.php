<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%opinion_read}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $assign
 * @property integer $back_user_id
 * @property integer $opinion_id
 * @property string $content
 * @property string $remark
 * @property string $reback
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Opinion $opinion
 */
class OpinionRead extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%opinion_read}}';

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
            [['is_delete', 'assign', 'back_user_id', 'opinion_id'], 'integer'],
            [['back_user_id', 'opinion_id', 'created_at'], 'required'],
            [['content', 'remark', 'reback'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['back_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BackUser::tableNameSuffix(), 'targetAttribute' => ['back_user_id' => 'id']],
            [['opinion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Opinion::tableNameSuffix(), 'targetAttribute' => ['opinion_id' => 'id']],
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
            'assign' => '分配;0=分配;1=自由;',
            'back_user_id' => '后台管理员ID',
            'opinion_id' => '意见表ID',
            'content' => '意见内容',
            'remark' => '备注',
            'reback' => '回馈',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBackUser()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'back_user_id', 'id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getOpinion()
    {
        return $this->hasOne(Opinion::tableNameSuffix(), ['id' => 'opinion_id']);
    }
}
