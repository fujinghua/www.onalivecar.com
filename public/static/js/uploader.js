/*!

 @Title: layui.upload 单文件上传 - 全浏览器兼容版
 @Author: 贤心
 @License：MIT

 */

layui.define('layer', function (exports) {
    "use strict";

    var $ = layui.jquery;
    var layer = top.layui.layer || layui.layer;
    var MOD_NAME = 'uploader';
    var elemDragEnter = 'layui-upload-enter';
    var elemIframe = 'layui-upload-iframe';
    var elemContainer = 'layui-upload-container';
    var elemShowContainer = 'layui-upload-show-container';
    var uploaderAttr = 'lay-uploader';
    var icon = {
        upload:"&#xe608;",
        delete:"&#xe640;"
    };
    // 创建一个XMLHTTPRequest对象
    var xhr = function () {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest;
        } else {
            return new ActiveXObject("Microsoft.XMLHttp");
        }
    }();
    var progressId = 'uploadProgress';
    var progressIndex;
    var stopProgress = false;
    var speed; //速度 单位b
    var total = 0; //总量 单位b

    var msgConf = {
        icon: 2
        , shift: 6
    }, fileType = {
        file: '文件'
        , video: '视频'
        , audio: '音频'
    };

    //上传器索引
    var uploaderIndex = 0;
    var Uploader = function (options) {
        if (this instanceof Uploader) {
            this.options = {
                //指定元素的选择器，默认直接查找class为layui-upload-file的元素
                elem: '.layui-upload-file',
                //指定元素的选择器，默认直接查找class为layui-upload-show的元素，如设置此项，返回json需要有src字典值 表示图片上传成功后的地址
                showElem: '.layui-upload-show',
                //指定每次成功更改图片后，把当前图片真实地址传递到指定的input表单;如有数据，以‘|’分割，如设置此项，返回json需要有src字典值 表示图片上传成功后的地址
                targetElem: undefined,
                //是否使用 gallery 展示
                isShow: true,
                //上传文件的接口
                url: undefined,
                //设置http类型，如：post、get。默认post。也可以直接在input设置lay-method="get"来取代
                method: 'post',
                //执行上传前的回调
                before: undefined,
                //上传成功后的回调,参数有当前上传的数据，res,出发表单input，和全部图片cache
                success: undefined,
                //初始化执数据，res,出发表单input，和全部图片cache
                data: undefined,
                //设定上传的文件类型，也可以直接在input设置lay-type=""来取代；图片类型images。默认，不用设定;普通文件类型 file ;视频文件类型 video; 音频文件类型 audio
                type: undefined,
                //自定义可支持的文件扩展名，也可以直接在input设置lay-ext=""来取代,注意是用|分割
                ext: undefined,
                //自定义自定义文本
                //默认情况下，我们对按钮输出的文本是：上传图片，或上传文件/视频/音频，如果你想定义一个不一样的文本，使用参数title即可，也可以 设置lay-title  来取代
                title: undefined,
                // 提示方法(传递参数是 msg, value 和 item；msg 表示 错误 信息， value 表示 当前文件的值，item 表示文件表单 )
                tip: undefined,
                // 是否多文件上传，默认不是多文件上传；可通过 lay-multi设置多传
                isMulti: false,
                // 是否设置上传上限，默认不设置; 可通过 lay-max设置上限
                max: undefined,
                // 是否 使用 ajax 上传，默认不使用
                isAjax: false,
                // 是否 使用 进度条 ，默认使用
                progress: true,
                //是否不改变input的样式风格。默认false
                unwrap: false
            };
            this.hasUploaded = false;  //是否已上传成功
            this.cache = []; //缓存
            this.input = undefined; //上传的input
            this.index = 0; //上传器索引
            this.imageId = 0; //已经上传图片数
            this.set(options);
            this.init(options);
        } else {
            return new Uploader(options);
        }
    };

    /**
     * 设置接口
     * @param options
     * @returns {Uploader}
     */
    Uploader.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.options, options || {});
        return that;
    };

    /**
     * 初始化渲染
     */
    Uploader.prototype.init = function () {
        var that = this, options = that.options;
        options.elem = options.elem || '.layui-upload-file';
        var body = $('body'), elem = $(options.elem);
        var iframe = $('<iframe id="' + elemIframe + '" class="' + elemIframe + '" name="' + elemIframe + '"></iframe>');

        //插入iframe
        $('#' + elemIframe)[0] || body.append(iframe);

        return elem.each(function (index, item) {
            item = $(item);

            //设置识别
            item.attr(uploaderAttr,index);

            var form = '<form target="' + elemIframe + '" method="' + (options.method || 'post') + '" key="set-mine" enctype="multipart/form-data" action="' + (options.url || '') + '"></form>';

            var type = item.attr('lay-type') || options.type; //获取文件类型

            //是否多传
            if (item.attr('lay-multi') !== undefined || that.options.isMulti){
                item.attr('multiple','multiple');
            }

            //有id识别
            var _id = item.attr('id') || options.elem+'-'+index;
            if (item.attr('id') === undefined){
                item.attr('id',_id);
            }

            //多个文件
            if (that.options.max !== undefined ){
                item.attr('lay-max',that.options.max);
            }

            //包裹ui元素
            if (!options.unwrap) {
                var show = '<div class="layui-box '+elemShowContainer+' '+that.options.showElem+'" style="display:none;border: 1px solid #e6e6e6;border-radius: 2px;padding: 15px;"></div>';
                form = '<div class="layui-box '+elemContainer+' layui-uploader-container'+index+'"><div class="layui-box layui-upload-button">' + form + '<label for="'+_id+'"><span class="layui-upload-icon"><i class="layui-icon">'+icon.upload+'</i>' + (
                        item.attr('lay-title') || options.title || ('上传' + (fileType[type] || '图片') )
                    ) + '</span></label></div>'+(that.options.isShow ? show : '' )+'</div>';
            }

            form = $(form);

            //拖拽支持
            if (!options.unwrap) {
                form.on('dragover', function (e) {
                    e.preventDefault();
                    $(this).addClass(elemDragEnter);
                }).on('dragleave', function () {
                    $(this).removeClass(elemDragEnter);
                }).on('drop', function () {
                    $(this).removeClass(elemDragEnter);
                });
            }

            //如果已经实例化，则移除包裹元素
            if (item.parent('form').attr('target') === elemIframe) {
                if (options.unwrap) {
                    item.unwrap();
                } else {
                    item.parent().next().remove();
                    item.unwrap().unwrap();
                }
            }

            //包裹元素
            item.wrap(form);

            //检查网速
            that.getSpeed();

            //检查浏览器是否支持进度条事件
            that.checkBrowserIsSupportProgressEvent();

            //设置前置缓存
            that.cache[index] = [];

            //初始化数据
            if (that.options.data){
                that.cache[index] = that.options.data[index] || that.options.data;
                that.show(that.cache[index],this);
            }

            if (that.options.isAjax) {
                //触发 Ajax 上传
                item.off('change').on('change', function () {
                    that.input = this;
                    that.ajax(this, type);
                });
            } else {
                //触发 iframe 上传
                item.off('change').on('change', function () {
                    that.input = this;
                    that.action(this, type);
                });
            }
        });
    };

    /**
     * 获取速度
     */
    Uploader.prototype.getSpeed = function () {
        var start = new Date().getTime(), time, size = 165585;
        var img = new Image();
        img.onload = function () {
            time = new Date().getTime() - start;
            speed = Math.floor(size / time);
            img.onload = null;
        };
        img.src = '/static/images/getSpeed.png?time=' + start;
    };

    /**
     * 获取速度
     * @returns {Image}
     */
    Uploader.prototype.getImage = function (src) {
        src = src || '/static/images/not-capture-2.png';
        var img = new Image();
        img.onload = function () {
            img.onload = null;
        };
        img.src = src;
        return img;
    };

    /**
     * 视图预览
     * @returns {Uploader}
     */
    Uploader.prototype.show = function (res,input) {
        var that = this;
        res = res || [];
        if(typeof res === "object"){
            var item = $(input);
            var index = item.attr(uploaderAttr);
            var value = [];
            if(res.length >0 ){
                var max = item.attr('lay-max') || that.options.max;
                if (!that.options.isMulti){
                    item.closest('.layui-upload-button').hide();
                }
                if(max && res.length >= max){
                    item.closest('.layui-upload-button').hide();
                }else if(max && res.length < max){
                    item.closest('.layui-upload-button').show();
                }
                item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer).html('');
                that.imageId = 0;
                for (var k  in res){
                    value.push(res[k].src);
                    var gallery = '<div class="layui-upload-show-item" title="预览图片" style="position:relative;display:inline-block;height:100px;width: 100px;margin:5px;">' +
                        '<img layer-pid="'+(that.imageId++)+'" layer-src="'+res[k].src+'" src="'+res[k].icon+'" alt="'+(res[k].tmp_name || res[k].name)+'" style="height:100px;width: 100px;">' +
                        '<div class="layui-upload-show-item-icon" title="删除图片" style="position:absolute;display:block;bottom: 0;width: 100%;height:0;overflow:hidden;text-align: center;background-color: rgba(0,0,0,0.372);color: #fff;cursor: pointer;line-height: 17px;">' +
                        '<i class="layui-icon">'+icon.delete+'</i>删除图片' +
                        '</div>' +
                        '</div>';
                    item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer).append(gallery).show();
                }

                //监听鼠标滑过
                item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer + ' .layui-upload-show-item').off('mouseover').on('mouseover',function () {
                    $(this).find('.layui-upload-show-item-icon').stop().animate({height: '17'});
                }).off('mouseout').on('mouseout',function () {
                    $(this).find('.layui-upload-show-item-icon').stop().animate({height: '0'});
                });

                //监听删除
                item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer + ' .layui-upload-show-item-icon').off('click').on('click',function (e) {
                    var _imgIndex = $(this).closest('.layui-upload-show-item').find('img').attr('layer-pid');
                    that.delete(_imgIndex,input);
                    e.stopPropagation();
                });

                that.gallery({
                    photos: '.layui-uploader-container'+ index + ' ' + '.'+elemShowContainer,
                    parent: document,
                    tab: function (pic, layero) {
                        top.layer.msg(pic.alt,{
                            offset: 't'
                        }) //当前图片的一些信息
                    },
                    shade:['0.372','#000'],
                    anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
                });
            }else {
                item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer).hide();
                item.closest('.layui-upload-button').show();
            }
            if(that.options.targetElem){
                $(that.options.targetElem).val(value.join('|'));
            }
        }
        return that;
    };


    /**
     * 画廊
     * @param options
     * @param loop
     * @param key
     * @return {*}
     */
    Uploader.prototype.gallery = function (options, loop, key) {
        var that = this;
        var dict = {};
        options = options || {};
        if(!options.photos) return;
        var type = options.photos.constructor === Object;
        var photos = type ? options.photos : {}, data = photos.data || [];
        var start = photos.start || 0;
        dict.imgIndex = (start|0) + 1;

        options.img = options.img || 'img';

        //增加一个查询上限，为了iframe查询
        options.parent = options.parent || document;

        var success = options.success;
        delete options.success;

        if(!type){ //页面直接获取
            var parent = $(options.photos,options.parent), pushData = function(){
                data = [];
                parent.find(options.img).each(function(index){
                    var othis = $(this);
                    othis.attr('layer-index', index);
                    data.push({
                        alt: othis.attr('alt'),
                        pid: othis.attr('layer-pid'),
                        src: othis.attr('layer-src') || othis.attr('src'),
                        thumb: othis.attr('src')
                    });
                });
            };

            pushData();

            if (data.length === 0) return;

            loop || parent.on('click', options.img, function(){
                var othis = $(this), index = othis.attr('layer-index');
                that.gallery($.extend(options, {
                    photos: {
                        start: index,
                        data: data,
                        tab: options.tab
                    },
                    full: options.full
                }), true);
                pushData();
            });

            //不直接弹出
            if(!loop) return;

        } else if (data.length === 0){
            return layer.msg('&#x6CA1;&#x6709;&#x56FE;&#x7247;');
        }

        //上一张
        dict.imgprev = function(key){
            dict.imgIndex--;
            if(dict.imgIndex < 1){
                dict.imgIndex = data.length;
            }
            dict.tabimg(key);
        };

        //下一张
        dict.imgnext = function(key,errorMsg){
            dict.imgIndex++;
            if(dict.imgIndex > data.length){
                dict.imgIndex = 1;
                if (errorMsg) {return}
            }
            dict.tabimg(key);
        };

        //方向键
        dict.keyup = function(event){
            if(!dict.end){
                var code = event.keyCode;
                event.preventDefault();
                if(code === 37){
                    dict.imgprev(true);
                } else if(code === 39) {
                    dict.imgnext(true);
                } else if(code === 27) {
                    layer.close(dict.index);
                }
            }
        };

        //切换
        dict.tabimg = function(key){
            if(data.length <= 1) return;
            photos.start = dict.imgIndex - 1;
            layer.close(dict.index);
            return that.gallery(options, true, key);
        };

        //一些动作
        dict.event = function(){
            dict.bigimg.hover(function(){
                dict.imgsee.show();
            }, function(){
                dict.imgsee.hide();
            });

            dict.bigimg.find('.layui-layer-imgprev').on('click', function(event){
                event.preventDefault();
                dict.imgprev();
            });

            dict.bigimg.find('.layui-layer-imgnext').on('click', function(event){
                event.preventDefault();
                dict.imgnext();
            });

            $(document).on('keyup', dict.keyup);
        };

        //图片预加载
        function loadImage(url, callback, error) {
            var img = new Image();
            img.src = url;
            if(img.complete){
                return callback(img);
            }
            img.onload = function(){
                img.onload = null;
                callback(img);
            };
            img.onerror = function(e){
                img.onerror = null;
                error(e);
            };
        }

        dict.loadi = layer.load(1, {
            shade: 'shade' in options ? false : 0.9,
            scrollbar: false
        });

        loadImage(data[start].src, function(img){
            layer.close(dict.loadi);
            dict.index = layer.open($.extend({
                type: 1,
                id: 'layui-layer-photos',
                area: function(){
                    var imgarea = [img.width, img.height];
                    var winarea = [$(window).width() - 100, $(window).height() - 100];

                    //如果 实际图片的宽或者高比 屏幕大（那么进行缩放）
                    if(!options.full && (imgarea[0]>winarea[0]||imgarea[1]>winarea[1])){
                        var wh = [imgarea[0]/winarea[0],imgarea[1]/winarea[1]];//取宽度缩放比例、高度缩放比例
                        if(wh[0] > wh[1]){//取缩放比例最大的进行缩放
                            imgarea[0] = imgarea[0]/wh[0];
                            imgarea[1] = imgarea[1]/wh[0];
                        } else if(wh[0] < wh[1]){
                            imgarea[0] = imgarea[0]/wh[1];
                            imgarea[1] = imgarea[1]/wh[1];
                        }
                    }

                    return [imgarea[0]+'px', imgarea[1]+'px'];
                }(),
                title: false,
                shade: 0.9,
                shadeClose: true,
                closeBtn: false,
                move: '.layui-layer-phimg img',
                moveType: 1,
                scrollbar: false,
                moveOut: true,
                //anim: Math.random()*5|0,
                isOutAnim: false,
                skin: 'layui-layer-photos',
                content: '<div class="layui-layer-phimg">'
                +'<img src="'+ data[start].src +'" alt="'+ (data[start].alt||'') +'" layer-pid="'+ data[start].pid +'">'
                +'<div class="layui-layer-imgsee">'
                +(data.length > 1 ? '<span class="layui-layer-imguide"><a href="javascript:;" class="layui-layer-iconext layui-layer-imgprev"></a><a href="javascript:;" class="layui-layer-iconext layui-layer-imgnext"></a></span>' : '')
                +'<div class="layui-layer-imgbar" style="display:'+ (key ? 'block' : '') +'"><span class="layui-layer-imgtit"><a href="javascript:;">'+ (data[start].alt||'') +'</a><em>'+ dict.imgIndex +'/'+ data.length +'</em></span></div>'
                +'</div>'
                +'</div>',
                success: function(layero, index){
                    dict.bigimg = layero.find('.layui-layer-phimg');
                    dict.imgsee = layero.find('.layui-layer-imguide,.layui-layer-imgbar');
                    dict.event(layero);
                    options.tab && options.tab(data[start], layero);
                    typeof success === 'function' && success(layero);
                }, end: function(){
                    dict.end = true;
                    $(document).off('keyup', dict.keyup);
                }
            }, options));
        }, function(){
            layer.close(dict.loadi);
            layer.msg('&#x5F53;&#x524D;&#x56FE;&#x7247;&#x5730;&#x5740;&#x5F02;&#x5E38;<br>&#x662F;&#x5426;&#x7EE7;&#x7EED;&#x67E5;&#x770B;&#x4E0B;&#x4E00;&#x5F20;&#xFF1F;', {
                time: 30000,
                btn: ['&#x4E0B;&#x4E00;&#x5F20;', '&#x4E0D;&#x770B;&#x4E86;'],
                yes: function(){
                    data.length > 1 && dict.imgnext(true,true);
                }
            });
        });
    };


    /**
     * 图片删除
     * @returns {Uploader}
     */
    Uploader.prototype.delete = function (_imgIndex,input) {
        var that = this;
        var item = $(input);
        var index = item.attr(uploaderAttr);
        item.closest('.layui-uploader-container'+index).find('.'+elemShowContainer + ' img[layer-pid="'+_imgIndex+'"]').closest('.layui-upload-show-item').remove();
        if(that.cache[index].length>0){
            that.cache[index].splice(_imgIndex,1);
            that.show(that.cache[index],input);
        }
        return that;
    };

    /**
     * 进度条事件
     * @param e
     * @returns {Uploader}
     */
    Uploader.prototype.progress = function (e) {
        var that = this;
        var progressValue = 0;
        //重置停止进度条命令
        stopProgress = false;
        that.showProgress();
        var getSpeed = function () {
            return speed;
        };
        var time = 500, send = 0, start = new Date().getTime(), progress = setInterval(function () {
            if (stopProgress) {
                clearInterval(progress);
                progressValue = 100;
                that.closeProgress();
                that.setProgress(progressValue);
                return false;
            }
            if (speed <= 0) {
                speed = getSpeed();
            }
            if (progressValue >= 95) {
                return false;
            } else if (progressValue >= 0 && progressValue <= 100) {
                send = speed * ((new Date().getTime()) - start);
                progressValue = Math.floor((send / total * 100));
                if (progressValue >= 95) {
                    progressValue = 95;
                }
                if (stopProgress) {
                    progressValue = 100;
                }
                that.setProgress(progressValue);
            }
        }, 500);
        return that;
    };

    /**
     * 显示进度条
     * @returns {Uploader}
     */
    Uploader.prototype.showProgress = function () {
        var that = this;
        var progress = '<div class="layui-progress"><div id="' + progressId + '" class="layui-progress-bar layui-bg-red" lay-percent="0%"></div></div>';
        progressIndex = layer.msg(progress, {
            time: false,
            area: ['400px'],
            shade: ['0.372', '#000'],
            closeBtn: 2,
            end: function (index, layero) {
                layer.close(progressIndex);
                that.stopUpload();
            }
        });
        return that;
    };

    /**
     * 关闭进度条
     * @returns {Uploader}
     */
    Uploader.prototype.closeProgress = function () {
        var that = this;
        layer.close(progressIndex);
        that.stopUpload();
        return that;
    };

    /**
     * 设置进度条
     * @param percent
     * @returns {Uploader}
     */
    Uploader.prototype.setProgress = function (percent) {
        var that = this;
        percent = parseInt(percent);
        if (percent >= 0 && percent <= 100) {
            percent = percent + '%';
        }
        var progress = $('#' + progressId, top.window.document);
        !progress[0] || progress.stop().animate({width: percent});
        if (percent >= 100) {
            stopProgress = true;
            that.closeProgress();
        }
        return that;
    };

    /**
     * 取消上传
     * @returns {Uploader}
     */
    Uploader.prototype.stopUpload = function () {
        var that = this;
        if (that.options.isAjax && !that.hasUploaded) {
            xhr.abort();
        }
        return that;
    };

    /**
     * Ajax 提交上传
     * @param input
     * @param type
     * @returns {string}
     */
    Uploader.prototype.ajax = function (input, type) {
        var that = this, options = that.options, val = input.value;
        var item = $(input), index = item.attr(uploaderAttr),ext = item.attr('lay-ext') || options.ext || ''; //获取支持上传的文件扩展名;

        //如果当前浏览器不支持 FormData，转用 iframe 上传
        if (!top.window.FormData) {
            that.action(input, type);
            return;
        }
        if (!val) {
            return;
        }

        that.hasUploaded = false; // 初始化是否上传成功
        total = 0; //重置上传总量
        for (var key = 0; key < input.files.length; key++) {
            /**
             * lastModified 最后一次修改文件的时间戳
             * lastModifiedDate 最后一次修改文件的时间对象
             * name 文件名称
             * type 文件类型
             * size 文件大小 单位是字节
             */
            var file = input.files[key];
            var name = file.name || val;
            total += file.size;
            //校验文件
            switch (type) {
                case 'file': //一般文件
                    if (ext && !RegExp('\\w\\.(' + ext + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该文件格式', msgConf);
                        return input.value = '';
                    }
                    break;
                case 'video': //视频文件
                    if (!RegExp('\\w\\.(' + (ext || 'avi|mp4|wma|rmvb|rm|flash|3gp|flv') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该视频格式', msgConf);
                        return input.value = '';
                    }
                    break;
                case 'audio': //音频文件
                    if (!RegExp('\\w\\.(' + (ext || 'mp3|wav|mid') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该音频格式', msgConf);
                        return input.value = '';
                    }
                    break;
                default: //图片文件
                    if (!RegExp('\\w\\.(' + (ext || 'jpg|png|gif|bmp|jpeg') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该图片格式', msgConf);
                        return input.value = '';
                    }
                    break;
            }
        }

        //检查 是否 设置最大值
        var max = item.attr('lay-max') || that.options.max;
        if(max !== undefined){
            if((that.cache[index].length + input.files.length) > max){
                layer.msg('最多只能上传 '+ max+' 个文件', msgConf);
                return input.value = '';
            }
        }

        //post方式，url为服务器请求地址，true 该参数规定请求是否异步处理。
        xhr.open('POST', that.options.url, true);

        //开始进度
        var progressValue = 0; //设置上传进度 为0
        var ot, oloaded;
        if (that.options.progress) {
            stopProgress = false;
            that.showProgress();
        }

        //监听XHR 状态变化
        xhr.onreadystatechange = function (e) {
            var res;
            if (this.readyState === 4 && this.status === 200) {
                var result = this.responseText;    //将返回的文本数据转换JSON对象
                try {
                    res = JSON.parse(result);
                } catch (e) {
                    res = {};
                    return layer.msg('请对上传接口返回JSON字符', msgConf);
                }
                that.hasUploaded = true;
                if (res.images !== undefined) {
                    if(typeof res.images === "object"){
                        for(var k in res.images){
                            if (res.images[k].src !== undefined){
                                that.cache[index].push(res.images[k]);
                            }
                        }
                    }
                }
                that.show(that.cache[index] || [],input);
                typeof options.success === 'function' && options.success(res, input,that.cache[index] || res);
            }
        };

        //请求完成
        xhr.onload = function () {
            if (that.options.progress) {
                that.setProgress(100);
                that.closeProgress();
            }
        };

        //请求失败
        xhr.onerror = function () {
            if (that.options.progress) {
                that.setProgress(100);
                that.closeProgress();
            }
        };

        //【上传进度调用方法实现】 //XHR.upload.onprogress 是提交请求的响应回调【上传进度调用方法实现】
        xhr.upload.onprogress = progressFunction;

        //XHR.onprogress 是上传成功后的响应回调
        xhr.onprogress = function (event) {
            that.setProgress(100);
        };

        //上传开始执行方法
        xhr.upload.onloadstart = function () {
            ot = new Date().getTime();   //设置上传开始时间
            oloaded = 0;//设置上传开始时，以上传的文件大小为0
        };

        xhr.send(new FormData(item.parent()[0]));

        //上传进度实现方法，上传过程中会频繁调用该方法
        function progressFunction(event) {
            // event.total是需要传输的总字节，event.loaded是已经传输的字节。如果event.lengthComputable不为真，则event.total等于0
            if (event.lengthComputable) {//
                progressValue = Math.round(event.loaded / event.total * 100);
                if (progressValue > 100) {
                    progressValue = 100;
                }
                that.setProgress(progressValue)
            }

            var nt = new Date().getTime();//获取当前时间
            var perTime = (nt - ot) / 1000; //计算出上次调用该方法时到现在的时间差，单位为s
            ot = new Date().getTime(); //重新赋值时间，用于下次计算

            var perLoad = event.loaded - oloaded; //计算该分段上传的文件大小，单位b
            oloaded = event.loaded;//重新赋值已上传文件大小，用以下次计算

            //上传速度计算
            var uploadSpeed = perLoad / perTime;//单位b/s
            var perSpeed = uploadSpeed;
            var units = 'b/s';//单位名称
            if (uploadSpeed / 1024 > 1) {
                uploadSpeed = uploadSpeed / 1024;
                units = 'k/s';
            }
            if (uploadSpeed / 1024 > 1) {
                uploadSpeed = uploadSpeed / 1024;
                units = 'M/s';
            }
            //上传速度
            uploadSpeed = uploadSpeed.toFixed(1);
            //剩余时间
            var restTime = ((event.total - event.loaded) / perSpeed).toFixed(1);
            if (perSpeed === 0) {
                that.closeProgress();
            }
        }

        input.value = '';
    };

    /**
     * iframe 提交上传
     * @param input
     * @param type
     * @returns {string}
     */
    Uploader.prototype.action = function (input, type) {
        var that = this, options = that.options, val = input.value;
        var item = $(input), index = item.attr(uploaderAttr), ext = item.attr('lay-ext') || options.ext || ''; //获取支持上传的文件扩展名;

        if (!val) {
            return;
        }

        that.hasUploaded = false; // 初始化是否上传成功
        total = 0; //重置上传总量
        for (var key = 0; key < input.files.length; key++) {
            /**
             * lastModified 最后一次修改文件的时间戳
             * lastModifiedDate 最后一次修改文件的时间对象
             * name 文件名称
             * type 文件类型
             * size 文件大小 单位是字节
             */
            var file = input.files[key];
            var name = file.name || val;
            total += file.size;
            //校验文件
            switch (type) {
                case 'file': //一般文件
                    if (ext && !RegExp('\\w\\.(' + ext + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该文件格式', msgConf);
                        return input.value = '';
                    }
                    break;
                case 'video': //视频文件
                    if (!RegExp('\\w\\.(' + (ext || 'avi|mp4|wma|rmvb|rm|flash|3gp|flv') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该视频格式', msgConf);
                        return input.value = '';
                    }
                    break;
                case 'audio': //音频文件
                    if (!RegExp('\\w\\.(' + (ext || 'mp3|wav|mid') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该音频格式', msgConf);
                        return input.value = '';
                    }
                    break;
                default: //图片文件
                    if (!RegExp('\\w\\.(' + (ext || 'jpg|png|gif|bmp|jpeg') + ')$', 'i').test(escape(name))) {
                        layer.msg('不支持该图片格式', msgConf);
                        return input.value = '';
                    }
                    break;
            }
        }

        var max = item.attr('lay-max') || that.options.max;
        if(max !== undefined){
            if((that.cache[index].length + input.files.length) > max){
                layer.msg('最多只能上传 '+ max+' 个文件', msgConf);
                return input.value = '';
            }
        }

        options.before && options.before(input);

        if (that.options.progress) {
            that.progress();
        }
        item.parent().submit();

        var iframe = $('#' + elemIframe), timer = setInterval(function () {
            var res;
            try {
                res = iframe.contents().find('body').text();
            } catch (e) {
                layer.msg('上传接口存在跨域', msgConf);
                if (that.options.progress) {
                    stopProgress = true;
                    that.setProgress(100);
                    setTimeout(function () {
                        that.closeProgress();
                    }, 400);
                }
                clearInterval(timer);
            }
            if (res) {
                if (that.options.progress) {
                    stopProgress = true;
                    that.setProgress(100);
                    setTimeout(function () {
                        that.closeProgress();
                    }, 400);
                }
                clearInterval(timer);
                iframe.contents().find('body').html('');
                try {
                    res = JSON.parse(res);
                } catch (e) {
                    res = {};
                    return layer.msg('请对上传接口返回JSON字符', msgConf);
                }
                that.hasUploaded = true;
                if (res.images !== undefined) {
                    if(typeof res.images === "object"){
                        for(var k in res.images){
                            if (res.images[k].src !== undefined){
                                that.cache[index].push(res.images[k]);
                            }
                        }
                    }
                }
                that.show(that.cache[index] || [],input);
                typeof options.success === 'function' && options.success(res, input,that.cache[index] || res);
            }
        }, 30);

        input.value = '';
    };

    /**
     * 新实例化
     * @param options
     */
    Uploader.prototype.create = function (options) {
        var uploader = new Uploader(options);
        uploader.index = ++uploaderIndex;
        uploader.init();
        return uploader;
    };

    /**
     * 检查 是否 可用 HTTP 的进度事件
     * @returns {Uploader}
     */
    Uploader.prototype.checkBrowserIsSupportProgressEvent = function () {
        var that = this;
        var isSupport = false;
        if ('onprogress' in (new XMLHttpRequest())) {
            isSupport = true;
        }
        if (!isSupport) {
            if (that.options.isAjax) {
                layer.msg('对不起您的浏览器不支持http进度事件！请更换谷歌或者火狐浏览器！');
            }
        }
        return that;
    };

    //暴露接口
    exports(MOD_NAME, function (options) {
        return new Uploader(options);
    });
});

