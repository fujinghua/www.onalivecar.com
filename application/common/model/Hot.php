<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%hot}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $is_passed
 * @property integer $back_user_id
 * @property integer $type
 * @property string $url
 * @property string $target
 * @property string $title
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
class Hot extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%hot}}';

    protected $field = [
        'id',
        'is_delete',
        'is_passed',
        'back_user_id',
        'type',
        'url',
        'target',
        'base_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'order',
        'app',
        'status',
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
     * @param array | string $where
     * @return array
     */
    public static function getHot($where = null){
        $ret = [];
        $query = Hot::load()->where([
            'is_delete'=>'1',
            'is_passed'=>'1',
            'status'=>'2',
        ]);
        if ($where){
            $query = $query->where($where);
        }
        $result = $query->select();

        if ($result){
            $helper = Contact::getHelper();
            $result = $helper::toArray($result);
            foreach ($result as $value){
                $item = [];
                $item['id'] = $value['id'];
                if (!$where){
                    $item['type'] = $value['type'];
                }
                $item['base_id'] = $value['base_id'];
                $ret[] = $item;
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
                ['is_passed','in:1,2,3','审核 无效'],
                ['type','in:0,1,2,3,4,5,6','类型 无效'],
                ['app','in:1,2','应用 无效'],
                ['status','in:0,1,2,3','状态 无效'],
                ['back_user_id','number',],
                ['type','number',],
                ['url','max:255',],
                ['target','max:255',],
                ['title','max:80',],
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
            'is_passed' => '审核;1=已通过,2=审核中,3=未通过;默认1;',
            'back_user_id' => '后台管理员ID',
            'type' => '父级类型:1=默认,2=首页,3=新房,4=二手房,5=楼盘,6=交易,;默认1;',
            'url' => '图片地址',
            'url_icon' => '图片缩略图',
            'target' => '跳转地址',
            'base_id' => '基础外键',
            'title' => '标题',
            'description' => '详细介绍',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'order' => '拖拽顺序',
            'app' => '应用;1=后台;2=前台;',
            'status' => '状态;0=失效,1=预定,2=上架,3=下架;默认1;',
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
