<?php

namespace app\back\model;

use app\common\model\HandHouse as BaseSecondHandHouse;
use app\back\validate\HandHouseValidate;
use app\back\model\HouseHost;
use app\back\model\City;

/**
 * This is the model class for table "{{%second_hand_house}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $house_host_id
 * @property integer $type
 * @property integer $city_id
 * @property string $address
 * @property string $room
 * @property string $description
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 *
 * @property HouseHost $houseHost
 * @property City $city
 */
class HandHouse extends BaseSecondHandHouse
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return HandHouseValidate::load();
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
