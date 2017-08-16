<?php

namespace app\common\components\decode;

/**
 * Class DecodeHelper
 * @package app\common\components\decode
 */
class DecodeHelper{

    /**
     * @description 字符串转换成进制，默认是字符串转换成十六进制
     * @param $str
     * @param int $bin
     * @param bool|string $part
     * @param bool $format
     * @return array|null|string
     */
    public static function StrToBin($str, $bin = null, $part = false,$format=false)
    {
        // Binary representation of a binary-string 1.列出每个字符
        if (!is_string($str)) return null; // Sanity check
        // Unpack as a hexadecimal string 2.unpack字符
        $value = unpack('H*', $str);
        // Output binary representation
        $value = str_split($value[1], 1);
//        $arr = preg_split('/(?<!^)(?!$)/u', $str); //正则分割
        $arr = [];
        foreach ($value as $v) {
            if ($bin !== null && is_int($bin)){
                $b = str_pad(base_convert($v, 16, 2), 4, '0', STR_PAD_LEFT);
                $arr[] = base_convert($b, 2, $bin);
            }else{
                $arr[] = $v;
            }
        }
        if ($format){
            return $arr;
        }
        if ($part !== false){
            $part = is_string($part)? :' ';
            return join($part,$arr);
        }
        return join('',$arr);
    }

    /**
     * @description 进制转换成字符串，默认是二进制换成字符串
     * @param $str
     * @param int $bin
     * @param bool|string $part
     * @param bool $format
     * @return string
     */
    public static function BinToStr($str, $bin = 2, $part = false,$format=false){
        if ($part !== false){
            $part = is_string($part)? :' ';
            if (is_string($str)){
                $str = explode($part, $str);
            }
        }
        $arr = [];
        if (is_array($str)){
            foreach($str as &$v){
                $v = pack("H".strlen(base_convert($v, $bin, 16)), base_convert($v, $bin, 16));
            }
            $arr = $str;
        }else{
            $value = str_split($str, 4);
            foreach ($value as $v) {
                $s = pack("H*", base_convert($v, $bin, 16));
                $arr[] = $s;
            }
        }
        if ($format){
            return $arr;
        }
        return join('', $arr);
    }

    /**
     * @description 获取BBC 的XOR 校验值
     * @param $checkHex //十六进制的字符串
     * @param int $format // 返回的校验值，默认是十六进制值
     * @return null|number|string
     */
    public static function BCC_Check_Xor($checkHex, $format = 16){
        if (!is_string($checkHex)){
            return null;
        }
        $valueHex = str_split($checkHex,2);
        $count = count($valueHex);
        if ($count <=1){
            return null;
        }
        $hex = hexdec($valueHex[0]);
        for ($i=1;$i<$count;$i++){
            $hex ^= hexdec($valueHex[$i]);
        }
        $format = (int)$format;
        $hex = base_convert($hex, 10, $format);
        return $hex;
    }

    /**
     * @description 获取真实的SIM
     * @param $sim
     * @return null|string
     */
    public static function getTrueSim($sim){
        if (!is_numeric($sim)){
            return null;
        }
        return '1'.$sim;
    }

    /**
     * @description 获取车辆，通过SIM
     * @param $sim
     * @return null|string
     */
    public static function getCarBySim($sim){
        if (!is_numeric($sim)){
            return null;
        }
        return '1'.$sim;
    }

}
