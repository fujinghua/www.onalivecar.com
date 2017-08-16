<?php
namespace app\back\model;

use app\common\model\Menu as BaseMenu;
use app\back\validate\MenuValidate;

/**
 * @description TThis is the model class for table "wf_menu".  菜单模型
 * @author Sir Fu
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property integer $order
 * @property string $type
 * @property string $data
 *
 */
class Menu extends BaseMenu
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return MenuValidate::load();
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
