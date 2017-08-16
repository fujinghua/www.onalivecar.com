<?php

namespace app\back\model;

use app\common\model\HomeUserLog as BaseHomeUserLog;
use app\back\validate\HomeUserLogValidate;

use app\back\model\HomeUser;

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
class HomeUserLog extends BaseHomeUserLog
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return HomeUserLogValidate::load();
    }

    /**
     * @param $data
     * @param string $scene
     * @return bool
     */
    public static function check($data,$scene = ''){
        $validate = self::getValidate();

        //设定场景
        if (is_string($scene) && $scene !== ''){
            $validate->scene($scene);
        }

        return $validate->check($data);
    }
}
