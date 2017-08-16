<?php

namespace app\back\model;

use app\common\model\Service as BaseCustomerService;
use app\back\validate\ServiceValidate;

use app\back\model\BackUser;

/**
 * This is the model class for table "{{%customer_service}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $level
 * @property integer $back_user_id
 * @property integer $duration
 * @property string $start_at
 * @property string $end_at
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 */
class Service extends BaseCustomerService
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ServiceValidate::load();
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
