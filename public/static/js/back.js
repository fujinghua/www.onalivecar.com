/*!
 * Back.js
 * URL:http://
 * Only for Guest mode
 * Sort as
 * 		Utils/ModuleFunction/Other Global InitFunction
 */

if (typeof Back === "undefined") {
    Back = {};
}

/**
 * 配置
 * @type {{}}
 */
Back.config = {
    layuiBase: '/static/js/',
    getCity:'/manage/ajax/getCity',
};

Back.layer =  top.layui.layer ? top.layui.layer : layui.layer;

/* 初始化操作 */
Back.init = function () {
    if (typeof Site === "undefined") {
        if(top.layui.layer !== "undefined" ){
            top.layui.layer.msg('未加载Site类,Back功能可能受限');
        }
    }
};

/**
 * 快捷搜索
 * @param keyword
 */
Back.goSearch = function (keyword){
    $('form.layui-form-search input[name="keyword"]').val(keyword);
    $("form.layui-form-search").submit();
};

/**
 * 信息列表基础操作
 */
Back.tableBase = function (){
    layui.use(['layer','form', 'laydate'], function () {
        var laydate = layui.laydate,
            form = layui.form();
        Back.layer = top.layui.layer ? top.layui.layer : layui.layer ;

        $(document).off('click', '[lay-filter="date"]').on('click', '[lay-filter="date"]:not([readonly]):not([disabled])', function () {
            var that = $(this);
            var date = {
                elem: this,
                istime: true,
                format: that.attr('lay-format') || 'YYYY-MM-DD',
                choose: function (dates) { //选择好日期的回调
                }
            };
            laydate(date);
        });

        $(document).off('click', '[lay-filter="startDate"]').on('click', '[lay-filter="startDate"]:not([readonly]):not([disabled])', function () {
            var that = $(this);
            if (that.hasAttribute('readonly') || that.hasAttribute('disabled')){
                return;
            }
            var date = {
                elem: this,
                istime: true,
                format: "YYYY-MM-DD",
                choose: function (dates) { //选择好日期的回调
                }
            };
            laydate(date);
        });

        $(document).off('click', '[lay-filter="endDate"]').on('click', '[lay-filter="endDate"]:not([readonly]):not([disabled])', function () {
            var that = $(this);
            if (that.hasAttribute('readonly') || that.hasAttribute('disabled')){
                return;
            }
            var date = {
                elem: this,
                istime: true,
                format: "YYYY-MM-DD",
                choose: function (dates) { //选择好日期的回调
                }
            };
            laydate(date);
        });

        // 重置为空处理
        $(document).off('click', '[lay-filter="reset"]').on('click', '[lay-filter="reset"]', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            if ($form.length > 0) {
                // select
                $form.find('select:not([lay-reset])').each(function () {
                    $(this).val('');
                });

                // input
                $form.find('input:not([lay-reset])').each(function () {
                    $(this).val('');
                });

                // textarea
                $form.find('textarea:not([lay-reset])').each(function () {
                    $(this).html('');
                });
            }
            form.render();
        });

        //全选 | 全不选
        form.on('checkbox(selectAll)',function (data) {
            var group = $(data.elem).attr('lay-group');
            var child = $(data.elem).parents('.layui-form').find('input[type="checkbox"][lay-group="'+group+'"]');
            child.each(function(index, item) {
                item.checked = data.elem.checked;
            });
            form.render('checkbox');
        });

    });
};

/**
 * 添加
 * @param selector
 * @param options
 * @param url
 */
Back.create = function (selector,options,url){
    if (!selector){
        return;
    }
    var config = {
        scrollbar:false,
        type: 2,
        title: '添加',
        shade: 0.3,
        area: ['1050px', '62.8%'],
        content: undefined,
    };
    config = $.extend(config,options);
    //添加
    $(document).off('click','[lay-filter="'+selector+'"]').on('click','[lay-filter="'+selector+'"]',function () {
        var that = $(this);
        config.content = url || config.content || that.attr('lay-url');
        if (!config.content){
            Back.layer.msg('地址无效');
            return;
        }
        Back.layer.open(config);
    });
};

