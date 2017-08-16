/*!
 * Site.js
 * URL:http://
 * Only for Guest mode
 * Sort as
 * 		Utils/ModuleFunction/Other Global InitFunction
 */
var _width = document.documentElement.clientWidth;//获取页面可见宽度
var _height = document.documentElement.clientHeight;//获取页面可见高度
var jquery, element, layer, util, form, code, laydate, flow, layedit, upload, laypage;
var zIndex = new Date().getTime();

if (typeof Site === "undefined") {
    Site = {};
}

/**
 * 配置
 * @type {{shade: [*], width: {max: string, small: string, min: string}, height: {max: string, small: string, min: string}, imageError: string, layuiBase: string, initComponents: [*], allComponents: [*]}}
 */
Site.config = {
    shade: ['0.372', '#000'],
    width: {
        max: '800px',
        small: '700px',
        min: '600px'
    },
    height: {
        max: '500px',
        small: '400px',
        min: '300px'
    },
    imageError: '/static/images/not-capture.png',
    layuiBase: '/static/js/',
    initComponents: ['jquery', 'element', 'layer', 'form', 'flow', 'laydate'],
    allComponents: ['jquery', 'element', 'layer', 'util', 'form', 'code', 'laydate', 'flow', 'layedit', 'upload', 'laypage']
};

/* 初始化操作 */
Site.init = function () {
    //layui
    layui.config({
        base: Site.config.layuiBase
    }).use(Site.config.initComponents, function () {
        window.jQuery = window.$ = layui.jquery;
        window.element = element = layui.element;
        window.layer = layer = layui.layer;
        window.form = form = layui.form;
        window.flow = flow = layui.flow;
        window.laydate = laydate = layui.laydate;
        if (typeof $ === "undefined") {
            $ = layui.jquery;
        }
        var device = layui.device();
        element();
        //阻止IE7以下访问
        if (device.ie && device.ie < 8) {
            layer.alert('Layui最低支持ie8，您当前使用的是古老的 IE' + device.ie + '，依旧怀旧');
        }
        //手机设备的简单适配
        var treeMobile = $('.site-tree-mobile');
        var shadeMobile = $('.site-mobile-shade');

        treeMobile.on('click', function () {
            $('body').addClass('site-mobile');
        });

        shadeMobile.on('click', function () {
            $('body').removeClass('site-mobile');
        });
    });
};

/*  */
Site.getModule = function (name, parentWin) {
    var module;

    switch (name) {
        case 'jquery': {
            module = top.window.jQuery ? top.window.jQuery : (window.jQuery ? window.jQuery : (jQuery ? jQuery : layui.jQuery ) );
        }
            break;
        case 'element': {
            module = top.window.element ? top.window.element : (window.element ? window.element : (element ? element : layui.element ) );
            if (typeof module === 'function') {
                module = module();
            }
        }
            break;
        case 'layer': {
            if (parentWin === false) {
                module = layui.layer ? layui.layer : (layer ? layer : (window.layer ? window.layer : top.window.layer ));
            } else {
                module = top.window.layer ? top.window.layer : (window.layer ? window.layer : (layer ? layer : layui.layer ));
            }
        }
            break;
        case 'form': {
            module = window.form ? window.form : (form ? form : layui.form );
            if (!module) {
                layui.use(['form'], function () {
                    module = layui.form;
                });
            }
        }
            break;
        case 'laydate': {
            module = top.window.laydate ? top.window.laydate : (window.laydate ? window.laydate : (laydate ? laydate : layui.laydate ) );
        }
            break;
        case 'flow': {
            module = top.window.flow ? top.window.flow : (window.flow ? window.flow : (flow ? flow : layui.flow )  );
        }
            break;
        default: {
        }
            break;
    }

    return module;
};

/*  */
Site.getUtil = function (callback, component) {
    if (util !== undefined) {
        if (typeof callback === 'function') {
            callback(util);
        }
    } else {
        if (!component) {
            component.push('util');
        } else {
            component = 'util';
        }
        layui.config({
            base: Site.config.layuiBase
        }).use(component, function () {
            window.util = util = layui.util;
            if (typeof callback === 'function') {
                callback(util);
            }
        });
    }
};

/*  */
Site.getCoder = function (component, callback) {
    if (code !== undefined) {
        if (typeof callback === 'function') {
            callback(code);
        }
    } else {
        layui.config({
            base: Site.config.layuiBase
        }).use(component, function () {
            window.code = code = layui.code;
            if (typeof callback === 'function') {
                callback(code);
            }
        });
    }
};

/*  */
Site.getLayEditor = function (component, callback) {
    if (layedit !== undefined) {
        if (typeof callback === 'function') {
            callback(layedit);
        }
    } else {
        layui.config({
            base: Site.config.layuiBase
        }).use(component, function () {
            window.layedit = layedit = layui.layedit;
            if (typeof callback === 'function') {
                callback(layedit);
            }
        });
    }
};

/*  */
Site.getUploader = function (component, callback) {
    if (upload !== undefined) {
        if (typeof callback === 'function') {
            callback(upload);
        }
    } else {
        layui.config({
            base: Site.config.layuiBase
        }).use(component, function () {
            window.upload = upload = layui.upload;
            if (typeof callback === 'function') {
                callback(upload);
            }
        });
    }
};

/*  */
Site.getLayPager = function (callback) {
    if (laypage !== undefined) {
        if (typeof callback === 'function') {
            callback(laypage);
        }
    } else {
        layui.config({
            base: Site.config.layuiBase
        }).use('laypage', function () {
            window.laypage = laypage = layui.laypage;
            if (typeof callback === 'function') {
                callback(laypage);
            }
        });
    }
};

/**
 * JS 加载到顶部LoadJS
 * @param url
 */
Site.loadJS = function loadJS(url, fn) {
    var ss = document.getElementsByName('script'),
        loaded = false;
    for (var i = 0, len = ss.length; i < len; i++) {
        if (ss[i].src && ss[i].getAttribute('src') == url) {
            loaded = true;
            break;
        }
    }
    if (loaded) {
        if (fn && typeof fn != 'undefined' && fn instanceof Function) fn();
        return false;
    }
    var s = document.createElement('script'),
        b = false;
    s.setAttribute('type', 'text/javascript');
    s.setAttribute('src', url);
    s.onload = s.onreadystatechange = function () {
        if (!b && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
            b = true;
            if (fn && typeof fn != 'undefined' && fn instanceof Function) fn();
        }
    };
    document.getElementsByTagName('head')[0].appendChild(s);
};

/**
 * 清空 LoadJS 加载到顶部的js引用
 * @param src
 * @constructor
 */
Site.ClearJS = function (src) {
    var js = document.getElementsByTagName('head')[0].children;
    var obj = null;
    for (var i = 0; i < js.length; i++) {
        if (js[i].tagName.toLowerCase() == "script" && js[i].attributes['src'].value.indexOf(src) > 0) {
            obj = js[i];
        }
    }
    document.getElementsByTagName('head')[0].removeChild(obj);
};

/* 克隆方法 */
Site.clone = function (obj) {
    var o;
    if (typeof obj === "object") {
        if (obj === null) {
            o = null;
        } else {
            if (obj instanceof Array) {
                o = [];
                for (var i = 0, len = obj.length; i < len; i++) {
                    o.push(Site.clone(obj[i]));
                }
            } else {
                o = {};
                for (var j in obj) {
                    o[j] = Site.clone(obj[j]);
                }
            }
        }
    } else {
        o = obj;
    }
    return o;
};

/*  */
Site.showUrl = function (title, url, width, height, type, maxmin, ele, shade, scroll, shadeClose, refresh, end) {
    var content = '', stop = true;
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    type = type || 1;
    type = parseInt(type);
    if (parseInt(type) !== 2) {
        $.post(url, {}, function (data) {
            content = data;
            stop = false;
        }, "html");
    } else {
        scroll = scroll === false ? 'no' : 'yes';
        content = [url, scroll];
        stop = false;
    }
    if (stop) {
        return;
    }
    width = width || Site.config.width.max;
    height = height || Site.config.height.max;
    maxmin = maxmin !== undefined && maxmin === true && parseInt(type) === 2;
    if (shade === true) {
        shade = Site.config.shade;
    }
    shade = shade || (maxmin !== undefined && maxmin === true && parseInt(type) === 2 ? 0 : 0.3);
    shadeClose = shadeClose || false;
    refresh = refresh || false;
    return myLayer.open({
        type: type,
        area: [width, height],
        maxmin: maxmin,
        shade: shade,
        shadeClose: shadeClose,
        refresh: refresh,
        id: ele,
        title: '<p style="text-align: center;">' + title + '</p>',
        content: content,
        end: end
    });
};

/*  */
Site.msg = function (content, options) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    options = $.extend({time: 1500}, options);
    return myLayer.msg(content, options);
};

/*  */
Site.wait = function (content) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    if (!content) {
        content = "请稍后....";
    }
    return myLayer.msg(content, {shade: 0.3, time: 30000});
};

/*  */
Site.loading = function (icon) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    icon = icon || 1;
    return myLayer.load(icon, {type: 3, icon: icon, shade: 0.3});
};

