<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\City;
use app\common\model\BuildingDetail;
use app\common\model\BuildingContent;
use app\common\model\ImagesBuilding;
use app\common\model\HouseBetter;
use app\common\model\House;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%building_base}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $type
 * @property integer $city_id
 * @property integer $county_id
 * @property string $title
 * @property string $titlePinYin
 * @property string $created_at
 * @property string $updated_at
 *
 * @property City $city
 * @property BuildingDetail $buildingDetail
 * @property BuildingContent $BuildingContent
 * @property images[] $images
 * @property HouseBetter[] $houseBetters
 * @property House[] $newHouses
 * @property BackUser $createdBy
 */
class BuildingBase extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%building_base}}';

    protected $field = [
        'id',
        'is_delete',
        'title',
        'titlePinyin',
        'city_id',
        'county_id',
        'created_by',
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
                ['city_id','number','城市 无效'],
                ['county_id','number','区县 无效'],
                ['title','max:255',],
                ['titlePinyin','max:255',],
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
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'city_id' => '城市表ID',
            'county_id' => '城市表区县ID',
            'title' => '楼盘名',
            'titlePinyin' => '楼盘名拼音',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCity()
    {
        return $this->hasOne(ucfirst(City::tableNameSuffix()), 'id', 'city_id');
    }

    /**
     * @return \think\model\relation\hasOne
     */
    public function getBuildingDetail()
    {
        return $this->hasOne(ucfirst(BuildingDetail::tableNameSuffix()), 'building_base_id','id');
    }

    /**
     * @return \think\model\relation\hasOne
     */
    public function getBuildingContent()
    {
        return $this->hasOne(ucfirst(BuildingContent::tableNameSuffix()), 'building_base_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getImages()
    {
        return $this->hasMany(ucfirst(ImagesBuilding::tableNameSuffix()), 'target_id', 'id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCreatedBy()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()),'id', 'created_by');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getHouseBetters()
    {
        return $this->hasMany(ucfirst(HouseBetter::tableNameSuffix()), 'building_base_id', 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getNewHouses()
    {
        return $this->hasMany(ucfirst(House::tableNameSuffix()), 'building_base_id', 'id');
    }
}
