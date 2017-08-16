<?php

namespace  app\common\components\region;

use app\common\components\region\Region;
use think\cache;

/**
 * RegionHelper used to get all Region or permission Region depend of user role.
 * Usage
 *
 * ~~~
 * use backend\components\region\RegionHelper;
 *
 * $regions = RegionHelper::getAssignedRegion(Yii::$app->user->id, null, $callback);
 * ~~~
 */
class RegionHelper
{
    const CACHE_TAG = 'COMMON_REGION';

    /**
     * @var integer Cache duration. Default to a hour.
     */
    public static $cacheDuration = 3600;

    /**
     * @var \think\Cache;
     */
    public static $cache;

    public static $regions;

    public static $regionExtras;

    /**
     * get all region
     * @return array|mixed
     */
    public static function getRegions(){
        if (!empty(self::$regions)){
            return self::$regions;
        }
        return self::$regions = RegionData::$region;
    }

    /**
     * @return Cache
     */
    public static function getCache(){
        if (!empty(self::$cache)){
            return self::$cache;
        }
        return self::$cache = Cache::getInstance();
    }

    /**
     * get all the repeat regions,alias as regionExtras
     * @return array|mixed
     */
    public static function getRegionExtras(){
        if (!empty(self::$regionExtras)){
            return self::$regionExtras;
        }
        return self::$regionExtras = RegionData::$regionExtras;
    }

    /**
     * Use to get all Region of user.
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * @param boolean  $refresh
     * @return array
     */
    public static function getAllRegion($userId, $root = null, $callback = null, $refresh = false)
    {
        $cache = self::getCache();

        $key = [self::CACHE_TAG,__METHOD__, $userId, $root];
        $key = implode('_',$key);

        if ($refresh || $callback !== null || (($regions = $cache::get($key)) === false)) {
            $regions = self::getRegions();
            if ($cache !== null && $callback === null) {
                $cache::set($key, $regions, self::$cacheDuration);
            }
        }
        return $regions;
    }

    /**
     * Use to get assigned Region of user.
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * callback should have format like
     *
     * ~~~
     * function () {
     *    return [
     *    ]
     * }
     * ~~~
     * @param boolean  $refresh
     * @return array
     */
    public static function getAssignedRegion($userId, $root = null, $callback = null, $refresh = false)
    {
        $cache = self::getCache();

        $key = [self::CACHE_TAG,__METHOD__, $userId, $root];
        $key = implode('_',$key);

        if ( $refresh || $callback !== null || (($assignedRegions = $cache::get($key)) === false)) {
            $regions = self::getAllRegion($userId, $root, $callback, $refresh);
            $user = self::getUserByUserId($userId);
            $regionId = $user -> region_id ; // 此数据是当前用户传递过来$userId的regionId
            $assignedRegions = $regionId != 1 ? static::normalizeRegion($regionId, $regions) : $regions ;
            if ($cache !== null && $callback === null) {
                $cache->set($key, $assignedRegions, self::$cacheDuration);
            }
        }
        return $assignedRegions;
    }

