<?php

namespace app\back\model;

use app\common\model\HomeUser as BaseHomeUser;
use app\back\validate\HomeUserValidate;

use app\back\model\Contact;
use app\back\model\HomeUserLog;
use app\back\model\Opinion;

/**
 * This is the model class for table "{{%home_user}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $code
 * @property string $phone
 * @property integer $phone_verified
 * @property string $email
 * @property integer $email_verified
 * @property string $username
 * @property string $password
 * @property string $nickname
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
 * @property Contact[] $contacts
 * @property HomeUserLog[] $homeUserLogs
 * @property Opinion[] $opinions
 */
class HomeUser extends BaseHomeUser
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return HomeUserValidate::load();
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