/*  */
Site.load = function (url, content) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    if (!content) {
        content = "加载中....";
    }

    myLayer.msg(content, {shade: 0.3, time: 628});
    setTimeout(function () {
        window.location.href = url;
    }, 628);
};

/*  */
Site.loadFrame = function (title, url, width, height, ele, btn) {
    var content;
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    title = title || '标题';
    content = [url, 'no'];
    width = width || Site.config.width.max;
    height = height || Site.config.height.min;
    btn = btn === true ? ['全部关闭'] : undefined;

    //多窗口模式，层叠置顶
    return myLayer.open({
        type: 2 //此处以iframe举例
        , title: title
        , area: [width, height]
        , shade: 0
        , shadeClose: true
        , maxmin: true
        , id: ele
        , content: content
        , btn: btn //只是为了演示
        , yes: function () {
            layer.closeAll();
        }
        , zIndex: myLayer.zIndex //重点1
    });
};

/*  */
Site.hide = function () {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    myLayer.closeAll();
    if (top.window.layer) {
        top.window.layer.closeAll();
    }
};

/*  */
Site.close = function (index) {
    if (top.window.layer) {
        top.window.layer.close(index);
    }
};

/*  */
Site.error = function (content) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    return myLayer.msg(content, {icon: 2, time: 2000});
};

/*  */
Site.success = function (content) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    return myLayer.msg(content, {icon: 1, time: 2000});
};

/*  */
Site.Alert = function (content) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    return myLayer.msg(content, {icon: 0, time: 2000});
};

/**
 * @description 信息提示函数
 * @param msg 提示信息
 * @param icon 提示图标：默认是0；可支持表情类型有 0-6
 *              icon 值解释 ：(0--感叹号；1--对符号；2---错符号；3--问号；4--锁头；5--失败表情，6--成功表情)
 * @param shift 提示出现动画：默认是0;可支持的动画类型有0-6 ，如果不想显示动画，设置 shift: -1 即可。
 *             shift值解释： (0--中间显示；1--从上显示；2--从下显示；3--从左显示；4--从左下旋转显示；5--淡出，6--震动)
 * @param time 提示消失时间: 单位是毫秒（1秒=1000毫秒）。
 * @param shade 弹层外区域:默认是0 。可取值 0到1，如果你想定义别的颜色，可以shade: [0.8, '#393D49']；如果你不想显示遮罩，可以shade: 0
 */
Site.showTip = function (msg, icon, shift, time, shade) {
    msg = (msg !== undefined && msg !== '') ? msg : '提示信息缺失';
    icon = (icon === undefined) ? 0 : ( (parseInt(icon) >= 0 && parseInt(icon) <= 6) ? parseInt(icon) : 0 );
    shift = (shift === undefined) ? 0 : ( (parseInt(shift) >= 0 && parseInt(shift) <= 6) ? parseInt(shift) : 0 );
    time = (time === undefined) ? 1000 : ( parseInt(time) > 0 ? parseInt(time) : 1000 );
    shade = (shade === undefined) ? 0 : ( (shade >= 0 && shade <= 1) ? shade : 0 );
    var config = {icon: icon, shift: shift, time: time, shade: shade};
    return top.layer.msg(msg, config);
};

/* 确认对话框 */
Site.confirm = function (url, msg, width, height, shade) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    width = width || Site.config.width.min;
    height = height || Site.config.height.min;
    if (shade === true) {
        shade = Site.config.shade;
    }
    shade = shade || Site.config.shade;
    return myLayer.open({
        area: [width, height],
        shade: shade,
        shadeClose: true,
        content: msg,
        btn: ['确定', '取消'],
        btnAlign: 'c',
        btn2: function (index, layero) {
            myLayer.close(index);
        },
        yes: function () {
            window.location.href = url;
            return false;
        }
    });
};

/*  */
Site.showDialog = function (title, msg, callBack, width, height, shade) {
    var myLayer = Site.getModule('layer');
    if (!myLayer) {
        myLayer = layer;
    }
    width = width || Site.config.width.min;
    height = height || Site.config.height.min;
    if (shade === true) {
        shade = Site.config.shade;
    }
    shade = shade || Site.config.shade;
    return myLayer.open({
        title: title,
        area: [width, height],
        shade: shade,
        shadeClose: true,
        content: msg,
        btn: ['确定', '取消'],
        btnAlign: 'c',
        btn2: function (index, layero) {
            myLayer.close(index);
        },
        yes: function (index) {
            myLayer.close(index);
            if (callBack && (typeof callBack === "function" )) {
                callBack();
            }
            return false;
        }
    });
};

/*  */
Site.tab = function (options, parentWin, width, height, shade) {
    parentWin = parentWin || true;
    var myLayer = Site.getModule('layer', parentWin);
    if (!myLayer) {
        myLayer = layer;
    }

    options = options || [{title: 'TAB1', content: '内容1'}, {title: 'TAB2', content: '内容2'}];

    width = width || Site.config.width.min;
    height = height || Site.config.height.min;
    if (shade === true) {
        shade = Site.config.shade;
    }
    shade = shade || Site.config.shade;
    return myLayer.tab({
        area: [width, height],
        shade: shade,
        shadeClose: true,
        tab: options
    });
};

/*  */
Site.imgLoading = function (ele) {
    var $this = $(ele);
    if ($this === undefined || $this.attr('lay-src') === undefined || $this.attr('lay-filter') !== 'loading') {
        return;
    }

    var _width = layui.getStyle(ele, 'width'), _height = layui.getStyle(ele, 'height');

    var img = '<div style="display: flex;width: ' + _width + ';height: ' + _height + ';"><div style="margin: auto;"><img src="/static/images/loading-2.gif"></div></div>';
    $this.html(img);

    var _src = $this.attr('lay-src'),
        _class = $this.attr('lay-class') || '',
        _title = $this.attr('lay-title') || '',
        _alt = $this.attr('lay-alt') || '',
        _error = $this.attr('lay-error') || '/static/images/lockscreenbg.png';
    var attr = '';
    if (_class !== '') {
        attr += ' class="' + _class + '"';
    }
    if (_title !== '') {
        attr += ' title="' + _title + '"';
    }
    if (_alt !== '') {
        attr += ' alt="' + _alt + '"';
    }
    setTimeout(function () {
        layui.img(_src, function () {
            img = '<img src="' + _src + '" ' + attr + '>';
            $this.html(img);
        }, function () {
            img = '<img src="' + _error + '" ' + attr + ' style="height: 100%;width: 100%;overflow: hidden;">';
            $this.html(img);
        })
    }, 800);
};

/*  */
Site.photos = function (options) {
    var photoConfig = {
        photos: undefined,
        url: undefined,
        parentWin: true,
        shade: Site.config.shade,
        tab: undefined,
        callback: null
    };
    photoConfig = $.extend(photoConfig, options);
    var myLayer = Site.getModule('layer', photoConfig.parentWin);
    if (!myLayer) {
        myLayer = layer;
    }
    var index, load;
    if (!(photoConfig.photos || photoConfig.url)) return;
    var type = typeof photoConfig.photos === "object";
    var photos = type ? photoConfig.photos : {};
    var tab = photoConfig.tab || function (pic, layero) {
            top.layer.msg(pic.alt, {
                offset: 't'
            }) //当前图片的一些信息
        };

    if (photoConfig.url !== undefined) {
        $.ajax({
            type: "post",
            url: photoConfig.url,
            dataType: 'json',
            beforeSend: function () {
                load = Site.loading();
            },
            success: function (data) {
                /**
                 *  data 返回格式
                 {
                     "title": "", //相册标题
                     "id": 123, //相册id
                     "start": 0, //初始显示的图片序号，默认0
                     "data": [   //相册包含的图片，数组格式
                         {
                             "alt": "图片名",
                             "pid": 666, //图片id
                             "src": "", //原图地址
                             "thumb": "" //缩略图地址
                         }
                     ]
                 }
                 */

                setTimeout(Site.close(load), 500);
                index = myLayer.photos({
                    photos: data,
                    tab: tab,
                    shade: photoConfig.shade,
                    anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                });
            },
            error: function (data) {
                Site.close(load);
                Site.error('加载失败', true);
            }
        });
    } else if (type) {
        /**
         *  data 返回格式
         {
             "title": "", //相册标题
             "id": 123, //相册id
             "start": 0, //初始显示的图片序号，默认0
             "data": [   //相册包含的图片，数组格式
                 {
                     "alt": "图片名",
                     "pid": 666, //图片id
                     "src": "", //原图地址
                     "thumb": "" //缩略图地址
                 }
             ]
         }
         */
        index = myLayer.photos({
            photos: photos,
            tab: tab,
            shade: photoConfig.shade,
            anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });
    } else {
        /**
         //HTML示例
         <div id="layer-photos-demo" class="layer-photos-demo">
         <img layer-pid="图片id，可以不写" layer-src="大图地址" src="缩略图" alt="图片名">
         <img layer-pid="图片id，可以不写" layer-src="大图地址" src="缩略图" alt="图片名">
         </div>
         */

        index = myLayer.photos({
            photos: photoConfig.photos,
            parent: document,
            tab: tab,
            shade: photoConfig.shade,
            anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });
    }
    return index;
};

