<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\Contact;
use app\common\model\HomeUserLog;
use app\common\model\Opinion;

/**
 * This is the model class for table "{{%home_user}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $code
 * @property integer $type
 * @property string $phone
 * @property integer $phone_verified
 * @property string $email
 * @property integer $email_verified
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $real_name
 * @property string $ID_cards
 * @property string $province
 * @property string $city
 * @property string $county
 * @property string $weChat
 * @property string $head_url
 * @property string $sex
 * @property string $signature
 * @property string $birthday
 * @property integer $height
 * @property integer $weight
 * @property string $token
 * @property string $md5
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
 * @property Contact[] $contacts
 * @property HomeUserLog[] $homeUserLogs
 * @property HouseHostServer[] $houseHostServers
 * @property Opinion[] $opinions
 */
class HomeUser extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%home_user}}';

    protected $field = [
        'id',
        'is_delete',
        'code',
        'type',
        'phone',
        'phone_verified',
        'email',
        'email_verified',
        'username',
        'password',
        'nickname',
        'real_name',
        'ID_cards',
        'province',
        'city',
        'county',
        'weChat',
        'head_url',
        'sex',
        'signature',
        'birthday',
        'height',
        'weight',
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['is_delete','number','时效 无效'],
                ['height','number','身高 无效'],
                ['weight','number','体重 无效'],
                ['status','number','状态 无效'],
                ['sex','in:男,女',],
                ['code','max:32',],
                ['username','max:32',],
                ['signature','max:32',],
                ['auth_key','max:32',],
                ['reg_ip','max:32',],
                ['email','max:64',],
                ['nickname','max:64',],
                ['real_name','max:64',],
                ['head_url','max:64',],
                ['password','max:255',],
                ['token','max:255',],
                ['password_reset_token','max:255',],
                ['password_reset_code','max:255',],
                ['reg_type','max:15',],
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
            'id' => '自增ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'code' => '会员编号ID',
            'type' => '类型1普通用户2成交用户3贵宾用户4卖家用户',
            'phone' => '手机号码',
            'phone_verified' => '手机号码是否已验证;\"0\"表示没有验证,\"1\"表示已验证',
            'email' => '邮箱',
            'email_verified' => '邮箱是否已验证;\"0\"表示没有验证,\"1\"表示已验证',
            'username' => '用户名',
            'password' => '密码',
            'nickname' => '昵称',
            'real_name' => '真实姓名',
            'ID_cards' => '身份证',
            'province' => '省份',
            'city' => '城市',
            'county' => '区/县',
            'weChat' => '微信',
            'head_url' => '头像地址',
            'sex' => '性别',
            'signature' => '个性签名',
            'birthday' => '生日',
            'height' => '身高/单位CM',
            'weight' => '体重/单位KG',
            'token' => '当前token',
            'md5' => 'MD5',
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
     * @return \think\model\relation\HasMany
     */
    public function getContacts()
    {
        return $this->hasMany(ucfirst(Contact::tableNameSuffix()), 'id', 'home_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getHomeUserLogs()
    {
        return $this->hasMany(ucfirst(HomeUserLog::tableNameSuffix()), 'id', 'home_user_id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOpinions()
    {
        return $this->hasMany(ucfirst(Opinion::tableNameSuffix()), 'id', 'home_user_id');
    }
}
