<?php

namespace app\back\model;

use app\common\model\Config as BaseConfig;
use app\back\validate\ConfigValidate;


/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $app
 * @property string $title
 * @property string $name
 * @property string $value
 * @property integer $group
 * @property string $type
 * @property string $options
 * @property string $tip
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order
 * @property integer $status
 */
class Config extends BaseConfig
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ConfigValidate::load();
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
