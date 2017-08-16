<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%delete_log}}".
 *
 * @property integer $id
 * @property integer $table_type
 * @property integer $back_user_id
 * @property integer $delete_id
 * @property string $remark
 * @property string $created_at
 *
 * @property BackUser $backUser
 */
class DeleteLog extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%delete_log}}';

    protected $field = [
        'id',
        'table_type',
        'back_user_id',
        'delete_id',
        'remark',
        'created_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static $tableList = ['0'=>'全部','1'=>'表一','2'=>'表二'];

    public static function getTableList(){
        return self::$tableList;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['table_type','number','目标表类型 不是数值'],
                ['delete_id','number','被操作ID 不是数值'],
                ['back_user_id','number','后台业务员ID 不是数值'],
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
            'table_type' => '目标表类型',
            'back_user_id' => '后台业务员ID',
            'delete_id' => '被操作ID',
            'remark' => '备注',
            'created_at' => '创建时间',
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
