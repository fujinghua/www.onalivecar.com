<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\ClientServer;
use app\common\model\Deal;
use app\common\model\Walk;

/**
 * This is the model class for table "{{%client}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $cardId
 * @property string $userName
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $avatar
 * @property integer $type
 * @property integer $sex
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $county_id
 * @property integer $clientFrom
 * @property string $clientFromExtra
 * @property string $agentName
 * @property string $agentTel
 * @property string $otherName
 * @property string $otherPhone
 * @property string $qq
 * @property string $weChat
 * @property string $birth
 * @property string $job
 * @property string $buyPurpose
 * @property string $notes
 * @property integer $requireType
 * @property integer $inHainan
 * @property integer $askType
 * @property integer $askDate
 * @property string $lastVisitDate
 * @property string $nextVisitDate
 * @property integer $visitNum
 * @property integer $serviceStatus
 * @property string $serviceAt
 * @property integer $belongUserId
 * @property integer $firstUserId
 * @property integer $arrangeId
 * @property integer $changeClient
 * @property string $delReason
 * @property integer $belongPid
 * @property integer $updatedBy
 * @property string $updatedAt
 * @property integer $createdBy
 * @property string $createdAt
 * @property integer $agentNextId
 * @property string $visitHouseAt
 * @property integer $isBargain
 * @property string $agentAt
 * @property integer $fitmentId
 * @property string $seeItem
 * @property string $buyCity
 * @property string $clientPoint
 * @property string $showAt
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClientServer[] $clientServers
 * @property Deal[] $takeOrders
 * @property Walk[] $walks
 */
class Client extends Model
{
    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%client}}';
    /**
     * @var array
     */
    protected $field = [
        'id',
        'is_delete',
        'cardId',
        'userName',
        'phone',
        'address',
        'email',
        'avatar',
        'type',
        'sex',
        'province_id',
        'city_id',
        'county_id',
        'clientFrom',
        'agentName',
        'agentTel',
        'otherName',
        'otherPhone',
        'qq',
        'weChat',
        'birth',
        'job',
        'notes',
        'requireType',
        'buyPurpose',
        'inHainan',
        'askType',
        'askDate',
        'lastVisitDate',
        'nextVisitDate',
        'visitNum',
        'serviceStatus',
        'serviceAt',
        'belongUserId',
        'firstUserId',
        'arrangeId',
        'changeClient',
        'delReason',
        'belongPid',
        'updatedBy',
        'updatedAt',
        'createdBy',
        'createdAt',
        'agentNextId',
        'visitHouseAt',
        'isBargain',
        'agentAt',
        'fitmentId',
        'seeItem',
        'buyCity',
        'clientPoint',
        'showAt',
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
            'rule' => [
                ['is_delete', 'in:0,1', '时效 无效'],
                ['type', 'number', '类型 无效'],
                ['server', 'number', '服务 无效'],
                ['level', 'number', '等级 无效'],
                ['ID_cards', 'max:255',],
                ['avatar', 'max:255',],
                ['real_name', 'max:64',],
                ['nickname', 'max:64',],
                ['email', 'max:64',],
                ['phone', 'max:32',],
                ['address', 'max:32',],
            ],
            'msg' => [],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_delete' => '时效;0=失效,1=有效;默认1;',
            'cardId' => '身份证',
            'userName' => '用户名',
            'phone' => '手机号码',
            'address' => '地址',
            'email' => '邮箱',
            'avatar' => '头像地址',
            'type' => '类型;0=过客,1=客户;默认1;',
            'sex' => '性别',
            'province_id' => '省份',
            'city_id' => '城市',
            'county_id' => '区/县',
            'clientFrom' => '客户来源',
            'clientFromExtra' => '其他客户来源方式',
            'agentName' => '置业人员名称',
            'agentTel' => '置业人员联系方式',
            'otherName' => '其他名称',
            'otherPhone' => '其他手机号码',
            'qq' => 'QQ',
            'weChat' => '微信',
            'birth' => '生日',
            'job' => '工作',
            'buyPurpose' => '置业目的',
            'buyPurposeExtra' => '其他置业目的',
            'notes' => '备注',
            'requireType' => '需求类别',
            'inHainan' => '是否来过海南',
            'askType' => '接洽方式',
            'askDate' => '接洽日期',
            'lastVisitDate' => '第一次回访时间',
            'nextVisitDate' => '最近一次回访日期',
            'visitNum' => '回访次数',
            'serviceStatus' => '服务状态',
            'serviceAt' => '服务时间',
            'belongUserId' => '权属人',
            'firstUserId' => '第一次添加人',
            'arrangeId' => '看房安排',
            'changeClient' => '改变客户',
            'delReason' => '删除原因',
            'belongPid' => '权属上级',
            'updatedBy' => '更新者',
            'updatedAt' => '更新时间',
            'createdBy' => '添加者',
            'createdAt' => '创建时间',
            'agentNextId' => '经纪人id',
            'visitHouseAt' => '看房时间',
            'isBargain' => '1已成交，2未成交',
            'agentAt' => '确定时间',
            'fitmentId' => '家装',
            'seeItem' => '看房项目',
            'buyCity' => '购买城市',
            'clientPoint' => '客户关注',
            'showAt' => '查看时间',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getClientServers()
    {
        return $this->hasMany(ClientServer::tableNameSuffix(), ['client_id' => 'id']);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getDeals()
    {
        return $this->hasMany(Deal::tableNameSuffix(), ['client_id' => 'id']);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getWalks()
    {
        return $this->hasMany(Walk::tableNameSuffix(), ['client_id' => 'id']);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCreatedBy()
    {
        return $this->hasMany(BackUser::tableNameSuffix(),'id','createdBy');
    }
}