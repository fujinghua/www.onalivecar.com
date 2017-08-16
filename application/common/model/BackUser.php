<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\AuthAssignment;
use app\common\model\AuthItem;
use app\common\model\Ban;
use app\common\model\Department;
use app\common\model\BackUserLog;
use app\common\model\Contact;
use app\common\model\ContactRead;
use app\common\model\Service;
use app\common\model\DeleteLog;
use app\common\model\Download;
use app\common\model\ClientServer;
use app\common\model\Hot;
use app\common\model\HouseHostServer;
use app\common\model\Notice;
use app\common\model\NoticeRead;
use app\common\model\Opinion;
use app\common\model\OpinionRead;
use app\common\model\Slider;
use app\common\model\DealOrder;
use app\common\model\Upload;

/**
 * This is the model class for table "{{%back_user}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $code
 * @property integer $department_id
 * @property string $phone
 * @property integer $phone_verified
 * @property string $email
 * @property integer $email_verified
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $service_name
 * @property string $real_name
 * @property string $head_url
 * @property string $sex
 * @property string $signature
 * @property string $birthday
 * @property integer $height
 * @property integer $weight
 * @property string $token
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $password_reset_code
 * @property integer $status
 * @property string $ip
 * @property string $reg_ip
 * @property string $reg_type
 * @property string $registered_at
 * @property string $logined_at
 * @property string $updated_at
 *
 * @property AuthAssignment[] $AuthAssignments
 * @property AuthItem[] $itemNames
 * @property Ban[] $Bans
 * @property AuthItem[] $itemNames0
 * @property Department $department
 * @property BackUserLog[] $backUserLogs
 * @property Contact[] $contacts
 * @property ContactRead[] $contactReads
 * @property Service[] $customerServices
 * @property DeleteLog[] $deleteLogs
 * @property Download[] $downloads
 * @property ClientServer[] $ClientServers
 * @property Hot[] $hots
 * @property HouseHostServer[] $houseHostServers
 * @property Notice[] $notices
 * @property NoticeRead[] $noticeReads
 * @property Opinion[] $opinions
 * @property OpinionRead[] $opinionReads
 * @property Slider[] $sliders
 * @property Deal[] $takeOrders
 * @property Upload[] $uploads
 */
