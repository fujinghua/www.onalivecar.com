<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthItem;
use app\common\model\BackUser;

/**
 * This is the model class for table "{{%ban}}".
 *
 * @property string $item_name
 * @property integer $back_user_id
 * @property integer $ban
 * @property string $created_at
 *
 * @property AuthItem $itemName
 * @property BackUser $backUser
 */
class Ban extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%ban}}';

    protected $field = [
        'item_name',
        'back_user_id',
        'ban',
        'created_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    /**
     * @return array
     */
    public static function getBanList(){
        return self::T('ban');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['back_user_id','number','用户 无效'],
                ['ban','in:0,1,2','权限 无效'],
                ['item_name','max:64',],
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
            'item_name' => '名称',
            'back_user_id' => 'UID',
            'ban' => '类型;0=无效,1=允许,2=禁止;',
            'created_at' => '变更时间',
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
        return $this->hasOne(ucfirst(BackUser::tableNameSuffix()), 'id', 'back_user_id');
    }


    /**
     * @return array
     */
    public static function getAllPermissionsName()
    {
        $all = AuthItem::load()->where(['type'=>'2'])->getField('name');
        $item = [];
        if ($all){
            $item = $all;
        }
        return $item;
    }

    /**
     * @return array
     */
    public static function getPermissions(){
        $model = Yii::$app->getDb()->createCommand('SELECT * FROM '.AuthItem::tableName().' WHERE `type`= \'2\' AND `data` is NOT null ORDER BY `created_at` ASC')->queryAll();
        $permissions = [];
        if (!empty($model)){
            foreach ($model as $key => $value){
                $tmp['name'] = $value['name'];
                $tmp['role'] = json_decode($value['data'],true);
                $permissions[] = $tmp;
            }
        }
        return $permissions;
    }

    /**
     * @param $uid
     * @return array
     */
    public static function getBanByUserId($uid){
        $permissions = self::getPermissionsByUserId($uid);
        $ban = Ban::find()->where(['user_id'=>$uid, 'ban'=>'1'])->asArray()->all();
        if (!empty($ban)){
            foreach ($ban as $key => $value){
                foreach ($permissions as $pKey => $pValue){
                    if ($value['item_name'] == $pValue['name']){
                        $permissions[$pKey]['ban'] = 1;
                    }
                }
            }
        }
        return $permissions;
    }

    /**
     * @param $uid
     * @return array
     */
    public static function getPermissionsByUserId($uid){
        $permissions = self::getPermissions();
        $user = Admin::findOne($uid);
        $res = [];
        if (!empty($permissions) && $user){
            foreach ($permissions as $key => $value){
                if (in_array($user->role, $value['role'])){
                    $value['ban'] = 0;
                    $res[] = $value;
                }
            }
        }
        return $res;
    }

    /**
     * @param $uid
     * @param array $ban
     * @return bool
     */
    public static function setBan($uid,$ban){
        $res = false;
        if (is_array($ban)){
            Ban::deleteAll(['user_id'=>$uid]);
            if (!empty($ban)){
                $allPermissions = self::getAllPermissionsName();
                foreach ($ban as $key => $value){
                    if (in_array($value,$allPermissions)){
                        $model = new Ban();
                        $model->item_name = $value;
                        $model->user_id = $uid;
                        $model->ban = '1';
                        $model->created_at = time();
                        $model->save();
                    }
                }
            }
            $res = true;
            $route = __CLASS__;
            $dependency = [Ban::className()];
            Helper::toDelete($uid, $route, $dependency);
        }
        return $res;
    }

    /**
     * @description 获取特殊禁止权限
     * @param string $uid
     * @param bool $refresh
     * @return array
     */
    public static function getBanRoutes($uid ='', $refresh = false){
        $res = [];

        if(!isset(Yii::$app->user->id)){
            return $res;
        }

        //设置缓存KEY
        $userId = Yii::$app->user->id;
        $route = __CLASS__;
        $dependency = [Ban::className()];
        if (!empty($uid)){
            $userId = $uid;
        }

        $routes = Helper::getCache($userId, $route, $dependency);
        if ($refresh || $routes === false || $routes === null ) {
            if (!empty($uid)){
                $user = Admin::findOne($uid);
                $role = $user->role;
            }else{
                $uid = $userId;
                $role = Yii::$app->user->identity->role;
            }
            if (empty($ban)){
                $permissions = self::getPermissions();
                $allowPermission = [];
                if (!empty($permissions)){
                    foreach ($permissions as $key => $value){
                        if (in_array($role, $value['role']) && $value['name'] != '权限管理'){
                            $allowPermission[] = $value['name'];
                        }
                    }
                }
                $model = Ban::find()->where(['user_id'=>$uid, 'ban'=>'1'])->asArray()->all();
                if (!empty($model)){
                    foreach ($model as $key => $value){
                        if (in_array($value['item_name'], $allowPermission)){
                            $res = array_merge($res,Yii::$app->authManager->getChildren($value['item_name']));;
                        }
                    }
                }
            }
            $res = array_keys($res);
            $routes = $res;
            Helper::setCache($routes, $userId, $route, $dependency);
        }
        $res = $routes;
        return $res;
    }
}
