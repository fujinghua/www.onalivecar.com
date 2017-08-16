<?php

namespace app\back\model;

use app\common\model\Notice as BaseNotice;
use app\back\validate\NoticeValidate;
use app\back\model\BackUser;
use app\back\model\NoticeRead;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $back_user_id
 * @property string $title
 * @property string $content
 * @property integer $is_passed
 * @property integer $order
 * @property string $remark
 * @property integer $readed
 * @property string $created_at
 * @property string $updated_at
 * @property string $start_at
 * @property string $end_at
 *
 * @property BackUser $backUser
 * @property NoticeRead[] $noticeReads
 */
class Notice extends BaseNotice
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return NoticeValidate::load();
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
