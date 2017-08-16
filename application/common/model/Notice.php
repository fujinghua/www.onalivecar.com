<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\NoticeRead;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $back_user_id
 * @property string $title
 * @property string $content
 * @property integer $is_passed
 * @property integer $order
 * @property string $remark
 * @property integer $readed
 * @property string $created_at
 * @property string $updated_at
 * @property string $start_at
 * @property string $end_at
 *
 * @property BackUser $backUser
 * @property NoticeRead[] $noticeReads
 */
class Notice extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%notice}}';

    protected $field = [
        'id',
        'is_delete',
        'back_user_id',
        'title',
        'content',
        'is_passed',
        'order',
        'remark',
        'readed',
        'created_at',
        'updated_at',
        'start_at',
        'end_at',
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
    public static function getPassList()
    {
        return self::T('pass');
    }

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
                ['is_passed','number'],
                ['order','number'],
                ['readed','number'],
                ['title','max:50',],
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
            'back_user_id' => '后台管理员ID',
            'title' => '推送标题',
            'content' => '推送内容',
            'is_passed' => '状态;0=无效;1=待审核,2=已通过,3=未通过,4=保存,5=已推送;',
            'order' => '顺序',
            'remark' => '备注',
            'readed' => '是否阅读; 1=未读 ,2=已读',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'start_at' => '开始时间',
            'end_at' => '截止时间',
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
     * @return \think\model\relation\HasMany
     */
    public function getNoticeReads()
    {
        return $this->hasMany(ucfirst(NoticeRead::tableNameSuffix()), 'id', 'notice_id');
    }
}