/*  */
Site.resizeShowTab = function () {
    if (window.parent) {
        window.parent.$(".layui-show").find("iframe").load();
    } else {
        $(".layui-show").find("iframe").load();
    }
};

/**
 * 获取url中的参数
 * @param name
 * @param url
 * @return {null}
 */
Site.getUrlParam = function (name, url) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (url) {
        r = url.substr(url.indexOf('?')).match(reg);
    }
    if (r != null) return unescape(r[2]);
    return null;
};

/*  */
Site.pjax = function (_url, _type, _data, _dataType, _beforeSend, _success, _error) {
    if (typeof _url !== 'string') {
        return;
    }
    _type = _type || "POST";
    _data = _data || {};
    _dataType = _dataType || {};
    _beforeSend = _beforeSend || function () {
        };
    _success = _success || function () {
        };
    _error = _error || function () {
        };
    $.ajax({
        url: _url,
        type: _type,
        data: _data,
        dataType: _dataType,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-PJAX', 'true');
            if (typeof _beforeSend === 'function') {
                _beforeSend();
            }
        },
        success: function (data) {
            if (typeof _success === 'function') {
                _success(data);
            }
            var title = $('head title').html() || '没有标题';
            var state = {
                title: title,
                url: _url,
                otherkey: {}
            };
            window.history.pushState(state, title, _url);
        },
        error: function (data) {
            if (typeof _error === 'function') {
                _error();
            }
        }
    });
};

/*  */
Site.initPjax = function () {
    if ($.support.pjax) {

        $(document).on('click', 'a[lay-filter^="pjax"]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var container = $(this).closest('[data-pjax-container]');
            if (typeof container !== undefined) {
                var $container = $(container);
                var containerId = $(container).attr('id');
                if (typeof containerId === undefined) {
                    containerId = 'layui_layout';
                    $container.attr('id', containerId);
                }
                var containerSelector = '#' + containerId;
                $.pjax.click(event, {container: containerSelector})
            }
        });

        $(document).on('click', '[lay-pjax="submit"]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var container = $(this).closest('[data-pjax-container]');
            if (typeof container !== undefined) {
                var $container = $(container);
                var containerId = $(container).attr('id');
                if (typeof containerId === undefined) {
                    containerId = 'layui_layout';
                    $container.attr('id', containerId);
                }
                var containerSelector = '#' + containerId;
                $.pjax.click(event, {container: containerSelector})
            }
        });

        $(document).on('submit', '[data-pjax-container] form[lay-pjax-form]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var container = $(this).closest('[data-pjax-container]');
            if (typeof container !== undefined) {
                var $container = $(container);
                var containerId = $(container).attr('id');
                if (typeof containerId === undefined) {
                    containerId = 'dinner-layui-form';
                    $container.attr('id', containerId);
                }
                var containerSelector = '#' + containerId;
                // $.pjax.submit(event, {container: containerSelector});
            }
        });

    }
};

/*  */
Site.addPjax = function (selector, container, options) {
    if ($.support.pjax) {
        $(document).pjax(selector, container, options);
    }
};

/*  */
Site.loadPage = function (pageElement, showTotal) {
    Site.getLayPager(function () {
        var laypage = layui.laypage;
        showTotal = showTotal || false;
        pageElement = pageElement || 'paging';
        layui.use(['laypage', 'layer'], function () {
            var laypage = layui.laypage,
                _page = $('#' + pageElement),
                currentPage = _page.attr('data-currentPage'),
                total = _page.attr('data-total'),
                rows = _page.attr('data-rows'),
                pages = Math.ceil(total / rows);
            laypage({
                curr: currentPage,
                cont: pageElement,
                pages: pages,
                skip: true,
                hash: true,
                jump: function (obj, first) {
                    if (obj.curr != currentPage) {
                        var url = location.href;
                        if (url.indexOf("?") == -1) {
                            location.href = url + "?page=" + obj.curr;
                        } else {
                            var page = Site.getUrlParam('page');
                            if (page) {
                                location.href = url.replace("page=" + page, "page=" + obj.curr);
                            } else {
                                location.href = url.replace("?", "?page=" + obj.curr + "&");
                            }
                        }
                    }
                    if (showTotal && total > 0) {
                        _page.prepend('<span class="page-total"> 数量: ' + (total) + ' </span>');
                    }
                }
            });
        });
    });
};

/**
 * 图片自适应
 * @param img
 * @param full
 * @return {[*,*]}
 */
Site.getAutoPhoto = function (img, full) {
    if (!(img instanceof Image)) {
        return;
    }
    var imgarea = [img.width, img.height];
    var winarea = [$(window).width() - 100, $(window).height() - 100];

    //如果 取全屏返回全屏减少 100 的宽高
    //如果 实际图片的宽或者高比 屏幕大（那么进行缩放）
    if (full) {
        imgarea[0] = winarea[0];
        imgarea[1] = winarea[1];
    } else if (imgarea[0] > winarea[0] || imgarea[1] > winarea[1]) {
        var wh = [imgarea[0] / winarea[0], imgarea[1] / winarea[1]];//取宽度缩放比例、高度缩放比例
        if (wh[0] > wh[1]) {//取缩放比例最大的进行缩放
            imgarea[0] = imgarea[0] / wh[0];
            imgarea[1] = imgarea[1] / wh[0];
        } else if (wh[0] < wh[1]) {
            imgarea[0] = imgarea[0] / wh[1];
            imgarea[1] = imgarea[1] / wh[1];
        }
    }
    return [imgarea[0] + 'px', imgarea[1] + 'px'];
};

/**
 *  //图片预加载
 * @param url
 * @param callback
 * @param error
 * @return {Image}
 */
Site.loadImage = function (url, callback, error) {
    var img = new Image();
    img.src = url;
    if (img.complete) {
        if (typeof callback === "function") {
            callback(img)
        }
    }
    img.onload = function () {
        img.onload = null;
        if (typeof callback === "function") {
            callback(img)
        }
    };
    img.onerror = function (e) {
        img.onerror = null;
        if (typeof callback === "function") {
            error(e);
        }
    };
    return img;
};

/**
 * 单张图片懒加载
 * @param options
 */
Site.lazyImages = function (options) {
    var $config = {
        img: undefined,
        url: null,
        error: null,
        callback: null,
        removeUrl: true
    };
    $config = $.extend($config, options);
    var $this = $($config.img), url = $this.data('url'), $parent = $this.data('target'), status = false;
    if (!url) {
        return;
    }
    var img = new Image(); // 创建一个Image对象，实现图片的预下载

    img.onload = function () {
        if (typeof(img.readyState) == 'undefined') {
            img.readyState = 'undefined';
        }
        //在IE8以及以下版本中需要判断readyState而不是complete
        if ((img.readyState == 'complete' || img.readyState == "loaded") || img.complete) {
            status = true;
        } else {
            img.onreadystatechange(event);
        }
        img.onload = null;
        reImage();
    };

    //当加载出错或者图片不存在
    img.onerror = function () {
        if (typeof $config.error === 'function') {
            $config.error.call();
        } else {
            var _width = 200, _height = 200;
            if ($this.width() < _width) {
                _width = $this.width();
            }
            if ($this.height() < _height) {
                _height = $this.height();
            }
            $this.parent().css({
                position: 'relative',
                display: 'inline-block',
                width: '100%',
                height: '100%',
                background: '#f4f4f4'
            });
            $this.attr('src', Site.config.imageError).css({
                position: 'absolute',
                width: _width,
                height: _height,
                border: '1px solid #e2e2e2',
                borderRadius: '2px',
                left: '50%',
                top: '50%',
                marginLeft: -(_width / 2),
                marginTop: -(_height / 2)
            });
        }
    };

    //当加载状态改变
    img.onreadystatechange = function (e) {
        //此方法只有IE8以及一下版本会调用
    };

    img.src = url;

    if (img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数
        reImage();
        return; // 直接返回，不用再处理onload事件
    }

    function reImage() {
        if (status || img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数
            if (typeof $config.callback === 'function') {
                $config.callback.call(img);
            }
            if (!$parent) {
                if ($this.is('img')) {
                    $this.attr('src', url);
                    if ($config.removeUrl) {
                        $this.removeAttr('data-url');
                    }
                }
            } else {
                $($parent).append("<img src='" + url + " />");
            }
        }
    }
};

/**
 * 图片延迟加载 图片懒加载
 * @param options
 */
