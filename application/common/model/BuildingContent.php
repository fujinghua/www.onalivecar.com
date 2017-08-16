<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BuildingBase;
use app\common\model\BuildingDetail;

/**
 * This is the model class for table "{{%building_content}}".
 *
 * @property integer $building_base_id
 * @property string $content
 *
 * @property BuildingBase $buildingBase
 * @property BuildingDetail $buildingDetail
 */
class BuildingContent extends Model
{


    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%building_content}}';

    protected $field = [
        'building_base_id',
        'content',
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
            'building_base_id' => '楼盘表ID',
            'content' => '楼盘介绍',
        ];
    }

    /**
     * @return \think\model\relation\hasOne
     */
    public function getBuildingBase()
    {
        return $this->hasOne(ucfirst(BuildingBase::tableNameSuffix()), 'id','building_base_id');
    }

    /**
     * @return \think\model\relation\hasOne
     */
    public function getBuildingDetail()
    {
        return $this->hasOne(ucfirst(BuildingDetail::tableNameSuffix()), 'building_base_id','building_base_id');
    }
}
