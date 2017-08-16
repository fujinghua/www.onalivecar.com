<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthItem;
use app\common\model\BackUser;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property integer $back_user_id
 * @property string $created_at
 *
 * @property AuthItem $itemName
 * @property BackUser $backUser
 */
class AuthAssignment extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%auth_assignment}}';

    protected $field = [
        'item_name',
        'user_id',
        'created_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    /**
     * @description 自动验证规则
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['back_user_id','number','用户 无效'],
                ['item_name','max:64',],
            ],
            'msg'=>[]
        ];
    }

    /**
     * 设置角色
     * @param null $id
     * @param null $role
     * @return array
     */
    public static function setRole($id = null, $role = null)
    {
        $ret = ['status' => 0, 'info' => '分配角色失败'];
        if (!(empty($id) || empty($role))) {
            if (!AuthItem::findByRole($role)) {
                $ret['info'] = '该角色不存在';
            } elseif (!BackUser::get(['id' => $id])) {
                $ret['info'] = '该账号不存在';
            } elseif (AuthAssignment::load()->where(['item_name' => $role, 'user_id' => $id])->find()) {
                $ret['info'] = '该账号已属于该角色';
            }else{
                $data['item_name'] = $role;
                $data['user_id'] = $id;
                $data['create_at'] = date('Y-m-d H:i:s');
                AuthAssignment::load()->save($data);
                $ret = ['status' => 1, 'info' => '分配角色成功'];
            }
        }
        return $ret;
    }

    /**
     * 移除角色
     * @param null $id
     * @param null $role
     * @return array
     */
    public static function removeRole($id = null, $role = null)
    {
        $ret = ['status' => 0, 'info' => '移除角色失败'];
        if (!(empty($id) || empty($role))) {
            if (!AuthItem::findByRole($role)) {
                $ret['info'] = '该角色不存在';
            } elseif (!BackUser::get(['id' => $id])) {
                $ret['info'] = '该账号不存在';
            } elseif (!AuthAssignment::load()->where(['item_name' => $role, 'user_id' => $id])->find()) {
                $ret['info'] = '该账号不属于该角色';
            }else{
                AuthAssignment::load()->where(['item_name' => $role, 'user_id' => $id])->find()->delete();
                $ret = ['status' => 1, 'info' => '移除角色成功'];
            }
        }
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => '分类权限等级',
            'back_user_id' => '分配对象UID',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getItemName()
    {
        return $this->hasOne(ucfirst(AuthItem::tableNameSuffix()), 'name', 'item_name');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getBackUser()
    {
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'id', 'user_id');
    }
}
