<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\Focus;

/**
 * This is the model class for table "{{%label_park}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $label_id
 * @property integer $target_id
 * @property integer $group
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Focus $label
 */
class FocusPark extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%focus_park}}';

    protected $field = [
        'id',
        'is_delete',
        'label_id',
        'target_id',
        'group',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static $groupList = ['1'=>'预定','2'=>'客户','4'=>'新房','5'=>'二手房','6'=>'楼房','7'=>'客服'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_delete', 'label_id', 'target_id', 'group'], 'integer'],
            [['label_id', 'target_id', 'name', 'description', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
            [['label_id'], 'exist', 'skipOnError' => true, 'targetClass' => Focus::tableNameSuffix(), 'targetAttribute' => ['label_id' => 'id']],
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
            'label_id' => '标签表ID',
            'target_id' => '目标表ID;根据group值外联',
            'group' => '父级类型:0=失效,1=预定,2=客户,3=房东,4=新房,5=二手房,6=楼房,7=客服,8=出租;默认1;',
            'name' => '标签',
            'description' => '详细描述',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getFocus()
    {
        return $this->hasOne(ucfirst(Focus::tableNameSuffix()), 'label_id', 'id');
    }

    public static function getGroupList(){
        return self::$groupList;
    }
}
