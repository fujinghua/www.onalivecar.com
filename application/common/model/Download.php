<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%download}}".
 *
 * @property integer $id
 * @property integer $back_user_id
 * @property string $title
 * @property string $url
 * @property string $fileName
 * @property string $tb_name
 * @property string $tb_id
 * @property string $tb_category
 * @property string $created_at
 *
 * @property BackUser $backUser
 */
class Download extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%download}}';

    protected $field = [
        'id',
        'is_delete',
        'back_user_id',
        'title',
        'url',
        'fileName',
        'tb_name',
        'tb_id',
        'tb_category',
        'created_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    //所有标签类型
    private static $typeList = ['1'=>'jpg','2'=>'png','3'=>'gif','4'=>'excel'];

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return self::$typeList;
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
                ['title','max:50'],
                ['tb_name','max:50'],
                ['fileName','max:255'],
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
            'id' => 'ID',
            'back_user_id' => '后台管理员ID',
            'title' => '资源描述',
            'url' => '资源地址',
            'fileName' => '资源名称',
            'tb_name' => '目标表名',
            'tb_id' => '表的id',
            'tb_category' => '类别信息',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getBackUser()
    {
        return $this->belongsTo(ucfirst(BackUser::tableNameSuffix()), 'back_user_id', 'id');
    }
}
