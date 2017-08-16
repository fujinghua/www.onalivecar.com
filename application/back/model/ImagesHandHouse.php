<?php

namespace app\back\model;

use app\common\model\Images;

use app\back\validate\ImagesValidate;

/**
 * This is the model class for table "{{%images_hand_house}}".
 *
 */
class ImagesHandHouse extends Images
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ImagesValidate::load();
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
