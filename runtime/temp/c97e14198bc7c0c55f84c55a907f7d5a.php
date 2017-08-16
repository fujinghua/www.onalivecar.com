<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"F:\aliveDinner\upupw\www\atAliveCar\public/../application/back\view\house\index.html";i:1502897794;s:92:"F:\aliveDinner\upupw\www\atAliveCar\public/../application/common\view\layouts\back-main.html";i:1502125153;}*/ ?>
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
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>

</head>
<body>

<fieldset class="layui-elem-field">
    <legend>我的房源查询</legend>
    <div class="layui-field-box">
        <form class="layui-form layui-form-pane layui-form-search" action="" lay-accordion>
            <div class="layui-form-item">
                <div class="layui-input-inline search">
                    <input type="text" name="keyword" placeholder="输入关键词" value="<?php echo \think\Request::instance()->get('keyword'); ?>" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <select name="houseType" lay-search >
                        <option value="">可选择类型</option>
                        <option value="">全部</option>
                        <?php if(is_array($lang['houseType']) || $lang['houseType'] instanceof \think\Collection || $lang['houseType'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lang['houseType'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>" <?php if($key == \think\Request::instance()->get('houseType')): ?>selected<?php endif; ?>><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>

                <div class="layui-inline">
                    <button type="submit" class="layui-btn">查找</button>
                </div>

                <div class="layui-inline">
                    <label class="layui-btn layui-btn-primary" lay-filter="reset">重置</label>
                </div>

                <div class="layui-inline">
                    <label class="layui-btn btn-more layui-accordion" lay-fliter="more" lay-for="#layui-colla-title">更多</label>
                </div>

            </div>
            <div class="layui-collapse" lay-accordion>
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title" id="layui-colla-title" style="display: none;"></h2>
                    <div class="layui-colla-content">
                        <div class="layui-form-item">
                            <label class="layui-form-label">城市:</label>
                            <div class="layui-input-inline">
                                <select name="city" lay-search >
                                    <option value="">可选择城市</option>
                                    <option value="0">不限</option>
                                    <?php if(is_array($cityLists) || $cityLists instanceof \think\Collection || $cityLists instanceof \think\Paginator): $i = 0; $__LIST__ = $cityLists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">

                            <label class="layui-form-label">时间：</label>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input name="outAt1" type="text" value="" class="layui-input" lay-filter="startDate">
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline">
                                    <input name="outAt2" type="text" value="" class="layui-input" lay-filter="endDate">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</fieldset>

<section class="layui-table-action" style="margin-top: 15px;">
    <div class="layui-form-item">
        <div class="layui-inline">
            <div class="layui-inline">
                <label class="layui-btn layui-btn-danger" lay-filter="delete">删除</label>
            </div>
            <div class="layui-inline">
                <label class="layui-text layui-label"> (共 <em class="layui-color-danger"><?php echo $list->total(); ?></em> 条信息)</label>
            </div>
        </div>
        <div class="layui-inline" style="margin-right: 15px;">
            <div class="layui-inline">
                <label class="layui-btn" lay-filter="createNewHouse">添加新房</label>
            </div>
            <div class="layui-inline">
                <label class="layui-btn" lay-filter="createHandHouse">添加二手房</label>
            </div>
        </div>
    </div>
</section>

<table class="layui-table layui-form" lay-even="" lay-skin="row">
    <thead>
    <tr>
        <th style="width: 20px;"><input type="checkbox" lay-filter="selectAll" lay-group="tableItem" lay-skin="primary" /></th>
        <th>序号</th>
        <th>标题</th>
        <th>楼盘名称</th>
        <th>区域</th>
        <th>价格</th>
        <th>基本参数</th>
        <th>发布日期</th>
        <th>发布人</th>
        <th>联系人</th>
        <th>状态</th>
        <th style="width:50px; text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $key = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "<tr class='empty'><td colspan='20' style='text-align: center;'> 没有数据 </td> </tr>" ;else: foreach($__LIST__ as $key=>$model): $mod = ($key % 2 );++$key;?>
    <tr data-key="<?php echo $model['id']; ?>">
        <td style="width: 20px;"><input type="checkbox" name="id[<?php echo $key; ?>]" value="<?php echo $model['id']; ?>" lay-group="tableItem" lay-skin="primary"/></td>
        <td style="width: 45px;text-align: center;">
            <?php echo $key+(($list->currentPage()-1) * ($list->listRows())); ?>
        </td>
        <td><?php echo $model['title']; ?></td>
        <td><?php echo $model['getBuildingBase']['title']; ?></td>
        <td><?php echo $model['getCity']['name']; if(!empty($model->county_id)): ?>/<?php endif; ?>
            <?php echo $model['getCounty']['name']; ?>
        </td>
        <td>
            单价：<?php echo $model['eachPrice']; ?>
            <br/>
            总价：<?php echo $model['price']; ?>
        </td>
        <td><?php echo $model['room']; ?></td>
        <td><?php echo $model['created_at']; ?></td>
        <td><?php echo $model['getCreatedBy']['username']; ?></td>
        <td><?php echo $model['contact']; ?></td>
        <td><?php echo $model->getValue('status',$model['status']); ?></td>
        <td style="width: 150px;" class="action">
            <!--<div class="layui-inline checkbox-left checkbox-mini">-->
                <!--<input type="checkbox" title="推荐" lay-filter="isHot" lay-skin="default"/>-->
                <!--<input type="checkbox" title="置顶" lay-filter="isTop" lay-skin="default"/>-->
            <!--</div>-->
            <label class="layui-btn layui-btn-mini" lay-filter="view">查看</label>
            <label class="layui-btn layui-btn-mini layui-btn-danger" lay-filter="delete">删除</label>
            <label class="layui-btn layui-btn-mini" lay-filter="update">编辑</label>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "<tr class='empty'><td colspan='20' style='text-align: center;'> 没有数据 </td> </tr>" ;endif; ?>
    </tbody>
</table>

<!-- 分页容器 -->
<div id="paging" class="paging" data-total="<?php echo $list->total(); ?>" data-rows="<?php echo $list->listRows(); ?>" data-currentPage="<?php echo $list->currentPage(); ?>"></div>

<script>
    var url = {
        createHouseUrl:"<?php echo url('/back/house/create'); ?>",
        createHandHouseUrl:"<?php echo url('/back/hand_house/create'); ?>",
        updateUrl:"<?php echo url('/back/house/update'); ?>",
        viewUrl:"<?php echo url('/back/house/view'); ?>",
        deleteUrl:"<?php echo url('/back/house/delete'); ?>"
    };
    $(function() {
        //分页
        Site.loadPage();

        //添加新房
        Back.create('createNewHouse',{content:url.createHouseUrl,area:['1050px','88%']});

        //添加二手房房
        Back.create('createHandHouse',{content:url.createHandHouseUrl,area:['1050px','88%']});

        //编辑
        Back.update('update',{content:url.updateUrl,area:['1050px','88%']});

        //查看
        Back.view('view',{content:url.viewUrl,area:['1050px','88%']});

        //删除
        Back.delete('delete',url.deleteUrl);
    });
    layui.use([ 'layer','laydate', 'form'], function() {});
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

