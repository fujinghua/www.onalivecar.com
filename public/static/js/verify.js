/**
 * verify 组件
 * @description 基于 form 、layer 封装的组件
 */
layui.define(['layer', 'form'], function (exports) {
    "use strict";
    var mod_name = 'verify',
        $ = layui.jquery,
        layer = top.layer === undefined ? layui.layer : top.layer,
        form = layui.form();

    var Verify = function () {
        this.config = {
            type: '1', //表格数据显示方式  可选值 1 或 2 ；1 为 选择确定，2为 滚动显示 ; 默认是 1
            elem: undefined, //表格容器
            toggleElem: undefined, //触发容器
            title: [], // 列标题 数组  为空自动读取
            max:8, // 表格最多显示列数 默认是 8 列
            success: undefined, //type:function 渲染成功调用
            fail: function (msg) { layer.msg(msg); }, //type:function 渲染失败调用
        };
    };
    var ELEM = {
        parent:{},
        toggle:{},
        valid: true // 配置是否有效 ,默认 有效
    };
    /**
     * 版本号
     */
    Verify.prototype.v = '1.0.0';

    /**
     * 设置
     * @param {Object} options
     */
    Verify.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.config, options);
        return that;
    };

    /**
     * 添加规则
     */
    Verify.prototype.add = function () {
        var that = this;
        var _config = that.config;

        return that;
    };

    /**
     * 渲染
     */
    Verify.prototype.render = function () {
        var that = this;
        var _config = that.config;
        ELEM.parent = $(_config.elem);
        ELEM.toggle = $(_config.toggleElem);
        if (ELEM.parent === 0) {
            layer.msg('Verify Error:找不到配置的容器elem!');
            ELEM.valid = false;
        }
        if (ELEM.toggle.length === 0) {
            layer.msg('Verify Error:找不到配置的容器toggleElem!');
            ELEM.valid = false;
        }
        if (ELEM.valid){
            if (_config.type !== 2){
                var container = ELEM.toggle.parent();
                container.css({position:"relative"});
                ELEM.parent.find('thead tr th').each(function () {
                    _config.title.push($(this).text());
                });
                var index = 1;
                var inputs = [];
                var select;
                $.each(_config.title,function (i,item) {
                    inputs.push('<dd><input type="checkbox" name="title[]" title="'+item+'" /></dd>');
                });
                select = '<div class="layui-unselect layui-form-select"><dl class="layui-anim layui-anim-upbit">'+inputs.join("")+'</dl><div';
                container.append(select);
                form.render('checkbox');
                ELEM.toggle.on('click',function (e) {
                    !container.find('layui-form-select').hasClass('layui-form-selected') || container.find('layui-form-select').addClass('layui-form-selected');
                    layer.msg('选择表列数');
                });
            }else {
                layer.msg('此功能未开放');
            }
        }
        return that;
    };

    /**
     * 查询Verify 标题 是否存在，如果存在则返回索引值，不存在返回-1
     * @param {String} title 标题
     * @param {String} id  参数filter设置为 2 才生效
     */
    Verify.prototype.exists = function(title,id) {
        var _config = this.config;
        var that = ELEM.titleBox === undefined ? this.init() : this,
            tabIndex = -1;
        if ( parseInt(_config.filter) !== 2 ){
            id = null;
        }
        ELEM.titleBox.find('li').each(function(i, e) {
            var $cite = $(this).children('cite');
            if ( parseInt(_config.filter) === 2 ){
                if (id){
                    if($cite.data('id') === id) {
                        tabIndex = i;
                    }
                }else {
                    if($cite.text() === title) {
                        tabIndex = i;
                    }
                }
            }else {
                if($cite.text() === title) {
                    tabIndex = i;
                }
            }
        });
        return tabIndex;
    };

    /**
     * 获取 title id
     * @param {String} title 标题
     * @param {String} id  参数filter设置为 2 才生效
     */
    Verify.prototype.getTitle=function(title,id){
        var _config = this.config;
        var that = ELEM.titleBox === undefined ? this.init() : this,
            tabId = -1;
        if ( parseInt(_config.filter) !== 2 ){
            id = null;
        }
        ELEM.titleBox.find('li').each(function(i, e) {
            var $cite = $(this).children('cite');
            if ( parseInt(_config.filter) === 2 ){
                if (id){
                    if($cite.data('id') === id) {
                        tabId = $(this).attr('lay-id');
                    }
                }else {
                    if($cite.text() === title) {
                        tabId = $(this).attr('lay-id');
                    }
                }
            }else {
                if($cite.text() === title) {
                    tabId = $(this).attr('lay-id');
                }
            }
        });
        return tabId;
    };

    Verify.prototype.on = function(events, callback) {
    };

    var table = new Verify();
    exports(mod_name, function (options) {
        return table;
    });
});