Site.lazyLoadScroll = function (options) {
    //配置些参数
    var _config = {
        element: null,
        lazyClass: 'lazy',
        hasLazyClass: 'hasLazy',
        removeUrl: true
    };
    _config = $.extend(_config, options);
    var temp = -1;//用来判断是否是向下滚动（向上滚动就不需要判断延迟加载图片了）
    var type = 0;
    _config.scrollHeight = Site.scrollTop(); // 滚动的高度
    _config.bodyHeight = Site.clientHeight(); // body（页面）可见区域的总高度
    if (_config.element) {
        _config.scrollHeight = Site.scrollTop(_config.element); // 滚动的高度
        _config.bodyHeight = Site.clientTop(_config.element); // body（页面）可见区域的总高度
    } else {
        type = 1;
        _config.element = document;
    }
    $(_config.element).find('img').each(function () {
        var $this = $(this);
        $this.removeClass(_config.lazyClass).addClass(_config.lazyClass);
    });
    $(_config.element).find('img' + '.' + _config.lazyClass).each(function () {
        var $this = $(this);
        var imgTop = Site.offsetTop($this);//（图片纵坐标）
        if (((imgTop - _config.scrollHeight) >= 0 && (imgTop - _config.scrollHeight) <= _config.bodyHeight) || ((imgTop - _config.scrollHeight) <= Site.clientHeight() && type )) {
            Site.lazyImages({img: $this});
            $this.removeClass(_config.lazyClass).removeClass(_config.hasLazyClass).addClass(_config.hasLazyClass);
            if (_config.removeUrl) {
                $this.removeAttr('data-url');
            }
        }
    });
    $(document).on('scroll', _config.element, function (e) {
        if (type) {
            _config.scrollHeight = Site.scrollTop(); // 滚动的高度
            _config.bodyHeight = Site.clientHeight(); // body（页面）可见区域的总高度
        } else {
            _config.scrollHeight = Site.scrollTop(_config.element); // 滚动的高度
            _config.bodyHeight = $(_config.element).height(); //可见区域的总高度
        }
        $(this).find('img' + '.' + _config.lazyClass).each(function () {
            var $this = $(this);
            var imgTop = Site.offsetTop($this);//（图片纵坐标）
            if (temp < _config.scrollHeight) {//为true表示是向下滚动，否则是向上滚动，不需要执行动作。
                if (((imgTop - _config.scrollHeight) <= _config.bodyHeight) || ((imgTop - _config.scrollHeight) <= Site.clientHeight() && type )) {
                    Site.lazyImages({img: $this});
                    $this.removeClass(_config.lazyClass).removeClass(_config.hasLazyClass).addClass(_config.hasLazyClass);
                    if (_config.removeUrl) {
                        $this.removeAttr('data-url');
                    }
                }
                temp = _config.scrollHeight;
            } else {
                if (((imgTop - _config.scrollHeight) >= 0 && (imgTop - _config.scrollHeight) <= _config.bodyHeight) || ((imgTop - _config.scrollHeight) <= Site.clientHeight() && type )) {
                    Site.lazyImages({img: $this});
                    $this.removeClass(_config.lazyClass).removeClass(_config.hasLazyClass).addClass(_config.hasLazyClass);
                    if (_config.removeUrl) {
                        $this.removeAttr('data-url');
                    }
                }
            }
        });
    });
};

/**
 * 图片懒加载
 * @param options
 */
Site.lazyLoad = function (options) {
    //配置些参数
    var _config = {
        element: null,
        eleGroup: [],
        eleTop: null,
        eleHeight: null,
        screenHeight: null,
        visibleHeight: null,
        scrollHeight: null,
        scrolloverHeight: null,
        limitHeight: null
    };
    _config = $.extend(_config, options);
    var _element = _config.element;
    var _eleGroup = _config.eleGroup;

    //没有数据中断
    if (!(_config.element || _eleGroup)) {
        return;
    }

    // 对数据进行初始化
    if (_config.element) {
        var $element = $(_config.element);
        if ($element.is('img')) {
            _eleGroup.push($element);
        } else {
            $element.find('img').each(function () {
                _eleGroup.push($(this));
            });
        }
    }

    _config.screenHeight = _config.screenHeight || Site.clientTop();
    _config.scrolloverHeight = _config.scrolloverHeight || Site.scrollTop();

    if (Site.scrollTop() === 0) {
        _config.limitHeight = _config.scrolloverHeight + _config.screenHeight;
    } else {
        _config.limitHeight = Site.scrollTop() + _config.screenHeight;
    }
    for (var i = 0, j = _config.eleGroup.length; i < j; i++) {
        if (Site.offsetTop(_config.eleGroup[i]) <= limitHeight && _config.eleGroup[i].attr('data-url')) {
            Site.lazyImages({img: _config.eleGroup[i]});
        }
    }
};

/**
 *
 * @param data [{"title":"xxx","desc":"","target":"","url":"","url_icon":""}]
 * @param options
 */
Site.initBanner = function (data, options) {
    if (data === undefined || data.length <= 0) {
        return;
    }
    var imageData = [],
        loadImage = [],
        imageWidth,
        position = 0,
        size = 0,
        index = 0,
        t, $index;
    var $default = {
        banner: "#webBanner",
        bannerBody: "bannerBody",
        bannerPrev: "bannerPrev",
        bannerNext: "bannerNext",
        switchStyle: "switchBtn",
        bannerBottom: "bannerBottom",
        height: "0",
        width: "0",
        playTime: "4000",
        animateTime: "1500",
        barType: "1", //底部类型
        hiddenBar: false,//设置是否隐藏底部导航条
        targetType: "2",
        wideScreen: false,
        autoPlay: false,//自动播放
        interval: 6000,//播放间隔
        barColor: false,//背景色
        hiddenControl: false,//设置隐藏左右切换按钮
        loading: '/static/images/loading.png',//设置懒加载等待图
    };
    var config = $.extend($default, options);
    var $banner = $(config.banner);
    $banner.css({overflow: "hidden"});
    //没有容器，中断
    if ($banner.length <= 0) {
        return;
    }
    if (config.wideScreen) {
        config.width = _width;
        config.height = _height;
    } else {
        var width = $banner.width();
        var height = $banner.height();
        if (config.width <= 0) {
            config.width = width > 0 ? width : _width;
        }
        if (config.height <= 0) {
            config.height = height > 0 ? height : config.width / 1200 * 400;
        }
    }
    imageData = data;
    imageWidth = config.width;
    var barButton = [],//底部导航条
        contentBody = [],  //轮播主体
        content,
        bar = '',
        control = '';
    //渲染HTML
    for (var i = 0; i < imageData.length; i++) {
        var item = '', aPicAttr = '', aDescAttr = '', alt = '', desc = '', btn = '', barDesc = '',icon='',title='';
        if (imageData[i].target !== undefined && imageData[i].target !== '') {
            if (config.targetType == '2') {
                aPicAttr = ' target="_blank" href="' + imageData[i].target + '"';
                aDescAttr = ' target="_blank" href="' + imageData[i].target + '"';
            } else if (config.targetType == '1') {
                aPicAttr = ' target="_blank" href="' + imageData[i].target + '"';
            }
        }
        if (imageData[i].title !== undefined) {
            alt = ' alt="' + imageData[i].title + '"';
            title = ' title="' + imageData[i].title + '"';
        }
        if (imageData[i].desc !== undefined && imageData[i].desc !== '') {
            // desc = '<h3><a hidefocus="true" ' + aDescAttr + ' ><span>' + imageData[i].desc + '</span></a></h3>';
            barDesc = imageData[i].desc;
        }
        if (typeof imageData[i].url_icon !== undefined){
            icon = 'src="'+imageData[i].url_icon+'" ';
        }
        item = '<span class="item">' +
            '<a hidefocus="true" ' + aPicAttr + ' style="width:' + config.width + 'px;height:' + config.height + 'px;" >' +
            '<img class="item-img" '+icon+'data-url="' + imageData[i].url + '" ' + alt + title + ' style="width:' + config.width + 'px;height:' + config.height + 'px;" />' +
            '<div class="layout-loading-container" style="width:' + config.width + 'px;height:' + config.height + 'px;position: relative;">' +
            '<img class="layout-loading" src="'+config.loading+'" style="position:absolute;left:50%;top:50%;margin-left:-40px;margin-top:-40px;width:80px;height:80px;z-index:-1;" />' +
            '</div>' +
            '</a> ' + desc + '</span>';

        btn = '<span data-cid="' + i + '" data-title="' + barDesc + '"></span>';
        if (config.barType == '1') {
            btn = '<span data-cid="' + i + '" lay-text="' + barDesc + '" lay-filter="tooltitle" >' + (i + 1) + '</span>';
        }

        barButton.push(btn);
        contentBody.push(item);
    }
    if (contentBody.length > 0) {
        contentBody.push(contentBody[0]);
    }
    size = contentBody.length;
    if (!config.hiddenControl) {
        control = '<div class="' + config.bannerPrev + ' ' + config.switchStyle + '" style="display: none;"></div> ' +
            '<div class="' + config.bannerNext + ' ' + config.switchStyle + '" style="display: none;"></div> ';
    }
    if (!config.hiddenBar) {
        bar = '<div class="' + config.bannerBottom + '">' + barButton.join("") + '</div>';
    }
    content = '<div class="' + config.bannerBody + '">' + contentBody.join("") + '</div>' + control + bar;
    if (config.wideScreen) {
        $banner.css({
            position: "absolute",
            width: "100%",
            left: '0',
            right: "0"
        }).after('<div style="height: ' + config.height + 'px;visibility: visible;"></div>');
        content = '<div style="position: relative;width:' + config.width + 'px;height:' + config.height + 'px;">' + content + '</div>';
    }
    $banner.html(content).css({width: config.width, height: config.height});
    //======= HTML 渲染结束 ========//

    var $bannerBody = $(config.banner + ' .' + config.bannerBody);
    var $bannerPrev = $(config.banner + ' .' + config.bannerPrev);
    var $bannerNext = $(config.banner + ' .' + config.bannerNext);
    var $bannerBottom = $(config.banner + ' .' + config.bannerBottom);

    /**
     * 初始化
     */
    $bannerBody.css({width: size * imageWidth, left: 0});
    for (var j = 0; j < size; j++) {
        if (j < index) {
            position = -(j * imageWidth);
        } else if (j > index) {
            position = j * imageWidth;
        } else {
            position = 0;
        }
        loadImage.push(false);
        $index = $bannerBody.find('.item').eq(j);
        !$index || $index.stop().css({left: position});
    }
    $bannerBottom.find('span').eq(0).addClass('active');
    load(0);
    load(size - 1);

    if (config.autoPlay) {
        autoShow();
    }
    $banner.hover(function () {
        $bannerPrev.css({display: "flex"});
        $bannerNext.css({display: "flex"});
        clearTimeout(t);
    }, function () {
        if (config.autoPlay) {
            autoShow();
        }
        $bannerPrev.css({display: "none"});
        $bannerNext.css({display: "none"});
    });

    /*
     * *****事件委托，点击下一张图片******
     * */
    $bannerNext.on("click", function () {
        NEXT();
    });

    /*
     * ********事件委托，上一张图片*******
     * */
    $bannerPrev.on("click", function () {
        LAST();
    });

    /*
     * ***** 事件委托，底部导航条点击
     * */
    $bannerBottom.find('span').on('click', function () {
        index = $(this).attr("data-cid");
        MOVE();
    });
    $bannerBottom.find('span').hover(function () {
        index = $(this).attr("data-cid");
        MOVE();
    }, function () {
        index = $(this).attr("data-cid");
        MOVE();
    });

    /*
     * ******自定义方法*****
     *
     */

    //图片懒加载
    function load(k) {
        k = k || index;
        if (loadImage[k] === true) {
            return;
        }
        var img = $bannerBody.find('.item').eq(k).find('img.item-img');
        Site.lazyImages({
            img: img,
            error:function () {
                $bannerBody.find('.item').eq(k).find('.layout-loading-container').remove();
            },
            callback:function () {
                $bannerBody.find('.item').eq(k).find('.layout-loading-container').remove();
            }
        });
        loadImage[k] = true;
    }

    //图片自动播放
    function autoShow() {
        t = setInterval(function () {
            NEXT();
        }, config.interval);
    }

    /*
     * *******下一张图片
     * */
    function NEXT() {
        index++;
        if (index === size) {
            index = 1;
            $bannerBody.css({left: 0});
        }
        MOVE();
    }

    /*
     * ***上一张
     * */
    function LAST() {
        index--;
        if (index === -1) {
            index = size - 2;
            $bannerBody.css({left: -(size - 1) * imageWidth});
        }
        MOVE();
    }

    /**
     * 初始化
     */
    function MOVE() {
        updateBar();
        load();
        $bannerBody.stop().animate({left: -imageWidth * index}, config.animateTime);
        $bannerBody.find('.item').eq(index).removeClass('active').addClass('active').siblings().removeClass('active');
    }

    /**
     * 更新底部导航条
     */
    function updateBar() {
        var i = index
        if (index === (size - 1)) {
            i = 0;
        }
        !$bannerBottom.find('span').eq(i).removeClass('active').addClass('active').siblings().removeClass('active');
    }

};

