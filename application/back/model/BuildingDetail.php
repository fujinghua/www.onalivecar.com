<?php

namespace app\back\model;

use app\common\model\BuildingDetail as BaseBuildingDetail;
use app\back\validate\BuildingDetailValidate;

use app\back\model\BuildingBase;

/**
 * This is the model class for table "{{%building_detail}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property integer $building_base_id
 * @property string $description
 * @property string $address
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BuildingBase $buildingBase
 */
class BuildingDetail extends BaseBuildingDetail
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BuildingDetailValidate::load();
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