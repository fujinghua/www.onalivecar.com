<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"F:\aliveDinner\upupw\www\atAliveCar\public/../application/back\view\house\create.html";i:1502897792;s:92:"F:\aliveDinner\upupw\www\atAliveCar\public/../application/common\view\layouts\back-main.html";i:1502125153;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="url" content="">
    <meta name="_csrf_param" content="<?php echo !empty($_csrf_param)?$_csrf_param : ''; ?>">
    <meta name="_csrf_token" content="<?php echo !empty($_csrf_token)?$_csrf_token : ''; ?>">
    <meta name="cachedKey" content="<?php echo !empty($_SESSION['_auth_token_'])?$_SESSION['_auth_token_'] : ''; ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="_SHORTCUT_" type="image/x-icon">

    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="_LAYUI_/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/layout.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/back.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/iconfont.css" media="all">

    <!-- For site js -->
    <script type="text/javascript" src="__JS__/jquery.js"></script>
    <script type="text/javascript" src="_LAYUI_/layui.js"></script>

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>

</head>
<body>

<div class="wrap">

    <div class="container"  id="container">

        <div class="layui-layout layui-layout-back-main" id="layui_layout" style="padding:0 15px 15px 15px;">

            <!-- 右侧主体内容开启 -->
            <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>

</head>

<body>

