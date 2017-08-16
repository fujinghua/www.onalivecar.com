<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%opinion}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $remark
 * @property integer $home_user_id
 * @property integer $back_user_id
 * @property string $content
 * @property string $username
 * @property string $contact
 * @property integer $readed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property HomeUser $homeUser
 * @property OpinionRead[] $opinionReads
 */
class Opinion extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%opinion}}';

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
     * @return array
     */
    public static function getReadList()
    {
        return self::T('read');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['is_delete','in:0,1','时效 无效'],
                ['back_user_id','number'],
                ['home_user_id','number'],
                ['readed','number'],
                ['username','max:255',],
                ['contact','max:255',],
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
            'id' => 'ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'remark' => '备注',
            'home_user_id' => '前端用户表ID',
            'back_user_id' => '后台业务员ID',
            'content' => '内容',
            'username' => '联系人',
            'contact' => '联系方式',
            'readed' => '是否阅读;0=未读,1=已读',
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
    public function getHomeUser()
    {
        return $this->hasOne(ucfirst(HomeUser::tableNameSuffix()), 'home_user_id', 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOpinionReads()
    {
        return $this->hasMany(ucfirst(OpinionRead::tableNameSuffix()), 'id', 'opinion_id');
    }
}