    /**
     * @param $value
     * @param $array
     * @return bool
     */
    public static function deep_in_array($value, $array) {
        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if(in_array($value, $item)) {
                return true;
            } else if(self::deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Normalize Region
     * @param $regionId
     * @param $regions
     * @return array
     */
    public static function normalizeRegion($regionId, $regions)
    {
        $active = [];
        $links = self::getLinks($regionId);
        $num = count($links);
        switch ($num){
            case 1; { $active = $regions[$links[0]]; } break;
            case 2; { $active = $regions[$links[0]]['cityLists'][$links[1]]; } break;
            case 3; { $active = $regions[$links[0]]['cityLists'][$links[1]]['areaLists'][$links[2]]; } break;
            default ; { $active = []; } break;
        }
        return $active;
    }

    /**
     * get the $regionId location unique region links.
     * @param $regionId
     * @return array ,it can link to a unique region
     */
    public static function getLinks($regionId){
        $order[] = $regionId;
        $continue = true;
        do{
            if ($model = self::getRegionById($regionId)){
                if ($model -> parent == 0 || $model -> parent == 1){
                   break;
                }else{
                    $order[] = $model -> parent;
                    $regionId = $model -> parent;
                }
            }else{
                break;
            }
        }while($continue);

        asort($order);
        $data = [];
        foreach ($order as $value){
            $data[] = $value;
        }
        $order = $data;
        return $order;
    }

    /**
     * it can transfer the $assignRegions to the $assignRegionId
     * @param $assignRegions
     * @return array ,it has the $assignRegions id ,alias as $assignRegionId.
     */
    public static function getAssignRegionChildId($assignRegions){
        $assignRegionId = [];

        if (empty($assignRegions)){
            return $assignRegionId;
        }

        if (isset($assignRegions['id'])){
            $assignRegionId[] = $assignRegions['id'];
        }

        if (isset($assignRegions['cityLists'])){
            foreach ($assignRegions['cityLists'] as $regionCityItem){
                $assignRegionId[] = $regionCityItem['id'];
                if (isset($regionCityItem['areaLists'])){
                    foreach ($regionCityItem['areaLists'] as $regionAreaItem){
                        $assignRegionId[] = $regionAreaItem['id'];
                    }
                }
            }
        }

        if (isset($assignRegions['areaLists'])){
            $assignRegionId[] = $assignRegions['id'];
            foreach ($assignRegions['areaLists'] as $regionAreaItem){
                $assignRegionId[] = $regionAreaItem['id'];
            }
        }
        return $assignRegionId;
    }

    /**
     * it can the $assignRegionId by user id
     * @param $userId
     * @param $root
     * @param $refresh
     * @return array ,it has the $assignRegions id ,alias as $assignRegionId.
     */
    public static function getAssignRegionChildIdByUserId($userId, $root = null, $refresh = false){
        $assignRegionId = [];

        $cache = self::getCache();
        $key = [self::CACHE_TAG,__METHOD__, $userId, $root];
        $key = implode('_',$key);

        if ( $refresh || (($assignRegionId = $cache::get($key)) === false)) {
            $assignRegions = self::getAssignedRegion($userId);
            $assignRegionId = self::getAssignRegionChildId($assignRegions);
            if ($cache !== null) {
                $cache::set($key, $assignRegionId, self::$cacheDuration);
            }
        }
        return $assignRegionId;
    }


    /**
     * @param $userId
     * @param $userIdChecked , it is come from The user who create a waiting Checks in the Check Table
     * @param bool $constraint ,if true,it means the $userId manage the $userIdChecked forcibly,or not
     * @return bool
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function hasPermission($userId, $userIdChecked, $constraint = false)
    {
        if ( $userId == $userIdChecked ){
            return true;
        }
        $role = Yii::$app->user->identity->role;
        if ( $role == 8 ){
            return true;
        }
        if (empty(self::$config)){
            self::$config = RegionConfigs::instance();
        }
        $config = self::$config;
        $key = [__METHOD__, $userId, null];
        $cache = self::$cache;

        if ( ($assignedRegions = $cache->get($key)) === false ) {
            $assignedRegions = self::getAssignedRegion($userId);
            $cache->set($key, $assignedRegions, self::$cacheDuration, new TagDependency([
                'tags' => RegionConfigs::CACHE_TAG
            ]));
        }
        if (empty($assignedRegions)){
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', '你没有该权限,请联系管理员!'));
        }

        $checkedUser = self::getUserByUserId($userIdChecked);
        if ( $role == 9 && $checkedUser -> role == $role){
            if ($constraint){
                return false;
            }
            return true;
        }
        if ( self::deep_in_array($checkedUser -> region_name ,$assignedRegions) ){
            $checkerUser = self::getUserByUserId($userId);
            $super = false;
            if ($checkerUser -> region_name == $checkedUser -> region_name){
                if ( $role == 18 || $role == 28){
                    $super = true;
                }
                if ($constraint && ($role == 19)){
                    $super = true;
                }
            }else{
                $super = true;
            }
            return $super;
        }else{
            return false;
        }
    }

    /**
     * Finds the RegionId model by userId.
     * If the model is not found, a 403 HTTP exception will be thrown.
     * @param $userId
     * @return null|static
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function getUserByUserId($userId)
    {
        if ( ($checkUser = \backend\models\Admin::findOne(['id' => $userId])) !== null) {
            return $checkUser;
        } else {
            return null;
        }

    }

    /**
     * Finds the Region model based on its unique region name.
     * If the model is not found, a 403 HTTP exception will be thrown.
     * @param $uniqueRegionName
     * @param $throw
     * @return null|static | \backend\models\Region;
     * @throws \yii\web\HttpException
     */
    public static function getRegionByUniqueName($uniqueRegionName,$throw = true)
    {
        if ( ($model = Region::findOne(['name' => $uniqueRegionName])) !== null) {
            return $model;
        } else {
            if ($throw){
                throw new \yii\web\HttpException('500','The server is busy. Please try again later.');
            }else{
                return null;
            }
        }

    }

    /**
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 500 HTTP exception will be thrown.
     * @param $id
     * @return null|static|\backend\models\Region;
     * @throws \yii\web\HttpException
     */
    protected static function getRegionById($id)
    {
        if ( ( $model = Region::findOne(['id'=>$id]) ) !== null) {
            return $model;
        } else {
            throw new \yii\web\HttpException('500','The server is busy. Please try again later.');
        }
    }

    /**
     * Finds the Region model based on its name.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $fullRegion
     * @return null|int ;if not find a region ID ，will return 0；
     * @throws \yii\web\HttpException
     */
    public static function getRegionIdByFullName($fullRegion = '')
    {
        if (!empty($fullRegion)){
            $fullRegion = trim($fullRegion);
            if ($fullRegion == '全国' || $fullRegion == '中国'){
                return 1;
            }
            $regions = explode(' ',$fullRegion);
            $index = count($regions);
            $regionExtras = RegionConfigs::getRegionExtras();

            switch ($index){
                case 1; {
                    $model = self::getRegionByUniqueName($regions[0],false);
                    if (!$model){
                        return 0;
                    }
                    return $model -> id;
                } break;
                case 2; {
                    $hasOne = self::deep_in_array($regions[1], $regionExtras);
                    if (!$hasOne){
                        $model = self::getRegionByUniqueName($regions[1],false);
                        if (!$model){
                            return 0;
                        }
                        return $model -> id;
                    }else{
                        if ( ( $model = Region::find()->where(['name'=>$regions[1]])->asArray()->all() ) !== null) {
                            foreach ($model as $value){
                                if ($value['data'] == $regions[0]){
                                    return $value['id'];
                                }
                            }
                            return 0;
                        } else {
                            return 0;
                        }
                    }
                } break;
                case 3; {
                    $hasOne = self::deep_in_array($regions[2], $regionExtras);
                    if (!$hasOne){
                        $model = self::getRegionByUniqueName($regions[2],false);
                        if (!$model){
                            return 0;
                        }
                        return $model -> id;
                    }else{
                        if ( ( $model = Region::find()->where(['name'=>$regions[2]])->asArray()->all() ) !== null) {
                            foreach ($model as $value){
                                if ($regions[1] == '市' || $regions[1] == '市辖区' ||  $regions[1] == '县'){
                                    if ($value['data'] == $regions[0]){
                                        return $value['id'];
                                    }
                                }else{
                                    if ($value['data'] == $regions[1]){
                                        return $value['id'];
                                    }
                                }
                            }
                            return 0;
                        } else {
                            return 0;
                        }
                    }
                } break;
                default; { return 0; } break;
            }
        }
        return 0;
    }

}
