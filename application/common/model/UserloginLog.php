<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BackUser;
use \think\Request;

/**
 * This is the model class for table "{{%user_login_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $app
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
 */
class UserloginLog extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%user_login_log}}';

    protected $field = [
        'id',
        'user_id',
        'app',
        'route',
        'url',
        'user_agent',
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

    public $appList = [];

    /**
     * @param $userId
     * @param $action
     * @param $type
     * @param $ip
     * @param $target
     */
    public static function addLog($userId = null, $action = null,$type = '1',$ip = null,$target=null)
    {
        $model = new UserloginLog();
        $request = Request::instance();
        $model->route = !empty($action) ? $action : (strtolower($request->module() . '/' . $request->controller() . '/' . $request->action()));
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
        $model->user_id = !empty($userId) ? $userId : (isset($_SESSION['identity']['id']) ? $_SESSION['identity']['id'] : '1');
        $model->app = $model->user_id == '0' ? (strtolower($request->module()) == 'home' ? '2' : '1') : $type;
        $model->ip = !empty($ip) ? $ip : $request->ip();
        $model->target = !empty($target) ? $target : ($model->app == '1' ? '后台' : '前台').'登录记录';
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['user_id','number','用户 无效'],
                ['route','max:255',],
                ['url','max:255',],
                ['user_agent','max:255',],
                ['ip','max:255',],
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
            'user_id' => '表user主键',
            'app' => '账户类型:1后台账户,前端用户',
            'route' => '路由',
            'url' => '地址',
            'user_agent' => '客户端',
            'gets' => 'GET方法',
            'posts' => 'POST方法',
            'target' => '目标',
            'ip' => 'IP',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
