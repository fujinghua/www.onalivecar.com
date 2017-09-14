layui.define('form', function (exports) {
    "use strict";
    var $ = layui.$,
        form = layui.form,
        MOD_NAME = 'forms',
        Forms = function (options) {
            this.verify ={
                required: [
                    /[\S]+/
                    , '必填项不能为空'
                ],
                phone: [
                    /^1\d{10}$/
                    , '请输入正确的手机号'
                ],
                email: [
                    /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
                    , '邮箱格式不正确'
                ],
                url: [
                    /(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/
                    , '链接格式不正确'
                ],
                number: [
                    /^\d+$/
                    , '只能填写数字'
                ],
                date: [
                    /^(\d{4})[-\/](\d{1}|0\d{1}|1[0-2])([-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/
                    , '日期格式不正确'
                ],
                identity: [
                    /(^\d{15}$)|(^\d{17}(x|X|\d)$)/
                    , '请输入正确的身份证号'
                ],
                //验证数字(double类型) [可以包含负号和小数点]
                IsNumber: [
                    /^-?\d+$|^(-?\d+)(\.\d+)?$/
                    , '只能填写数值'
                ],
                //验证整数
                IsInteger: [
                    /^-?\d+$/
                    , '只能是整数'
                ],
                //验证非负整数
                IsIntegerNotNagtive: [
                    '/^\d+$/',
                    '只能是非负整数'
                ],
                //验证正整数
                IsIntegerPositive: [
                    /^[0-9]*[1-9][0-9]*$/
                    , '只能是正整数'
                ],
                //验证小数
                IsDecimal: [
                    /^([-+]?[1-9]\d*\.\d+|-?0\.\d*[1-9]\d*)$/
                    , '需要带有小数'
                ],
                //验证只包含英文字母
                IsEnglishCharacter: [
                    /^[A-Za-z]+$/
                    , '只包含英文字母'
                ],
                //验证只包含数字和英文字母
                IsIntegerAndEnglishCharacter: [
                    /^[0-9A-Za-z]+$/
                    , '只包含数字和英文字母'
                ],
                //验证只包含汉字
                IsChineseCharacter: [
                    /^[\u4e00-\u9fa5]+$/
                    , '只包含汉字'
                ],
                //验证固定电话号码 [3位或4位区号；区号可以用小括号括起来；区号可以省略；区号与本地号间可以用减号或空格隔开；可以有3位数的分机号，分机号前要加减号]
                IsTelePhoneNumber: [
                    /^(((0\d2|0\d{2})[- ]?)?\d{8}|((0\d3|0\d{3})[- ]?)?\d{7})(-\d{3})?$/
                    , '固定电话号码不合法'
                ],
                //验证手机号码 [可匹配"(+86)013325656352"，括号可以省略，+号可以省略，(+86)可以省略，11位手机号前的0可以省略；11位手机号第二位数可以是3、4、5、8中的任意一个]
                IsMobilePhoneNumber: [
                    /^((\+)?86|((\+)?86)?)0?1[3458]\d{9}$/
                    , '手机号码不合法'
                ],
                //验证电话号码（可以是固定电话号码或手机号码）
                IsPhoneNumber: [
                    /^((\+)?86|((\+)?86)?)0?1[3458]\d{9}$|^(((0\d2|0\d{2})[- ]?)?\d{8}|((0\d3|0\d{3})[- ]?)?\d{7})(-\d{3})?$/
                    , '电话号码不合法'
                ],
                //验证邮政编码
                IsZipCode: [
                    /^\d{6}$/
                    , '验证邮政编码'
                ],
                //验证电子邮箱 [@字符前可以包含字母、数字、下划线和点号；@字符后可以包含字母、数字、下划线和点号；@字符后至少包含一个点号且点号不能是最后一个字符；最后一个点号后只能是字母或数字]
                //邮箱名以数字或字母开头；邮箱名可由字母、数字、点号、减号、下划线组成；邮箱名（@前的字符）长度为3～18个字符；邮箱名不能以点号、减号或下划线结尾；不能出现连续两个或两个以上的点号、减号。
                //var regex = /^[a-zA-Z0-9]((?<!(\.\.|--))[a-zA-Z0-9\._-]){1,16}[a-zA-Z0-9]@([0-9a-zA-Z][0-9a-zA-Z-]{0,62}\.)+([0-9a-zA-Z][0-9a-zA-Z-]{0,62})\.?|((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/;
                IsEmail: [
                    /^([\w\_\-\.]+)@([\w\_\-\.]+)(\.[a-zA-Z0-9]+)$/
                    , '电子邮箱不合法'
                ],
                //验证网址（可以匹配IPv4地址但没对IPv4地址进行格式验证；IPv6暂时没做匹配）[允许省略"://"；可以添加端口号；允许层级；允许传参；域名中至少一个点号且此点号前要有内容]
                ////每级域名由字母、数字和减号构成（第一个字母不能是减号），不区分大小写，单个域长度不超过63，完整的域名全长不超过256个字符。在DNS系统中，全名是以一个点“.”来结束的，例如“www.nit.edu.cn.”。没有最后的那个点则表示一个相对地址。
                ////没有例如"http://"的前缀，没有传参的匹配
                //var regex = /^([0-9a-zA-Z][0-9a-zA-Z-]{0,62}\.)+([0-9a-zA-Z][0-9a-zA-Z-]{0,62})\.?$/;
                //var regex = /^(((file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)://)|(www\.))+(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(/[a-zA-Z0-9\&%_\./-~-]*)?$/;
                IsURL: [
                    /^([a-zA-Z]+:\/\/)?([\w\_\-\.]+)(\.[a-zA-Z0-9]+)(:\d{0,5})?\/?([\w\_\-\/]*)\.?([a-zA-Z]*)\??(([\w-]*=[\w%]*&?)*)$/
                    , '网站地址不合法'
                ],
                //验证IPv4地址 [第一位和最后一位数字不能是0或255；允许用0补位]
                IsIPv4: [
                    /^(25[0-4]|2[0-4]\d]|[01]?\d{2}|[1-9])\.(25[0-5]|2[0-4]\d]|[01]?\d?\d)\.(25[0-5]|2[0-4]\d]|[01]?\d?\d)\.(25[0-4]|2[0-4]\d]|[01]?\d{2}|[1-9])$/
                    , 'TPv4不合法'
                ],
                //验证IPv6地址 [可用于匹配任何一个合法的IPv6地址]
                IsIPv6: [
                    /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/
                    , 'IPv6不合法'
                ],
                //验证经度
                IsLongitude: [
                    /^[-\+]?((1[0-7]\d{1}|0?\d{1,2})\.\d{1,5}|180\.0{1,5})$/
                    , '经度不合法'
                ],
                //验证纬度
                IsLatitude: [
                    /^[-\+]?([0-8]?\d{1}\.\d{1,5}|90\.0{1,5})$/
                    , '纬度不合法'
                ],
                //验证日期 [只能验证日期，不能验证时间]
                IsDateTime: function (value, item) {
                    var regex = /((19|20)[0-9]{2})-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])/;
                    var message = '日期不符合';
                    if (regex.test(value)) {
                        var ymd = value.match(/(\d{4})-(\d+)-(\d+).*/);
                        var year = parseInt(ymd[1]);
                        var month = parseInt(ymd[2]);
                        var day = parseInt(ymd[3]);
                        if (day > 28) {
                            //获取当月的最后一天
                            var lastDay = new Date(year, month, 0).getDate();
                            return message;
                        }
                    } else {
                        return message;
                    }
                },
                //验证数字长度范围（数字前端的0计长度）[若要验证固定长度，可传入相同的两个长度数值]
                IsIntegerLength: function (value, item) {
                    var message = "数值超出规定范围";
                    var lengthBegin = $(item).attr('lay-begin');
                    var lengthEnd = $(item).attr('lay-end');
                    if (!lengthBegin || !lengthEnd) {
                        return '配置错';
                    }
                    var pattern = '^\\d{' + lengthBegin + ',' + lengthEnd + '}$';
                    var regex = new RegExp(pattern);
                    if (!value.match(regex)) {
                        return message;
                    }
                },
                //验证字符串包含内容
                IsStringInclude: function (value, item) {
                    var message = '包含无效字符';
                    var withEnglishCharacter = false;
                    var withNumber = false;
                    var withChineseCharacter = false;
                    var format = $(item).attr('lay-format').split('|');
                    for (var i = 0; i < format.length; i++) {
                        if (format[i] === 'English') {
                            withEnglishCharacter = true;
                        } else if (format[i] === 'Number') {
                            withNumber = true;
                        } else if (format[i] === 'Chinese') {
                            withChineseCharacter = true;
                        }
                    }
                    if (!Boolean(withEnglishCharacter) && !Boolean(withNumber) && !Boolean(withChineseCharacter)) {
                        return message; //如果英文字母、数字和汉字都没有，则返回false
                    }
                    var pattern = '^[';
                    if (Boolean(withEnglishCharacter)) {
                        pattern += 'a-zA-Z';
                    }
                    if (Boolean(withNumber)) {
                        pattern += '0-9';
                    }
                    if (Boolean(withChineseCharacter)) {
                        pattern += '\\u4E00-\\u9FA5';
                    }
                    pattern += ']+$';
                    var regex = new RegExp(pattern);
                    if (!value.match(regex)) {
                        return message;
                    }
                },
                //验证字符串长度范围 [若要验证固定长度，可传入相同的两个长度数值]
                IsStringLength: function (value, item, LengthBegin, LengthEnd) {
                    var message = '字符串超出规定范围';
                    var pattern = '^.{' + lengthBegin + ',' + lengthEnd + '}$';
                    var regex = new RegExp(pattern);
                    if (!value.match(regex)) {
                        return message;
                    }
                },
                //验证字符串长度范围（字符串内只包含数字和/或英文字母）[若要验证固定长度，可传入相同的两个长度数值]
                IsStringLengthOnlyNumberAndEnglishCharacter: function (value, item, LengthBegin, LengthEnd) {
                    var message = '字符串超出规定范围';
                    var pattern = '^[0-9a-zA-z]{' + lengthBegin + ',' + lengthEnd + '}$';
                    var regex = new RegExp(pattern);
                    if (!value.match(regex)) {
                        return message;
                    }
                },
                //验证字符串长度范围 [若要验证固定长度，可传入相同的两个长度数值]
                IsStringLengthByInclude: function (value, item, withEnglishCharacter, withNumber, withChineseCharacter, lengthBegin, lengthEnd) {
                    var message = '字符串超出规定范围';
                    if (!withEnglishCharacter && !withNumber && !withChineseCharacter) {
                        return '无效的字符串'; //如果英文字母、数字和汉字都没有，则返回false
                    }
                    var pattern = '^[';
                    if (Boolean(withEnglishCharacter))
                        pattern += 'a-zA-Z';
                    if (Boolean(withNumber))
                        pattern += '0-9';
                    if (Boolean(withChineseCharacter))
                        pattern += '\\u4E00-\\u9FA5';
                    pattern += ']{' + lengthBegin + ',' + lengthEnd + '}$';
                    var regex = new RegExp(pattern);
                    if (!value.match(regex)) {
                        return message;
                    }
                },
                //验证字符串字节数长度范围 [若要验证固定长度，可传入相同的两个长度数值；每个汉字为两个字节长度]
                IsStringByteLength: function (value, item, lengthBegin, lengthEnd) {
                    var message = '字符串字节数超出规定范围';
                    var regex = /[^\x00-\xff]/g;
                    var byteLength = value.replace(regex, 'ok').length;
                    if (!(byteLength >= lengthBegin && byteLength <= lengthEnd)) {
                        return message;
                    }
                },
                //验证身份证号 [可验证一代或二代身份证]
                IsIDCard: function (value, item) {
                    var message = '无效身份证号码';
                    value = value.toUpperCase();
                    //验证身份证号码格式 [一代身份证号码为15位的数字；二代身份证号码为18位的数字或17位的数字加字母X]
                    if (!(/(^\d{15}$)|(^\d{17}([0-9]|X)$)/i.test(value))) {
                        return message;
                    }
                    //验证省份
                    var arrCity = {
                        11: '北京',
                        12: '天津',
                        13: '河北',
                        14: '山西',
                        15: '内蒙古',
                        21: '辽宁',
                        22: '吉林',
                        23: '黑龙江 ',
                        31: '上海',
                        32: '江苏',
                        33: '浙江',
                        34: '安徽',
                        35: '福建',
                        36: '江西',
                        37: '山东',
                        41: '河南',
                        42: '湖北',
                        43: '湖南',
                        44: '广东',
                        45: '广西',
                        46: '海南',
                        50: '重庆',
                        51: '四川',
                        52: '贵州',
                        53: '云南',
                        54: '西藏',
                        61: '陕西',
                        62: '甘肃',
                        63: '青海',
                        64: '宁夏',
                        65: '新疆',
                        71: '台湾',
                        81: '香港',
                        82: '澳门',
                        91: '国外'
                    };
                    if (arrCity[parseInt(value.substr(0, 2))] == null) {
                        return message;
                    }
                    //验证出生日期
                    var regBirth, birthSplit, birth;
                    var len = value.length;
                    if (len == 15) {
                        regBirth = new RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/);
                        birthSplit = value.match(regBirth);
                        birth = new Date('19' + birthSplit[2] + '/' + birthSplit[3] + '/' + birthSplit[4]);
                        if (!(birth.getYear() == Number(birthSplit[2]) && (birth.getMonth() + 1) == Number(birthSplit[3]) && birth.getDate() == Number(birthSplit[4]))) {
                            return message;
                        }
                    } else if (len == 18) {
                        regBirth = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/i);
                        birthSplit = value.match(regBirth);
                        birth = new Date(birthSplit[2] + '/' + birthSplit[3] + '/' + birthSplit[4]);
                        if (!(birth.getFullYear() == Number(birthSplit[2]) && (birth.getMonth() + 1) == Number(birthSplit[3]) && birth.getDate() == Number(birthSplit[4]))) {
                            return message;
                        }
                        //验证校验码
                        var valnum;
                        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                        var nTemp = 0,
                            i;
                        for (i = 0; i < 17; i++) {
                            nTemp += value.substr(i, 1) * arrInt[i];
                        }
                        valnum = arrCh[nTemp % 11];
                        if (valnum != value.substr(17, 1)) {
                            return message;
                        }
                    }
                },
                //不为空支持 lay-error自定义 错误信息
                require: function (value, item) {
                    var regex = /[\S]+/;
                    var _value = $(item);
                    var message = _value.attr('lay-error') || '必填项不能为空';
                    if (!regex.test(value)) {
                        return message;
                    }
                },
                //exist 使用方式 lay-verify="exist" lay-group="XXX" lay-error="XXX"
                exist: function (value, item) {
                    var $this = $(item);
                    var has;
                    if ($this.is('input[type="checkbox"]') || $this.is('input[type="radio"]')){
                        if($this.checked){
                            has = true;
                        }
                    }else if ($.trim(value) !== ''){
                        has = true;
                    }
                    if (!has) {
                        var group = $this.attr('lay-group');
                        var message = $this.attr('lay-error') || $this.closest('[lay-error]').attr('lay-error') || '至少选择一个 ' + ($this.attr('name')||'选项');
                        var pass = false;
                        //检查checkbox radio类型
                        var checked = $('input[lay-group="' + group + '"]:checked');
                        if (checked.length > 0) {
                            pass = true;
                        } else {
                            //检查其他类型 正常input
                            $('input[lay-group="' + group + '"][type!="checkbox"][type!="radio"]').each(function () {
                                if ($.trim($(this).val()) !== '') {
                                    pass = true;
                                }
                            });
                            //检查其他类型 select
                            $('select[lay-group="' + group + '"]').each(function () {
                                if ($.trim($(this).val()) !== '') {
                                    pass = true;
                                }
                            });
                            //检查其他类型 textarea
                            $('textarea[lay-group="' + group + '"]').each(function () {
                                if ($.trim($(this).val()) !== '') {
                                    pass = true;
                                }
                            });
                        }
                        if (!pass) {
                            return message;
                        }
                    }
                },
                // checked 使用方式 lay-verify="checked" lay-group="XXX" lay-error="XXX"
                checked: function (value, item) {
                    var $this = $(item);
                    if (!$this.checked) {
                        var group = $this.attr('lay-group');
                        var message = $this.attr('lay-error') || $this.closest('[lay-error]').attr('lay-error') || '至少选择一个 ' + $this.attr('name');
                        var checked = $('input[lay-group="' + group + '"]:checked');
                        if (checked.length <= 0) {
                            return message;
                        }
                    }
                },
                // isString 使用方式 lay-verify="isString" lay-length="6|20" lay-error="XXX"
                isString: function (value, item) {
                    var $this = $(item);
                    var length = $this.attr('lay-length');
                    if (length){
                        length = length.split('|');
                        var message = $this.attr('lay-error') || '超过限定长度';
                        var pattern;
                        if (length.length >= 2){
                            pattern = '^.{'+length[0]+','+length[1]+'}$';
                            message = $this.attr('lay-error') || '限定在'+length[0]+'-'+length[1]+'长度';
                        }else if (length.length == 1){
                            pattern = '^.{0,'+length[0]+'}$';
                            message = $this.attr('lay-error') || '超过限定长度'+length[0];
                        }
                        value = value.replace(/[^\x00-\xff]/g,'aaa');
                        var regex = new RegExp(pattern);
                        if (!value.match(regex)) {
                            return message;
                        }
                    }
                },
                compare: function (value, item) {
                    var $this = $(item);
                    var check = $this.attr('lay-compare');
                    if (check){
                        var target = $('[lay-filter="'+check+'"]') ||　$('[name="'+check+'"]');
                        var message = $this.attr('lay-error') || '与 ('+$this.attr('name') + ') 的值不一致';
                        if(target.length >0 ){
                            if (value != target.val()){
                                return message;
                            }
                        }
                    }
                },
                // checked 使用方式 lay-verify="layAjax" lay-url="XXX" lay-error="XXX"
                layAjax: function (value, item) {
                    var $this = $(item);
                    var url = $this.attr('lay-url') || '';
                    var message = $this.attr('lay-error') || '验证不通过';
                    var hasAjax = $this.attr('lay-hasAjax') || false;
                    var name = $this.attr('name') || 'key';
                    var param = '&' + name + '=' + value;
                    if (!hasAjax && url !== '') {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: param,
                            async: false,
                            success: function (data) {
                                if (data.code == '1') {
                                    hasAjax = true;
                                    $this.attr('lay-hasAjax', 'true');
                                }else{
                                    if (data.msg !== undefined) {
                                        message = data.msg;
                                    }
                                }
                            }
                        });
                        if (!hasAjax){
                            return message;
                        }
                    }
                },
            };
            $.extend(true, this.verify, options);
            form.verify(this.verify);
            return form;
        };

    var forms = new Forms();
    exports(MOD_NAME, forms);
});

 
