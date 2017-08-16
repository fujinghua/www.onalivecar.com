<?php

namespace app\common\components\decode;


/**
 * Class SocketSwitch
 * @package app\common\components\decode
 */
class SocketSwitch{

    private static $_switch = true; //控制Socket是否循环下去的条件，方便手动关闭循环

    /**
     * @return bool
     */
    public static function getSwitch(){
        return self::$_switch;
    }

}
