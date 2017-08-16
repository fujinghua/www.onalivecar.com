<?php

namespace app\common\components\decode;

/**
 * Interface IDecipher
 * @package app\common\components\decode
 */
interface IDecipher
{
    /**
     * @description 获取数据
     * @param string $str
     * @param int $sum
     * @return array
     */
    public function getData($str,$sum = 0);
}