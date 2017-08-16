<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\Client;

/**
 * This is the model class for table "{{%deal}}".
 *
 * @property integer $id
 * @property integer $arrange_id
 * @property integer $client_id
 * @property string $belongUserId
 * @property integer $type
 * @property integer $building_base_id
 * @property integer $hand_house_id
 * @property integer $sellerId
 * @property string $sellerTel
 * @property double $buildingReback
 * @property integer $dealAward
 * @property string $houseNo
 * @property integer $area
 * @property double $eachPrice
 * @property integer $total
 * @property string $earnestDate
 * @property string $earnest
 * @property string $dealDate
 * @property string $signDate
 * @property string $preference
 * @property integer $preferencePrice
 * @property integer $preferenceWay
 * @property integer $payWay
 * @property integer $isBack
 * @property string $firstDate
 * @property string $firstMoney
 * @property string $lastDate
 * @property string $lastMoney
 * @property string $notes
 * @property string $recepter
 * @property string $receptPhone
 * @property string $preSenter
 * @property integer $status
 * @property string $prePhone
 * @property string $checkedAt
 * @property string $checkedBy
 * @property integer $updatedBy
 * @property string $updatedAt
 * @property integer $createdBy
 * @property string $createdAt
 * @property integer $accounted
 * @property string $url
 * @property string $url_icon
 */
class Deal extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%deal}}';

    protected $field = [
        'id',
        'is_delete',
        'arrange_id',
        'client_id',
        'belongUserId',
        'type',
        'building_base_id',
        'hand_house_id',
        'sellerId',
        'sellerTel',
        'buildingReback',
        'dealAward',
        'houseNo',
        'area',
        'eachPrice',
        'total',
        'earnestDate',
        'earnest',
        'dealDate',
        'signDate',
        'preference',
        'preferencePrice',
        'preferenceWay',
        'payWay',
        'isBack',
        'firstDate',
        'firstMoney',
        'lastDate',
        'lastMoney',
        'notes',
        'recepter',
        'receptPhone',
        'preSenter',
        'status',
        'prePhone',
        'checkedAt',
        'checkedBy',
        'updatedBy',
        'updatedAt',
        'createdBy',
        'createdAt',
        'accounted',
        'url',
        'url_icon',
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
                ['house_type','in:2,1','类型 无效'],
                ['deal_status','in:2,1','交易状态 无效'],
                ['back_user_id','number',],
                ['client_id','number',],
                ['house_type','number',],
                ['goods_id','number',],
                ['deal_status','number',],
                ['order_code','max:255',],
                ['money','max:255',],
                ['description','max:255',],
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
            'is_delete' => '时效',
            'arrange_id' => '带看单id',
            'client_id' => '客户表id',
            'belongUserId' => '权属人id',
            'type' => '类型：1新房，2二手房',
            'building_base_id' => '楼盘id',
            'hand_house_id' => '房东id',
            'sellerId' => '销售者id',
            'sellerTel' => '销售人电话',
            'buildingReback' => '楼盘返点',
            'dealAward' => '成交奖励',
            'houseNo' => '房号',
            'area' => '面积',
            'eachPrice' => '成交单价',
            'total' => '房款总价',
            'earnestDate' => '认购时间',
            'earnest' => '定金金额',
            'dealDate' => '成交时间',
            'signDate' => '签约时间',
            'preference' => '优惠备注',
            'preferencePrice' => '优惠金额',
            'preferenceWay' => '优惠方式(1:优先从成交奖励减，不够再从总房款减；2：从总房款减 )',
            'payWay' => '付款方式;1一次性，2按揭，3分期付款',
            'isBack' => '是否结佣：1未结佣，2部分结佣，3全部结佣',
            'firstDate' => '首款时间',
            'firstMoney' => '首款金额',
            'lastDate' => '付后款时间',
            'lastMoney' => '后款金额',
            'notes' => '备注',
            'recepter' => '签约者',
            'receptPhone' => '签约者手机',
            'preSenter' => '预备签约者',
            'status' => '状态',
            'prePhone' => '签约预备手机号',
            'checkedAt' => '审核时间',
            'checkedBy' => 'Checked By',
            'updatedBy' => '更新者',
            'updatedAt' => 'Updated At',
            'createdBy' => '创建者',
            'createdAt' => '创建时间',
            'accounted' => '可结金额',
            'url' => '成交单',
            'url_icon' => '成交单略缩图',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBackUser()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'back_user_id', 'id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getClient()
    {
        return $this->hasOne(ucfirst(Client::tableNameSuffix()), 'client_id', 'id');
    }
}
