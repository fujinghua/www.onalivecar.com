<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BuildingBase;

/**
 * This is the model class for table "hfzy_building_detail".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $building_base_id
 * @property string $description
 * @property string $reason
 * @property string $feature
 * @property string $featureExtra
 * @property string $decoration
 * @property string $decorationExtra
 * @property string $saleStatus
 * @property integer $priceMin
 * @property integer $priceMax
 * @property integer $priceAvg
 * @property string $priceSum
 * @property string $priceSumType
 * @property string $status
 * @property string $buildingType
 * @property string $buildingTypeExtra
 * @property double $buildingArea
 * @property double $houseArea
 * @property integer $areaMax
 * @property integer $areaMin
 * @property integer $buildingNum
 * @property integer $houseNum
 * @property integer $parkingNum
 * @property string $started_at
 * @property string $joined_at
 * @property string $weight
 * @property string $address
 * @property string $url
 * @property string $url_icon
 * @property string $adTitle
 * @property double $FAR
 * @property double $poolRate
 * @property string $structure
 * @property string $wall
 * @property string $builders
 * @property string $developer
 * @property string $investor
 * @property string $contacter
 * @property string $contacterTel
 * @property string $saleTel
 * @property string $saleAddress
 * @property string $propertyName
 * @property string $propertyFee
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property BuildingBase $buildingBase
 */
class BuildingDetail extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%building_detail}}';

    protected $field = [
        'id',
        'is_delete',
        'building_base_id',
        'description',
        'reason',
        'feature',
        'featureExtra',
        'decoration',
        'decorationExtra',
        'saleStatus',
        'priceMin',
        'priceMax',
        'priceAvg',
        'priceSum',
        'priceSumType',
        'status',
        'buildingType',
        'buildingTypeExtra',
        'buildingArea',
        'houseArea',
        'areaMax',
        'areaMin',
        'buildingNum',
        'houseNum',
        'parkingNum',
        'started_at',
        'joined_at',
        'weight',
        'address',
        'url',
        'url_icon',
        'adTitle',
        'FAR',
        'poolRate',
        'structure',
        'wall',
        'builders',
        'developer',
        'investor',
        'contacter',
        'contacterTel',
        'saleTel',
        'saleAddress',
        'propertyName',
        'propertyFee',
        'created_by',
        'created_at',
        'updated_by',
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
                ['building_base_id','number','楼盘 无效'],
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
            'description' => '详细描述',
            'reason' => '推荐理由',
            'feature' => '楼盘特色',
            'featureExtra' => '其他楼盘特色',
            'decoration' => '装修情况',
            'decorationExtra' => '其他装修情况',
            'saleStatus' => '销售状态',
            'priceMin' => '楼盘起价',
            'priceMax' => '楼盘最高价',
            'priceAvg' => '楼盘均价',
            'priceSum' => '单套总价',
            'priceSumType' => '单套总价范围',
            'status' => '现房期房',
            'buildingType' => '建筑形式',
            'buildingTypeExtra' => '其他建筑形式',
            'buildingArea' => '占地面积',
            'houseArea' => '建筑面积',
            'areaMax' => '单套最大面积',
            'areaMin' => '单套最小面积',
            'buildingNum' => '总栋数',
            'houseNum' => '总套数',
            'parkingNum' => '停车位',
            'started_at' => '开盘日期',
            'joined_at' => '入住日期',
            'weight' => '权重设置',
            'address' => '详细地址',
            'url' => '楼盘封面',
            'url_icon' => '楼盘封面缩略图',
            'adTitle' => '楼盘广告语',
            'FAR' => '容积率',
            'poolRate' => '绿化率',
            'structure' => '结构',
            'wall' => '外墙',
            'builders' => '承建商',
            'developer' => '开发商',
            'investor' => '投资商',
            'contacter' => '现场接待人',
            'contacterTel' => '现场接待人',
            'saleTel' => '售楼处电话',
            'saleAddress' => '售楼处地址',
            'propertyName' => '物业公司',
            'propertyFee' => '物业费',
            'created_by' => '创建者',
            'created_at' => '修改时间',
            'updated_by' => '更新者',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBuildingBase()
    {
        return $this->hasOne(BuildingBase::tableNameSuffix(), ['id' => 'building_base_id']);
    }
}
