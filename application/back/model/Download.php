<?php

namespace app\back\model;

use app\common\model\Download as BaseDownload;
use app\back\validate\DownloadValidate;

use app\back\model\BackUser;

/**
 * This is the model class for table "{{%download}}".
 *
 * @property integer $id
 * @property integer $back_user_id
 * @property string $title
 * @property string $url
 * @property string $fileName
 * @property string $tb_name
 * @property string $tb_id
 * @property string $tb_category
 * @property string $created_at
 *
 * @property BackUser $backUser
 */
class Download extends BaseDownload
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return DownloadValidate::load();
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
