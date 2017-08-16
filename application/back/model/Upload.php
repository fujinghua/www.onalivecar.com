<?php

namespace app\back\model;

use app\common\model\Upload as BaseUpload;
use app\back\validate\UploadValidate;
use app\back\model\BackUser;

/**
 * This is the model class for table "{{%upload}}".
 *
 * @property string $id
 * @property integer $is_delete
 * @property integer $back_user_id
 * @property string $name
 * @property string $path
 * @property string $url
 * @property string $ext
 * @property integer $size
 * @property string $md5
 * @property string $sha1
 * @property string $location
 * @property integer $download
 * @property string $create_time
 * @property string $update_time
 * @property integer $sort
 * @property integer $status
 *
 * @property BackUser $backUser
 */
class Upload extends BaseUpload
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return UploadValidate::load();
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
