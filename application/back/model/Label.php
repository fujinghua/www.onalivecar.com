<?php

namespace app\back\model;

use app\common\model\Label as BaseLabel;
use app\back\validate\LabelValidate;

use app\back\model\LabelPark;

/**
 * This is the model class for table "{{%label}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $type
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LabelPark[] $labelParks
 */
class Label extends BaseLabel
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return LabelValidate::load();
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
