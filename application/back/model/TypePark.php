<?php

namespace app\back\model;

use app\common\model\TypePark as BaseTypePark;
use app\back\validate\TypeParkValidate;
use app\back\model\Type;
use app\back\validate\TypeValidate;

/**
 * This is the model class for table "{{%type_park}}".
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
class TypePark extends BaseTypePark
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return TypeParkValidate::load();
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
