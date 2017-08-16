<?php

namespace app\common\taglib;

use think\template\TagLib;

/**
 * Class W
 * @package app\common\taglib
 */
class T extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'close' => ['attr' => 'time,format', 'close' => 0], //闭合标签，默认为不闭合
        'empty' => ['attr' => 'name,empty', 'close' => 0], //闭合标签，默认为不闭合
        'open' => ['attr' => 'name,type', 'close' => 1],
        'lang' => ['attr' => 'name,type,key', 'close' => 0], //闭合标签，默认为不闭合

    ];

    /**
     * 这是一个闭合标签的简单演示
     * @param $tag
     * @return string
     */
    public function tagClose($tag)
    {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time = empty($tag['time']) ? time() : $tag['time'];
        $parse = '<?php ';
        $parse .= 'echo date("' . $format . '",' . $time . ');';
        $parse .= ' ?>';
        return $parse;
    }

    /**
     * 这是一个非闭合标签的简单演示
     */
    public function tagOpen($tag, $content)
    {
        $type = empty($tag['type']) ? 0 : 1; // 这个type目的是为了区分类型，一般来源是数据库
        $name = $tag['name']; // name是必填项，这里不做判断了
        $parse = '<?php ';
        $parse .= '$test_arr=[[1,3,5,7,9],[2,4,6,8,10]];'; // 这里是模拟数据
        $parse .= '$__LIST__ = $test_arr[' . $type . '];';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    public function tagEmpty($tag)
    {
        $empty = empty($tag['empty']) ? '' : $tag['empty'];
        $parse = $empty;
        if (!empty($tag['name'])) {
            $parse = "<?php if(!empty(" . $tag['name'] . ")) { echo " . $tag['name'] . ";}else{ echo '" . $empty . "';} ?>";
        }
        return $parse;
    }

    /**
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagLang($tag, $content)
    {
        $class = $tag['name']; // name是必填项，这里不做判断了
        $type = $tag['type']; // type是必填项，这里不做判断了
        $key = $tag['key']; // type是必填项，这里不做判断了
        $namespace = '\\app\\common\\model\\';
        $class = $namespace.$class;
        $ret = '';
        if (class_exists($class)){
            $model = new $class();
            $type = $type.'List';
            if(property_exists($model,$type)){
                $data = $model->$type;
                if (isset($data[$key])){
                    $ret = $data[$key];
                }else if (isset($data[0])){
                    $ret = $data[0];
                }
            }
        }
        $parse = '<?php echo "'.$ret.'";  ?>';
        return $parse;
    }

}