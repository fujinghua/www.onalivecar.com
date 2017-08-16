<?php

namespace app\back\model;

use app\common\model\Opinion as BaseOpinion;
use app\back\validate\OpinionValidate;

/**
 * This is the model class for table "{{%opinion}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $remark
 * @property integer $home_user_id
 * @property integer $back_user_id
 * @property string $content
 * @property string $username
 * @property string $contact
 * @property integer $readed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property HomeUser $homeUser
 * @property OpinionRead[] $opinionReads
 */
class Opinion extends BaseOpinion
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return OpinionValidate::load();
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
