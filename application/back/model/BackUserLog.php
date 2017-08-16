<?php

namespace app\back\model;

use app\common\model\BackUserLog as BaseBackUserLog;
use app\back\validate\BackUserLogValidate;

use app\back\model\BackUser;

/**
 * This is the model class for table "{{%back_user_log}}".
 *
 * @property integer $id
 * @property integer $back_user_id
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
 * @property BackUser $backUser
 */
class BackUserLog extends BaseBackUserLog
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BackUserLogValidate::load();
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