/**
 * 动态锚点 平滑滚动
 * 通过 offsetTop 获取对象到窗体顶部的距离，然后赋值给 scrollTop，就能实现锚点的功能
 * 需要注意的是，各浏览器下获取 scrollTop 有所差异
 * 将总距离分成很多小段，然后每隔一段时间跳一段
 * @param {object} options {index:"xxx",selector:"xxx",type:true}
 */
Site.jump = function (options) {
    var _config = $.extend({index: 0, selector: ".jump-this", type: true}, options);
    var jump = document.querySelectorAll(_config.selector);
    // 获取需要滚动的距离
    var total = jump[index].offsetTop;
    var distance = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
    var step = total / 50;
    // 平滑滚动，时长500ms，每10ms一跳，共50跳
    if (_config.type !== true) {
        // Chrome
        document.body.scrollTop = total;
        // Firefox
        document.documentElement.scrollTop = total;
        // Safari
        window.pageYOffset = total;
    }
    if (total > distance) {
        smoothDown()
    } else {
        var newTotal = distance - total;
        step = newTotal / 50;
        smoothUp()
    }
    function smoothDown() {
        if (distance < total) {
            distance += step;
            document.body.scrollTop = distance;
            document.documentElement.scrollTop = distance;
            window.pageYOffset = distance;
            setTimeout(smoothDown, 10);
        } else {
            document.body.scrollTop = total;
            document.documentElement.scrollTop = total;
            window.pageYOffset = total
        }
    }

    function smoothUp() {
        if (distance > total) {
            distance -= step;
            document.body.scrollTop = distance;
            document.documentElement.scrollTop = distance;
            window.pageYOffset = distance;
            setTimeout(smoothUp, 10);
        } else {
            document.body.scrollTop = total;
            document.documentElement.scrollTop = total;
            window.pageYOffset = total
        }
    }
};

/**
 * 动态锚点 平滑滚动
 * 将总距离分成很多小段，然后每隔一段时间跳一段
 * @param {Number} distance
 */
Site.jumpAnimate = function (distance) {

};

/**
 * 鼠标滚动
 */
Site.srcoll = function () {

};

/**
 * 阻止浏览器冒泡事件
 */
Site.stopPropagation = function (event) {
    event.stopPropagation();
};

/**
 * 阻止浏览器默认事件
 */
Site.preventDefault = function (event) {
    event.preventDefault();
};

/**
 *
 * @param obj
 * @param type
 * @param func
 * @returns {Array}
 */
Site.addEvent = function (obj, type, func) {
    if (obj.addEventListener) {
        obj.addEventListener(type, func, false);
    } else if (obj.attachEvent) {
        obj.attachEvent('on' + type, func);
    }
};

/**
 * 获取元素的距离页面顶端的距离
 * @param element
 * @returns {String||Number}
 */
Site.offsetTop = function (element) {
    element = element || document.body;
    var that = $(element);
    return that.offset().top;
};

/**
 * 获取滚动条距离页面顶端的距离
 * @param element
 * @returns {String||Number}
 */
Site.scrollTop = function (element) {
    var scrollTop = 0;
    if (element === undefined) {
        scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
    } else {
        scrollTop = $(element).scrollTop();
    }
    return scrollTop;
};

/**
 * 获取元素距离浏览器可见区域顶端的距离
 * @param element
 * @returns {String||Number}
 */
Site.clientTop = function (element) {
    element = element || document.body;
    var that = $(element);
    return that.scrollTop() - Site.offsetTop();
};

/**
 * 获取页面可见高度
 * @param element
 * @returns {String||Number}
 */
Site.clientHeight = function (element) {
    var _height = document.documentElement.clientHeight;//获取页面可见高度
    if (element !== undefined) {
        _height = $(element).height();
    }
    return _height;
};

/**
 * 获取页面可见宽度
 * @param element
 * @returns {String||Number}
 */
Site.clientWidth = function (element) {
    var _width = document.documentElement.clientWidth;//获取页面可见宽度
    if (element !== undefined) {
        _width = $(element).width();
    }
    return _width;
};

/**
 *
 * @param selector //组别
 * @param checked //类别是 选择 或 不选择
 * @returns {Array}
 */
Site.getSelectCheckboxValues = function (selector, checked) {
    selector = selector || '[lay-group="selected"]';
    var values = [];
    if (checked === undefined || checked === true) {
        $('input' + selector + ':checked').each(function () {
            values.push($(this).val());
        });
    } else {
        $('input' + selector + ':not(:checked)').each(function () {
            values.push($(this).val());
        });
    }
    return values;
};

/**
 *
 * @return {*}
 */
Site.getXhr = function () {
    if (window.ActiveXObject) {
        return new ActiveXObject('Microsoft.XMLHTTP');
    } else if (window.XMLHTTPRequest) {
        return new XMLHTTPRequest();
    }
};

/**
 * 获取后台iframe展示的页面 , 或只有一个页面的刷新
 * @return {*}
 */
Site.getTab = function () {
    if (typeof top.window.getActive === 'function') {
        return top.window.getActive();
    } else {
        return top.window;
    }
};

/**
 * 后台iframe展示的页面重新加载
 */
Site.reLoad = function () {
    var active = Site.getTab();
    if (active) {
        active.location.reload();
    }
};

/**
 * 获取特定iframe层的索引
 * @param win
 */
Site.getLayerIndex = function (win) {
    win = win || window;
    var layer = Site.getModule('layer');
    return layer.getFrameIndex(win.name);
};

/**
 * 获取iframe页的DOM
 * @param selector
 * @param index
 */
Site.getLayerIndex = function (selector, index) {
    var layer = Site.getModule('layer');
    return layer.getChildFrame(selector, index);

};

/**
 * 页面加载readyState的五种状态
 * 可以通过用document.onreadystatechange的方法来监听状态改变
 * 0 －'Uninitialized' （未初始化）还没有调用send()方法
 * 1 －'Loading' （载入）已调用send()方法，正在发送请求
 * 2 －'Loaded' （载入完成）send()方法执行完成，已经接收到全部响应内容
 * 3 －'Interactive' （交互）正在解析响应内容
 * 4 －'Completed' （完成）响应内容解析完成，可以在客户端调用了
 */
