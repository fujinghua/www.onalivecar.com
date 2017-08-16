<?php

namespace app\back\model;

use app\common\model\Opinion as BaseOpinion;
use app\back\validate\OpinionReadValidate;
use app\back\validate\OpinionValidate;

/**
 * This is the model class for table "{{%opinion_read}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $assign
 * @property integer $back_user_id
 * @property integer $opinion_id
 * @property string $content
 * @property string $remark
 * @property string $reback
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser $backUser
 * @property Opinion $opinion
 */
class OpinionRead extends BaseOpinion
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return OpinionReadValidate::load();
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
