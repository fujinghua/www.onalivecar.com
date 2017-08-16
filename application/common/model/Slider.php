<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $is_passed
 * @property integer $back_user_id
 * @property integer $type
 * @property string $url
 * @property string $target
 * @property string $title
 * @property string $description
 * @property string $start_at
 * @property string $end_at
 * @property integer $order
 * @property integer $app
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 */
class Slider extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%slider}}';

    protected $field = [
        'id',
        'is_delete',
        'is_passed',
        'back_user_id',
        'type',
        'url',
        'url_icon',
        'target',
        'title',
        'description',
        'start_at',
        'end_at',
        'order',
        'app',
        'status',
        'isDefault',
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
            ],
            'msg' => []
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
            'is_passed' => '审核;1=未通过,2=审核中,3=已通过;默认2;',
            'back_user_id' => '后台管理员ID',
            'type' => '父级类型:1=默认,2=首页,3=新房,4=二手房,5=出租,6=楼房,7=联系我们,;默认1;',
            'url' => '图片地址',
            'url_icon' => '图片略缩图',
            'target' => '跳转地址',
            'title' => '标题',
            'description' => '详细介绍',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'order' => '拖拽顺序',
            'app' => '应用;1=后台;2=前台;',
            'status' => '状态;0=失效,1=预定,1=上架,2=下架;默认1;',
            'isDefault' => '是否是默认图片',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param array $where
     * @param int $limit
     * @return array
     */
    public static function getSlider($where = [],$limit = 6){
        $ret = [];
        if (!empty($where)){
            $time = "'".date('Y-m-d')."'";
            $condition = [
                'is_delete'=>'1',
                'is_passed'=>'3',
                'status'=>'3',
                ['exp',implode(' ',['`start_at`','<=',$time])],
                ['exp',implode(' ',['`end_at`','>=',$time])],
                ];
            $where = array_merge($condition,$where);
            $model = Slider::load();
            $ret = $model->asArray($model->where($where)->order('`order` ASC')->select());
        }
        return $ret;
    }


    /**
     * @return \think\model\relation\HasOne
     */
    public function getBackUser()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'id', 'back_user_id');
    }
}
