<?php

namespace app\back\model;

use app\common\model\NoticeRead as BaseNoticeRead;
use app\back\validate\NoticeReadValidate;
use app\back\model\BackUser;
use app\back\model\Notice;

/**
 * This is the model class for table "{{%notice_read}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $back_user_id
 * @property integer $notice_id
 * @property string $content
 * @property string $remark
 * @property string $reback
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Notice $notice
 */
class NoticeRead extends BaseNoticeRead
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return NoticeReadValidate::load();
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