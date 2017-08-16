<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $is_passed
 * @property integer $back_user_id
 * @property string $url
 * @property string $target
 * @property string $title
 * @property string $start_at
 * @property string $end_at
 * @property integer $order
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 */
class HouseBetter extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%house_better}}';

    protected $field = [
        'id',
        'is_delete',
        'is_passed',
        'back_user_id',
        'type',
        'url',
        'target',
        'title',
        'start_at',
        'end_at',
        'order',
        'status',
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
            [['is_delete', 'is_passed', 'back_user_id', 'order', 'status'], 'integer'],
            [['back_user_id', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'target'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 80],
            [['start_at', 'end_at'], 'string', 'max' => 10],
            [['back_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BackUser::tableNameSuffix(), 'targetAttribute' => ['back_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'is_passed' => '审核;0=未通过,1=审核中,2=已通过;默认1;',
            'back_user_id' => '后台管理员ID',
            'url' => '图片地址',
            'target' => '目标地址',
            'title' => '标题',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'order' => '拖拽顺序',
            'status' => '状态;0=失效,1=预定,2=上架,3=下架;默认1;',
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
}
