/**
 * table 组件
 * @description 基于 form 、layer 封装的组件
 */
layui.define(['layer', 'form','laypage'], function (exports) {
    "use strict";
    var mod_name = 'layTable',
        $ = layui.jquery,
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        form = layui.form(),
    laypage = layui.laypage;

    var Table = function () {
        this.config = {
            type: '1', //表格数据显示方式  可选值 1 或 2 ；1 为 选择确定，2为 滚动显示 ; 默认是 1
            elem: undefined, //表格容器
            toggleElem: undefined, //触发容器
            pageElem: undefined,  //分页容器
            tHead: [], // 列标题 数组  为空自动读取
            max:8, // 表格最多显示列数 默认是 8 列
            page: 1, // 当前页数
            count: 10, // 每页显示条数
            total: 0, // 总数
            success: undefined, //type:function 渲染成功调用
            fail: function (msg) { layer.msg(msg); }, //type:function 渲染失败调用
        };
        this.cache = {
            tHead:[],
            data:[],
        };
        this.parent = {};
        this.toggle = {};
        this.valid = true; // 配置是否有效 ,默认 有效
    };
    /**
     * 版本号
     */
    Table.prototype.v = '1.0.0';

    /**
     * 设置
     * @param {Object} options
     */
    Table.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.config, options);
        return that;
    };

    /**
     * 渲染
     */
    Table.prototype.render = function () {
        var that = this;
        var _config = that.config;
        that.parent = $(_config.elem);
        that.toggle = $(_config.toggleElem);
        if (that.parent === 0) {
            layer.msg('Table Error:找不到配置的容器elem!');
            that.valid = false;
        }
        if (that.toggle.length === 0) {
            layer.msg('Table Error:找不到配置的容器toggleElem!');
            that.valid = false;
        }
        if (that.valid){
            var tHead = [],
                tBody = [],
                paging = '<div id="'+_config.pageElem+'"></div>';
            var table = '<table class="layui-table" lay-even="" lay-skin="row"><thead></thead><tbody></tbody></table>';
            that.parent.html(table);

            if (_config.type !== 2){
                var container = that.toggle.parent();
                container.css({position:"relative"});
                that.parent.find('thead tr th').each(function () {
                    _config.tHead.push($(this).text());
                });
                var index = 1;
                var inputs = [];
                var select;
                $.each(_config.tHead,function (i,item) {
                    inputs.push('<dd><input type="checkbox" name="title[]" title="'+item+'" /></dd>');
                });
                select = '<div class="layui-unselect layui-form-select"><dl class="layui-anim layui-anim-upbit">'+inputs.join("")+'</dl><div';
                container.append(select);
                form.render('checkbox');
                that.toggle.on('click',function (e) {
                    !container.find('layui-form-select').hasClass('layui-form-selected') || container.find('layui-form-select').addClass('layui-form-selected');
                    layer.msg('选择表列数');
                });
            }
        }
        return that;
    };

    /**
     * 渲染
     */
    Table.prototype.jump = function () {
        var that = this;
        var _config = that.config;
        var _page = $('#' + _config.elem),
            currPage = _config.page || getUrlParam('pageNumber'),
            count =  _config.count || _page.attr('data-count'),
            pages =  _config.count == '0' ? '0' : Math.ceil(_config.total/_config.count);
        currPage = currPage > 1 ? currPage : 1;
        laypage({
            curr: currPage,
            cont: _config.pageElem,
            pages: pages,
            skip: true,
            jump: function (obj, first) {
                if (obj.curr != currPage) {
                    var url = location.href;
                    if (url.indexOf("?") == -1) {
                        location.href = url + "?pageNumber=" + obj.curr;
                    } else {
                        var page = getUrlParam('pageNumber');
                        if (page) {
                            location.href = url.replace("pageNumber=" + page, "pageNumber=" + obj.curr);
                        } else {
                            location.href = url.replace("?", "?pageNumber=" + obj.curr + "&");
                        }
                    }
                }
                if (_config.total) {
                    _page.prepend('<span class="page-total"> 数量: ' + (count) + ' </span>');
                }
            }
        });
        return that;
    };

    /**
     * 查询Table 标题 是否存在，如果存在则返回索引值，不存在返回-1
     * @param {String} title 标题
     * @param {String} id  参数filter设置为 2 才生效
     */
    Table.prototype.exists = function(title,id) {
        var _config = this.config;
        var that = that.titleBox === undefined ? this.init() : this,
            tabIndex = -1;
        if ( parseInt(_config.filter) !== 2 ){
            id = null;
        }
        that.titleBox.find('li').each(function(i, e) {
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
    Table.prototype.getTitle=function(title,id){
        var _config = this.config;
        var that = that.titleBox === undefined ? this.init() : this,
            tabId = -1;
        if ( parseInt(_config.filter) !== 2 ){
            id = null;
        }
        that.titleBox.find('li').each(function(i, e) {
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

    Table.prototype.on = function(events, callback) {
    };

    var getUrlParam = function (name,url) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (url){
            r = url.substr(url.indexOf('?')).match(reg);
        }
        if (r != null) return unescape(r[2]);
        return null;
    }

    var model = new Table();
    exports(mod_name, function (options) {
        return model;
    });
});