/**
 * 编辑
 * @param selector
 * @param options
 * @param url
 */
Back.update = function (selector,options,url){
    if (!selector){
        return;
    }
    var config = {
        scrollbar:false,
        type: 2,
        title: '编辑',
        shade: 0.3,
        area: ['1050px', '62.8%'],
        content: undefined,
    };
    config = $.extend(config,options);
    var baseUrl = config.content;
    //编辑
    $(document).off('click','[lay-filter="'+selector+'"]').on('click','[lay-filter="'+selector+'"]',function () {
        var that = $(this);
        var actionUrl = url || baseUrl || that.attr('lay-url');
        var id = that.closest('[data-key]').attr('data-key');
        if (!actionUrl || !id){
            Back.layer.msg('地址无效');
            return;
        }else{
            config.content = baseUrl +'?id='+ id;
        }
        Back.layer.open(config);
    });
};

/**
 * 操作工厂
 * @param selector
 * @param options
 * @param url
 * @param key
 */
Back.action = function (selector,options,url,key){
    if (!selector){
        return;
    }
    var config = {
        scrollbar:false,
        type: 2,
        title: '操作提示',
        shade: 0.3,
        area: ['1050px', '62.8%'],
        content: undefined,
    };
    config = $.extend(config,options);
    var baseUrl = config.content;
    //操作
    $(document).off('click','[lay-filter="'+selector+'"]').on('click','[lay-filter="'+selector+'"]',function () {
        var that = $(this);
        var actionUrl = url || baseUrl || that.attr('lay-url');
        var id = that.closest('[data-key]').attr('data-key');
        if (!actionUrl || !id){
            layer.msg('地址无效');
            return;
        }else{
            key = key || 'id';
            config.content = baseUrl +'?'+key+'='+ id;
        }
        Back.layer.open(config);
    });
};

/**
 * 查看
 * @param selector
 * @param options
 * @param url
 */
Back.view = function (selector,options,url){
    if (!selector){
        return;
    }
    var config = {
        scrollbar:false,
        type: 2,
        title: '查看',
        maxmin: true,
        shade: false,
        area: ['1050px', '62.8%'],
        content: undefined,
    };
    config = $.extend(config,options);
    var baseUrl = config.content;
    //查看
    $(document).off('click','[lay-filter="'+selector+'"]').on('click','[lay-filter="'+selector+'"]',function () {
        var that = $(this);
        var actionUrl = url || baseUrl || that.attr('lay-url');
        var id = that.closest('[data-key]').attr('data-key');
        if (!actionUrl || !id){
            Back.layer.msg('地址无效');
            return;
        }else{
            config.content = baseUrl +'?id='+ id;
        }
        Back.layer.open(config);
    });
};

/**
 * 删除 （注意：与添加和编辑的参数顺序不一致）
 * @param selector
 * @param url
 * @param options
 */
Back.delete = function (selector,url,options){
    if (!selector){
        return;
    }
    var item;
    var param = {id:[]};
    var config = {
        title:'删除提示',
        area: ['600px','300px'],
        shade: ['0.372','#000'],
        shadeClose:true,
        content:'确实要删除选择项吗？',
        btn: ['确定', '取消'],
        btnAlign: 'c',
        btn2: function(index, layero){
            Back.layer.close(index);
        },
        yes:function(index) {
            Back.layer.close(index);
            var i;
            $.ajax({
                url:url,
                type:'POST',
                data:param,
                beforeSend:function () {
                    i = top.layer.load(1, {shade:0.1});
                },
                success:function (data) {
                    Back.layer.close(i);
                    if (data.status == 1){
                        if (param.id){
                            $.each(param.id,function (index,item) {
                                $('[data-key="'+item+'"]').remove();
                            })
                        }else {
                            item.closest('[data-key="'+id+'"]').remove();
                        }
                    }
                    if (data.info !== undefined){
                        Back.layer.msg(data.info);
                    }
                },
                error:function (data) {
                    Back.layer.close(i);
                    Back.layer.msg('删除失败');
                }
            });
            return false;
        }
    };
    config = $.extend(config,options);
    //删除
    $(document).off('click','[lay-filter="'+selector+'"]').on('click','[lay-filter="'+selector+'"]',function () {
        item = $(this);
        var id = item.closest('[data-key]').attr('data-key');
        param.id = [];
        var has = true;
        if (!id){
            has = false;
            param.id = Back.getSelectCheckboxValues('[lay-group="tableItem"]');
            if (param.id.length >0){
                has = true;
            }
        }else {
            param.id.push(id);
        }
        if (!has)
        {
            Back.layer.msg('请选择删除项');
            return false;
        }
        url = url || item.attr('lay-url');
        if (!url){
            Back.layer.msg('地址无效');
            return;
        }
        Back.layer.open(config);
    });
};

