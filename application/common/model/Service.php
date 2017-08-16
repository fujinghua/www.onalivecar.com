<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%service}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $level
 * @property integer $back_user_id
 * @property integer $duration
 * @property string $start_at
 * @property string $end_at
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 */
class Service extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%service}}';

    protected $field = [
        'id',
        'is_delete',
        'level',
        'back_user_id',
        'duration',
        'start_at',
        'end_at',
        'order',
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
     * @param bool $hot
     * @return array
     */
    public static function getService($hot = false){
        $ret = [];
        $query = Service::load()->alias('t')
            ->join([BackUser::tableName()=>'b'],'t.back_user_id = b.id')
            ->where([
            't.is_delete'=>'1',
            'b.is_delete'=>'1',
                ]);
        $where = ['t.level'=>'1'];
        if ($hot){
            $where = ['t.level'=>'2'];
        }
        $result = $query->where($where)->order('t.order','ASC')->select();

        if ($result){
            $helper = Service::getHelper();
            $result = $helper::toArray($result);
            foreach ($result as $value){
                $name = $value['service_name'];
                if (empty($name)){
                    $name = $value['nickname'];
                }
                if (empty($name)){
                    $name = $value['username'];
                }
                $ret[$value['back_user_id']] = $name;
            }
        }

        return $ret;

    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['is_delete','in:0,1','时效 无效'],
                ['level','number','等级 不是数值'],
                ['back_user_id','number','后台管理员 不是数值'],
                ['duration','number','有效时间 不是数值'],
                ['order','number','拖拽顺序 不是数值'],
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
            'level' => '等级;1=普通客服;2=金牌客服;',
            'back_user_id' => '后台管理员ID',
            'duration' => '有效时间',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'order' => '拖拽顺序',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBackUser()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'back_user_id', 'id');
    }
}
