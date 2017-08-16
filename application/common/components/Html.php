<?php

namespace app\common\components;

/**
 * Description of Html
 */
class Html
{
    private static $_instance;

    /**
     * @return \app\common\components\Html
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    private function __construct()
    {

    }

    /**
     * 抛出异常
     * @param null $statusCode
     * @param null $message
     * @param array $headers
     * @param null $code
     */
    public static function HttpException($statusCode = null, $message = null, array $headers = [], $code = null)
    {
        if (empty($statusCode)) {
            $statusCode = '404';
        }
        if (empty($code)) {
            $code = $statusCode;
        }
        if (empty($message)) {
            $message = '请求不存在';
        }
        if (empty($headers)) {
            $headers = ['code' => $code, 'msg' => $message, 'info' => $message];
        }
        throw new \think\Exception\HttpException($statusCode, $message, null, $headers, $code);
    }

    /**
     * @param array |\think\Model |mixed $resultSet
     * @return array
     */
    public static function toArray($resultSet)
    {
        $ret = [];
        if (empty($resultSet) || !(is_array($resultSet) || is_object($resultSet))) {
            return $ret;
        }
        $item = current($resultSet);
        if ($resultSet instanceof \think\Model) {
            $ret = $resultSet->toArray();
        } elseif ($item instanceof \think\Model) {
            foreach ($resultSet as $value) {
                $ret[] = $value->toArray();
            }
        } else {
            $ret = (array)$resultSet;
        }
        return $ret;
    }

    /**
     * @param \app\common\model\Model $model
     * @return string
     */
    public static function render(\app\common\model\Model $model)
    {
        $labels = $model->attributeLabels();
        $header = self::getHeader();
        $body = self::getBody($labels);
        $html = <<<HTML
<!DOCTYPE html>
<html>
$header
$body
</html>
HTML;

        return $html;
    }

    /**
     * @return string
     */
    protected static function getHeader()
    {
        $header = <<<HEADER
<head>
    <meta charset="utf-8">

    <title>{\$meta_title ? \$meta_title.' - ' : ''}_TITLE_</title>

    <style>
        .layui-form-item{
            margin-bottom: 24px;
        }
        .layui-form-item .layui-input-block.right-width{
            padding-right: 120px;
        }
    </style>

</head>
HEADER;

        return $header;
    }

    /**
     * @return string
     */
    protected static function getBody($labels = [])
    {
        $input = '';
        foreach ($labels as $name => $label) {
            $input .= <<<ITEM
            
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="layui-color-danger"></span>$label:</label>
            <div class="layui-input-block right-width">
                <input type="text" value="" name="$name" lay-verify="" placeholder="$label" class="layui-input">
            </div>
        </div>

ITEM;

        }
        $js = self::getJs();
        $body = <<<BODY
<body>

<section style="max-width:1200px;margin: 0 auto 30px;position: relative;">

    <form class="layui-form forms" id="defaultForm" action="" method="post" style="padding: 15px 0">

        $input

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"></label>
                <button class="layui-btn" lay-submit="" lay-filter="submit">添加</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>

    </form>

</section>

$js

</body>
BODY;
        return $body;
    }

    /**
     * @return string
     */
    protected static function getJs()
    {
        $js = <<<JS
<script src="__JS__/site.js"></script>
<script src="__JS__/back.js"></script>
<script>

    layui.use(['form'],function () {
        var form = layui.form();

    });

    $(function () {
        var options = {
            form:'#defaultForm',
            success:console.log
        };
        Back.submit(options);
    });


</script>
JS;

        return $js;
    }


}