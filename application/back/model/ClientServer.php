<?php

namespace app\back\model;

use app\common\model\ClientServer as BaseClientServer;
use app\back\validate\ClientServerValidate;

use app\back\model\BackUser;
use app\back\model\Client;

/**
 * This is the model class for table "{{%client_server}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $client_id
 * @property integer $back_user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Client $client
 */
class ClientServer extends BaseClientServer
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ClientServerValidate::load();
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
