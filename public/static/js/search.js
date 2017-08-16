/**
 * 查询 组件
 * @param options
 * 1. 当前的input add targetClass
 * 2. 隐藏域里面统一增加同类名 叫 hiddenClass 必需有属性 lay-search="refresh"
 * 3. 在各个父级元素上 添加类名 parentClass
 * @constructor
 */

//例子

//html
/*
 <div class="layui-input-block">
 <input type="hidden" name="seeItem" value="{" class="layui-input" lay-search="refresh" lay-filter="build" />
 <input type="text" value="{" placeholder="提示"  class="layui-input" lay-filter="buildKey" />
 </div>
 */
//使用

/*
 var auto = layui.use(['search'],function(export){
 var search = layui.search({
 url: 'http://www.xxx.com',
 targetClass: '[lay-filter="buildKey"]',        // 输入框目标元素
 parentClass: '.layui-input-block',             // 父级类
 hiddenClass: '[lay-filter="build"]',             // 隐藏域input
 key: 'name'          // 请求参数键
 });
 });
 */

layui.define('jquery', function (exports) {
    "use strict";

    var $ = layui.jquery,
        MOD_NAME = 'search',
        Search = function (options) {
            if (this instanceof Search) {
                this.config = {
                    targetClass: '',          // 输入框目标元素
                    parentClass: '',          // 父级类
                    hiddenClass: '',          // 隐藏域input
                    searchForm: '',           // form表单
                    hoverBg: 'hoverBg',       // 鼠标移上去的背景
                    outBg: 'outBg',           // 鼠标移下拉的背景
                    hasSelected: false,       // 是否已选择
                    isSelectHide: true,       // 点击下拉框 是否隐藏
                    isCache: true,            // 点击下拉框 是否隐藏
                    isHideTarget: false,      // 点击下拉框 是否隐藏输入框
                    paste: true,              // 禁止 ctrl+v 和 黏贴事件 默认不禁止
                    enter: true,              // 回车选择下拉项是否 阻止默认事件和冒泡事件  默认禁止 （此功能是防止 form 绑定 回车事件）
                    reValue: true,            // 是否恢复上次所选值，上次所选值是以 data-value 属性存放，所以支持初始化该值
                    url: '',                  // url接口
                    key: '',                  // url借口参数默认键值如为空，则取hidden input name，如再空则为 key
                    height: 300,              // 默认为0 不设置的话 那么高度自适应
                    manySelect: false,        // 输入框是否多选 默认false 单选
                    renderHTMLCallback: null, // keyup时 渲染数据后的回调函数
                    callback: null,           // 点击某一项 提供回调
                    closedCallback: null      // 点击输入框某一项x按钮时 回调函数
                };
                this.cache = {
                    currentIndex: -1,
                    oldIndex: -1,
                    data: [],                 // ajax 接收数据
                    itemVal: null,            // 被选中的值
                    inputArrs: []             // 多选时候 输入框值放到数组里面去
                };
                this.init(options);
            } else {
                return new Search(options);
            }
        };

    Search.prototype = {

        constructor: Search,
        set: function (options) {
            var that = this;
            this.config = $.extend(this.config, options || {});
            return that;
        },
        init: function (options) {

            this.config = $.extend(this.config, options || {});
            var self = this,
                _config = self.config,
                _cache = self.cache;
            if (_config.key === ''){
                _config.key = $(_config.targetClass).attr('name') || 'key';
            }
            // 鼠标点击输入框时候
            $(_config.targetClass).each(function (index, item) {

                /*
                 *  禁止 ctrl+v 和 黏贴事件
                 */
                if (!_config.paste){
                    $(item).unbind('paste');
                    $(item).bind('paste', function (e) {
                        e.preventDefault();
                        var target = e.target,
                            targetParent = $(target).closest(_config.parentClass);
                        $(this).val('');
                        $(_config.hiddenClass, targetParent) && $(_config.hiddenClass, targetParent).val('');
                    });
                }

                $(item).keyup(function (e) {
                    _cache.inputArrs = [];
                    var targetVal = $.trim($(this).val()),
                        keyCode = e.keyCode,
                        elemHeight = $(this).outerHeight(),
                        elemWidth = $(this).outerWidth();

                    // 如果输入框值为空的话 那么隐藏域的value清空掉
                    if (targetVal == '') {
                        var curParents = $(this).closest(_config.parentClass);
                        $(_config.hiddenClass, curParents).val('');
                    }
                    var targetParent = $(this).parent();
                    $(targetParent).css({'position': 'relative'});

                    if ($('.auto-tips', targetParent).length == 0) {
                        // 初始化时候 动态创建下拉框容器
                        $(targetParent).append($('<div class="auto-tips hidden"></div>'));
                        $('.auto-tips', targetParent).css({
                            'position': 'absolute',
                            'top': elemHeight+2,
                            'left': '0px',
                            'z-index': 999,
                            'width': elemWidth-2,
                            'border': '1px solid #ccc'
                        });
                    }


                    var curIndex = self._keyCode(keyCode);
                    if (curIndex > -1) {
                        self._keyUpAndDown(targetVal, e, targetParent);
                    } else {
                        if (targetVal != '') {
                            self._doPostAction(targetVal, targetParent);
                        }

                    }
                });

                // 失去焦点时 如果没有点击 或者上下移时候 直接输入 那么当前输入框值情况 隐藏域值情况
                $(item).blur(function (e) {
                    var target = e.target,
                        targetParent = $(target).closest(_config.parentClass);
                    if ($(this).attr('up') || $(this).attr('down')) {
                        return;
                    } else {
                        var targetValue,hiddenValue;
                        targetValue = $(this).attr('data-value');
                        hiddenValue = $(_config.hiddenClass, targetParent).attr('data-value');
                        if (!($(this).attr('readOnly') !== undefined || $(this).attr('disabled') !== undefined || _config.hasSelected)){
                            if (targetValue && _config.reValue){
                                $(this).val(targetValue);
                            }else {
                                $(this).val('');
                            }
                            if (hiddenValue && _config.reValue){
                                $(_config.hiddenClass, targetParent).val(hiddenValue);
                            }else {
                                $(_config.hiddenClass, targetParent).val('');
                            }
                        }
                        if (_config.hasSelected){
                            if (targetValue && _config.reValue){
                                $(this).val(targetValue);
                            }else {
                                $(this).val('');
                            }
                            if (hiddenValue && _config.reValue){
                                $(_config.hiddenClass, targetParent).val(hiddenValue);
                            }else {
                                $(_config.hiddenClass, targetParent).val('');
                            }
                        }
                    }
                });

            });
            // 阻止form表单默认enter键提交
            $(_config.searchForm).each(function (index, item) {
                $(item).keydown(function (e) {
                    var keyCode = e.keyCode;
                    if (keyCode == 13) {
                        return false;
                    }
                });
            });
            // 点击文档
            $(document).click(function (e) {
                e.stopPropagation();
                var target = e.target,
                    tagParent = $(target).parent(),
                    attr = $(target, tagParent).closest('.auto-tips');
                var tagCls = _config.targetClass.replace(/^\./, '');
                if (attr.length > 0 || $(target, tagParent).hasClass(tagCls)) {
                    return;
                } else {
                    $('.auto-tips').each(function (index, item) {
                        if(_config.isSelectHide){
                            !$(item, tagParent).hasClass('hidden') && $(item, tagParent).addClass('hidden');
                        }
                    });

                }
            });

            var stylesheet = '.auto-tips { margin: 0;left: 0;top: 40px;z-index: 999;max-width: 100%;border: 1px solid #d2d2d2; min-height: 36px; max-height: 300px;  list-style: none;  padding: 0;  position: absolute;  background: #fff !important;  overflow-y: auto;  }' +
                '.auto-tips p {overflow: hidden:margin: 1px 0;padding: 5px 5px 5px 10px;border-bottom: 1px solid #e7e7e7;color: #666;text-decoration: none;line-height: 24px;white-space: nowrap;cursor: pointer;zoom: 1;}' +
                '.auto-tips p.noResult{color: #999;}' +
                '.auto-tips p img{ vertical-align:middle;float:left;}' +
                '.create-input{display:none;line-height:26px,padding-left:3px;}' +
                '.create-input span{margin-top:1px;height:24px;float:left;}' +
                '.create-input span i,.auto-tips span a{font-style:normal;float:left;cursor:default;}' +
                '.create-input span a{padding:0 8px 0 3px;cursor:pointer;}' +
                '.auto-tips p.hoverBg {background-color: #669cb6;color: #fff;cursor: pointer;}' +
                '.hidden {display:none;}';

            this._addStyleSheet(stylesheet);

        },
        /**
         * 键盘上下键操作
         */
        _keyUpAndDown: function (targetVal, e, targetParent) {
            var self = this,
                _cache = self.cache,
                _config = self.config,
                percentage = 0.001;
            // 如果请求成功后 返回了数据(根据元素的长度来判断) 执行以下操作
            if ($('.auto-tips p', targetParent) && $('.auto-tips p', targetParent).length > 0) {
                var plen = $('.auto-tips p', targetParent).length,
                    keyCode = e.keyCode;
                _cache.oldIndex = _cache.currentIndex;
                var curVal = '',embId = '',_index = -1, pCls;
                // 上移操作
                if (keyCode == 38) {
                    if (_cache.currentIndex == -1) {
                        _cache.currentIndex = plen - 1;
                    } else {
                        _cache.currentIndex = _cache.currentIndex - 1;
                        if (_cache.currentIndex < 0) {
                            _cache.currentIndex = plen - 1;
                        }
                    }
                    if (_cache.currentIndex !== -1) {
                        if (_cache.currentIndex > 2 ){
                            percentage = (_cache.currentIndex+3)/plen;
                        }
                        self._scrollAdjust($('.auto-tips'),percentage*_config.height);
                        !$('.auto-tips .p-index' + _cache.currentIndex, targetParent).hasClass(_config.hoverBg) &&
                        $('.auto-tips .p-index' + _cache.currentIndex, targetParent).addClass(_config.hoverBg).siblings().removeClass(_config.hoverBg);
                        curVal = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('data-html');
                        embId = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('embId');
                        _index = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('data-index');

                        // 判断是否是多选操作 多选操作 暂留接口
                        if (_config.manySelect) {
                            _cache.inputArrs.push(curVal);
                            _cache.inputArrs = self._unique(_cache.inputArrs);
                            self._manySelect(targetParent);
                        } else {
                            $(_config.targetClass, targetParent).val(curVal).attr('data-value',curVal);
                            // 上移操作增加一个属性 当失去焦点时候 判断有没有这个属性
                            if (!$(_config.targetClass, targetParent).attr('up')) {
                                $(_config.targetClass, targetParent).attr('up', 'true');
                            }

                            pCls = $(_config.targetClass, targetParent).closest(_config.parentClass);
                            $(_config.hiddenClass, pCls).val(embId).attr('data-value',embId);
                            self._createDiv(targetParent, curVal);
                            self._closed(targetParent);
                            // hover
                            self._hover(targetParent);
                        }
                        if(!_config.hasSelected){
                            _config.hasSelected = true;
                        }
                        _config.callback && $.isFunction(_config.callback) && _config.callback(_cache.data[_index]);

                    }
                } else if (keyCode == 40) { //下移操作
                    if (_cache.currentIndex == plen - 1) {
                        _cache.currentIndex = 0;
                    } else {
                        _cache.currentIndex++;
                        if (_cache.currentIndex > plen - 1) {
                            _cache.currentIndex = 0;
                        }
                    }
                    if (_cache.currentIndex !== -1) {
                        if (_cache.currentIndex > 2 ){
                            percentage = (_cache.currentIndex+3)/plen;
                        }
                        self._scrollAdjust($('.auto-tips'),percentage*_config.height);
                        !$('.auto-tips .p-index' + _cache.currentIndex, targetParent).hasClass(_config.hoverBg) &&
                        $('.auto-tips .p-index' + _cache.currentIndex, targetParent).addClass(_config.hoverBg).siblings().removeClass(_config.hoverBg);
                        curVal = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('data-html');
                        embId = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('embId');
                        _index = $('.auto-tips .p-index' + _cache.currentIndex, targetParent).attr('data-index');

                        // 判断是否是多选操作 多选操作 暂留接口
                        if (_config.manySelect) {
                            _cache.inputArrs.push(curVal);
                            _cache.inputArrs = self._unique(_cache.inputArrs);
                            self._manySelect(targetParent);
                        } else {
                            $(_config.targetClass, targetParent).val(curVal).attr('data-value',curVal);

                            // 下移操作增加一个属性 当失去焦点时候 判断有没有这个属性
                            if (!$(_config.targetClass, targetParent).attr('down')) {
                                $(_config.targetClass, targetParent).attr('down', 'true');
                            }
                            pCls = $(_config.targetClass, targetParent).closest(_config.parentClass);
                            $(_config.hiddenClass, pCls).val(embId).attr('data-value',embId);
                            self._createDiv(targetParent, curVal);
                            self._closed(targetParent);
                            // hover
                            self._hover(targetParent);
                        }
                        if(!_config.hasSelected){
                            _config.hasSelected = true;
                        }
                        _config.callback && $.isFunction(_config.callback) && _config.callback(_cache.data[_index]);
                    }
                } else if (keyCode == 13) { //回车操作
                    curVal = $('.auto-tips .p-index' + _cache.oldIndex, targetParent).attr('data-html');
                    embId = $('.auto-tips .p-index' + _cache.oldIndex, targetParent).attr('embId');
                    _index = $('.auto-tips .p-index' + _cache.oldIndex, targetParent).attr('data-index');
                    $(_config.targetClass, targetParent).val(curVal).attr('data-value',curVal);
                    $(_config.hiddenClass, targetParent).val(embId).attr('data-value',embId);
                    if (_config.isSelectHide) {
                        !$(".auto-tips", targetParent).hasClass('hidden') && $(".auto-tips", targetParent).addClass('hidden');
                    }
                    _cache.currentIndex = -1;
                    _cache.oldIndex = -1;
                    if(!_config.hasSelected){
                        _config.hasSelected = true;
                    }
                    _config.callback && $.isFunction(_config.callback) && _config.callback(_cache.data[_index]);
                    if(_config.enter){
                        return;
                    }
                }
            }
        },
        // 键码判断
        _keyCode: function (code) {
            var arrs = ['17', '18', '38', '40', '37', '39', '33', '34', '35', '46', '36', '13', '45', '44', '145', '19', '20', '9'];
            for (var i = 0, ilen = arrs.length; i < ilen; i++) {
                if (code == arrs[i]) {
                    return i;
                }
            }
            return -1;
        },
        //键盘上下滚动条适应
        _scrollAdjust: function (target,goto) {
            if (!target || !goto){
                return;
            }
            $(target).animate({
                scrollTop: goto
            }, 200);
        },
        _doPostAction: function (targetVal, targetParent) {

            var self = this,
                _cache = self.cache,
                _config = self.config,
                url = _config.url;
            // // 假如返回的数据如下：
            // var results = [{name: '结果1', id: '15',image:''}];
            // self._renderHTML(results, targetParent);
            // self._executeClick(results, targetParent);
            $.get(url+"?"+_config.key+"="+targetVal+"&tamp="+new Date().getTime(),function(data){
                var results = data;
                if(results.length > 0) {
                    self._renderHTML(results,targetParent);
                    self._executeClick(results,targetParent);
                }else {
                    self._renderHTML([],targetParent);
                }
            });

        },
        _renderHTML: function (ret, targetParent) {
            var self = this,
                _config = self.config,
                _cache = self.cache,
                html = '';
            if (ret.length <=0){
                ret = _cache.data;
                if(_config.isCache){
                    ret = [];
                }
            }else {
                self.cache.data = ret;
            }

            for (var i = 0, ilen = ret.length; i < ilen; i += 1) {
                var img = '';
                if (ret[i].image !== undefined){
                    img = '<img src="' + ret[i].image + '" style="margin-right:5px;" height="25" width="25" title="" alt="">';
                }

                html += '<p  data-html = "' + ret[i].name +'" embId="' + ret[i].id + '" data-index="' + i + '" class="p-index' + i + '">' +
                    img +
                    '<span>' + ret[i].name + '</span>' +
                    '</p>';
            }
            // 渲染值到下拉框里面去
            if (ret.length <=0){
                html = '<p class="noResult">没有选项</p>';
            }
            $('.auto-tips', targetParent).html(html);
            $('.auto-tips', targetParent).hasClass('hidden') && $('.auto-tips', targetParent).removeClass('hidden');
            $('.auto-tips p:last', targetParent).css({"border-bottom": 'none'});
            _config.renderHTMLCallback && $.isFunction(_config.renderHTMLCallback) && _config.renderHTMLCallback(ret);
            // 出现滚动条 计算p的长度 * 一项p的高度 是否大于 设置的高度 如是的话 出现滚动条 反之
            var plen = $('.auto-tips p', targetParent).length,
                pheight = $('.auto-tips p', targetParent).height();
            if (_config.height > 0) {
                if (plen * pheight > _config.height) {
                    $('.auto-tips', targetParent).css({'height': _config.height, 'overflow-y': 'auto'});
                } else {
                    $('.auto-tips', targetParent).css({'height': 'auto', 'overflow-y': 'auto'});
                }
            }
        },
        /**
         * 当数据相同的时 点击对应的项时 返回数据
         */
        _executeClick: function (ret, targetParent) {
            var self = this,
                _config = self.config,
                _cache = self.cache;
            if (ret.length <=0){
                ret = _cache.data;
            }
            $('.auto-tips p', targetParent).unbind('click');
            $('.auto-tips p', targetParent).bind('click', function (e) {
                var dataAttr = $(this).attr('data-html'),
                    embId = $(this).attr('embId'),
                    index = $(this).attr('data-index');

                // 判断是否多选
                if (_config.manySelect) {
                    _cache.inputArrs.push(dataAttr);
                    _cache.inputArrs = self._unique(_cache.inputArrs);
                    self._manySelect(targetParent);
                } else {
                    $(_config.targetClass, targetParent).val(dataAttr).attr('data-value',dataAttr);
                    var parentClass = $(_config.targetClass, targetParent).closest(_config.parentClass),
                        hiddenClass = $(_config.hiddenClass, parentClass);
                    $(hiddenClass).val(embId).attr('data-value',embId);
                    self._createDiv(targetParent, dataAttr);
                    // hover
                    self._hover(targetParent);
                    if(_config.isHideTarget){
                        !$(_config.targetClass, targetParent).hasClass('hidden') && $(_config.targetClass, targetParent).addClass('hidden');
                    }
                }
                self._closed(targetParent);
                if (_config.isSelectHide) {
                    !$('.auto-tips', targetParent).hasClass('hidden') && $('.auto-tips', targetParent).addClass('hidden');
                }
                if(!_config.hasSelected){
                    _config.hasSelected = true;
                }
                _config.callback && $.isFunction(_config.callback) && _config.callback(ret[index]);
            });
            // 鼠标移上效果
            $('.auto-tips p', targetParent).hover(function (e) {
                !$(this, targetParent).hasClass(_config.hoverBg) &&
                $(this, targetParent).addClass(_config.hoverBg).siblings().removeClass(_config.hoverBg);
            });
        },
        _hover: function (targetParent) {
            $('.create-input span', targetParent).hover(function () {
                $(this).css({"background": '#ccc', 'padding-left': '0px'});
            }, function () {
                $(this).css({"background": ''});
            });
        },
        // 动态的创建div标签 遮住input输入框
        _createDiv: function (targetParent, dataAttr) {
            var self = this,
                _config = self.config;
            var iscreate = $('.create-input', targetParent);

            // 确保只创建一次div
            if (iscreate.length > 0) {
                $('.create-input', targetParent).remove();
            }
            $(targetParent).prepend($('<div class="create-input"><span><i></i></span></div>'));
            $('.create-input span i', targetParent).html(dataAttr);
            $(_config.targetClass, targetParent).val(dataAttr);
            $('.create-input span', targetParent).append('<a class="alink">X</a>');
            $('.alink', targetParent).css({'float': 'left', 'background': 'none'});
        },
        // X 关闭事件
        _closed: function (targetParent) {
            var self = this,
                _config = self.config;
            /*
             * 点击X 关闭按钮
             * 判断当前输入框有没有up和down属性 有的话 删除掉 否则 什么都不做
             */
            $('.alink', targetParent).click(function () {
                $('.create-input', targetParent) && $('.create-input', targetParent).remove();
                $(_config.targetClass, targetParent) && $(_config.targetClass, targetParent).hasClass('hidden') &&
                $(_config.targetClass, targetParent).removeClass('hidden');
                $(_config.targetClass, targetParent).val('');
                //清空隐藏域的值
                var curParent = $(_config.targetClass, targetParent).closest(_config.parentClass);
                $(_config.hiddenClass, curParent).val('');
                var targetInput = $(_config.targetClass, targetParent);
                if ($(targetInput).attr('up') || $(targetInput).attr('down')) {
                    $(targetInput).attr('up') && $(targetInput).removeAttr('up');
                    $(targetInput).attr('down') && $(targetInput).removeAttr('down');
                }
                _config.closedCallback && $.isFunction(_config.closedCallback) && _config.closedCallback();
            });
        },
        /*
         * 数组去重复
         */
        _unique: function (arrs) {
            var obj = {},
                newArrs = [];
            for (var i = 0, ilen = arrs.length; i < ilen; i++) {
                if (obj[arrs[i]] != 1) {
                    newArrs.push(arrs[i]);
                    obj[arrs[i]] = 1;
                }
            }
            return newArrs;
        },
        /*
         * 输入框多选操作
         */
        _manySelect: function (targetParent) {
            var self = this,
                _config = self.config,
                _cache = self.cache;
            if (_cache.inputArrs.length > 0) {
                $(_config.targetClass, targetParent).val(_cache.inputArrs.join(',')).attr('data-value',_cache.inputArrs.join(','));
            }
        },
        /*
         * 判断是否是string
         */
        _isString: function (str) {
            return Object.prototype.toString.apply(str) === '[object String]';
        },
        /*
         * JS 动态添加css样式
         */
        _addStyleSheet: function (refWin, cssText, id) {

            var self = this;
            if (self._isString(refWin)) {
                id = cssText;
                cssText = refWin;
                refWin = window;
            }
            refWin = $(refWin);
            var doc = document;
            var elem;
            if (id && (id = id.replace('#', ''))) {
                elem = $('#' + id, doc);
            }
            // 仅添加一次，不重复添加
            if (elem) {
                return;
            }
            //elem = $('<style></style>'); 不能这样创建 IE8有bug
            elem = document.createElement("style");
            // 先添加到 DOM 树中，再给 cssText 赋值，否则 css hack 会失效
            $('head', doc).append(elem);

            if (elem.styleSheet) { // IE
                elem.styleSheet.cssText = cssText;
            } else { // W3C
                elem.appendChild(doc.createTextNode(cssText));
            }
        },
        /*
         * 销毁操作 释放内存
         */
        destory: function () {
            var self = this,
                _config = self.config,
                _cache = self.cache;
            _cache.ret = [];
            _cache.currentIndex = 0;
            _cache.oldIndex = 0;
            _cache.inputArrs = [];
            _config.targetClass = null;
        }
    };

    exports(MOD_NAME, function (options) {
        return new Search(options);
    });
});

 
