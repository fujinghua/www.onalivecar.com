<?php

namespace app\back\model;

use app\common\model\Deal as BaseDeal;
use app\back\validate\DealValidate;
use app\back\model\BackUser;
use app\back\model\Client;

/**
 * This is the model class for table "{{%take_order}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $back_user_id
 * @property integer $client_id
 * @property string $order_code
 * @property integer $house_type
 * @property integer $goods_id
 * @property integer $deal_status
 * @property string $money
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Client $client
 */
class Deal extends BaseDeal
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return DealValidate::load();
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
