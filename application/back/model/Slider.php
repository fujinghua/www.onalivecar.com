<?php

namespace app\back\model;

use app\common\model\Slider as BaseSlider;
use app\back\validate\SliderValidate;
use app\back\model\BackUser;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $is_passed
 * @property integer $back_user_id
 * @property integer $type
 * @property string $url
 * @property string $url_icon
 * @property string $target
 * @property string $title
 * @property string $start_at
 * @property string $end_at
 * @property integer $order
 * @property integer $app
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 */
class Slider extends BaseSlider
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return SliderValidate::load();
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
