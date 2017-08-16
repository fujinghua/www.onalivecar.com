<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\HomeUser;
use app\common\model\ContactRead;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $name
 * @property string $contact
 * @property string $email
 * @property string $weChat
 * @property string $address
 * @property string $content
 * @property integer $home_user_id
 * @property integer $back_user_id
 * @property integer $readed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property HomeUser $homeUser
 * @property ContactRead[] $contactReads
 */
class Contact extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%contact}}';

    protected $field = [
        'id',
        'is_delete',
        'name',
        'contact',
        'email',
        'weChat',
        'address',
        'content',
        'home_user_id',
        'back_user_id',
        'readed',
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
                ['is_delete','in:0,1','时效 无效'],
                ['name','max:64',],
                ['contact','max:32',],
                ['email','max:255',],
                ['address','max:200',],
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
            'name' => '联系人',
            'contact' => '联系方式',
            'email' => '邮箱',
            'weChat' => '微信',
            'address' => '联系地址',
            'content' => '联系内容',
            'home_user_id' => '前端用户表ID',
            'back_user_id' => '后台业务员ID',
            'readed' => '是否阅读; 1=未读 ,2=已读',
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
    public function getContactReads()
    {
        return $this->hasMany(ucfirst(ContactRead::tableNameSuffix()), 'id', 'contact_id');
    }
}
