<?php

namespace app\back\model;

use app\common\model\LabelPark as BaseLabelPark;
use app\back\validate\LabelParkValidate;
use app\back\model\Label;

/**
 * This is the model class for table "{{%label_park}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $label_id
 * @property integer $target_id
 * @property integer $type
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Label $label
 */
class LabelPark extends BaseLabelPark
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return LabelParkValidate::load();
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
