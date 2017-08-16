<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\HouseHost;
use app\common\model\City;

/**
 * This is the model class for table "{{%hand_house}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $house_host_id
 * @property string $title
 * @property integer $city_id
 * @property integer $county_id
 * @property string $address
 * @property integer $type
 * @property string $code
 * @property integer $room
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
 */
class HandHouse extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%hand_house}}';

    protected $field = [
        'id',
        'is_delete',
        'house_host_id',
        'title',
        'city_id',
        'county_id',
        'address',
        'type',
        'code',
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
                ['city_id','number',],
                ['room','max:255',],
                ['description','max:255',],
                ['address','max:255',],
                ['url','max:255',],
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
            'house_host_id' => '房东表ID',
            'title' => '介绍/标题',
            'city_id' => '城市',
            'county_id' => '区域/县级',
            'address' => '地址',
            'type' => '类型',
            'code' => '门牌号',
            'room' => '卧室',
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
    public function getHouseHost()
    {
        return $this->hasOne(HouseHost::tableNameSuffix(), ['id' => 'house_host_id']);
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getCity()
    {
        return $this->hasOne(City::tableNameSuffix(), ['id' => 'city_id']);
    }
}