Site.loadStatus = function (dom) {
    dom = dom || document;

    var handler = function (e) {
        if (e.type === 'readystatechange' && dom.readyState !== 'commplete') {
        }
    };

    if (dom.addEventListener) {
        dom.addEventListener('readystatechange', handler, false);
    } else {
        //兼容IE等不支持addEventListener方法的浏览器
        dom.attachEvent('onreadystatechange', handler);
    }

};

/**
 *
 * @param str
 * @param tag
 * @returns {*}
 */
Site.getTag = function (str, tag) {
    var reg = '/<' + tag + '>(.*?)<\/' + tag + '>/';
    reg = eval(reg);
    var ret = '';
    if (reg.test(str)) {
        ret = reg.exec(str)[1];
    }
    return ret;
};

/**
 *
 * @param str
 * @returns {*}
 */
Site.reconvert = function (str) {
    str = str.replace(/(\\u)(\w{4})/gi, function ($0) {
        return (String.fromCharCode(parseInt((escape($0).replace(/(%5Cu)(\w{4})/g, "$2")), 16)));
    });

    str = str.replace(/(&#x)(\w{4});/gi, function ($0) {
        return String.fromCharCode(parseInt(escape($0).replace(/(%26%23x)(\w{4})(%3B)/g, "$2"), 16));
    });
    return str;
};

/**
 *
 * @param str
 * @returns {*}
 */
Site.toUpperCase = function (str) {
    return str.toUpperCase();
};

/**
 *
 * @param str
 * @returns {*}
 */
Site.toLowerCase = function (str) {
    return str.toLowerCase();
};

/**
 *
 * @param str
 * @returns {*}
 */
Site.toUpper = function (str) {
    return s.toLowerCase().split(/\s+/).map(function (item, index) {
        return item.slice(0, 1).toUpperCase() + item.slice(1);
    }).join(' ');
};

/**
 * //删除左右两端的空格
 * @param str
 */
Site.trim = function (str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
};

/**
 * //删除左边的空格
 * @param str
 */
Site.ltrim = function (str) {
    return str.replace(/(^\s*)/g, "");
};

/**
 * //删除右边的空格
 * @param str
 */
Site.rtrim = function (str) {
    return str.replace(/(\s*$)/g, "");
};

/**
 * //获得对象长度
 * @param jsonData
 * @returns {number}
 */
Site.getJsonLength = function (jsonData) {
    var jsonLength = 0;
    for (var item in jsonData) {
        jsonLength++;
    }
    return jsonLength;
};

/**
 * 获取日期
 * // 内部给Date对象加上自定义属性
 * @param format
 * @param times
 * @returns {string}
 */
Site.date = function (format, times) {
    //给Date对象加上自定义属性
    if (Date.format === undefined) {
        Date.prototype.format = function (format) {
            var o = {
                "M+": this.getMonth() + 1, //month
                "d+": this.getDate(), //day
                "h+": this.getHours(), //hour
                "m+": this.getMinutes(), //minute
                "s+": this.getSeconds(), //second
                "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
                "S": this.getMilliseconds() //millisecond
            };

            if (/(y+)/.test(format)) {
                format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            }

            if (/(Y+)/.test(format)) {
                format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            }

            for (var k in o) {
                if (new RegExp("(" + k + ")").test(format)) {
                    format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
                }
            }
            // var myDate = new Date();
            // myDate.getYear(); //获取当前年份(2位)
            // myDate.getFullYear(); //获取完整的年份(4位,1970-????)
            // myDate.getMonth(); //获取当前月份(0-11,0代表1月)
            // myDate.getDate(); //获取当前日(1-31)
            // myDate.getDay(); //获取当前星期X(0-6,0代表星期天)
            // myDate.getTime(); //获取当前时间(从1970.1.1开始的毫秒数)
            // myDate.getHours(); //获取当前小时数(0-23)
            // myDate.getMinutes(); //获取当前分钟数(0-59)
            // myDate.getSeconds(); //获取当前秒数(0-59)
            // myDate.getMilliseconds(); //获取当前毫秒数(0-999)
            // myDate.toLocaleDateString(); //获取当前日期
            // myDate.toLocaleTimeString(); //获取当前时间
            // myDate.toLocaleString( ); //获取日期与时间
            return format;
        };
    }
    return new Date(times).format(format);
};

Site.compareDate = function (left, right, format) {
    if (left.length > 0 && right.length > 0) {
        var sDate = new Date(left.replace(/-/g, "/"));
        var eDate = new Date(right.replace(/-/g, "/"));
        if (sDate > eDate) {
            return false;
        }
    }
};

/**
 * 手机类型判断
 * @return {{userAgent: string, isAndroid: (*|boolean), isIphone: (*|boolean), isIpad: (*|boolean), isWeixin: (*|boolean)}}
 * @constructor
 */
Site.BrowserInfo = function () {
    return {
        userAgent: navigator.userAgent.toLowerCase(),
        isAndroid: Boolean(navigator.userAgent.match(/android/ig)),
        isIphone: Boolean(navigator.userAgent.match(/iphone|ipod/ig)),
        isIpad: Boolean(navigator.userAgent.match(/ipad/ig)),
        isWeixin: Boolean(navigator.userAgent.match(/MicroMessenger/ig)),
    }
};

/**
 * 返回字符串长度，汉子计数为2
 * @return {number}
 */
Site.strLength = function () {
    var a = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255)
            a += 2;//按照预期计数增加2
        else
            a++;
    }
    return a;
};

/**
 * 字符串截取方法
 * @param charStr
 * @param cutCount
 * @return {string}
 */
Site.getCharactersLen = function (charStr, cutCount) {
    if (charStr == null || charStr == '') return '';
    var totalCount = 0;
    var newStr = '';
    for (var i = 0; i < charStr.length; i++) {
        var c = charStr.charCodeAt(i);
        if (c < 255 && c > 0) {
            totalCount++;
        } else {
            totalCount += 2;
        }
        if (totalCount >= cutCount) {
            newStr += charStr.charAt(i);
            break;
        }
        else {
            newStr += charStr.charAt(i);
        }
    }
    return newStr;
};

/**
 * 获得当前浏览器JS的版本
 * @return {string}
 */
Site.getJSVersion = function () {
    var n = navigator;
    var u = n.userAgent;
    var apn = n.appName;
    var v = n.appVersion;
    var ie = v.indexOf('MSIE ');
    if (ie > 0) {
        apv = parseInt(i = v.substring(ie + 5));
        if (apv > 3) {
            apv = parseFloat(i);
        }
    } else {
        apv = parseFloat(v);
    }
    var isie = (apn == 'Microsoft Internet Explorer');
    var ismac = (u.indexOf('Mac') >= 0);
    var javascriptVersion = "1.0";
    if (String && String.prototype) {
        javascriptVersion = '1.1';
        if (javascriptVersion.match) {
            javascriptVersion = '1.2';
            var tm = new Date;
            if (tm.setUTCDate) {
                javascriptVersion = '1.3';
                if (isie && ismac && apv >= 5) javascriptVersion = '1.4';
                var pn = 0;
                if (pn.toPrecision) {
                    javascriptVersion = '1.5';
                    a = new Array;
                    if (a.forEach) {
                        javascriptVersion = '1.6';
                        i = 0;
                        o = new Object;
                        tcf = new Function('o', 'var e,i=0;try{i=new Iterator(o)}catch(e){}return i');
                        i = tcf(o);
                        if (i && i.next) {
                            javascriptVersion = '1.7';
                        }
                    }
                }
            }
        }
    }
    return javascriptVersion;
};

/**
 * 判断是否是 IE 浏览器
 */
Site.isIE = function () {
    if (document.all) {
        alert('IE浏览器');
    } else {
        alert('非IE浏览器');
    }
    if (!!window.ActiveXObject) {
        alert('IE浏览器');
    } else {
        alert('非IE浏览器');
    }
    //判断是IE几
    var isIE = !!window.ActiveXObject;
    var isIE6 = isIE && !window.XMLHttpRequest;
    var isIE8 = isIE && !!document.documentMode;
    var isIE7 = isIE && !isIE6 && !isIE8;
    if (isIE) {
        if (isIE6) {
            alert('ie6');
        } else if (isIE8) {
            alert('ie8');
        } else if (isIE7) {
            alert('ie7');
        }
    }
};

/**
 * 判断浏览器
 */
Site.getOs = function () {
    if (navigator.userAgent.indexOf("MSIE 8.0") > 0) {
        return "MSIE8";
    }
    else if (navigator.userAgent.indexOf("MSIE 6.0") > 0) {
        return "MSIE6";
    }
    else if (navigator.userAgent.indexOf("MSIE 7.0") > 0) {
        return "MSIE7";
    }
    else if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
        return "Firefox";
    }
    if (navigator.userAgent.indexOf("Chrome") > 0) {
        return "Chrome";
    }
    else {
        return "Other";
    }
};

/**
 * 回车提交
 * @param selector
 * @param submit
 */
