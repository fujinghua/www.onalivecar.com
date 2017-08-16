/** tab.js By Beginner Emain:zheng_jinfan@126.com HomePage:http://www.zhengjinfan.cn */
layui.define(['element','layer'], function(exports) {
    "use strict";

    var mod_name = 'tab',
        $ = layui.jquery,
        element = layui.element(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        globalTabIdIndex = 0,
        Tab = function() {
            this.config = {
                elem: undefined,
                closed: true, //是否包含删除按钮
                filter: 1, //过滤方式 可选值 1 或 2  默认为1 以title 为 过滤， 2 以 data-id
                refresh: true,   //相当于重新加载 与下面的refresh只能生效一个，且优先判断 此项
                autoRefresh: true, //相当于F5重新刷新
                isBlank:true , // 附有target属性且开启此项，则打开新页面
                contextMenu:false,
                dblclickRefresh:true,
                iconShow:true, // tab 图标是否显示 默认 显示
                // maxSetting:{
                // 	max:10,
                // }
            };
        };
    var ELEM = {};
    //版本号
    Tab.prototype.v = '0.1.5';
    /**
     * 参数设置
     * @param {Object} options
     */
    Tab.prototype.set = function(options) {
        var that = this;
        $.extend(true, that.config, options);
        return that;
    };
    /**
     * 初始化
     */
    Tab.prototype.init = function() {
        var that = this;
        var _config = that.config;
        if(typeof(_config.elem) !== 'string' && typeof(_config.elem) !== 'object') {
            layer.msg('Tab error: elem参数未定义或设置出错，具体设置格式请参考文档API.');
        }
        var $container;
        if(typeof(_config.elem) === 'string') {
            $container = $('' + _config.elem + '');
        }
        if(typeof(_config.elem) === 'object') {
            $container = _config.elem;
        }
        if($container.length === 0) {
            layer.msg('Tab error:找不到elem参数配置的容器，请检查.');
        }
        var filter = $container.attr('lay-filter');
        if(filter === undefined || filter === '') {
            layer.msg('Tab error:请为elem容器设置一个lay-filter过滤器');
        }
        _config.elem = $container;
        ELEM.elem = $container;
        ELEM.titleBox = $container.children('ul.layui-tab-title');
        ELEM.contentBox = $container.children('div.layui-tab-content');
        ELEM.tabFilter = filter;
        return that;
    };
    /**
     * 查询tab是否存在，如果存在则返回索引值，不存在返回-1
     * @param {String} title 标题
     * @param {String} id  参数filter设置为 2 才生效
     */
    Tab.prototype.exists = function(title,id) {
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
     * 获取tabid
     * @param {String} title 标题
     * @param {String} id  参数filter设置为 2 才生效
     */
    Tab.prototype.getTabId=function(title,id){
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
    /**
     * 添加选择卡，如果选择卡存在则获取焦点
     * @param {Object} data
     */
    Tab.prototype.tabAdd = function(data) {
        var that = this;
        var _config = that.config;
        if (_config.isBlank && data.target !== undefined && data.target !== false){
            top.window.open(data.href);
            return;
        }
        var tabIndex = that.exists(data.title,data.id || null);
        if(tabIndex === -1) {
            //设置只能同时打开多少个tab选项卡
            if(_config.maxSetting !== 'undefined') {
                var currentTabCount = _config.elem.children('ul.layui-tab-title').children('li').length;
                if(typeof _config.maxSetting === 'number') {
                    if(currentTabCount === _config.maxSetting) {
                        layer.msg('为了系统的流畅度，只能同时打开' + _config.maxSetting + '个选项卡。');
                        return;
                    }
                }
                if(typeof _config.maxSetting === 'object') {
                    var max = _config.maxSetting.max || 8;
                    var msg = _config.maxSetting.tipMsg || '为了系统的流畅度，只能同时打开' + max + '个选项卡。';
                    if(currentTabCount === max) {
                        layer.msg(msg);
                        return;
                    }
                }
            }
            globalTabIdIndex++;
            var content = '<iframe src="' + data.href + '" data-id="' + globalTabIdIndex + '"></iframe>';
            var title = '';
            if(data.icon !== undefined && _config.iconShow) {
                if(data.icon.indexOf('fa-') !== -1) {
                    title += '<i class="fa ' + data.icon + '" aria-hidden="true"></i>';
                } else {
                    title += '<i class="layui-icon">' + data.icon + '</i>';
                }
            }

            var unique = globalTabIdIndex;
            if(data.id !== undefined) {
                unique = data.id;
            }
            title += '<cite data-id="'+unique+'">' + data.title + '</cite>';

            //关闭参数 只接受 字符串的 true 或 false ,其他情况读取全局配置
            var closed = _config.closed;
            if(data.closed === true){
                closed = true;
            }else if(data.closed === false){
                closed = false;
            }
            if(closed) {
                title += '<i class="layui-icon layui-unselect layui-tab-close" data-id="' + globalTabIdIndex + '">&#x1006;</i>';
            }else {
                title += '<i class="layui-icon layui-unselect layui-tab-close" data-id="' + globalTabIdIndex + '" style="display: none;">&#x1006;</i>';
            }

            //添加tab
            element.tabAdd(ELEM.tabFilter, {
                title: title,
                content: content,
                id:globalTabIdIndex
            });

            //iframe 自适应
            ELEM.contentBox.find('iframe[data-id=' + globalTabIdIndex + ']').each(function() {
                $(this).height(ELEM.contentBox.height());
                $(this).attr('frameborder','0');
            });
            if(_config.closed) {
                //监听关闭事件
                ELEM.titleBox.find('li').children('i.layui-tab-close[data-id=' + globalTabIdIndex + ']').on('click', function() {
                    element.tabDelete(ELEM.tabFilter, $(this).parent('li').attr('lay-id')).init();
                    if(_config.contextMenu) {
                        $(document).find('div.uiba-contextmenu').remove(); //移除右键菜单dom
                    }
                });
            };

            //点击双击刷新
            if(_config.dblclickRefresh) {
                //监听双击事件
                ELEM.titleBox.find('li[lay-id="'+globalTabIdIndex+'"]').on('dblclick', function() {
                    _config.elem.find('div.layui-tab-content > div').children('iframe[data-id="'+globalTabIdIndex+'"]')[0].contentWindow.location.reload();
                });
            }

            //切换到当前打开的选项卡
            element.tabChange(ELEM.tabFilter, that.getTabId(data.title, data.id || null));
        } else {
            // 已打开 此标识的tab 切换到当前打开的选项卡
            element.tabChange(ELEM.tabFilter, that.getTabId(data.title, data.id || null));
            // 自动刷新 根据当前状态刷新
            if(_config.refresh) {
                var _thisIframe = _config.elem.find('div.layui-tab-content > div').eq(tabIndex).children('iframe');
                _thisIframe.attr('src',null);
                _thisIframe.attr('src',data.href);
            }else if (_config.autoRefresh){
                _config.elem.find('div.layui-tab-content > div').eq(tabIndex).children('iframe')[0].contentWindow.location.reload();
            }
        }

        if(_config.contextMenu) {
            element.on('tab(' + ELEM.tabFilter + ')', function(data) {
                $(document).find('div.dinner-contextmenu').remove();
            });
            ELEM.titleBox.find('li').on('contextmenu', function(e) {
                var $that = $(e.target);
                e.preventDefault();
                e.stopPropagation();

                var $target = e.target.nodeName === 'LI' ? e.target : e.target.parentElement;
                //判断，如果存在右键菜单的div，则移除，保存页面上只存在一个
                if($(document).find('div.dinner-contextmenu').length > 0) {
                    $(document).find('div.dinner-contextmenu').remove();
                }
                //创建一个div
                var div = document.createElement('div');
                //设置一些属性
                div.className = 'dinner-contextmenu';
                div.style.width = '130px';
                div.style.backgroundColor = 'white';

                var ul = '<ul>';
                ul += '<li data-target="refresh" title="刷新当前选项卡"><i class="fa fa-refresh" aria-hidden="true"></i> 刷新当前</li>';
                ul += '<li data-target="closeCurrent" title="关闭当前选项卡"><i class="fa fa-close" aria-hidden="true"></i> 关闭当前</li>';
                ul += '<li data-target="closeOther" title="关闭其他选项卡"><i class="fa fa-circle-o" aria-hidden="true"></i> 关闭其他</li>';
                ul += '<li data-target="closeAll" title="关闭全部选项卡"><i class="fa fa-circle-o" aria-hidden="true"></i> 全部关闭</li>';
                ul += '</ul>';
                div.innerHTML = ul;
                div.style.top = e.pageY + 'px';
                div.style.left = e.pageX + 'px';
                //将dom添加到body的末尾
                document.getElementsByTagName('body')[0].appendChild(div);

                //获取当前点击选项卡的id值
                var id = $($target).find('i.layui-tab-close').data('id');
                //获取当前点击选项卡的索引值
                var clickIndex = $($target).attr('lay-id');
                var $context = $(document).find('div.dinner-contextmenu');
                if($context.length > 0) {
                    $context.eq(0).children('ul').children('li').each(function() {
                        var $that = $(this);
                        //绑定菜单的点击事件
                        $that.on('click', function() {
                            //获取点击的target值
                            var target = $that.data('target');
                            //
                            switch(target) {
                                case 'refresh': //刷新当前
                                    var src = ELEM.contentBox.find('iframe[data-id=' + id + ']')[0].src;
                                    ELEM.contentBox.find('iframe[data-id=' + id + ']')[0].src = src;
                                    break;
                                case 'closeCurrent': //关闭当前
                                    if(clickIndex !== 0) {
                                        element.tabDelete(ELEM.tabFilter, clickIndex);
                                    }
                                    break;
                                case 'closeOther': //关闭其他
                                    ELEM.titleBox.children('li').each(function() {
                                        var $t = $(this);
                                        var id1 = $t.find('i.layui-tab-close').data('id');
                                        if(id1 != id && id1 !== undefined) {
                                            element.tabDelete(ELEM.tabFilter, $t.attr('lay-id'));
                                        }
                                    });
                                    break;
                                case 'closeAll': //全部关闭
                                    ELEM.titleBox.children('li').each(function() {
                                        var $t = $(this);
                                        if($t.index() !== 0) {
                                            element.tabDelete(ELEM.tabFilter, $t.attr('lay-id'));
                                        }
                                    });
                                    break;
                            }
                            //处理完后移除右键菜单的dom
                            $context.remove();
                        });
                    });

                    $(document).on('click',function(){
                        $context.remove();
                    });
                }
                return false;
            });
        }
    };

    Tab.prototype.getActive = function () {
        var active = ELEM.elem.find('div.layui-tab-content > .layui-tab-item.layui-show').children('iframe');
        var ret;
        if (active.length > 0){
            ret = active[0].contentWindow;
        }
        return ret || window;
    };

    Tab.prototype.on = function(events, callback) {
    };

    var tab = new Tab();
    exports(mod_name, function(options) {
        return tab.set(options);
    });
});