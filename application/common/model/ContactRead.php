<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\Contact;

/**
 * This is the model class for table "{{%contact_read}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $assign
 * @property integer $back_user_id
 * @property integer $contact_id
 * @property string $content
 * @property string $remark
 * @property string $reback
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Contact $contact
 */
class ContactRead extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%contact_read}}';

    protected $field = [
        'id',
        'is_delete',
        'assign',
        'back_user_id',
        'contact_id',
        'content',
        'remark',
        'reback',
        'created_at',
        'updated_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static $assignList = ['0'=>'分配','1'=>'自由'];

    public static function getAssignType(){
        return self::$assignList;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['is_delete','in:0,1','时效 无效'],
                ['assign','number','分配 不是数值'],
                ['back_user_id','number','后台管理员 不是数值'],
                ['contact_id','number','联系我们表 不是数值'],
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
            'assign' => '分配;0=分配;1=自由;',
            'back_user_id' => '后台管理员ID',
            'contact_id' => '联系我们表ID',
            'content' => '通知信息内容',
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
    public function getContact()
    {
        return $this->hasOne(Contact::tableNameSuffix(), ['id' => 'contact_id']);
    }
}
