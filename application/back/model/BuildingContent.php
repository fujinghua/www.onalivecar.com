<?php

namespace app\back\model;

use app\common\model\BuildingContent as BaseBuildingContent;
use app\back\validate\BuildingContentValidate;

use app\back\model\City;
use app\back\model\BuildingDetail;
use app\back\model\HouseBetter;
use app\back\model\House;

/**
 * This is the model class for table "{{%building_content}}".
 *
 * @property integer $building_base_id
 * @property string $content
 *
 * @property BuildingBase $buildingBase
 * @property BuildingDetail $buildingDetail
 */
class BuildingContent extends BaseBuildingContent
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BuildingContentValidate::load();
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
