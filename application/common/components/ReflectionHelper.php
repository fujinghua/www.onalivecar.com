<?php

namespace app\common\components;

/**
 * @description 反射类
 * Class ReflectionHelper
 * @package app\common\components
 */
class ReflectionHelper
{

    /**
     * @return \Reflection
     */
    public static function getReflection(){
        return new \Reflection();
    }

    /**
     * @param $argument
     * @return \ReflectionClass
     */
    public static function getReflectionClass($argument){
        return new \ReflectionClass($argument);
    }

    /**
     * @return \ReflectionException
     */
    public static function getReflectionException(){
        return new \ReflectionException();
    }

    /**
     * @param $name
     * @return \ReflectionFunction
     */
    public static function getReflectionFunction($name){
        return new \ReflectionFunction($name);
    }

    /**
     * @param $function
     * @param $parameter
     * @return \ReflectionParameter
     */
    public static function getReflectionParameter($function, $parameter){
        return new \ReflectionParameter($function, $parameter);
    }

    /**
     * @param $class
     * @param $name
     * @return \ReflectionMethod | null
     */
    public static function getReflectionMethod($class, $name){
        if (!method_exists($class,$name)){
            return null;
        }
        return new \ReflectionMethod ($class, $name);
    }

    /**
     * @param $argument
     * @return \ReflectionObject
     */
    public static function getReflectionObject($argument){
        return new \ReflectionObject($argument);
    }

    /**
     * @param $class
     * @param $name
     * @return \ReflectionProperty
     */
    public static function getReflectionProperty($class, $name){
        return new \ReflectionProperty($class, $name);
    }

    /**
     * @param $name
     * @return \ReflectionExtension
     */
    public static function getReflectionExtension($name){
        return new \ReflectionExtension($name);
    }

}