layui.define('jquery', function (exports) {
    "use strict";

    var $ = layui.$,
        hint = layui.hint(),
        device = layui.device(), // 属性：{android:false,ie:false,ios:false,os:"windows",weixin:false}
        MOD_NAME = 'menu',
        THIS = 'layui-this',
        SHOW = 'layui-show',
        HIDE = 'layui-hide',
        WIDTH = document.documentElement.clientWidth,//获取页面可见宽度
        HEIGHT = document.documentElement.clientHeight,//获取页面可见高度
        Menu = function () {
            this.config = {
                elem: '.layui-menu-container', //顶部容器
                parentElem: 'body', //父级容器
                hideTopClass: 'layui-top-hide', //父级容器标识状态
                hideButtonClass: 'layui-bottom-hide', //父级容器标识状态
                menu: [], //侧栏菜单数据
                isPhone: false, //是否手机
                isPC: true,//是否电脑
                type: 1, // 类型 可选值 1 或 2 ，1表示侧栏面包显示，2表示顶层面包显示
                showHeight: 120, //出现top menu的滚动条高度临界值
                buttonElem: '.layui-button-container', //底部容器
            };
        };

    //全局设置
    Menu.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.config, options);
        return that;
    };

    //全局设置
    Menu.prototype.render = function (options) {
        var that = this,
            ELEM = $(that.config.elem || '.layui-menu-container'),
            BUTTON_ELEM = $(that.config.buttonElem || '.layui-button-container'),
            PARENT = $(that.config.parentElem || '.layui-top-parent'),
            MENU_ITEM = '.layui-menu-container',
            win = $(window),
            dom = $(document),
            body = $('body'),
            is, timer;
        var config = that.config;
        config = $.extend(config, options);

        var lastStop = dom.scrollTop(), isX, isShowTop, isShowButton,
            scroll = function () {
                var stop = dom.scrollTop();
                if ((stop - lastStop) > 0) {
                    if ((stop - lastStop) > 60 && stop >= (config.showHeight)) {
                        isShowTop = false;
                    } else {
                        isShowTop = true;
                    }
                    if ((stop - lastStop) > 60 && stop >= (config.showHeight) || stop <= (config.showHeight)) {
                        isShowButton = true;
                    } else {
                        isShowButton = false;
                    }
                } else if((stop - lastStop)<0) {
                    if ((-(stop - lastStop)) > 60 && stop >= (config.showHeight)) {
                        isShowTop = false;
                    } else {
                        isShowTop = true;
                    }
                    if ((-(stop - lastStop)) > 60 && stop >= (config.showHeight) || stop <= (config.showHeight)) {
                        isShowButton = false;
                    } else {
                        isShowButton = true;
                    }
                }else {
                    isShowTop = true;
                    isShowButton = true;
                }
                render();
                lastStop = stop;
            }, render = function () {
            if(isShowTop){
                !PARENT.hasClass(config.hideTopClass) || PARENT.removeClass(config.hideTopClass) && ELEM.css({height: 50});
            }else {
                PARENT.hasClass(config.hideTopClass) || PARENT.addClass(config.hideTopClass) && ELEM.css({height: 0});
            }
            if(isShowButton){
                !PARENT.hasClass(config.hideButtonClass) || PARENT.removeClass(config.hideButtonClass) && BUTTON_ELEM.css({height: 50});
            }else {
                PARENT.hasClass(config.hideButtonClass) || PARENT.addClass(config.hideButtonClass) && BUTTON_ELEM.css({height: 0});
            }
            };

        scroll();


        //bar点击事件
        ELEM.find('li').on('click', function () {
            var othis = $(this), type = othis.attr('lay-type');
            if (type === 'top') {
                $('html,body').animate({
                    scrollTop: 0
                }, 200);
            }
            options.click && options.click.call(this, type);
        });

        //Top显示控制
        dom.on('scroll', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                scroll();
            }, 100);
        });

    };

    //实例化
    var model = new Menu();

    exports(MOD_NAME, model);
});