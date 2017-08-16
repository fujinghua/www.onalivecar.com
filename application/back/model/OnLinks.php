<?php

namespace app\back\model;

use app\common\model\OnLinks as BaseOnLinks;
use app\back\validate\OnLinksValidate;

/**
 * This is the model class for table "{{%on_links}}".
 *
 * @property string $id
 * @property integer $is_delete
 * @property integer $app
 * @property string $title
 * @property string $logo
 * @property string $url
 * @property integer $type
 * @property string $create_time
 * @property string $update_time
 * @property string $sort
 * @property integer $status
 */
class OnLinks extends BaseOnLinks
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return OnLinksValidate::load();
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
