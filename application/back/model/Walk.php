<?php

namespace app\back\model;

use app\common\model\Walk as BaseWalk;
use app\back\validate\WalkValidate;
use app\back\model\Client;

/**
 * This is the model class for table "{{%walk}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $client_id
 * @property integer $on_server
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Client $client
 */
class Walk extends BaseWalk
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return WalkValidate::load();
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
