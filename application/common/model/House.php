<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\BuildingBase;
use app\common\model\City;
use app\common\model\ImagesNewHouse;

/**
 * This is the model class for table "{{%house}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $building_base_id
 * @property string $title
 * @property integer $city_id
 * @property integer $county_id
 * @property string $address
 * @property integer $type
 * @property string $room
 * @property integer $hall
 * @property integer $kitchen
 * @property integer $toilet
 * @property integer $veranda
 * @property string $url
 * @property string $url_icon
 * @property integer $floorsType
 * @property integer $onFloor
 * @property integer $floors
 * @property integer $face
 * @property integer $houseType
 * @property integer $fitment
 * @property integer $eachPrice
 * @property integer $price
 * @property integer $years
 * @property integer $area
 * @property string $description
 * @property string $supporting
 * @property string $traffic
 * @property string $around
 * @property string $houseLabel
 * @property integer $isTop
 * @property integer $status
 * @property integer $saleStatus
 * @property string $contact
 * @property string $tel
 * @property string $email
 * @property string $weChat
 * @property string $qq
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BuildingBase $buildingBase
 */
class House extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%house}}';

    protected $field = [
        'id',
        'is_delete',
        'building_base_id',
        'title',
        'city_id',
        'county_id',
        'address',
        'type',
        'room',
        'hall',
        'kitchen',
        'toilet',
        'veranda',
        'url',
        'url_icon',
        'floorsType',
        'onFloor',
        'floors',
        'face',
        'houseType',
        'fitment',
        'eachPrice',
        'price',
        'years',
        'area',
        'description',
        'supporting',
        'traffic',
        'around',
        'houseLabel',
        'isTop',
        'status',
        'saleStatus',
        'contact',
        'tel',
        'email',
        'weChat',
        'qq',
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
                ['building_base_id','number',],
                ['type','number',],
                ['room','max:255',],
                ['description','max:255',],
                ['address','max:255',],
                ['url','max:255',],
            ],
            'msg'=>[],
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
            'building_base_id' => '楼盘表ID',
            'title' => '介绍/标题',
            'city_id' => '城市',
            'county_id' => '区域/县级',
            'address' => '地址',
            'type' => '类型',
            'room' => '房号',
            'hall' => '大厅',
            'kitchen' => '厨房',
            'toilet' => '独卫',
            'veranda' => '阳台',
            'url' => '封面宣传',
            'url_icon' => '缩略图',
            'floorsType' => '所在楼层类型',
            'onFloor' => '位于楼层',
            'floors' => '共计楼层',
            'face' => '房屋朝向',
            'houseType' => '房屋类型',
            'fitment' => '装修情况',
            'eachPrice' => '房屋单价(元/平方米)',
            'price' => '售价',
            'years' => '建筑年代',
            'area' => '建筑面积',
            'description' => '详细描述',
            'supporting' => '配套设施',
            'traffic' => '交通状况',
            'around' => '周边环境',
            'houseLabel' => '房源标签',
            'isTop' => '置顶',
            'status' => '状态',
            'saleStatus' => '销售状态',
            'contact' => '联系人',
            'tel' => '手机/固话',
            'email' => '邮箱',
            'weChat' => '微信',
            'qq' => 'QQ',
            'created_by' => '创建者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBuildingBase()
    {
        return $this->hasOne(ucfirst(BuildingBase::tableNameSuffix()),'id', 'building_base_id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCreatedBy()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()),'id', 'created_by');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCity()
    {
        return $this->hasOne(ucfirst(City::tableNameSuffix()),'id', 'city_id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCounty()
    {
        return $this->hasOne(ucfirst(City::tableNameSuffix()),'id', 'county_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getImages()
    {
        return $this->hasMany(ucfirst(ImagesNewHouse::tableNameSuffix()), 'target_id', 'id');
    }
}
