<?php

namespace app\back\model;

use app\common\model\Department as BaseDepartment;
use app\back\validate\DepartmentValidate;

use app\back\model\BackUser;


/**
 * This is the model class for table "{{%department}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $remark
 * @property string $name
 * @property integer $parent
 * @property string $code
 * @property integer $order
 * @property integer $level
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackUser[] $backUsers
 */
class Department extends BaseDepartment
{

    /**
     * @description 获取全部部门
     * @param $key
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public static function getAllDepartment($key = 'department')
    {
        $key = $key === 'department' ? :'department';
        $key = __METHOD__.'_'.$key;
        $res = Department::load()->cache(md5($key),1800)->select();
        return $res;
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return DepartmentValidate::load();
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