/**
 * 提交表单
 * @param _options
 */
Back.submit = function (_options){
    var options = $.extend(
        {
            form:'form[action]',  // form 提交 对应的选择器
            url:undefined,  // form 提交地址
            submit:'submit',  // 提交选择器
            success:undefined, // 提交成功执行回调,参数是返回数据
            isClose:true, // 提交成功后关闭弹出层
            isReload:true, // 提交成功后刷新当前窗口
            verify:{} // 自定义验证规则
        },_options);
    layui.use(['layer','forms'],function () {
        var layer = top.layui.layer || layui.layer;

        //绑定form提交
        var forms = layui.forms().create({ELEM:options.form});

        //自定义验证规则
        forms.verify( options.verify);

        //监听提交
        forms.on('submit('+options.submit+')', function (data) {
            var param = $(data.form).serialize(),$form = $(data.form),url = options.url || $form.attr('action'),index;
            if (url === undefined || url === ''){
                //没有提交地址 中断
                url = window.location.href;
            }

            $.ajax({
                url:url,
                type:'POST',
                data:param,
                beforeSend:function () {
                    index = layer.load(1, {shade:0.1});
                },
                success:function (data) {
                    layer.close(index);
                    if (data.msg !== undefined || data.info !== undefined){
                        layer.msg(data.msg || data.info);
                    }
                    if (data.code == '1' || data.status == '1'){
                        if(typeof options.success === 'function'){
                            options.success(data);
                        }

                        if(options.isClose){
                            layer.close(layer.getFrameIndex(window.name));
                        }

                        if(options.isReload){
                            Site.reLoad();
                        }

                    }
                },
                error:function (data) {
                    layer.close(index);
                    layer.msg('提交出错');
                }
            });
            //必须中断
            return false;
        });

    });
};


/**
 *
 * @param selector //组别
 * @param checked //类别是 选择 或 不选择
 * @returns {Array}
 */
Back.getSelectCheckboxValues = function (selector, checked) {
    selector = selector || '[lay-group="selected"]';
    var values = [];
    if (checked === 'all') {
        $('input' + selector + ':not([lay-filter="selectAll"])').each(function () {
            values.push($(this).val());
        });
    }else if (checked !== false) {
        $('input' + selector + ':not([lay-filter="selectAll"])' + ':checked').each(function () {
            values.push($(this).val());
        });
    } else {
        $('input' + selector + ':not([lay-filter="selectAll"])' + ':not(:checked)').each(function () {
            values.push($(this).val());
        });
    }
    return values;
};

/**
 * 手风琴
 * @param selector
 */
Back.accordion = function(selector)  {
    selector = selector || '.layui-accordion';
    var icon = ['&#xe602;','&#xe61a;'];
    $(document).off('click', selector+'[lay-for]').on('click', selector+'[lay-for]', function () {
        var that = $(this);
        var target = that.attr('lay-for');
        var status = that.attr('lay-status');
        status = (parseInt(status) === 0 ? 1: 0);
        that.find('i').html(icon[status]);
        that.attr('lay-status',status);
        $(target).stop().trigger('click');
    });
    $(selector+'[lay-for]').each(function () {
        var that = $(this);
        $(this).attr('lay-status') || $(this).attr('lay-status',0);
        that.html(that.text()+'<i class="layui-icon layui-colla-icon">'+icon[0]+'</i>');
    });
};


$(function () {

    Back.init();
    Back.accordion();
    Back.tableBase();

});