Site.enterSubmit = function (selector, submit) {
    $(selector).onkeypress = function (event) {
        var that = $(this);
        event = (event) ? event : ((window.event) ? window.event : "")
        keyCode = event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode);
        if (keyCode == 13) {
            if (submit) {
                $(submit).onclick();
            } else {
                that.closest('form').submit();
            }
        }
    }
};

/**
 * 初始化 文本框根据输入内容自适应高度 自动适应
 * @param selector
 */
Site.initTextarea = function (selector) {
    selector = selector || '[lay-height="auto"]';
    $(document).off('keyup', selector).on('keyup', selector, function () {
        Site.autoTextarea(this);
    });
};

/**
 * 文本框根据输入内容自适应高度
 * @param elem 输入框元素
 * @param extra 设置光标与输入框保持的距离(默认20)
 * @param maxHeight 设置最大高度(可选)
 */
Site.autoTextarea = function (elem, extra, maxHeight) {
    extra = extra || 20;
    var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
        isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
        addEvent = function (type, callback) {
            elem.addEventListener ?
                elem.addEventListener(type, callback, false) :
                elem.attachEvent('on' + type, callback);
        },
        getStyle = elem.currentStyle ? function (name) {
            var val = elem.currentStyle[name];

            if (name === 'height' && val.search(/px/i) !== 1) {
                var rect = elem.getBoundingClientRect();
                return rect.bottom - rect.top -
                    parseFloat(getStyle('paddingTop')) -
                    parseFloat(getStyle('paddingBottom')) + 'px';
            }
            ;

            return val;
        } : function (name) {
            return getComputedStyle(elem, null)[name];
        },
        minHeight = parseFloat(getStyle('height'));


    elem.style.resize = 'none';

    var change = function () {
        var scrollTop, height,
            padding = 0,
            style = elem.style;

        if (elem._length === elem.value.length) return;
        elem._length = elem.value.length;

        if (!isFirefox && !isOpera) {
            padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
        }
        ;
        scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

        elem.style.height = minHeight + 'px';
        if (elem.scrollHeight > minHeight) {
            if (maxHeight && elem.scrollHeight > maxHeight) {
                height = maxHeight - padding;
                style.overflowY = 'auto';
            } else {
                height = elem.scrollHeight - padding;
                style.overflowY = 'hidden';
            }
            ;
            style.height = height + extra + 'px';
            scrollTop += parseInt(style.height) - elem.currHeight;
            document.body.scrollTop = scrollTop;
            document.documentElement.scrollTop = scrollTop;
            elem.currHeight = parseInt(style.height);
        }
        ;
    };

    addEvent('propertychange', change);
    addEvent('input', change);
    addEvent('focus', change);
    change();
};

/**
 * 自定义 提示信息
 */
Site.initTip = function () {
    $(document).off('click', '[lay-filter="tooltip"]',showTip).on('click', '[lay-filter="tooltip"]', function (e) {
        showTip(e);
    });
    $(document).off('click',hideTip).on('click',function (e) {
        hideTip(e);
    });
    $('[lay-text][lay-filter="tooltitle"]',document).hover(showTip,hideTip);
    function showTip(e) {
        var container = $('.layui-tooltip-container .tooltip-body', document);
        if (container.length<1) {
            $('body', document).append('<div class="layui-tooltip-container" style="z-index: '+index+';"><div class="tooltip-body"></div></div>');
            container = $('.layui-tooltip-container .tooltip-body', document);
        }
        var that = $(e.target),
            arrow = that.attr('lay-arrow') || 'auto',
            x = e.clientX, y = e.clientY,
            text = that.attr('lay-text') || that.text(),
            index = that.attr('lay-index');
        var tip = container.find('.tooltip[lay-index="' + index + '"]');
        if (tip.length<1) {
            index = ++zIndex;
            that.attr('lay-index',index);
            if (arrow == 'auto'){
                var _x = _width - x,_y = _height - y;
                var horizontal = x > _x ? 'left' : 'right';
                var vertical = y > _y ? 'top' : 'bottom';
                arrow = vertical + '-' + horizontal;
                that.attr('lay-arrow',arrow);
            }
            var html = '<div class="tooltip" lay-arrow="' + arrow + '" lay-index="' + index + '" style="display: none;z-index: '+index+';">' +
                '<div class="tooltip-arrow"></div>' +
                '<div class="tooltip-inner"></div></div>';
            container.append(html);
            tip = container.find('.tooltip[lay-index="' + index + '"]');
        }
        tip.find('.tooltip-inner').html(text);
        var width = tip.width(), height = tip.height(),left = x,top = y;

        switch (arrow){
            case 'in':{
                left = x - (width/2);
                top = y-(height/2);
            }break;
            case 'top':{
                left = x - (width/2);
                top = y-20-height;
            }break;
            case 'right':{
                left = x+20;
                top = y-(height/2);
            }break;
            case 'bottom':{
                left = x - (width/2);
                top = y+20;
            }break;
            case 'left':{
                left = x-20-width;
                top = y-(height/2);
            }break;
            case 'top-left':{
                left = x-5-width;
                top = y-15-height;
            }break;
            case 'top-right':{
                left = x+5;
                top = y-15-height;
            }break;
            case 'bottom-left':{
                left = x-10-width;
                top = y+30;
            }break;
            case 'bottom-right':{
                left = x+10;
                top = y+30;
            }break;
            default:{
                that.attr('lay-arrow','bottom');
                left = x - (width/2);
                top = y+30;
            }break
        }
        tip.css({left:left,top:top});
        container.find('.tooltip[lay-index="' + index + '"]').show().siblings().hide();
    }
    function hideTip(e) {
        var target = $(e.target);
        if(target.closest('.layui-tooltip-container').length<1 && target.attr('lay-filter') != 'tooltip'){
            $('.layui-tooltip-container .tooltip').hide();
        }
    }
};

/**
 * JS 写Cookie
 * @param name
 * @param value
 * @param expires
 * @param path
 * @param domain
 */
Site.setCookie = function (name, value, expires, path, domain) {
    if (!expires) expires = -1;
    if (!path) path = "/";
    var d = "" + name + "=" + value;
    var e;
    if (expires < 0) {
        e = "";
    }
    else if (expires == 0) {
        var f = new Date(1970, 1, 1);
        e = ";expires=" + f.toUTCString();
    }
    else {
        var now = new Date();
        var f = new Date(now.getTime() + expires * 1000);
        e = ";expires=" + f.toUTCString();
    }
    var dm;
    if (!domain) {
        dm = "";
    }
    else {
        dm = ";domain=" + domain;
    }
    top.window.document.cookie = name + "=" + value + ";path=" + path + e + dm;
};

/**
 * JS 读Cookie
 * @param name
 * @return {*}
 */
Site.getCookie = function (name) {
    var nameEQ = name + "=";
    var ca = top.window.document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) {
            return decodeURIComponent(c.substring(nameEQ.length, c.length))
        }
    }
    return null
};

/**
 * 下载
 * @param url
 * @return {*}
 */
Site.download = function (url) {
    var form = $("form#download-form");   //定义一个form表单
    if (!form.length > 0) {
        $('html body', document).append('<form id="download-form" class="_new"></form>');
        form = $("form#download-form._new");
    }
    form.attr('style', 'display:none');   //在form表单中添加查询参数
    form.attr('target', '_blank');
    form.attr('method', 'POST');
    form.attr('action', url);
    form.submit();
};

/**
 * Ajax 中间件
 * @param options
 * @param middleWare
 */
Site.ajax = function (options, middleWare) {
    var deferred = $.Deferred();
    var index;
    var $options = $.extend({
        url: null,
        data: {},
        dataType: 'json',
        type: 'post',
        beforeSend: function () {
            index = Site.loading();
        }
    }, options);

    $.ajax($options).success(function (data) {
        Site.close(index);
        if (data.code != 200) {
            deferred.reject(data.msg || data.info);
        } else {
            deferred.resolve(data.data)
        }
    }).error(function (error) {
        Site.close(index);
        deferred.reject('请求出错');
    });

    // 添加中间件
    if (!middleWare || typeof middleWare !== "function") {
        middleWare = function () {
        };
    }
    return deferred.done(middleWare).fail(function (error) {
        Site.msg(error);
    });
};


var uploaderIndex = 1;
var uploader = [];
/**
 * 上传
 * @param options
 * @returns {number}
 */
Site.uploader = function (options) {
    var index = uploaderIndex;
    uploaderIndex++;
    options = $.extend({
        elem: '.layui-upload-file',
        url: '/manage/ajax/uploader',
        isAjax: true,
        before: null,  // 上传成功后的回调函数,参数 input 表单
        success: null, // 上传成功后的回调函数,参数res代表后天返回的数据，input是文件input 表单
    }, options);

    layui.config({
        base: Site.config.layuiBase
    }).use(['uploader'], function () {
        uploader[uploaderIndex] = layui.uploader(options);
    });

    return index;
};

/**
 * 获取上传器
 * @param index
 * @returns {*|undefined}
 */
Site.getUploader = function (index) {
    return uploader[index] || undefined;
};

/**
 * 搜索
 * @param options
 */
Site.search = function (options) {
    options = $.extend({
        url: undefined,                  // 搜索地址
        targetClass: undefined,          // 输入框目标元素
        parentClass: undefined,          // 父级类
        hiddenClass: undefined,          // 隐藏域input
        key: 'name'
    }, options);

    if (!options.url || !options.targetClass || !options.parentClass || !options.hiddenClass) {
        return;
    }
    layui.config({
        base: Site.config.layuiBase
    }).use(['search'], function () {
        var search = layui.search(options);
    });
};