<section style="max-width:1200px;margin: 0 auto 30px;position: relative;">

    <form class="layui-form forms" id="defaultForm" action="" method="post" style="padding: 15px 0">

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="layui-color-danger">*</span>房源标题：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <input type="text" value="" name="title" lay-verify="require" placeholder="请输入房源标题" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>所属区域：</label>
                <div class="layui-input-inline">
                    <select name="city_id" lay-verify="require" lay-search >
                        <option value="">选择城市</option>
                        <?php if(is_array($cityLists) || $cityLists instanceof \think\Collection || $cityLists instanceof \think\Paginator): $i = 0; $__LIST__ = $cityLists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="county_id" lay-search="" id="county" >
                        <option value="">选择区/县</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="layui-color-danger">*</span>房源地址：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <input type="text" value="" name="address" lay-verify="require" placeholder="请输入房源地址" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>房源房型：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="room" lay-verify="require" lay-search >
                        <option value="">选择居室</option>
                        <?php if(is_array($lang['room']) || $lang['room'] instanceof \think\Collection || $lang['room'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['room'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?> 室</option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="hall" lay-verify="require" lay-search >
                        <option value="">选择大厅</option>
                        <?php if(is_array($lang['hall']) || $lang['hall'] instanceof \think\Collection || $lang['hall'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['hall'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?> 厅</option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="kitchen" lay-verify="require" lay-search >
                        <option value="">选择厨房</option>
                        <?php if(is_array($lang['kitchen']) || $lang['kitchen'] instanceof \think\Collection || $lang['kitchen'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['kitchen'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?> 厨</option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="toilet" lay-verify="require" lay-search >
                        <option value="">选择独卫</option>
                        <?php if(is_array($lang['toilet']) || $lang['toilet'] instanceof \think\Collection || $lang['toilet'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['toilet'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?> 卫</option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="veranda" lay-verify="require" lay-search >
                        <option value="">选择阳台</option>
                        <?php if(is_array($lang['veranda']) || $lang['veranda'] instanceof \think\Collection || $lang['veranda'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['veranda'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?> 台</option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>楼层信息：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="floorsType" lay-verify="require" lay-search >
                        <option value="">楼层类型</option>
                        <?php if(is_array($lang['floorsType']) || $lang['floorsType'] instanceof \think\Collection || $lang['floorsType'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['floorsType'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <label class="layui-form-label" style="width: 110px;">所在楼层：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <input type="text" value="" name="onFloor" lay-verify="require" placeholder="所在楼层" class="layui-input">
                </div>
                <label class="layui-form-label" style="width: 110px;">楼层总数：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <input type="text" value="" name="floors" lay-verify="require" placeholder="楼层总数" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>房屋信息：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="face" lay-verify="require" lay-search >
                        <option value="">房屋朝向</option>
                        <?php if(is_array($lang['face']) || $lang['face'] instanceof \think\Collection || $lang['face'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['face'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <label class="layui-form-label" style="width: 110px;">房屋类型：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="houseType" lay-verify="require" lay-search >
                        <option value="">房屋类型</option>
                        <?php if(is_array($lang['houseType']) || $lang['houseType'] instanceof \think\Collection || $lang['houseType'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['houseType'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <label class="layui-form-label" style="width: 110px;">装修情况：</label>
                <div class="layui-input-inline" style="width: 130px;">
                    <select name="fitment" lay-verify="require" lay-search >
                        <option value="">装修情况</option>
                        <?php if(is_array($lang['fitment']) || $lang['fitment'] instanceof \think\Collection || $lang['fitment'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['fitment'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>房源类型：</label>
                <div class="layui-input-block checkbox-left checkbox-primary" style="padding-right: 120px;" lay-error="请至少选择一个房源类型">
                    <?php if(is_array($lang['houseType']) || $lang['houseType'] instanceof \think\Collection || $lang['houseType'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['houseType'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <input type="radio" name="houseType[]" value="<?php echo $key; ?>" title="<?php echo $item; ?>" lay-verify="checked" lay-group="houseType" />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>房屋单价：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="eachPrice" lay-verify="require" placeholder="房屋单价" class="layui-input">
                </div>
                <label class="layui-text layui-label">元/平方米</label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>房屋总价：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="price" lay-verify="require" placeholder="房屋总价" class="layui-input">
                </div>
                <label class="layui-text layui-label">万元</label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">建筑年代：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="years" placeholder="建筑年代" class="layui-input">
                </div>
                <label class="layui-text layui-label">年</label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">建筑面积：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="area" placeholder="建筑面积" class="layui-input">
                </div>
                <label class="layui-text layui-label">平方米</label>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label"><span class="layui-color-danger">*</span>房源描述：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <textarea  name="description" placeholder="请输入房源描述" lay-verify="require" class="layui-textarea" lay-height="auto"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="layui-color-danger">*</span>房源封面：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <input type="file" id="house_url_file" name="file" placeholder="房源封面" class="layui-upload-file">
                <input type="hidden" value="" name="url" id="url" lay-verify="require" lay-error="房源封面不能为空" placeholder="房源封面" class="layui-input">
                <div lay-filter="show-url" style="display:none;border: 1px solid #e6e6e6;border-radius: 2px;max-width: 700px;"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="layui-color-danger">*</span>房源图片：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <input type="file" id="house_detail_file" name="file[]" multiple placeholder="房源图片" class="layui-upload-file">
                <input type="hidden" value="" name="detail" id="detail" class="layui-input">
                <div lay-filter="show-detail" style="display:none;border: 1px solid #e6e6e6;border-radius: 2px;max-width: 700px;"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">基础设施：</label>
            <div class="layui-input-block" lay-error="请至少选择一个基础设施" style="padding-right: 120px;" >
                <?php if(is_array($lang['supporting']) || $lang['supporting'] instanceof \think\Collection || $lang['supporting'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['supporting'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <input type="checkbox" name="supporting[]" value="<?php echo $key; ?>" title="<?php echo $item; ?>" lay-verify="checked" lay-group="supporting" lay-skin="primary" />
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">推荐理由：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <textarea placeholder="请输入推荐理由" name="reason" class="layui-textarea" lay-height="auto"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">交通状况：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <textarea placeholder="请输入交通状况" name="traffic" class="layui-textarea" lay-height="auto"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">周边配套：</label>
            <div class="layui-input-block" style="padding-right: 120px;">
                <textarea placeholder="请输入周边配套" name="around" class="layui-textarea" lay-height="auto"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">房源标签：</label>
            <div class="layui-input-block" lay-error="请至少选择一个房源标签" style="padding-right: 120px;" >
                <?php if(is_array($lang['houseLabel']) || $lang['houseLabel'] instanceof \think\Collection || $lang['houseLabel'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['houseLabel'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <input type="checkbox" name="houseLabel[]" value="<?php echo $key; ?>" title="<?php echo $item; ?>" lay-verify="checked" lay-group="houseLabel" lay-skin="primary" />
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>销售状态：</label>
                <div class="layui-input-block checkbox-left checkbox-primary" style="padding-right: 120px;" lay-error="请至少选择一个销售状态">
                    <?php if(is_array($lang['saleStatus']) || $lang['saleStatus'] instanceof \think\Collection || $lang['saleStatus'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['saleStatus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <input type="radio" name="saleStatus[]" value="<?php echo $key; ?>" title="<?php echo $item; ?>" lay-verify="checked" lay-group="saleStatus" />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">联系人：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="Contact" lay-verify="require" placeholder="联系人" class="layui-input">
                </div>
                <label class="layui-text layui-label"></label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">联系方式：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="contacterTel" lay-verify="require" placeholder="手机/固话" class="layui-input">
                </div>
                <label class="layui-text layui-label"></label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">Email：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="email" lay-verify="require" placeholder="邮箱" class="layui-input">
                </div>
                <label class="layui-text layui-label"></label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">QQ：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="qq" lay-verify="require" placeholder="QQ" class="layui-input">
                </div>
                <label class="layui-text layui-label"></label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">微信：</label>
                <div class="layui-input-inline">
                    <input type="text" value="" name="weChat" lay-verify="require" placeholder="微信" class="layui-input">
                </div>
                <label class="layui-text layui-label"></label>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="layui-color-danger">*</span>状态：</label>
                <div class="layui-input-block checkbox-left checkbox-primary" style="padding-right: 120px;" lay-error="请至少选择一个状态状态">
                    <?php if(is_array($lang['status']) || $lang['status'] instanceof \think\Collection || $lang['status'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['status'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <input type="radio" name="status[]" value="<?php echo $key; ?>" title="<?php echo $item; ?>" lay-verify="checked" lay-group="status" />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"></label>
                <button class="layui-btn" lay-submit="" lay-filter="submit">添加</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>

    </form>

</section>

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

    Site.search({
        url: '/back/ajax/getBuildingBase',
        targetClass: '[lay-filter="keyword"]',      // 输入框目标元素
        parentClass: '.layui-input-block',          // 父级类
        hiddenClass: '[lay-filter="key"]',          // 隐藏域input
        key:'name'
    });

    var indexUrl = Site.uploader({
        elem:'#house_url_file',
        targetElem:'#url',
        url: '/back/ajax/uploader?file=file',
        title:'上传房源封面',
        isAjax:true,
        success: function (res, input) {
            console.log(res);
            console.log($('#url').val());
        }
    });

    var indexDetail = Site.uploader({
        elem:'#house_detail_file',
        targetElem:'#detail',
        url: '/back/ajax/uploader?file=file',
        title:'上传房源图片',
        isAjax:true,
        isMulti:true,
        success: function (res, input) {
            console.log(res);
            console.log($('#detail').val());
        }
    });

</script>

</body>

</html>
            <!-- 右侧主体内容结束 -->

        </div>
    </div>

</div>

<!-- 加载js文件-->
<!--  全局 -->
<script type="text/javascript" src="__JS__/site.js"></script>
<script type="text/javascript" src="__JS__/back.js"></script>
<script>
    layui.use(['layer', 'util'], function() {
        var $ = layui.jquery,
            layer = layui.layer,
            util = layui.util;

        var top = false, bar1 = false, bar2 = false,
        metaUtil = $('meta[name="util"]').attr('content');
        if (metaUtil !== undefined && metaUtil === 'true'){
            top = true;
            bar1 = true;
            bar2 = true;
        }

        //回到顶部
        //使用内部工具组件
        util.fixbar({
            top: top,
            // bar1: bar1,
//            bar2: bar2,
            css: {right: 15, bottom: 15},
            click: function(type){
                if(type === 'bar1'){
                    layer.msg('工具一');
                }else if (type === 'bar2'){
                    layer.msg('工具二');
                }
            }
        });

    });
</script>

</body>
</html>

