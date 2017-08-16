<?php

namespace app\back\model;

use app\common\model\ContactRead as BaseContactRead;
use app\back\validate\ContactReadValidate;

use app\back\model\BackUser;
use app\back\model\Contact;

/**
 * This is the model class for table "{{%contact_read}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $assign
 * @property integer $back_user_id
 * @property integer $contact_id
 * @property string $content
 * @property string $remark
 * @property string $reback
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Contact $contact
 */
class ContactRead extends BaseContactRead
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ContactReadValidate::load();
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
