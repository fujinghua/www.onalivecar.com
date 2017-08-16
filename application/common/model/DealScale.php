<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use app\common\model\Client;

/**
 * This is the model class for table "{{%deal_scale}}".
 *
 * @property string $id
 * @property string $deal_id
 * @property string $client_id
 * @property integer $type
 * @property string $belongId
 * @property string $belongName
 * @property double $scale
 * @property string $departmentId
 * @property double $performance
 * @property double $payment
 * @property string $earnestDate
 */
class DealScale extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%deal_scale}}';

    protected $field = [
        'id',
        'deal_id',
        'client_id',
        'type',
        'belongId',
        'belongName',
        'scale',
        'departmentId',
        'performance',
        'payment',
        'earnestDate',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static $houseType = ['1'=>'新房','2'=>'二手房'];

    public static function getHouseType(){
        return self::$houseType;
    }

    public static $status = ['1'=>'成交','2'=>'取消'];

    public static function getDealStatus(){
        return self::$status;
    }

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
            'id' => 'id',
            'deal_id' => '购房明细id',
            'client_id' => '客户id',
            'type' => '1表示权属，2表示地接',
            'belongId' => '业务人员id',
            'belongName' => '业务人员',
            'scale' => '业绩所占比例',
            'departmentId' => '部门id',
            'performance' => '业绩',
            'payment' => '提成基数',
            'earnestDate' => '认购时间',
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
