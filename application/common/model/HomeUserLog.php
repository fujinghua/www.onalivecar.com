<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\HomeUser;
use think\Request;

/**
 * This is the model class for table "{{%home_user_log}}".
 *
 * @property integer $id
 * @property integer $home_user_id
 * @property string $route
 * @property string $url
 * @property string $user_agent
 * @property string $gets
 * @property string $posts
 * @property string $target
 * @property string $ip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property HomeUser $homeUser
 */
class HomeUserLog extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%home_user_log}}';

    protected $field = [
        'id',
        'home_user_id',
        'route',
        'url',
        'user_agent',
        'user_agent_type',
        'gets',
        'posts',
        'target',
        'ip',
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
     * @param null $target
     * @param null $userId
     * @param null $action
     * @param null $ip
     */
    public static function log($target=null, $userId = null,$action = null,$ip = null)
    {
        $model = new BackUserLog();
        $request = Request::instance();
        $model->route = !empty($action) ? $action : $request->url();
        $model->url = $request->url();
        $model->user_agent = $request->server('HTTP_USER_AGENT');
        $model->gets = json_encode($request->get());
        $data = $request->post();
        if (isset($data['password'])){
            unset($data['password']);
        }
        if (isset($data['newPassword'])){
            unset($data['newPassword']);
        }
        if (isset($data['rePassword'])){
            unset($data['rePassword']);
        }
        if (isset($data['oldPassword'])){
            unset($data['oldPassword']);
        }
        $model->posts = json_encode($data);
        $model->back_user_id = !empty($userId) ? $userId : (isset($_SESSION['identity']['id']) ? $_SESSION['identity']['id'] : '1');
        $model->ip = !empty($ip) ? $ip : $request->ip();
        $model->target = !empty($target) ? $target : '例行记录';
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();

    }

    //所有标签类型
    private static $typeList = ['1'=>'PC','2'=>'Android','3'=>'IOS'];

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return self::$typeList;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['home_user_id','number'],
                ['route','max:255'],
                ['url','max:255'],
                ['user_agent','max:255'],
                ['ip','max:255'],
            ],
            'msg'=>[
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'home_user_id' => '表user主键',
            'route' => '路由',
            'url' => '地址',
            'user_agent' => '客户端',
            'user_agent_type' => '客户类型;1=PC,2=Android,3=IOS;默认1',
            'gets' => 'GET方法',
            'posts' => 'POST方法',
            'target' => '目标',
            'ip' => 'IP',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getHomeUser()
    {
        return $this->hasOne(ucfirst(HomeUser::tableNameSuffix()), 'home_user_id','id');
    }
}
