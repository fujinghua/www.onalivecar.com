<?php

namespace app\back\model;

use app\common\model\DeleteLog as BaseDeleteLog;
use app\back\validate\DeleteLogValidate;

use app\back\model\BackUser;

/**
 * This is the model class for table "{{%delete_log}}".
 *
 * @property integer $id
 * @property integer $table_type
 * @property integer $back_user_id
 * @property integer $delete_id
 * @property string $remark
 * @property string $created_at
 *
 * @property BackUser $backUser
 */
class DeleteLog extends BaseDeleteLog
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return DeleteLogValidate::load();
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