class BackUser extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%back_user}}';

    protected $field = [
        'id',
        'is_delete',
        'code',
        'department_id',
        'phone',
        'phone_verified',
        'email',
        'email_verified',
        'username',
        'nickname',
        'service_name',
        'real_name',
        'head_url',
        'sex',
        'signature',
        'birthday',
        'height',
        'weight',
        'password',
        'token',
        'md5',
        'auth_key',
        'password_reset_token',
        'password_reset_code',
        'status',
        'ip',
        'reg_ip',
        'reg_type',
        'registered_at',
        'logined_at',
        'updated_at',
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
    public static function getDepartmentList()
    {
        return self::T('department');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule' => [
                ['is_delete', 'number', '时效 无效'],
                ['department_id', 'number', '部门 无效'],
                ['height', 'number', '身高 无效'],
                ['weight', 'number', '体重 无效'],
                ['status', 'number', '状态 无效'],
                ['username', 'require', '用户名 不能为空'],
                ['password', 'require', '密码 不能为空'],
                ['department_id', 'require', '部门 不能为空'],
                ['sex', 'in:男,女',],
                ['code', 'max:32',],
                ['username', 'max:32',],
                ['signature', 'max:32',],
                ['auth_key', 'max:32',],
                ['reg_ip', 'max:32',],
                ['email', 'max:64',],
                ['nickname', 'max:64',],
                ['real_name', 'max:64',],
                ['head_url', 'max:64',],
                ['password', 'max:255',],
                ['token', 'max:255',],
                ['password_reset_token', 'max:255',],
                ['password_reset_code', 'max:255',],
                ['reg_type', 'max:15',],
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
            'id' => '自增ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'code' => '会员编号ID',
            'department_id' => '部门表ID',
            'phone' => '手机号码',
            'phone_verified' => '手机号码是否已验证;\"0\"表示没有验证,\"1\"表示已验证',
            'email' => '邮箱',
            'email_verified' => '邮箱是否已验证;\"0\"表示没有验证,\"1\"表示已验证',
            'username' => '用户名',
            'password' => '密码',
            'nickname' => '昵称',
            'service_name' => '服务昵称',
            'real_name' => '真实姓名',
            'head_url' => '头像地址',
            'sex' => '性别',
            'signature' => '个性签名',
            'birthday' => '生日',
            'height' => '身高/单位CM',
            'weight' => '体重/单位KG',
            'token' => '当前token',
            'md5' => '密匙',
            'auth_key' => '自动密匙',
            'password_reset_token' => '重置密匙',
            'password_reset_code' => '验证码',
            'status' => '状态;0:正常,1:异常;2:禁用;',
            'ip' => '登录IP',
            'reg_ip' => '注册IP',
            'reg_type' => '注册方式',
            'registered_at' => '注册时间',
            'logined_at' => '登录时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param null $target
     */
    public static function record($target = null)
    {
        BackUserLog::log($target);
    }

    /**
     * 设置角色
     * @param null $id
     * @param null $permission
     * @return array
     */
    public static function setRole($id = null, $permission = null)
    {
        return AuthAssignment::setRole($id, $permission);
    }

    /**
     * 设置角色
     * @param null $id
     * @param null $departmentId
     * @return array
     */
    public static function setRoleByDepartmentId($id = null, $departmentId = null)
    {
        $permissionList = self::Lang('permission');
        $permission = isset($permissionList[$departmentId]) ? $permissionList[$departmentId] : '';
        return self::setRole($id, $permission);
    }

    /**
     * 移除角色
     * @param null $id
     * @param null $permission
     * @return array
     */
    public static function removeRole($id = null, $permission = null)
    {
        return AuthAssignment::removeRole($id, $permission);
    }

    /**
     * 移除角色
     * @param null $id
     * @param null $departmentId
     * @return array
     */
    public static function removeRoleByDepartmentId($id = null, $departmentId = null)
    {
        $permissionList = self::Lang('permission');
        $permission = isset($permissionList[$departmentId]) ? $permissionList[$departmentId] : '';
        return self::removeRole($id, $permission);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(ucfirst(AuthAssignment::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return mixed
     */
    public function getItemNames()
    {
        return $this->hasMany(ucfirst(AuthItem::tableNameSuffix()), ['name' => 'item_name'])->viaTable('{{%back_auth_assignment}}', ['back_user_id' => 'id']);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBans()
    {
        return $this->hasMany(ucfirst(Ban::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return mixed
     */
    public function getItemNames0()
    {
        return $this->hasMany(AuthItem::tableNameSuffix(), ['name' => 'item_name'])->viaTable('{{%back_ban}}', ['back_user_id' => 'id']);
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getDepartment()
    {
        return $this->hasOne(ucfirst(Department::tableNameSuffix()), 'id', 'department_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBackUserLogs()
    {
        return $this->hasMany(ucfirst(BackUserLog::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getContacts()
    {
        return $this->hasMany(ucfirst(Contact::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getContactReads()
    {
        return $this->hasMany(ucfirst(ContactRead::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCustomerServices()
    {
        return $this->hasMany(ucfirst(Service::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getDeleteLogs()
    {
        return $this->hasMany(ucfirst(DeleteLog::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getDownloads()
    {
        return $this->hasMany(ucfirst(Download::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getClientServers()
    {
        return $this->hasMany(ucfirst(ClientServer::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getHots()
    {
        return $this->hasMany(ucfirst(Hot::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getHouseHostServers()
    {
        return $this->hasMany(ucfirst(HouseHostServer::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getNotices()
    {
        return $this->hasMany(ucfirst(Notice::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getNoticeReads()
    {
        return $this->hasMany(ucfirst(NoticeRead::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOpinions()
    {
        return $this->hasMany(ucfirst(Opinion::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOpinionReads()
    {
        return $this->hasMany(ucfirst(OpinionRead::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getSliders()
    {
        return $this->hasMany(ucfirst(Slider::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeOrders()
    {
        return $this->hasMany(ucfirst(DealOrder::tableNameSuffix()), 'id', 'back_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getUploads()
    {
        return $this->hasMany(ucfirst(Upload::tableNameSuffix()), 'id', 'back_user_id');
    }
}
