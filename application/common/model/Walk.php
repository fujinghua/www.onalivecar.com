<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\Client;

/**
 * This is the model class for table "{{%walk}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $client_id
 * @property integer $on_server
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Client $client
 */
class Walk extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%walk}}';

    protected $field = [
        'id',
        'is_delete',
        'client_id',
        'on_server',
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
            [['is_delete', 'client_id', 'on_server'], 'integer'],
            [['client_id', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::tableNameSuffix(), 'targetAttribute' => ['client_id' => 'id']],
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
            'client_id' => '客户表ID',
            'on_server' => '跟进;0=未跟进,1=已跟进;默认1;',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getClient()
    {
        return $this->hasOne(Client::tableNameSuffix(), ['id' => 'client_id']);
    }
}