/**
 * 提交表单
 * @param _options
 */
Site.submit = function (_options) {
    var options = $.extend(
        {
            form: 'form[action]',  // form 提交 对应的选择器
            url: undefined,  // form 提交地址
            submit: 'submit',  // 提交选择器
            success: undefined, // 提交成功执行回调,参数是返回数据
            isClose: true, // 提交成功后关闭弹出层
            isReload: false, // 提交成功后刷新当前窗口
            verify: {} // 自定义验证规则
        }, _options);
    layui.use(['layer', 'forms'], function () {
        var layer = top.layui.layer || layui.layer;

        //绑定form提交
        var forms = layui.forms().create({ELEM: options.form});

        //自定义验证规则
        forms.verify(options.verify);

        //监听提交
        forms.on('submit(' + options.submit + ')', function (data) {
            var param = $(data.form).serialize(), $form = $(data.form), url = options.url || $form.attr('action'),
                index;
            if (url === undefined || url === '') {
                //没有提交地址 中断
                url = window.location.href;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: param,
                beforeSend: function () {
                    index = layer.load(1, {shade: 0.1});
                },
                success: function (data) {
                    layer.close(index);
                    if (data.msg !== undefined || data.info !== undefined) {
                        layer.msg(data.msg || data.info);
                    }
                    if (data.code == '1' || data.status == '1') {
                        if (typeof options.success === 'function') {
                            options.success(data);
                        }

                        if (options.isClose) {
                            layer.close(layer.getFrameIndex(window.name));
                        }

                        if (options.isReload) {
                            Site.reLoad();
                        }

                    }
                },
                error: function (data) {
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
 * 防止网页被嵌入框架 （Frame）代码
 */
Site.isHostWindow = function () {
    // 判断当前的window对象是否是top对象
    if (window != top) {
        // 如果不是，将top对象的网址自动导向被嵌入网页的网址
        top.location.href = window.location.href;
    }
};

/**
 * 防止网页被嵌入框架 （Frame）代码(自己域名能嵌套)
 */
Site.isHostNameWindow = function () {
    try {
        top.location.hostname;
        if (top.location.hostname != window.location.hostname) {
            top.location.href = window.location.href;
        }
    } catch (e) {
        top.location.href = window.location.href;
    }
};

$(function () {

    Site.init();

    if (typeof top.window.addTab === 'function') {
        $(document).on('click', '[lay-filter="url"]', function () {
            top.window.addTab(this);
        });
    }

    Site.initTip();

    Site.initTextarea();


    setTimeout(function () {
        $('[lay-filter="loading"]').each(function () {
            Site.imgLoading(this);
        });
    }, 200);

    window.addEventListener('popstate', function (e) {
        if (history.state) {
            var state = e.state;
            //do something(state.url, state.title);
        }
    }, false);

    Site.initPjax();

    Site.lazyLoadScroll();

    // Site.wait('Most people can not see this message, please share with us! <br> 【God is a girl, please treat her as a lover】');
});


function isArray(value) {
    return Object.prototype.toString.call(value) === "[object Array]";
}

function isFunction(value) {
    return Object.prototype.toString.call(value) === "[object Function]";
}

function isRegExp(value) {
    return Object.prototype.toString.call(value) === "[object RegExp]";
}

function isNativeJSON() {
    return window.JSON && Object.prototype.toString.call(JSON) === "[object JSON]";
}

/*
 定义类 时 需要注意 作用域 安全的构造函数
 不安全的因素：由于this是运行时分配，如使用 new 指令实例化类 this会指向 实例化对象；
 如果直接调用构造函数 那么 this 会指向全局 对象 window ，
 然后你的代码就会覆盖window原生的同名函数或属性，埋下bug;
 推荐使用安全方式编写类或函数
 */
function Helper(name) {
    if (this instanceof Helper) {
        this.name = name;
    } else {
        return new Helper(name);
    }
}

// 浏览器兼容函数编写规范
// 使用 call 或 apply 来继承
function X() {
    if (A) {
        A.call(X); // 如果存在A 让 X 继承 A
    } else if (B) {
        B.call(X); // 如果存在B 让 X 继承 B
    } else {
        throw new Error('no A or B');
    }
    return new X();
}

/*
 使用 Object.preventExtensions()  来声明 对象 是 不可扩展对象;
 var person = {name:'wdd'};
 undefined
 Object.preventExtensions(person);
 Object {name: "wdd"}
 person.age = 10;
 10
 person
 Object {name: "wdd"}
 Object.isExtensible(person);
 false
 */

/*
 密封对象 Object.seal 密封对象不可扩展，并且不能删除对象的属性或方法，但是属性值可以修改
 */

/*
 冰冻对象 Object.freeze 冰冻对象不可扩展，并且不能修改，只可访问
 */

/*
 函数节流 思想：某些代码不可以没有间断的连续重复执行 （即反复执行）
 */
var processor = {
    timeoutId: null,
    //实际进行处理的方法
    performProcessing: function () {
        console.log('重复执行中...');
    },
    //初始化调用方法
    process: function () {
        clearTimeout(this.timeoutId);
        var that = this;
        this.timeoutId = setTimeout(function () {
            that.performProcessing();
        }, 100);
    }
};

//尝试开启执行
// processor.process();

/*
 text 转 数组
 */
var toArray = function (data) {
    return eval('(' + data + ')');
};

/**
 * 动态规划求解
 * @param num
 * @returns {number}
 */
// var getClimbingWays = function (num) {
//   if (num<1){
//       return 0;
//   }
//   if (num === 1){
//       return 1;
//   }
//   if (num ===21){
//       return 2;
//   }
//   var a = 1;
//   var b = 2;
//   var temp = 0;
//
//   for(var i=3;i<=num;i++){
//       temp = a + b;
//       a = b;
//       b = temp;
//   }
//
//   return temp;
// };

/*
 中央定时器
 */

var timers = {
    timerId: 0,
    timers: [],
    add: function (fn) {
        this.timers.push(fn);
    },
    start: function () {
        if (this.timerId) {
            return;
        }
        (function runNext() {
            if (timers.timers.length > 0) {
                for (var i = 0; i < timers.timers.length; i++) {
                    if (timers.timers[i]() === false) {
                        timers.timers.splice(i, 1);
                        i--;
                    }
                }
                timers.timerId = setTimeout(runNext, 16);
            }
        })();
    }
};

/*
 AJAX 进度条
 */

function progress() {
    var myXhr = $.ajaxSettings.xhr();
    if (myXhr.upload) {
        myXhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                $('progress').attr({
                    value: e.loaded,
                    max: e.total,
                });
            }
        }, false);
    }
    return myXhr;
}

/*
 AJAX 跨域
 */
function ajaxJump(url, _Callback) {
    $.ajax({
        url: url,
        dataType: 'jsonp',
        processData: false,
        type: 'get',
        jsonpCallback: _Callback,
        success: function (data) {
            console.log(data);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest.status);
            console.log(XMLHttpRequest.readyState);
            console.log(textStatus);
        }
    });
}

/*
 类型
 */
// console.log(typeof undefined); //输出 undefined
// console.log(typeof null); //输出 object
// console.log(typeof true); //输出 boolean
// console.log(typeof 42); //输出 number
// console.log(typeof "42du"); //输出 string
// console.log(typeof new Object()); //输出 object
// console.log(typeof function(){}); //输出 function

//
//        layer.open({
//            type: 2,
//            content: 'http://www.alivehouse.com/manage',
//            success: function(layero, index){
//                // 在父窗口中获取iframe中的元素
//                // 格式 $("#iframe的ID").contents().find("#iframe中的控件ID")
//                // 格式 $('#父窗口中的元素ID', parent.document)
////                $('#'+ doms[0] + index).find('iframe').contents().find(selector);
//                var body = layer.getChildFrame('body', index); //当你试图在当前页获取iframe页的DOM元素时，你可以用此方法。selector即iframe页的选择器
//                var iframeWin = window[layero.find('iframe')[0]['name']]; //得到iframe页的窗口对象，执行iframe页的方法：
//                var _index = layer.getFrameIndex(iframeWin.name);  //重新获取iframe索引
////                layer.iframeAuto(index); // 指定iframe层自适应 ，调用该方法时，iframe层的高度会重新进行适应
////                layer.iframeSrc(index, url) //重置特定iframe url
////                layer.setTop(layero); //置顶当前窗口
////                layer.title(name, index); // 改变title
////                layer.full(index);  //全屏
////                layer.min(index);  //最小化
////                layer.restore(index)  //还原
//            }
//        });

//对字符串处理的方法
String.prototype.trim = function (str) {//删除左右两端的空格
    str = str || "";
    return this.replace(/(^\s*)|(\s*$)/g, '');
};
String.prototype.ltrim = function (str) {//删除左边的空格
    str = str || "";
    return this.replace(/(^\s*)/g, '');
};
String.prototype.rtrim = function (str) {//删除右边的空格
    str = str || "";
    return this.replace(/(\s*$)/g, '');
};