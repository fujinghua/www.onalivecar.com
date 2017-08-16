try {
    if ($.browser.msie && $.browser.version == "6.0") {
        document.execCommand("BackgroundImageCache", false, true)
    }
} catch (e) {
}
if (typeof Helper == "undefined") {
    Helper = {};
    Helper.top = top
}
Helper.openLog = false;
Helper.isDbg = function () {
    var a = Helper.Cookie.get("_fai_debug");
    return a == "true"
};
(function (FUNC, undefined) {
    FUNC.fkEval = function (data) {
        return eval(data)
    }
})(Helper);
Helper.logDog = function (b, a) {
    $.ajax({type: "get", url: "/ajax/log_h.php?cmd=dog&dogId=" + Helper.encodeUrl(b) + "&dogSrc=" + Helper.encodeUrl(a)})
};
Helper.logDbg = function () {
    var d = Helper.isDbg();
    if (d || Helper.openLog) {
        var a = $.makeArray(arguments);
        if (Helper.isIE()) {
            var b = '<div id="faiDebugMsg" style="position:absolute; top:30px; left:45%; margin:0px auto; width:auto; height:auto; z-index:9999;"></div>';
            var c = Helper.top.$("#faiDebugMsg");
            if (c.length == 0) {
                c = Helper.top.$(b);
                c.appendTo("body")
            }
            Helper.top.$('<div class="tips" style="position:relative; top:0px; left:-50%; width:auto; _width:50px; height:24px; margin:3px 0; line-height:24px; color:#000000; border:1px solid #EAEA00; background:#FFFFC4; z-index:9999;"><div class="msg" style="width:auto; margin:0 3px; height:24px; line-height:24px; word-break:keep-all; white-space:nowrap;">' + a.join("") + "</div></div>").appendTo(c)
        } else {
            console.log(a.join(""))
        }
    }
};
Helper.logAlert = function () {
    var b = Helper.Cookie.get("_fai_debug");
    if (b == "true" || Helper.openLog) {
        var a = $.makeArray(arguments);
        alert(a.join(""))
    }
};
Helper.replaceContentOfURL = function (a) {
    return (a.replace(/((https?|ftp|file):\/\/[-a-zA-Z0-9+&@#\/%?=~_|!:,.;]*)/g, '<a href="$1" target="_blank">$1</a>'))
};
Helper.getByteLength = function (f, g) {
    var d = 0, b, c, a;
    g = g ? g.toLowerCase() : "";
    if (g === "utf-16" || g === "utf16") {
        for (c = 0, a = f.length; c < a; c++) {
            b = f.charCodeAt(c);
            if (b <= 65535) {
                d += 2
            } else {
                d += 4
            }
        }
    } else {
        for (c = 0, a = f.length; c < a; c++) {
            b = f.charCodeAt(c);
            if (b <= 127) {
                d += 1
            } else {
                if (b <= 2047) {
                    d += 2
                } else {
                    if (b <= 65535) {
                        d += 3
                    } else {
                        d += 4
                    }
                }
            }
        }
    }
    return d
};
Helper.isNull = function (a) {
    return (typeof a == "undefined") || (a == null)
};
Helper.isDate = function (a) {
    if (a.constructor == Date) {
        return true
    } else {
        return false
    }
};
Helper.isNumber = function (a) {
    if (/[^\d]/.test(a)) {
        return false
    } else {
        return true
    }
};
Helper.isFloat = function (a) {
    return /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)
};
Helper.isInteger = function (a) {
    return /^-?\d+$/.test(a)
};
Helper.isLowerCase = function (a) {
    return /^[a-z]+$/.test(a)
};
Helper.isUpperCase = function (a) {
    return /^[A-Z]+$/.test(a)
};
Helper.toLowerCaseFirstOne = function (a) {
    if (typeof a === "undefined" || Helper.isLowerCase(a.charAt(0))) {
        return a
    } else {
        var c = a.substring(0, 1).toLowerCase();
        var b = a.substring(1, a.length);
        return c + b
    }
};
Helper.toUpperCaseFirstOne = function (a) {
    if (typeof a === "undefined" || Helper.isUpperCase(a.charAt(0))) {
        return a
    } else {
        var c = a.substring(0, 1).toUpperCase();
        var b = a.substring(1, a.length);
        return c + b
    }
};
Helper.isDigit = function (a) {
    if (a < "0" || a > "9") {
        return false
    }
    return true
};
Helper.isLetter = function (a) {
    if ((a < "a" || a > "z") && (a < "A" || a > "Z")) {
        return false
    }
    return true
};
Helper.isChinese = function (a) {
    if (a < "一" || a > "龥") {
        return false
    }
    return true
};
Helper.isIp = function (c) {
    if (typeof c != "string" || $.trim(c) == "") {
        return false
    }
    var b = c.split(".");
    if (b.length != 4) {
        return false
    }
    var a = true;
    $.each(b, function (d, f) {
        if (!Helper.isNumber(f) || parseInt(f) < 0 || parseInt(f) > 255) {
            a = false;
            return true
        }
    });
    return a
};
Helper.isDomain = function (a) {
    if (/^[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9]$/.test(a)) {
        if (a.indexOf("--") >= 0) {
            return false
        }
        return true
    } else {
        return false
    }
};
Helper.isWord = function (b) {
    var a = /^[a-zA-Z0-9_]+$/;
    return a.test(b)
};
Helper.isEmail = function (a) {
    var b = /^[a-zA-Z0-9][a-zA-Z0-9_=\&\-\.\+]*[a-zA-Z0-9]*@[a-zA-Z0-9][a-zA-Z0-9_\-\.]+[a-zA-Z0-9]$/;
    return b.test(a)
};
Helper.isEmailDomain = function (a) {
    var b = /^[a-zA-Z0-9][a-zA-Z0-9_\-]*\.[a-zA-Z0-9\-][a-zA-Z0-9_\-\.]*[a-zA-Z0-9]$/;
    return b.test(a)
};
Helper.isMobile = function (a) {
    var b = /^1\d{10}$/;
    return b.test(a)
};
Helper.isPhone = function (a) {
    var c = /^([^\d])+([^\d])*([^\d])$/;
    var b = /^([\d\+\s\(\)-])+([\d\+\s\(\)-])*([\d\+\s\(\)-])$/;
    if (c.test(a)) {
        return false
    }
    return b.test(a)
};
Helper.isNationMobile = function (a) {
    var b = /^\d{8,14}$/;
    return b.test(a)
};
Helper.isCardNo = function (a) {
    var b = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
    return b.test(a)
};
Helper.isUrl = function (d, c) {
    if (typeof c == "undefined") {
        c = true
    }
    if (c && d.length >= 1 && d.charAt(0) == "/") {
        return true
    }
    if (c && d.length >= 1 && d.charAt(0) == "#") {
        return true
    }
    var b = /^(\w+:).+/;
    var a = b.test(d);
    return a
};
Helper.fixUrl = function (a, b) {
    if (Helper.isUrl(a, b)) {
        return a
    }
    return "http://" + a
};
Helper.checkBit = function (j, b) {
    var f = true;
    if (j > 2147483647 || j < 0 || b > 2147483647 || b < 0) {
        f = false
    }
    if (f) {
        return (j & b) == b
    }
    var g = j.toString(2);
    var d = b.toString(2);
    if (g.length > 62 || d.length > 62) {
        alert("Does not support more than 62 bit. flagBinary.length=" + g.length + ",bitFlagBinary.length" + d.length + ".");
        return false
    }
    var h = flagLow = bitFlagHight = bitFlagLow = 0;
    if (g.length > 31) {
        var c = g.slice(0, g.length - 31);
        var a = g.slice(g.length - 31);
        h = parseInt(c, "2");
        flagLow = parseInt(a, "2")
    } else {
        flagLow = parseInt(g.slice(0, g.length), "2")
    }
    if (d.length > 31) {
        var c = d.slice(0, d.length - 31);
        var a = d.slice(d.length - 31);
        bitFlagHight = parseInt(c, "2");
        bitFlagLow = parseInt(a, "2")
    } else {
        bitFlagLow = parseInt(d.slice(0, d.length), "2")
    }
    var k = (flagLow & bitFlagLow) == bitFlagLow;
    if (k) {
        k = (h & bitFlagHight) == bitFlagHight
    }
    return k
};
Helper.andBit = function (b, h) {
    var a = true;
    if (b > 2147483647 || b < 0 || h > 2147483647 || h < 0) {
        a = false
    }
    if (a) {
        return b &= h
    }
    var f = b.toString(2);
    var d = h.toString(2);
    if (f.length > 62 || d.length > 62) {
        alert("Does not support more than 62 bit. flagBinary.length=" + f.length + ",bitFlagBinary.length" + d.length + ".");
        return 0
    }
    var c = flagLow = bitFlagHight = bitFlagLow = 0;
    if (f.length > 31) {
        var g = f.slice(0, f.length - 31);
        var j = f.slice(f.length - 31);
        c = parseInt(g, "2");
        flagLow = parseInt(j, "2")
    } else {
        flagLow = parseInt(f.slice(0, f.length), "2")
    }
    if (d.length > 31) {
        var g = d.slice(0, d.length - 31);
        var j = d.slice(d.length - 31);
        bitFlagHight = parseInt(g, "2");
        bitFlagLow = parseInt(j, "2")
    } else {
        bitFlagLow = parseInt(d.slice(0, d.length), "2")
    }
    flagLow &= bitFlagLow;
    c &= bitFlagHight;
    f = flagLow.toString(2);
    for (; f.length < 31;) {
        f = "0" + f
    }
    f = c.toString(2) + f;
    return parseInt(f, "2")
};
Helper.orBit = function (b, h) {
    var a = true;
    if (b > 2147483647 || b < 0 || h > 2147483647 || h < 0) {
        a = false
    }
    if (a) {
        return b |= h
    }
    var f = b.toString(2);
    var d = h.toString(2);
    if (f.length > 62 || d.length > 62) {
        alert("Does not support more than 62 bit. flagBinary.length=" + f.length + ",bitFlagBinary.length" + d.length + ".");
        return 0
    }
    var c = flagLow = bitFlagHight = bitFlagLow = 0;
    if (f.length > 31) {
        var g = f.slice(0, f.length - 31);
        var j = f.slice(f.length - 31);
        c = parseInt(g, "2");
        flagLow = parseInt(j, "2")
    } else {
        flagLow = parseInt(f.slice(0, f.length), "2")
    }
    if (d.length > 31) {
        var g = d.slice(0, d.length - 31);
        var j = d.slice(d.length - 31);
        bitFlagHight = parseInt(g, "2");
        bitFlagLow = parseInt(j, "2")
    } else {
        bitFlagLow = parseInt(d.slice(0, d.length), "2")
    }
    flagLow |= bitFlagLow;
    c |= bitFlagHight;
    f = flagLow.toString(2);
    for (; f.length < 31;) {
        f = "0" + f
    }
    f = c.toString(2) + f;
    return parseInt(f, "2")
};
Helper.renderUEditor = function (b) {
    var a = {
        ueditorId: null,
        setPageChange: null,
        initContent: null,
        minFrameHeight: 0,
        faiscoRichTip: null,
        withPage: null
    };
    $.extend(a, b);
    var c = new baidu.editor.ui.Editor({
        upLoadFlashUrl: "/ajax/upfile_h.php?type=50",
        upLoadImageUrl: "/ajax/upimg_h.php",
        ueditorChangeEvent: a.setPageChange,
        htmlModuleRichTip: a.faiscoRichTip,
        initialContent: a.initContent,
        minFrameHeight: a.minFrameHeight,
        toolbars: [["shrinkopenup", "removeformat", "|", "bold", "italic", "underline", "|", "fontfamily", "fontsize", "forecolor", "backcolor", "|", "insertorderedlist", "insertunorderedlist", "lineheight", "justifyright", "|", "link", "unlink", "qqservice", "image", "flash", "inserttable", a.withPage, "|", "source", "||", "pasteplain", "|", "selectall", "undo", "redo", "|", "strikethrough", "superscript", "subscript", "horizontal", "|", "indent", "rowspacingtop", "rowspacingbottom", "|", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols", "|", "fullscreen"]]
    });
    c.render(a.ueditorId);
    return c
};
Helper.isEnterKey = function (a) {
    if ($.browser.msie) {
        if (event.keyCode == 13) {
            return true
        } else {
            return false
        }
    } else {
        if (a.which == 13) {
            return true
        } else {
            return false
        }
    }
};
Helper.isNumberKey = function (b, a) {
    if ($.browser.msie) {
        if (a && event.keyCode == 45) {
            return true
        }
        if (((event.keyCode > 47) && (event.keyCode < 58)) || (event.keyCode == 8)) {
            return true
        } else {
            return false
        }
    } else {
        if (a && b.which == 45) {
            return true
        }
        if (((b.which > 47) && (b.which < 58)) || (b.which == 8)) {
            return true
        } else {
            return false
        }
    }
};
Helper.isPhoneNumberKey = function (b, a) {
    if ($.browser.msie) {
        if (a && event.keyCode == 45) {
            return true
        }
        if (((event.keyCode > 47) && (event.keyCode < 58)) || (event.keyCode == 8) || (event.keyCode == 44)) {
            return true
        } else {
            return false
        }
    } else {
        if (a && b.which == 45) {
            return true
        }
        if (((b.which > 47) && (b.which < 58)) || (b.which == 8) || (b.which == 44)) {
            return true
        } else {
            return false
        }
    }
};
Helper.checkTwoDecimal = function (c, f) {
    var d = $("#" + f).val();
    var b = /^[0-9]\d*(?:\.\d{1,2}|\d*)$/;
    if ($.browser.msie) {
        if (event.keyCode > 47 && event.keyCode < 58) {
            var a = String.fromCharCode(c.which);
            d = d + "" + a;
            return b.test(d)
        }
    } else {
        if (c.which > 47 && c.which < 58) {
            var a = String.fromCharCode(c.which);
            d = d + "" + a;
            return b.test(d)
        }
    }
};
Helper.checkOneDecimal = function (c, f) {
    var d = $("#" + f).val();
    var b = /^[0-9]\d*(?:\.\d{1}|\d*)$/;
    if ($.browser.msie) {
        if (event.keyCode > 47 && event.keyCode < 58) {
            var a = String.fromCharCode(c.which);
            d = d + "" + a;
            return b.test(d)
        }
    } else {
        if (c.which > 47 && c.which < 58) {
            var a = String.fromCharCode(c.which);
            d = d + "" + a;
            return b.test(d)
        }
    }
};
Helper.isNumberKey2 = function (c, a, b) {
    if (a) {
        $(c).val($(c).val().replace(/[^0-9\-]/g, ""))
    } else {
        $(c).val($(c).val().replace(/[^0-9]/g, ""))
    }
};
Helper.isFloatKey = function (a) {
    if ($.browser.msie) {
        if (((event.keyCode > 47) && (event.keyCode < 58)) || (event.keyCode == 8) || (event.keyCode == 46)) {
            return true
        } else {
            return false
        }
    } else {
        if (((a.which > 47) && (a.which < 58)) || (a.which == 8) || (a.which == 46)) {
            return true
        } else {
            return false
        }
    }
};
Helper.flashChecker = function () {
    var hasFlash = 0;
    var flashVersion = 0;
    var isIE =
        /*@cc_on!@*/
        0;
    if (isIE) {
        try {
            var swf = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
            if (swf) {
                hasFlash = 1;
                VSwf = swf.GetVariable("$version");
                flashVersion = parseInt(VSwf.split(" ")[1].split(",")[0])
            }
        } catch (ex) {
        }
    } else {
        if (navigator.plugins && navigator.plugins.length > 0) {
            var swf = navigator.plugins["Shockwave Flash"];
            if (swf) {
                hasFlash = 1;
                var words = swf.description.split(" ");
                for (var i = 0; i < words.length; ++i) {
                    if (isNaN(parseInt(words[i]))) {
                        continue
                    }
                    flashVersion = parseInt(words[i])
                }
            }
        }
    }
    return {f: hasFlash, v: flashVersion}
};
Helper.isIE = function () {
    return $.browser.msie ? true : false
};
Helper.isIE6 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "6.0") {
            return true
        }
    }
    return false
};
Helper.isIE7 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "7.0") {
            return true
        }
    }
    return false
};
Helper.isIE8 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "8.0") {
            return true
        }
    }
    return false
};
Helper.isIE9 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "9.0") {
            return true
        }
    }
    return false
};
Helper.isIE10 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "10.0") {
            return true
        }
    }
    return false
};
Helper.isIE11 = function () {
    if ($.browser.msie) {
        if ($.browser.version == "11.0" || $.browser.rv) {
            return true
        }
    }
    return false
};
Helper.isSafari = function () {
    return $.browser.safari ? true : false
};
Helper.isWebkit = function () {
    return $.browser.webkit ? true : false
};
Helper.isChrome = function () {
    return $.browser.chrome ? true : false
};
Helper.isMozilla = function () {
    return $.browser.mozilla ? true : false
};
Helper.isAppleWebKit = function () {
    var a = window.navigator.userAgent;
    if (a.indexOf("AppleWebKit") >= 0) {
        return true
    }
    return false
};
Helper.isOpera = function () {
    return $.browser.opera || $.browser.opr ? true : false
};
Helper.isAndroid = function () {
    return $.browser.android ? true : false
};
Helper.isIpad = function () {
    return $.browser.ipad ? true : false
};
Helper.isIphone = function () {
    return $.browser.iphone ? true : false
};
Helper.isPC = function () {
    var a = navigator.userAgent;
    var d = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
    var b = true;
    for (var c = 0; c < d.length; c++) {
        if (a.indexOf(d[c]) > 0) {
            b = false;
            break
        }
    }
    return b
};
Helper.BrowserType = {
    UNKNOWN: 0,
    SPIDER: 1,
    CHROME: 2,
    FIREFOX: 3,
    MSIE8: 4,
    MSIE7: 5,
    MSIE6: 6,
    MSIE9: 7,
    SAFARI: 8,
    MSIE10: 9,
    MSIE11: 10,
    OPERA: 11,
    APPLE_WEBKIT: 12
};
Helper.getBrowserType = function () {
    if (Helper.isIE6()) {
        return Helper.BrowserType.MSIE6
    } else {
        if (Helper.isIE7()) {
            return Helper.BrowserType.MSIE7
        } else {
            if (Helper.isIE8()) {
                return Helper.BrowserType.MSIE8
            } else {
                if (Helper.isIE9()) {
                    return Helper.BrowserType.MSIE9
                } else {
                    if (Helper.isIE10()) {
                        return Helper.BrowserType.MSIE10
                    } else {
                        if (Helper.isIE11()) {
                            return Helper.BrowserType.MSIE11
                        } else {
                            if (Helper.isMozilla()) {
                                return Helper.BrowserType.FIREFOX
                            } else {
                                if (Helper.isOpera()) {
                                    return Helper.BrowserType.OPERA
                                } else {
                                    if (Helper.isChrome()) {
                                        return Helper.BrowserType.CHROME
                                    } else {
                                        if (Helper.isSafari()) {
                                            return Helper.BrowserType.SAFARI
                                        } else {
                                            if (Helper.isAppleWebKit()) {
                                                return Helper.BrowserType.APPLE_WEBKIT
                                            } else {
                                                return Helper.BrowserType.UNKNOWN
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
};
Helper.Screen = function () {
    return {width: window.screen.width, height: window.screen.height}
};
Helper.ScreenType = {
    OTHER: 0,
    W1920H1080: 1,
    W1680H1050: 2,
    W1600H1200: 3,
    W1600H1024: 4,
    W1600H900: 5,
    W1440H900: 6,
    W1366H768: 7,
    W1360H768: 8,
    W1280H1024: 9,
    W1280H960: 10,
    W1280H800: 11,
    W1280H768: 12,
    W1280H720: 13,
    W1280H600: 14,
    W1152H864: 15,
    W1024H768: 16,
    W800H600: 17
};
Helper.getScreenType = function (b, a) {
    if (b == 1920 && a == 1080) {
        return Helper.ScreenType.W1920H1080
    } else {
        if (b == 1680 && a == 1050) {
            return Helper.ScreenType.W1680H1050
        } else {
            if (b == 1600 && a == 1200) {
                return Helper.ScreenType.W1600H1200
            } else {
                if (b == 1600 && a == 1024) {
                    return Helper.ScreenType.W1600H1024
                } else {
                    if (b == 1600 && a == 900) {
                        return Helper.ScreenType.W1600H900
                    } else {
                        if (b == 1440 && a == 900) {
                            return Helper.ScreenType.W1440H900
                        } else {
                            if (b == 1366 && a == 768) {
                                return Helper.ScreenType.W1366H768
                            } else {
                                if (b == 1360 && a == 768) {
                                    return Helper.ScreenType.W1360H768
                                } else {
                                    if (b == 1280 && a == 1024) {
                                        return Helper.ScreenType.W1280H1024
                                    } else {
                                        if (b == 1280 && a == 960) {
                                            return Helper.ScreenType.W1280H960
                                        } else {
                                            if (b == 1280 && a == 800) {
                                                return Helper.ScreenType.W1280H800
                                            } else {
                                                if (b == 1280 && a == 768) {
                                                    return Helper.ScreenType.W1280H768
                                                } else {
                                                    if (b == 1280 && a == 720) {
                                                        return Helper.ScreenType.W1280H720
                                                    } else {
                                                        if (b == 1280 && a == 600) {
                                                            return Helper.ScreenType.W1280H600
                                                        } else {
                                                            if (b == 1152 && a == 864) {
                                                                return Helper.ScreenType.W1152H864
                                                            } else {
                                                                if (b == 1024 && a == 768) {
                                                                    return Helper.ScreenType.W1024H768
                                                                } else {
                                                                    if (b == 800 && a == 600) {
                                                                        return Helper.ScreenType.W800H600
                                                                    } else {
                                                                        return Helper.ScreenType.OTHER
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
};
Helper.getCssInt = function (c, b) {
    if (c.css(b)) {
        var a = parseInt(c.css(b).replace("px", ""));
        if (isNaN(a)) {
            return 0
        }
        return a
    } else {
        return 0
    }
};
Helper.getEventX = function (a) {
    a = a || window.event;
    return a.pageX || a.clientX + document.body.scrollLeft
};
Helper.getEventY = function (a) {
    a = a || window.event;
    return a.pageY || a.clientY + document.body.scrollTop
};
Helper.inRect = function (a, b) {
    if (a.x > b.left && a.x < (b.left + b.width) && a.y > b.top && a.y < (b.top + b.height)) {
        return true
    }
    return false
};
Helper.addUrlParams = function (a, b) {
    if (Helper.isNull(b)) {
        return a
    }
    if (a.indexOf("?") < 0) {
        return a + "?" + b
    }
    return a + "&" + b
};
Helper.addArrElementsNoRepeat = function (a, c) {
    if (a.length > 0) {
        var b = 0;
        $.each(a, function (d, f) {
            if (a[d] == c) {
                b++
            }
        });
        if (b == 0) {
            a[a.length] = c
        }
    } else {
        a[a.length] = c
    }
    return a
};
Helper.getUrlRoot = function (a) {
    var b = a.indexOf("://");
    if (b < 0) {
        return a
    }
    b = a.indexOf("/", b + 3);
    if (b < 0) {
        return "/"
    }
    return a.substring(b)
};
Helper.getUrlParam = function (b, a) {
    var d = b.substring(b.indexOf("?") + 1, b.length).split("&");
    var c;
    $.each(d, function (f, h) {
        var g = decodeURIComponent(h.substring(0, h.indexOf("=")));
        if (g === a) {
            c = decodeURIComponent(h.substring(h.indexOf("=") + 1, h.length));
            return false
        }
    });
    return c
};
Helper.encodeHtml = function (a) {
    return a && a.replace ? (a.replace(/&/g, "&amp;").replace(/ /g, "&nbsp;").replace(/\b&nbsp;+/g, " ").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\\/g, "&#92;").replace(/\'/g, "&#39;").replace(/\"/g, "&quot;").replace(/\n/g, "<br/>").replace(/\r/g, "")) : a
};
Helper.decodeHtml = function (a) {
    return a && a.replace ? (a.replace(/&nbsp;/gi, " ").replace(/&lt;/gi, "<").replace(/&gt;/g, ">").replace(/&#92;/gi, "\\").replace(/&#39;/gi, "'").replace(/&quot;/gi, '"').replace(/\<br\/\>/gi, "\n").replace(/&amp;/gi, "&")) : a
};
Helper.encodeHtmlJs = function (a) {
    return a && a.replace ? (a.replace(/\\/g, "\\\\").replace(/\'/g, "\\x27").replace(/\"/g, "\\x22").replace(/\n/g, "\\n").replace(/</g, "\\x3c").replace(/>/g, "\\x3e")) : a
};
Helper.encodeHtmlAttr = function (a) {
    return a && a.replace ? (a.replace(/\"/g, "&#x22;").replace(/\'/g, "&#x27;").replace(/</g, "&#x3c;").replace(/>/g, "&#x3e;").replace(/&/g, "&#x26;")).replace(/\\/g, "&#5c;") : a
};
Helper.encodeUrl = function (a) {
    return typeof a === "undefined" ? "" : encodeURIComponent(a)
};
Helper.decodeUrl = function (b) {
    var a = "";
    try {
        a = (typeof b === "undefined" ? "" : decodeURIComponent(b))
    } catch (c) {
        a = ""
    }
    return a
};
Helper.toUN = {
    on: function (d) {
        var b = [], c = 0;
        for (; c < d.length;) {
            b[c] = ("00" + d.charCodeAt(c++).toString(16)).slice(-4)
        }
        return "\\u" + b.join("\\u")
    }, un: function (a) {
        return unescape(a.replace(/\\/g, "%"))
    }
};
Helper.parseFileName = function (b, a) {
    var d = b.lastIndexOf("/");
    if (d < 0) {
        d = b.lastIndexOf("\\")
    }
    if (d >= 0) {
        b = b.substring(d + 1)
    }
    if (a) {
        return b
    } else {
        var c = b.lastIndexOf(".");
        if (c >= 0) {
            return b.substring(0, c)
        } else {
            return b
        }
    }
};
Helper.format = function () {
    var c = arguments[0];
    for (var a = 0; a < arguments.length - 1; a++) {
        var b = new RegExp("\\{" + a + "\\}", "gm");
        c = c.replace(b, arguments[a + 1])
    }
    return c
};
Helper.checkValid = function (f, b, a, d, c) {
    var g;
    if (!f && d > 0) {
        g = Helper.format("您还未输入{0}", b)
    } else {
        if (f.length < d) {
            g = Helper.format("{0}不能少于{1}个字", b, d)
        } else {
            if (f.length > c) {
                g = Helper.format("{0}不能多于{1}个字，请裁减后重试。", b, c)
            }
        }
    }
    Helper.showErr(a, g);
    return !g
};
Helper.showErr = function (a, c) {
    var b = $("#" + a);
    if (c) {
        b.show();
        b.text(c)
    } else {
        b.hide()
    }
};
Helper.showMsg = function (b) {
    var a = $("#msg");
    if (b && b.length > 0) {
        a.show();
        a.text(b)
    } else {
        a.hide();
        a.text("")
    }
};
Helper.checkVal = function (c, d, b, a) {
    var f;
    if (d.length < b) {
        alert(Helper.format("{0}不能少于{1}个字符", c, b));
        f = false
    } else {
        if (d.length > a) {
            alert(Helper.format("{0}不能多于{1}个字符", c, a));
            f = false
        } else {
            f = true
        }
    }
    return f
};
Helper.getEl = function (a) {
    return typeof(a) == "string" ? document.getElementById(a) : a
};
Helper.getBrowserWidth = function () {
    return document.documentElement.clientWidth
};
Helper.getBrowserHeight = function () {
    return document.documentElement.clientHeight
};
Helper.delNode = function (a) {
    if (Helper.getEl(a) != null) {
        Helper.getEl(a).parentNode.removeChild(Helper.getEl(a))
    }
};
Helper.delChildNodes = function (b) {
    if (b == null || b.childNodes == null) {
        return
    }
    var c = b.childNodes.length;
    for (var a = 0; a < c; ++a) {
        b.removeChild(b.firstChild)
    }
};
Helper.showFlowMsg = function (b) {
    Helper.delNode("showFlowMsg");
    var a = document.createElement("div");
    a.id = "showFlowMsg";
    a.style.position = "absolute";
    a.style.top = "80px";
    a.style.left = "400px";
    a.style.border = "1px";
    a.style.borderTop = "0px";
    a.style.borderBottomStyle = "solid";
    a.style.borderColor = "#E9F0F4";
    a.style.height = "22px";
    a.style.lineHeight = "22px";
    a.style.width = "auto";
    a.style.paddingTop = "1px";
    a.style.paddingBottom = "1px";
    a.style.paddingLeft = "10px";
    a.style.paddingRight = "10px";
    a.style.backgroundColor = "#FFFFC4";
    a.style.zIndex = "999";
    a.style.textAlign = "left";
    a.style.fontSize = "12";
    a.innerHTML = b;
    document.body.appendChild(a);
    Helper.delay(150)
};
Helper.closeFlowMsg = function () {
    Helper.delNode("showFlowMsg")
};
Helper.delay = function (b) {
    var a = new Date();
    var c = a.getTime() + b;
    while (true) {
        a = new Date();
        if (a.getTime() > c) {
            return
        }
    }
};
Helper.Msg = {CONFIRM: 0, SUCCEED: 1, FAIL: 2, TIP: 3};
Helper.Msg.box = function (a, b) {
    Helper.delNode("msgBox");
    alert("ok")
};
Helper.Debug = {};
Helper.Debug.alert = function (c) {
    if (typeof(c) != "object") {
        alert(c);
        return
    }
    var a = "";
    for (var b in c) {
        if (typeof(c[b]) == "function") {
            a += b + "=function\t"
        } else {
            a += b + "=" + c[b] + "\t"
        }
    }
    alert(a)
};
Helper.Debug.msg = function (c) {
    if (Helper.isNull(Helper.Debug.cnt)) {
        Helper.Debug.cnt = 0
    }
    ++Helper.Debug.cnt;
    var b = "";
    b = "<div id='dbgMsg' style=\"position:absolute;  top:30px; left: 45%; margin:0px auto; width:auto;  height:auto; z-index:9999;\"></div>";
    if (Helper.top.$("#dbgMsg").length == 0) {
        Helper.top.$(b).appendTo("body")
    }
    var a = "";
    a += '<div class="tips"><div class="msg">' + Helper.Debug.cnt + ":" + c + "</div></div>";
    Helper.top.$("#dbgMsg").find(".tips").remove();
    Helper.top.$(a).appendTo("#dbgMsg")
};
Helper.Debug.track = function (d) {
    if (Helper.isNull(Helper.Debug.outtrack) || !Helper.Debug.outtrack) {
        return
    }
    var a = (new Date()).getTime();
    if (Helper.isNull(Helper.Debug.last)) {
        Helper.Debug.last = a
    }
    var c = (a - Helper.Debug.last);
    Helper.Debug.last = a;
    if (d == "" || c <= 0) {
        return
    }
    var b = "";
    b = "<div id='dbgMsg' style=\"position:absolute;  top:30px; left: 45%; margin:0px auto; width:auto;  height:auto; z-index:9999;\"></div>";
    var f = Helper.top.$("#dbgMsg");
    if (f.length == 0) {
        f = Helper.top.$(b);
        f.appendTo("body")
    }
    Helper.top.$("<div class='tips'><div class='msg' style='clear:both;'>" + d + " : " + c + "</div></div>").appendTo(f)
};
Helper.Cookie = {};
Helper.Cookie.set = function (c, f) {
    var a = arguments;
    var j = arguments.length;
    var b = (j > 2) ? a[2] : null;
    var h = (j > 3) ? a[3] : "/";
    var d = (j > 4) ? a[4] : null;
    var g = (j > 5) ? a[5] : false;
    document.cookie = c + "=" + escape(f) + ((b == null) ? "" : ("; expires=" + b.toGMTString())) + ((h == null) ? "" : ("; path=" + h)) + ((d == null) ? "" : ("; domain=" + d)) + ((g == true) ? "; secure" : "")
};
Helper.Cookie.get = function (d) {
    var b = d + "=";
    var g = b.length;
    var a = document.cookie.length;
    var f = 0;
    var c = 0;
    while (f < a) {
        c = f + g;
        if (document.cookie.substring(f, c) == b) {
            return Helper.Cookie.getCookieVal(c)
        }
        f = document.cookie.indexOf(" ", f) + 1;
        if (f == 0) {
            break
        }
    }
    return null
};
Helper.Cookie.clear = function (a) {
    if (Helper.Cookie.get(a)) {
        var b = new Date();
        b.setTime(b.getTime() - (86400 * 1000 * 1));
        Helper.Cookie.set(a, "", b)
    }
};
Helper.Cookie.clearCloseClient = function (a) {
    if (Helper.Cookie.get(a)) {
        Helper.Cookie.set(a, "", null)
    }
};
Helper.Cookie.getCookieVal = function (b) {
    var a = document.cookie.indexOf(";", b);
    if (a == -1) {
        a = document.cookie.length
    }
    return unescape(document.cookie.substring(b, a))
};
Helper.Conn = {};
Helper.Conn.requestJs = function (d, b, a) {
    oScript = document.getElementById(d);
    var c = null;
    if (typeof a == "object" && a.target) {
        c = document.getElementsByName(a.target)[0]
    } else {
        c = document.getElementsByTagName("head")[0]
    }
    if (oScript) {
        c.removeChild(oScript)
    }
    if (typeof a == "object" && a.callback) {
        b = Helper.addUrlParams(b, "_callback=" + a.callback)
    }
    if (typeof a == "object" && a.refresh) {
        b = Helper.addUrlParams(b, "_random=" + Math.random())
    }
    oScript = document.createElement("script");
    oScript.setAttribute("src", b);
    oScript.setAttribute("id", d);
    oScript.setAttribute("type", "text/javascript");
    oScript.setAttribute("language", "javascript");
    c.appendChild(oScript);
    return oScript
};
Helper.IFrame = {};
Helper.IFrame.autoHeight = function (f, a) {
    var b = $("#" + f);
    var c = b[0].contentWindow.document;
    if (c) {
        var d = c.createElement("div");
        c.body.appendChild(d);
        d.style.clear = "both";
        d.style.margin = "0px";
        d.style.padding = "0px";
        d.style.fontSize = "1px"
    }
    if (a) {
        Helper.IFrame.doAutoHeight(f, a)
    } else {
        Helper.IFrame.doAutoHeight(f)
    }
    if (a) {
        setInterval("Helper.IFrame.doAutoHeight('" + f + "','" + a + "')", 1000)
    } else {
        setInterval("Helper.IFrame.doAutoHeight('" + f + "')", 1000)
    }
};
Helper.IFrame.doAutoHeight = function (f, b) {
    try {
        var c = $("#" + f);
        if (c.length < 0) {
            return
        }
        var a = 0;
        if (b) {
            a = c[0].contentWindow.$("#" + b).height()
        } else {
            if (Helper.isIE6()) {
                a = c[0].contentWindow.document.body.scrollHeight
            } else {
                a = c[0].contentWindow.$("body").height()
            }
        }
        if (a != c.height()) {
            c.height(a)
        }
    } catch (d) {
    }
};
Helper.ptInRect = function (b, a) {
    if (b.x >= a.left && b.x <= a.left + a.width && b.y >= a.top && b.y <= a.top + a.height) {
        return true
    }
    return false
};
Helper.Img = {};
Helper.Img = {
    MODE_SCALE_FILL: 1,
    MODE_SCALE_WIDTH: 2,
    MODE_SCALE_HEIGHT: 3,
    MODE_SCALE_DEFLATE_WIDTH: 4,
    MODE_SCALE_DEFLATE_HEIGHT: 5,
    MODE_SCALE_DEFLATE_FILL: 6,
    MODE_SCALE_DEFLATE_MAX: 7
};
Helper.Img.optimize = function (d, g) {
    var b = new Image();
    b.src = d.src;
    var c = b.width;
    var a = b.height;
    if (Helper.isNull(c) || c == 0 || Helper.isNull(a) || a == 0) {
        c = d.width;
        a = d.height
    }
    var f = Helper.Img.calcSize(c, a, g.width, g.height, g.mode);
    d.width = f.width;
    d.height = f.height;
    if (g.display == 1) {
        d.style.display = "inline"
    } else {
        if (g.display == 2) {
            d.style.display = "none"
        } else {
            if (g.display == 3) {
                d.style.display = "inline-block"
            } else {
                d.style.display = "block"
            }
        }
    }
    return {width: d.width, height: d.height}
};
Helper.Img.calcSize = function (f, a, h, g, j) {
    var c = {width: f, height: a};
    if (j == Helper.Img.MODE_SCALE_FILL) {
        var d = f / h;
        var b = a / g;
        if (d > b) {
            c.width = h;
            c.height = a / d
        } else {
            c.width = f / b;
            c.height = g
        }
    } else {
        if (j == Helper.Img.MODE_SCALE_WIDTH) {
            var d = f / h;
            c.width = h;
            c.height = a / d
        } else {
            if (j == Helper.Img.MODE_SCALE_HEIGHT) {
                var b = a / g;
                c.width = f / b;
                c.height = g
            } else {
                if (j == Helper.Img.MODE_SCALE_DEFLATE_WIDTH) {
                    var d = f / h;
                    if (d > 1) {
                        c.width = h;
                        c.height = a / d
                    }
                } else {
                    if (j == Helper.Img.MODE_SCALE_DEFLATE_HEIGHT) {
                        var b = a / g;
                        if (b > 1) {
                            c.width = f / b;
                            c.height = g
                        }
                    } else {
                        if (j == Helper.Img.MODE_SCALE_DEFLATE_FILL) {
                            var d = f / h;
                            var b = a / g;
                            if (d > b) {
                                if (d > 1) {
                                    c.width = h;
                                    c.height = a / d
                                }
                            } else {
                                if (b > 1) {
                                    c.width = f / b;
                                    c.height = g
                                }
                            }
                        } else {
                            if (j == Helper.Img.MODE_SCALE_DEFLATE_MAX) {
                                if (f > h && a > g) {
                                    var d = f / h;
                                    var b = a / g;
                                    if (d < b) {
                                        c.width = h;
                                        c.height = a / d
                                    } else {
                                        c.width = f / b;
                                        c.height = g
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    c.width = Math.floor(c.width);
    c.height = Math.floor(c.height);
    if (c.width == 0) {
        c.width = 1
    }
    if (c.height == 0) {
        c.height = 1
    }
    return c
};
Helper.ing = function (n, m, k) {
    var f = (n == null || n == "") ? "正在处理..." : n;
    var d = Helper.top.document.body.clientWidth;
    var a = Helper.top.document.body.clientHeight;
    var j = "";
    var g = "position:absolute; top:50px; left: 50%; margin:0px auto; width:auto;  height:auto; z-index:9999;";
    var o = "transition: opacity ease .6s; -moz-transition: opacity ease .6s; -webkit-transition: opacity ease .6s; -o-transition: opacity ease .6s; opacity: 0; -webkit-opacity: 0; -moz-opacity: 0; -khtml-opacity: 0; filter:alpha(opacity=0);";
    j = "<div id='ing' style='" + g + o + "'></div>";
    if (Helper.top.$("#ing").length == 0) {
        Helper.top.$(j).appendTo("body")
    }
    var b = Helper.top.$("#ing");
    var q = Helper.top.$(document).scrollTop();
    if (Helper.isIE() && q == 0) {
        q = Helper.top.$("html").scrollTop()
    }
    if (q > 0) {
        b.css("top", ((q) + 50) + "px")
    }
    var c = parseInt(Math.random() * 10000);
    var l = "";
    l += '<div id="' + c + '" class="tips"><div class="msg">' + f + "</div><div class='close' onclick=\"Helper.top.Helper.removeIng(false, " + c + ');"></div></div>';
    b.find(".tips").remove();
    Helper.top.$(l).appendTo(b);
    var h = Helper.top.$(b).width();
    Helper.top.$(b).css("left", (Helper.top.document.documentElement.clientWidth - h) / 2);
    if (m) {
        Helper.top.Helper.removeIng(m, c, k)
    }
    var p = Helper.isIE6() || Helper.isIE7() || Helper.isIE8();
    if (p) {
        Helper.top.$("#ing").animate({opacity: 1, filter: "alpha(opacity=100)"}, 300)
    } else {
        Helper.top.$("#ing").css({opacity: 1})
    }
    Helper.top.$("#ing").find(".close").bind("mouseenter", function () {
        $(this).addClass("close_hover")
    }).bind("mouseleave", function () {
        $(this).removeClass("close_hover")
    })
};
Helper.ing2 = function (n, m, k) {
    var f = (n == null || n == "") ? "正在处理..." : n;
    var d = Helper.top.document.body.clientWidth;
    var a = Helper.top.document.body.clientHeight;
    var j = "";
    var g = " margin:180px auto; width:500px;  height:auto; z-index:9999;";
    var o = "transition: opacity ease .6s; -moz-transition: opacity ease .6s; -webkit-transition: opacity ease .6s; -o-transition: opacity ease .6s; opacity: 0; -webkit-opacity: 0; -moz-opacity: 0; -khtml-opacity: 0; filter:alpha(opacity=0);";
    j = "<div id='ing2' style='" + g + o + "'></div>";
    if (Helper.top.$("#ing2").length == 0) {
        Helper.top.$(j).appendTo("body")
    }
    var b = Helper.top.$("#ing2");
    var q = Helper.top.$("body").scrollTop();
    if (Helper.isIE() && q == 0) {
        q = Helper.top.$("html").scrollTop()
    }
    if (q > 0) {
        b.css("top", (q) + 50 + "px")
    } else {
        b.css("top", (q) + 200 + "px")
    }
    var c = parseInt(Math.random() * 10000);
    var l = "";
    l += '<div id="' + c + '" class="tips2"><div class="msg2">' + f + "</div><div class='close' onclick=\"Helper.top.Helper.removeIng2(false, " + c + ');Helper.top.Helper.removeBg();"></div></div>';
    b.find(".tips2").remove();
    Helper.top.$(l).appendTo(b);
    var h = Helper.top.$(b).width();
    Helper.top.$(b).css("left", (Helper.top.document.documentElement.clientWidth - h) / 2);
    if (m) {
        Helper.top.Helper.removeIng(m, c, k)
    }
    var p = Helper.isIE6() || Helper.isIE7() || Helper.isIE8();
    if (p) {
        Helper.top.$("#ing2").animate({opacity: 1, filter: "alpha(opacity=100)"}, 300)
    } else {
        Helper.top.$("#ing2").css({opacity: 1})
    }
    Helper.top.$("#ing2").find(".close").bind("mouseenter", function () {
        $(this).addClass("close_hover")
    }).bind("mouseleave", function () {
        $(this).removeClass("close_hover")
    })
};
Helper.removeAllIng = function () {
    Helper.top.$("#ing").remove()
};
Helper.removeIng = function (a, c, b) {
    if (a) {
        if (typeof c != "undefined" && Helper.top.$("#" + c).length > 0) {
            Helper.top.window.setTimeout(function () {
                $("#" + c).fadeOut(1000)
            }, (b ? b : 3000));
            Helper.top.window.setTimeout(function () {
                $("#" + c).remove()
            }, (b ? b + 1500 : 4500))
        } else {
            Helper.top.$(".tips").fadeOut(1000);
            Helper.top.window.setTimeout(function () {
                $("#ing").remove()
            }, (b ? b : 3000))
        }
    } else {
        if (typeof c != "undefined" && Helper.top.$("#" + c).length > 0) {
            Helper.top.$("#" + c).fadeOut(500);
            Helper.top.window.setTimeout(function () {
                $("#" + c).remove()
            }, 1000)
        } else {
            Helper.top.$(".tips").fadeOut(500);
            Helper.top.window.setTimeout(function () {
                $("#ing").remove()
            }, 1000)
        }
    }
    Helper.top.$("#ing").css("opacity", 0)
};
Helper.removeIng2 = function (a, c, b) {
    if (a) {
        if (typeof c != "undefined" && Helper.top.$("#" + c).length > 0) {
            Helper.top.window.setTimeout(function () {
                $("#" + c).fadeOut(1000)
            }, (b ? b : 3000));
            Helper.top.window.setTimeout(function () {
                $("#" + c).remove()
            }, (b ? b + 1500 : 4500))
        } else {
            Helper.top.$(".tips").fadeOut(1000);
            Helper.top.window.setTimeout(function () {
                $("#ing2").remove()
            }, (b ? b : 3000))
        }
    } else {
        if (typeof c != "undefined" && Helper.top.$("#" + c).length > 0) {
            Helper.top.$("#" + c).fadeOut(500);
            Helper.top.window.setTimeout(function () {
                $("#" + c).remove()
            }, 1000)
        } else {
            Helper.top.$(".tips").fadeOut(500);
            Helper.top.window.setTimeout(function () {
                $("#ing2").remove()
            }, 1000)
        }
    }
    Helper.top.$("#ing2").css("opacity", 0)
};
Helper.bg = function (h, c, g) {
    var d = "", b = "", a = "";
    if (Helper.top.$(".popupBg").length > 0) {
        c = 0.15
    }
    if (c) {
        b = "filter: alpha(opacity=" + c * 100 + "); opacity:" + c + ";"
    }
    if (g) {
        a = " z-index:" + (g - 1)
    }
    if (Helper.isIE6()) {
        var f = Helper.top.$("html").scrollTop();
        Helper.top.$("html").data("scrollTop", f);
        Helper.top.$("html").scrollTop(0);
        var f = Helper.top.$("body").scrollTop();
        Helper.top.$("body").data("scrollTop", f);
        Helper.top.$("body").scrollTop(0);
        Helper.top.$("html").data("overflow-x", Helper.top.$("html").css("overflow-x"));
        Helper.top.$("html").data("overflow-y", Helper.top.$("html").css("overflow-y"));
        Helper.top.$("html").css("overflow-x", "hidden");
        Helper.top.$("html").css("overflow-y", "hidden");
        Helper.top.$("body").data("overflow-x", Helper.top.$("body").css("overflow-x"));
        Helper.top.$("body").data("overflow-y", Helper.top.$("body").css("overflow-y"));
        Helper.top.$("body").css("overflow-x", "hidden");
        Helper.top.$("body").css("overflow-y", "hidden")
    }
    if (Helper.isIE6() || Helper.isIE7() || Helper.isIE8()) {
        if (Helper.top.$("html").css("filter")) {
            Helper.top.$("html").data("filter", Helper.top.$("html").css("filter"));
            Helper.top.$("html").css("filter", "none")
        }
    }
    d = '<div id="popupBg' + h + '" class="popupBg popupBgForWin" style=\'' + b + a + "' onclick='Helper.logDog(200046, 40);'>" + ($.browser.msie && $.browser.version == 6 ? '<iframe id="fixSelectIframe' + h + '" wmode="transparent" style="filter: alpha(opacity=0);opacity: 0;" class="popupBg" style="z-index:-111" src="javascript:"></iframe>' : "") + "</div>";
    Helper.top.$(d).appendTo("body");
    Helper.stopInterval(null)
};
Helper.removeBg = function (a) {
    if (a) {
        Helper.top.$("#popupBg" + a).remove()
    } else {
        Helper.top.$(".popupBg").remove()
    }
    if (Helper.isIE6()) {
        Helper.top.$("html").css("overflow-x", Helper.top.$("html").data("overflow-x"));
        Helper.top.$("html").css("overflow-y", Helper.top.$("html").data("overflow-y"));
        Helper.top.$("body").css("overflow-x", Helper.top.$("body").data("overflow-x"));
        Helper.top.$("body").css("overflow-y", Helper.top.$("body").data("overflow-y"));
        Helper.top.$("html").scrollTop(Helper.top.$("html").data("scrollTop"));
        Helper.top.$("body").scrollTop(Helper.top.$("body").data("scrollTop"))
    }
    if (Helper.isIE6() || Helper.isIE7() || Helper.isIE8()) {
        if (Helper.top.$("html").data("filter")) {
            Helper.top.$("html").css("filter", Helper.top.$("html").data("filter"))
        }
    }
    Helper.startInterval(null)
};
Helper.removeBg = function (a) {
    if (a) {
        Helper.top.$("#popupBg" + a).remove()
    } else {
        Helper.top.$(".popupBg").remove()
    }
    if (Helper.isIE6()) {
        Helper.top.$("html").css("overflow-x", Helper.top.$("html").data("overflow-x"));
        Helper.top.$("html").css("overflow-y", Helper.top.$("html").data("overflow-y"));
        Helper.top.$("body").css("overflow-x", Helper.top.$("body").data("overflow-x"));
        Helper.top.$("body").css("overflow-y", Helper.top.$("body").data("overflow-y"));
        Helper.top.$("html").scrollTop(Helper.top.$("html").data("scrollTop"));
        Helper.top.$("body").scrollTop(Helper.top.$("body").data("scrollTop"))
    }
    if (Helper.isIE6() || Helper.isIE7() || Helper.isIE8()) {
        if (Helper.top.$("html").data("filter")) {
            Helper.top.$("html").css("filter", Helper.top.$("html").data("filter"))
        }
    }
    Helper.startInterval(null)
};
Helper.bodyBg = function (h, d, c) {
    var g = "", a = "", f = c || {}, b = f.extClass || "";
    if (d) {
        a = "filter: alpha(opacity=" + d * 100 + "); opacity:" + d + ";"
    }
    if (Helper.isIE6()) {
        $("body").data("height", $("body").css("height"));
        $("body").css("height", "100%")
    }
    g = '<div id="popupBg' + h + '" class="popupBg ' + b + "\" style='" + a + "' >" + ($.browser.msie && $.browser.version == 6 ? '<iframe id="fixSelectIframe' + h + '" wmode="transparent" style="filter: alpha(opacity=0);opacity: 0;" class="popupBg" style="z-index:-111" src="javascript:"></iframe>' : "") + "</div>";
    $(g).appendTo("body")
};
Helper.removeBodyBg = function (a) {
    if (Helper.isIE6()) {
        $("body").css("height", $("body").data("height"))
    }
    if (a) {
        $("#popupBg" + a).remove()
    } else {
        $(".popupBg").remove()
    }
};
Helper.popupWindow = function (g) {
    var y = {
        title: "",
        width: 500,
        height: 300,
        frameSrcUrl: "about:_blank",
        frameScrolling: "auto",
        bannerDisplay: true,
        framePadding: true,
        opacity: "0.5",
        displayBg: true,
        bgClose: false,
        closeBtnClass: "",
        waitingPHide: true
    };
    y = $.extend(y, g);
    var u = parseInt(y.width), x = parseInt(y.height);
    var h = Helper.top.document.documentElement.clientWidth;
    if (!$.browser.msie) {
        h = Helper.top.document.body.clientWidth
    }
    var o = Helper.top.document.documentElement.clientHeight;
    var k = (h - u) / 2;
    if (y.leftMar != null) {
        k = parseInt(y.leftMar)
    }
    var F = 80;
    if (!y.bannerDisplay) {
        F = 0
    }
    var s = (o - x - F) / 2;
    if (y.topMar != null) {
        s = parseInt(y.topMar)
    }
    var z = "", D = "", j = "";
    if (!y.bannerDisplay) {
        D = "display:none;";
        z = "background:none;";
        if (!y.closeBtnClass) {
            y.closeBtnClass = "formX_old"
        }
    }
    if (!y.framePadding) {
        z += "padding:0;";
        j = 'allowtransparency="true"'
    }
    var t = parseInt(Math.random() * 10000),
        f = "<iframe " + j + " id='popupWindowIframe" + t + "' class='popupWindowIframe' src='' frameborder='0' scrolling='" + y.frameScrolling + "' style='width:100%;height:100%;'></iframe>",
        v = true;
    if (y.divId != null) {
        v = false;
        f = $(y.divId).html()
    }
    if (y.divContent != null) {
        v = false;
        f = y.divContent
    }
    if (y.displayBg) {
        Helper.bg(t, y.opacity)
    }
    var r = "";
    var d = Helper.top.$("body").scrollTop();
    if (d == 0) {
        d = Helper.top.$("html").scrollTop()
    }
    var w = "left:" + k + "px; top:" + (s + d) + "px;";
    if (Helper.isIE6() || Helper.isIE7()) {
        w += "width:" + u + "px;"
    }
    var c = "position:relative;width:" + u + "px;height:" + x + "px;", a = "";
    if ($.browser.msie) {
        a = '<iframe id="fixFlashIframe' + t + '" style="position:absolute;z-index:-1;left:0;top:0;" frameborder="0" width="100%" height="100%" src="javascript:"></iframe>'
    }
    var r = ['<div id="popupWindow' + t + '" class="formDialog" style="' + w + '">', a, '<div class="formTL" style=\'' + D + '\'><div class="formTR"><div class="formTC">' + y.title + "</div></div></div>", '<div class="formBL" style=\'' + z + "'>", '<div class="formBR" style=\'' + z + "'>", '<div class="formBC" id="formBC' + t + '" style="height:auto;' + z + '">', '<div class="formMSG" style="' + c + '">', f, "</div>", "<table cellpadding='0' cellspacing='0' class='formBtns'>", "<tr><td align='center' style='padding:15px 0px;'></td></tr>", "</table>", "</div>", '<div id="waitingP' + t + '" class="waitingP" style="height:auto;"></div>', "</div>", "</div>", '<a href="javascript:;" class="formX ' + y.closeBtnClass + "\" hidefocus='true' onclick='return false;'></a>", "</div>"];
    var q = Helper.top.$(r.join("")).appendTo("body");
    if (Helper.isIE6() || Helper.isIE7()) {
        var b = q.find(".formBL");
        var p = Helper.getCssInt(b, "padding-left") + Helper.getCssInt(b, "padding-right") + Helper.getCssInt(b, "border-left-width") + Helper.getCssInt(b, "border-right-width");
        var E = q.find(".formBR");
        var B = Helper.getCssInt(E, "padding-left") + Helper.getCssInt(E, "padding-right") + Helper.getCssInt(E, "border-left-width") + Helper.getCssInt(E, "border-right-width");
        var m = q.find(".formBC");
        var A = Helper.getCssInt(m, "padding-left") + Helper.getCssInt(m, "padding-right") + Helper.getCssInt(m, "border-left-width") + Helper.getCssInt(m, "border-right-width");
        q.css("width", (u + p + B + A) + "px")
    }
    var C = 40;
    var l = 20;
    if (!y.bannerDisplay) {
        C = 0
    }
    if (q.height() + C > (o - l)) {
        var n = q.height() + C - q.find(".formMSG").height();
        q.find(".formMSG").css("height", (o - l - n) + "px");
        q.css("top", (10 + d) + "px")
    }
    if (v) {
        Helper.top.$("#waitingP" + t).height(Helper.top.$("#formBC" + t).height()).width(Helper.top.$("#formBC" + t).width())
    } else {
        if (y.waitingPHide) {
            Helper.top.$("#waitingP" + t).hide()
        }
    }
    if (y.divInit != null) {
        y.divInit(t)
    }
    Helper.top.$("#popupWindow" + t).ready(function () {
        if (v) {
            var G = "popupID=" + t;
            Helper.top.$("#popupWindowIframe" + t).attr("src", Helper.addUrlParams(y.frameSrcUrl, G)).load(function () {
                if (y.waitingPHide) {
                    Helper.top.$("#waitingP" + t).hide()
                }
            })
        }
        q.draggable({
            start: function () {
                Helper.top.$("body").disableSelection();
                Helper.top.$("#colorpanel").remove();
                Helper.top.$(".faiColorPicker").remove()
            }, handle: ".formTL", stop: function () {
                Helper.top.$("body").enableSelection();
                Helper.logDog(200046, 39)
            }
        });
        q.find(".formX").bind("click", function () {
            if (Helper.isNull(g.msg)) {
                Helper.closePopupWindow(t)
            } else {
                Helper.closePopupWindow(t, undefined, g.msg)
            }
            return false
        });
        q.find(".formTL").disableSelection();
        if (y.bgClose) {
            Helper.top.$("#popupBg" + t).bind("click", function () {
                if (Helper.isNull(g.msg)) {
                    Helper.closePopupWindow(t)
                } else {
                    Helper.closePopupWindow(t, undefined, g.msg)
                }
                return false
            })
        }
    });
    if (Helper.isNull(Helper.top._popupOptions)) {
        Helper.top._popupOptions = {}
    }
    if (Helper.isNull(Helper.top._popupOptions["popup" + t])) {
        Helper.top._popupOptions["popup" + t] = {}
    }
    if (!Helper.isNull(g.callArgs)) {
        Helper.top._popupOptions["popup" + t].callArgs = g.callArgs
    }
    Helper.top._popupOptions["popup" + t].options = g;
    Helper.top._popupOptions["popup" + t].change = false;
    return t
};
(function (c, a, f) {
    a.createPopupWindow = function (g) {
        return new d(g)
    };
    a.addPopupWindowButton = function (h) {
        var l = {
            popupId: 0,
            id: "",
            extClass: "",
            text: "",
            msg: "",
            popUpWinClass: "",
            popUpWinZIndex: 0,
            disable: false,
            click: function () {
            },
            callback: null
        }, g, o, j, p, m, k;
        l = c.extend(l, h);
        g = l.popupId;
        o = Helper.top.$("#popupWindow" + g);
        j = o.find(".pWBottom");
        p = o.find(".pWBtns");
        m = "popup" + g + l.id;
        k = p.find("#" + m);
        if (k.length > 0) {
            k.remove()
        }
        if (l.click != "help") {
            if (typeof l.extClass != "undefined") {
                var n = l.extClass;
                Helper.top.$("<input id='" + m + "' type='button' value='" + l.text + "' class='editbutton' extClass='" + n + "' />").appendTo(p)
            } else {
                Helper.top.$("<input id='" + m + "' type='button' value='" + l.text + "' class='editbutton' />").appendTo(p)
            }
        }
        k = p.find("#" + m);
        if (typeof k.faiButton == "function") {
            k.faiButton()
        }
        if (l.callback && Object.prototype.toString.call(l.callback) === "[object Function]") {
            k.click(function () {
                l.callback();
                if (Helper.isNull(l.msg)) {
                    Helper.top.Helper.closePopupWindow(g)
                } else {
                    Helper.top.Helper.closePopupWindow(g, f, l.msg)
                }
            })
        }
        if (l.click == "close") {
            k.click(function () {
                if (Helper.isNull(l.msg)) {
                    Helper.top.Helper.closePopupWindow(g)
                } else {
                    Helper.top.Helper.closePopupWindow(g, f, l.msg)
                }
            })
        } else {
            k.click(l.click)
        }
        if (l.disable) {
            k.attr("disabled", true);
            k.faiButton("disable")
        }
        c(document).keydown(function (r) {
            if (r.keyCode == 13) {
                var q = o.find("#popup" + g + "save"), s;
                if (q.length > 0 && !q.prop("disabled")) {
                    var s = c(":focus");
                    if (s.is("input[type='text']") || s.is("textarea")) {
                        return
                    }
                    q.trigger("click")
                }
            }
        })
    };
    var d = function (g) {
        var h = {
            title: "",
            width: 500,
            height: 300,
            frameSrcUrl: "about:_blank",
            frameScrolling: "auto",
            bannerDisplay: true,
            framePadding: true,
            opacity: "0.5",
            displayBg: true,
            bgClose: false,
            waitingPHide: true,
            divId: null,
            divContent: null,
            msg: "",
            popUpWinClass: "",
            popUpWinZIndex: 0
        };
        this.settings = c.extend(h, g);
        this.contentWidth = parseInt(this.settings.width);
        this.contentHeight = parseInt(this.settings.height);
        this.popUpWinClass = this.settings.popUpWinClass;
        this.popupWindowId = parseInt(Math.random() * 10000);
        this.iframeMode = true;
        this.popupWin;
        this.pWHead;
        this.pWHeadHeight;
        this.pwLoading;
        this.init();
        b(this)
    };

    function b(h) {
        var j = h, g = j.popupWin;
        g.ready(function () {
            var k = j.popupWindowId;
            if (j.iframeMode) {
                var l = "popupID=" + k;
                Helper.top.$("#popupWindowIframe" + k).attr("src", Helper.addUrlParams(j.settings.frameSrcUrl, l)).load(function () {
                    var n = j.popupWin.find(".pWBottom");
                    var m = n.height();
                    if (j.popUpWinClass != "fileUploadV2") {
                        j.popupWin.find(".pWCenter").height(j.contentHeight - j.pWHeadHeight - m).find(".pWCenter").width(j.contentWidth)
                    }
                    if (j.settings.waitingPHide) {
                        j.pwLoading.hide()
                    }
                })
            }
            g.draggable({
                start: function () {
                    Helper.top.$("body").disableSelection();
                    Helper.top.$("#colorpanel").remove();
                    Helper.top.$(".faiColorPicker").remove()
                }, handle: ".pWHead", stop: function () {
                    Helper.top.$("body").enableSelection();
                    Helper.logDog(200046, 39)
                }
            });
            g.find(".J_pWHead_close").bind("click", function () {
                var m = j.popupWindowId;
                if (Helper.isNull(j.settings.msg)) {
                    Helper.closePopupWindow(m)
                } else {
                    Helper.closePopupWindow(m, f, j.settings.msg)
                }
                return false
            });
            g.find(".J_pWHead_close").disableSelection();
            if (j.settings.bgClose) {
                Helper.top.$("#popupBg" + id).bind("click", function () {
                    if (Helper.isNull(j.settings.msg)) {
                        Helper.closePopupWindow(id)
                    } else {
                        Helper.closePopupWindow(id, f, j.settings.msg)
                    }
                    return false
                })
            }
        })
    }

    c.extend(d.prototype, {
        init: function () {
            var k, h, g;
            h = this.popupWindowId;
            k = "<iframe id='popupWindowIframe" + h + "' class='popupWindowIframe' src='' frameborder='0' scrolling='" + this.settings.frameScrolling + "' style='width:100%;height:100%;'></iframe>";
            if (this.settings.divId != null) {
                this.iframeMode = false;
                k = c(this.settings.divId).html()
            }
            if (this.settings.divContent != null) {
                this.iframeMode = false;
                k = this.settings.divContent
            }
            if (this.settings.displayBg) {
                Helper.bg(h, this.settings.opacity, this.settings.popUpWinZIndex)
            }
            var j = "";
            if (this.settings.popUpWinZIndex) {
                j = "z-index: " + this.settings.popUpWinZIndex
            }
            g = '<div id="popupWindow' + h + "\" class='fk-popupWindowVT " + this.settings.popUpWinClass + "' style='width:" + this.contentWidth + "px;height:" + this.contentHeight + "px; " + j + "'><div class='pWHead'><div class='pWHead_title'>" + this.settings.title + "</div><div class='pWHead_close'><div class='J_pWHead_close pWHead_close_img'></div></div></div><div class='pWCenter'>" + k + "<div class='tabs_extendedLine' style='display:none;'></div></div><div class='pWBottom'><div class='pWBtns'></div></div><div id=\"pwLoading" + h + "\" class='pwLoading' style='height:auto;'></div></div>";
            Helper.top.$("body").append(g);
            this.popupWin = Helper.top.$("#popupWindow" + h);
            this.pWHead = this.popupWin.find(".pWHead");
            this.pWHeadHeight = this.pWHead.height();
            this.pwLoading = this.popupWin.find("#pwLoading" + h);
            if (this.iframeMode) {
                this.pwLoading.height(this.contentHeight - this.pWHeadHeight).width(this.contentWidth)
            } else {
                if (this.settings.waitingPHide) {
                    this.pwLoading.hide()
                }
            }
            if (Helper.isNull(Helper.top._popupOptions)) {
                Helper.top._popupOptions = {}
            }
            if (Helper.isNull(Helper.top._popupOptions["popup" + h])) {
                Helper.top._popupOptions["popup" + h] = {}
            }
            if (!Helper.isNull(this.settings.callArgs)) {
                Helper.top._popupOptions["popup" + h].callArgs = this.settings.callArgs
            }
            Helper.top._popupOptions["popup" + h].options = this.settings;
            Helper.top._popupOptions["popup" + h].change = false
        }, getPopupWindowId: function () {
            return this.popupWindowId
        }
    })
})(jQuery, Helper.popupWindowVersionTwo || (Helper.popupWindowVersionTwo = {}));
Helper.setPopupWindowChange = function (b, a) {
    if (Helper.isNull(Helper.top._popupOptions)) {
        return
    }
    if (Helper.isNull(Helper.top._popupOptions["popup" + b])) {
        return
    }
    Helper.top._popupOptions["popup" + b].change = a
};
Helper.closePopupWindow = function (h, d, g) {
    if (h) {
        try {
            if (Helper.isNull(Helper.top._popupOptions["popup" + h])) {
                return
            }
            var c = Helper.top._popupOptions["popup" + h];
            if (c.change) {
                if (typeof(g) == "undefined" || g == "") {
                    if (!window.confirm("您的修改尚未保存，确定要离开吗？")) {
                        return
                    }
                } else {
                    if (!window.confirm(g)) {
                        return
                    }
                }
            }
            if (c.refresh) {
                Helper.top.location.reload();
                return
            }
            Helper.top.Helper.removeAllIng(false);
            var b = c.options;
            if (!Helper.isNull(b.closeFunc)) {
                if (d) {
                    b.closeFunc(d)
                } else {
                    b.closeFunc(Helper.top._popupOptions["popup" + h].closeArgs)
                }
            }
            Helper.top._popupOptions["popup" + h] = {};
            var a = Helper.top.$("#popupWindow" + h);
            if (b.animate) {
                Helper.top.Helper.closePopupWindowAnimate(a, b.animateTarget, b.animateOnClose)
            }
            Helper.top.setTimeout("Helper.closePopupWindow_Internal('" + h + "')")
        } catch (f) {
            console.log(f)
        }
    } else {
        Helper.removeBg();
        Helper.top.$(".formDialog").remove()
    }
};
Helper.closePopupWindow_Internal = function (c) {
    if (typeof c == "undefined") {
        if ($.browser.msie && $.browser.version == 10) {
            var a = Helper.top.$(".formDialog").find(".popupWindowIframe")[0];
            if (a) {
                popupWindowIframeWindow = a.contentWindow;
                if (popupWindowIframeWindow) {
                    try {
                        if (popupWindowIframeWindow.swfObj) {
                            popupWindowIframeWindow.swfObj.destroy()
                        }
                        if (popupWindowIframeWindow.editor) {
                            if (popupWindowIframeWindow.editor.swfObj) {
                                popupWindowIframeWindow.editor.swfObj.destroy()
                            }
                        }
                    } catch (b) {
                    }
                }
            }
        }
        Helper.top.$(".popupBg").remove();
        Helper.top.$(".formDialog").remove()
    } else {
        if ($.browser.msie && $.browser.version == 10) {
            var a = Helper.top.document.getElementById("popupWindowIframe" + c);
            if (a) {
                popupWindowIframeWindow = a.contentWindow;
                if (popupWindowIframeWindow) {
                    try {
                        if (popupWindowIframeWindow.swfObj) {
                            popupWindowIframeWindow.swfObj.destroy()
                        }
                        if (popupWindowIframeWindow.editor) {
                            if (popupWindowIframeWindow.editor.swfObj) {
                                popupWindowIframeWindow.editor.swfObj.destroy()
                            }
                        }
                    } catch (b) {
                    }
                }
            }
        }
        Helper.top.Helper.removeBg(c);
        Helper.top.$("#popupWindowIframe" + c).remove();
        Helper.top.$("#popupWindow" + c).remove()
    }
};
Helper.closePopupWindowAnimate = function (b, f, d) {
    var c = $("<div>");
    Helper.top.$("body").append(c);
    c.css({
        border: "1px solid #ff4400",
        position: "absolute",
        "z-index": "9999",
        top: b.offset().top,
        left: b.offset().left,
        height: b.height() + "px",
        width: b.width() + "px"
    });
    var a = Helper.top.$("body").find(f);
    c.animate({
        top: a.offset().top + "px",
        left: a.offset().left + "px",
        width: a.width() + "px",
        height: a.height() + "px"
    }, "slow", function () {
        if (typeof d == "function") {
            d()
        }
        c.remove()
    })
};
Helper.addPopupWindowBtn = function (h, b) {
    var f = Helper.top.$("#popupWindow" + h);
    f.find(".formBtns").show();
    var g = "popup" + h + b.id;
    var d = f.find(".formBtns td");
    var c = d.find("#" + g);
    if (c.length > 0) {
        c.remove()
    }
    if (b.click != "help") {
        if (typeof b.extClass != "undefined") {
            var a = b.extClass;
            Helper.top.$("<input id='" + g + "' type='button' value='" + b.text + "' class='abutton faiButton' extClass='" + a + "'></input>").appendTo(d)
        } else {
            Helper.top.$("<input id='" + g + "' type='button' value='" + b.text + "' class='abutton faiButton'></input>").appendTo(d)
        }
    }
    c = d.find("#" + g);
    if (typeof c.faiButton == "function") {
        c.faiButton()
    }
    if (b.callback && Object.prototype.toString.call(b.callback) === "[object Function]") {
        c.click(function () {
            b.callback();
            if (Helper.isNull(b.msg)) {
                Helper.top.Helper.closePopupWindow(h)
            } else {
                Helper.top.Helper.closePopupWindow(h, undefined, b.msg)
            }
        })
    }
    if (b.click == "close") {
        c.click(function () {
            if (Helper.isNull(b.msg)) {
                Helper.top.Helper.closePopupWindow(h)
            } else {
                Helper.top.Helper.closePopupWindow(h, undefined, b.msg)
            }
        })
    } else {
        if (b.click == "help") {
            if (f.find("a.formH").length == 0) {
                f.append("<a class='formH' href='" + b.helpLink + "' target='_blank' title='" + b.text + "'></a>")
            }
        } else {
            c.click(b.click)
        }
    }
    if (b.disable) {
        c.attr("disabled", true);
        c.faiButton("disable")
    }
    $(document).keydown(function (k) {
        if (k.keyCode == 13) {
            var j = f.find("#popup" + h + "save"), l;
            if (j.length > 0 && !j.prop("disabled")) {
                var l = $(":focus");
                if (l.is("input[type='text']") || l.is("textarea")) {
                    return
                }
                j.trigger("click")
            }
        }
    })
};
Helper.enablePopupWindowBtn = function (f, d, a) {
    var c = Helper.top.$("#popupWindow" + f);
    d = "popup" + f + d;
    var b = c.find("#" + d);
    if (a) {
        b.removeAttr("disabled");
        b.faiButton("enable")
    } else {
        b.attr("disabled", true);
        b.faiButton("disable")
    }
};
Helper.popupBodyWindow = function (d) {
    var t = {
        title: "",
        width: 500,
        height: 300,
        bannerDisplay: true,
        opacity: "0.3",
        displayBg: true,
        window_extClass: "",
        bg_extClass: ""
    };
    t = $.extend(t, d);
    var p = parseInt(t.width);
    var s = parseInt(t.height);
    var c = $("body").scrollTop();
    if (c == 0) {
        c = $("html").scrollTop()
    }
    var f = document.documentElement.clientWidth;
    if (!$.browser.msie) {
        f = document.body.clientWidth
    }
    var j = document.documentElement.clientHeight;
    var x = "";
    var u = "";
    if (!t.bannerDisplay) {
        x = "display:none;";
        u = "background:none;"
    }
    var g = 20;
    if (t.leftMar != null) {
        g = t.leftMar
    } else {
        g = (f - p) / 2
    }
    var n = 20;
    if (t.topMar != null) {
        n = t.topMar
    } else {
        n = (j - s - 80) / 2
    }
    var r = "";
    if (t.content != null) {
        r = t.content
    }
    var o = parseInt(Math.random() * 10000);
    if (t.displayBg) {
        Helper.bodyBg(o, t.opacity, {extClass: t.bg_extClass})
    }
    var q = "left:" + g + "px; top:" + (n + c) + "px;";
    if (Helper.isIE6() || Helper.isIE7()) {
        q += "width:" + p + "px;"
    }
    var b = "position:relative;width:" + p + "px;height:" + s + "px;";
    var m = ['<div id="popupWindow' + o + '" class="formDialog ' + t.window_extClass + '" style="' + q + '">', '<div class="formTL" style=\'' + x + "'>", '<div class="formTR">', '<div class="formTC">' + t.title + "</div>", "</div>", "</div>", '<div class="formBL" style=\'' + u + "'>", '<div class="formBR" style=\'' + u + "'>", '<div class="formBC" id="formBC" style="height:auto;' + u + '">', '<div class="formMSG" style="' + b + '">', r, "</div>", "<table cellpadding='0' cellspacing='0' class='formBtns'>", "<tr><td align='center' class='formBtnsContent'></td></tr>", "</table>", "</div>", "</div>", "</div>", "<a class=\"formX\" href='javascript:;' hidefocus='true' onclick='return false;'></a>", "</div>"];
    $(m.join("")).appendTo("body");
    var l = $("#popupWindow" + o);
    if (Helper.isIE6() || Helper.isIE7()) {
        var a = l.find(".formBL");
        var k = Helper.getCssInt(a, "padding-left") + Helper.getCssInt(a, "padding-right") + Helper.getCssInt(a, "border-left-width") + Helper.getCssInt(a, "border-right-width");
        var z = l.find(".formBR");
        var w = Helper.getCssInt(z, "padding-left") + Helper.getCssInt(z, "padding-right") + Helper.getCssInt(z, "border-left-width") + Helper.getCssInt(z, "border-right-width");
        var h = l.find(".formBC");
        var v = Helper.getCssInt(h, "padding-left") + Helper.getCssInt(h, "padding-right") + Helper.getCssInt(h, "border-left-width") + Helper.getCssInt(h, "border-right-width");
        var y = l.find(".formMSG");
        var A = Helper.getCssInt(y, "margin-left") + Helper.getCssInt(y, "margin-right") + Helper.getCssInt(y, "border-left-width") + Helper.getCssInt(y, "border-right-width");
        l.css("width", (p + k + w + v + A) + "px")
    }
    l.ready(function () {
        $(".formDialog").draggable({handle: ".formTL"});
        $(".formTL").disableSelection();
        $(".formX").click(function () {
            if (t.beforeClose) {
                t.beforeClose()
            }
            Helper.closePopupBodyWindow(o);
            Helper.top.$("#popupBgTitle" + o).remove()
        })
    });
    l.data("settings", t);
    return o
};
Helper.closePopupBodyWindow = function (c) {
    if (c) {
        Helper.removeBodyBg(c);
        var a = $("#popupWindow" + c);
        var b = a.data("settings");
        if (b && typeof b.closeFunc == "function") {
            b.closeFunc()
        }
        a.remove();
        $("body").focus()
    } else {
        Helper.removeBodyBg();
        $(".formDialog").remove()
    }
};
Helper.addPopupBodyWindowBtn = function (j, c) {
    var g = $("#popupWindow" + j);
    g.find(".formBtns").show();
    var h = "popup" + j + c.id;
    var f = g.find(".formBtns td");
    var d = f.find("#" + h);
    if (d.length > 0) {
        d.remove()
    }
    if (g.find(".popupButtons").length != 1) {
        $("<span class='popupButtons'></span>").appendTo(f)
    }
    if (g.find(".popupCheckboxs").length === 1) {
        $(g.find(".popupButtons")[0]).css("margin-right", "10px").css("float", "right").css("margin-top", "-3px");
        if (Helper.isIE6()) {
            $(g.find(".popupButtons")[0]).css("margin-top", "-20px")
        }
    }
    var a = "";
    if (typeof c.extClass != "undefined") {
        var b = " " + c.extClass;
        a = "<input id='" + h + "' type='button' value='" + c.text + "' class='abutton faiButton' extClass='" + b + "'></input>"
    } else {
        a = "<input id='" + h + "' type='button' value='" + c.text + "' class='abutton faiButton'></input>"
    }
    $(a).appendTo($(f).find(".popupButtons"));
    d = f.find("#" + h);
    if (typeof d.faiButton == "function") {
        d.faiButton()
    }
    if (c.click == "close") {
        d.click(function () {
            Helper.closePopupBodyWindow(j)
        })
    } else {
        d.click(c.click)
    }
    if (c.disable) {
        d.attr("disabled", true);
        d.faiButton("disable")
    }
};
Helper.addPopupBodyWindowCheckBox = function (h, b) {
    var f = $("#popupWindow" + h);
    f.find(".formBtns").show();
    var g = "popup" + h + b.id;
    var d = f.find(".formBtns td");
    var c = d.find("#" + g);
    if (c.length > 0) {
        c.remove()
    }
    var a = "<input id='" + g + "' type='checkbox' /><label for='" + g + "'>" + b.text + "</label>";
    if (f.find(".popupCheckboxs").length != 1) {
        d.removeAttr("align").css("line-height", "22px");
        $(d.find(".popupButtons")[0]).css("margin-right", "10px").css("float", "right");
        $("<span class='popupCheckboxs'>" + a + "</span>").appendTo(d)
    } else {
        $(a).appendTo($(f.find(".popupCheckboxs")[0]))
    }
    if (b.init === "checked") {
        $("#" + g).attr("checked", "checked")
    }
    c = d.find("#" + g);
    c.click(b.click);
    if (b.disable) {
        c.attr("disabled", true)
    }
};
Helper.enablePopupBodyWindowBtn = function (f, d, a) {
    var c = $("#popupWindow" + f);
    d = "popup" + f + d;
    var b = c.find("#" + d);
    if (a) {
        b.removeAttr("disabled");
        b.faiButton("enable")
    } else {
        b.attr("disabled", true);
        b.faiButton("disable")
    }
};
Helper.successHandle = function (f, b, g, h, d, j) {
    Helper.top.$("#ing").find(".tips").remove();
    var a = jQuery.parseJSON(f);
    var c = "";
    if (a.success) {
        if (a.msg) {
            c = a.msg
        }
        if (b != "") {
            c = b
        }
        if (c && c != "") {
            if (j == 0) {
                Helper.top.Helper.removeIng(true);
                alert(c)
            } else {
                if (j == 1) {
                    Helper.ing(c, true)
                } else {
                    if (j == 2) {
                        Helper.ing(c, false)
                    } else {
                        if (j == 3) {
                        } else {
                            Helper.top.Helper.removeIng(true);
                            alert(c)
                        }
                    }
                }
            }
        }
        if (h != "") {
            if (d == 1) {
                h = h.replace(/#.*/, "");
                if (Helper.top.location.href == h) {
                    Helper.top.location.reload()
                } else {
                    Helper.top.location.href = h
                }
            } else {
                if (d == 2) {
                    h = h.replace(/#.*/, "");
                    if (parent.location.href == h) {
                        parent.location.reload()
                    } else {
                        parent.location.href = h
                    }
                } else {
                    if (d == 3) {
                        return a.success
                    } else {
                        if (d == 4) {
                            Helper.fkEval(h)
                        } else {
                            if (d == 5) {
                                if (Helper.top.location.href == h) {
                                    Helper.top.location.reload()
                                } else {
                                    Helper.top.location.href = h
                                }
                            } else {
                                h = h.replace(/#.*/, "");
                                if (document.location.href == h) {
                                    document.location.reload()
                                } else {
                                    document.location.href = h
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        if (a.msg) {
            c = a.msg
        }
        if (c == "") {
            c = g
        }
        if (c == "") {
            c = "系统错误"
        }
        if (j == 0) {
            alert(c)
        } else {
            if (j == 1 || j == 2) {
                Helper.ing(c, false)
            } else {
                alert(c)
            }
        }
    }
    return a.success
};
Helper.checkEmbed = function (a, c) {
    if (Helper.top.location.href == document.location.href) {
        var b = document.location.href;
        b = b.replace(/http:\/\/[^\/]+/, "");
        Helper.top.location.href = Helper.addUrlParams(a, "url=" + Helper.encodeUrl(b) + "&item=" + Helper.encodeUrl(c))
    }
};
Helper.disable = function (b, a) {
    if (a) {
        $("#" + b).attr("disabled", true)
    } else {
        $("#" + b).removeAttr("disabled")
    }
};
var timeout = 500;
var closetimer = 0;
var ddmenuitem = 0;
Helper.dropdownForm_open = function () {
    Helper.dropdownForm_canceltimer();
    Helper.dropdownForm_close();
    ddmenuitem = $(this).find("ul").eq(0).css("visibility", "visible")
};
Helper.dropdownForm_close = function () {
    if (ddmenuitem) {
        ddmenuitem.css("visibility", "hidden")
    }
};
Helper.dropdownForm_timer = function () {
    closetimer = window.setTimeout(Helper.dropdownForm_close, timeout)
};
Helper.dropdownForm_canceltimer = function () {
    if (closetimer) {
        window.clearTimeout(closetimer);
        closetimer = null
    }
};
$(function () {
    try {
        $(".dropdownForm > div").bind("click", Helper.dropdownForm_open);
        $(".dropdownForm > div").bind("mouseover", Helper.dropdownForm_open);
        $(".dropdownForm > div").bind("mouseout", Helper.dropdownForm_timer);
        Number.prototype.toFixed = function (f) {
            var c = (parseInt(Math.round(this * Math.pow(10, f) + Math.pow(10, -(f + 2)))) / Math.pow(10, f)).toString();
            var b = c.indexOf(".");
            if (b < 0 && f > 0) {
                c = c + ".";
                for (var d = 0; d < f; d++) {
                    c = c + "0"
                }
            } else {
                b = c.length - b;
                for (var d = 0; d < (f - b) + 1; d++) {
                    c = c + "0"
                }
            }
            return c
        }
    } catch (a) {
    }
});
Helper.preloadImg = function () {
    var c = new Array();
    if (typeof(arguments[0]) == "string") {
        c[0] = arguments[0]
    }
    if (typeof(arguments[0]) == "object") {
        for (var b = 0; b < arguments[0].length; b++) {
            c[b] = arguments[0][b]
        }
    }
    var a = new Array();
    for (var b = 0; b < c.length; b++) {
        a[b] = new Image();
        a[b].src = c[b]
    }
};
Helper.delayLoadImg = function (a) {
    if (typeof a == "undefined" || a <= 0) {
        a = 10
    }
    setTimeout("Helper.doDelayLoadImg(" + a + ")", 200)
};
Helper.doDelayLoadImg = function (b) {
    var a = 0;
    $("img").each(function () {
        var c = $(this).attr("faiSrc");
        if (!Helper.isNull(c) && c != "") {
            if (c != $(this).attr("src")) {
                ++a;
                $(this).show();
                $(this).attr("src", c);
                if (a >= b) {
                    return false
                }
            }
        }
    });
    if (a >= b) {
        setTimeout("Helper.doDelayLoadImg(" + b + ")", 200)
    }
};
Helper.editableDiv = function (b) {
    var f = $("#" + b);
    var d = f.width();
    var a = f.text();
    var c = $("<input type='text' value='" + a + "'/>");
    f.html(c);
    c.click(function () {
        return false
    });
    c.css("font-size", "12px");
    c.css("text-align", "left");
    c.width(d - 10);
    c.trigger("focus").trigger("select");
    c.focus();
    c.blur(function () {
        var g = $(this);
        var h = g.val();
        if (h == "") {
            f.html("<span>默认栏目名称</span>")
        } else {
            f.html("<span>" + h + "</span>")
        }
        if (h != a) {
            $("#saveButton").attr("disabled", false)
        }
    });
    c.keyup(function (j) {
        var l = j || window.event;
        var g = l.keyCode;
        var h = $(this);
        switch (g) {
            case 13:
                var k = h.val();
                if (k == "") {
                    f.html("<span>默认栏目名称</span>")
                } else {
                    f.html("<span>" + k + "</span>")
                }
                if (k != a) {
                    $("#saveButton").attr("disabled", false)
                }
                break;
            case 27:
                f.html("<span>" + a + "</span>");
                break
        }
    })
};
Helper.containsChinese = function (b) {
    var a = /[\u4e00-\u9fa5]+/;
    return a.test(b)
};
Helper.refreshClass = function (a) {
    a.children().each(function () {
        $(this).attr("class", $(this).attr("class"));
        Helper.refreshClass($(this))
    })
};
Helper.addInterval = function (d, c, a) {
    if (Helper.isNull(Helper.intervalFunc)) {
        Helper.intervalFunc = new Array()
    }
    for (var b = 0; b < Helper.intervalFunc.length; ++b) {
        if (Helper.intervalFunc[b].id == d) {
            Helper.intervalFunc.splice(b, 1);
            break
        }
    }
    Helper.intervalFunc.push({id: d, func: c, interval: a, type: 1})
};
Helper.addTimeout = function (d, c, a) {
    if (Helper.isNull(Helper.intervalFunc)) {
        Helper.intervalFunc = new Array()
    }
    for (var b = 0; b < Helper.intervalFunc.length; ++b) {
        if (Helper.intervalFunc[b].id == d) {
            Helper.intervalFunc.splice(b, 1);
            break
        }
    }
    Helper.intervalFunc.push({id: d, func: c, interval: a, type: 0})
};
Helper.startInterval = function (c) {
    if (Helper.isNull(Helper.intervalFunc)) {
        return
    }
    for (var b = 0; b < Helper.intervalFunc.length; ++b) {
        var a = Helper.intervalFunc[b];
        if (c == null || a.id == c) {
            if (a.timer) {
                clearInterval(a.timer)
            }
            if (a.type == 1) {
                if (c == "marquee1168") {
                    a.func()
                }
                a.timer = setInterval(a.func, a.interval)
            } else {
                a.timer = setTimeout(a.func, a.interval)
            }
        }
    }
};
Helper.stopInterval = function (c) {
    if (Helper.isNull(Helper.intervalFunc)) {
        return
    }
    for (var b = 0; b < Helper.intervalFunc.length; ++b) {
        var a = Helper.intervalFunc[b];
        if (c == null || a.id == c) {
            if (a.timer) {
                clearInterval(a.timer)
            }
        }
    }
};
jQuery.extend(jQuery.fx.step, {
    opacity: function (a) {
        var b = jQuery.style(a.elem, "opacity");
        if (b == null || b == "" || b != a.now) {
            jQuery.style(a.elem, "opacity", a.now)
        }
    }
});
jQuery.extend(jQuery.easing, {
    faicount: 10, failinear: function (f, h, a, k, j) {
        var g = Math.abs(k - a) / jQuery.easing.faicount;
        if (g == 0) {
            return k
        }
        f = parseInt(f / g) * g;
        return jQuery.easing.linear(f, h, a, k, j)
    }, easeOutQuart: function (f, g, a, j, h) {
        return -j * ((g = g / h - 1) * g * g * g - 1) + a
    }
});
Helper.easingHelperLinear = function () {
    jQuery.extend(jQuery.easing, {
        swing: function (f, g, a, j, h) {
            return jQuery.easing.failinear(f, g, a, j, h)
        }
    })
};
Helper.getDivHeight = function (f) {
    var d = Helper.getCssInt(f, "padding-top") + Helper.getCssInt(f, "padding-bottom");
    var c = Helper.getCssInt(f, "margin-top") + Helper.getCssInt(f, "margin-bottom");
    var b = Helper.getCssInt(f, "border-top-width") + Helper.getCssInt(f, "border-bottom-width");
    var a = f.height();
    return a + b + c + d
};
Helper.getDivWidth = function (f) {
    var d = Helper.getCssInt(f, "padding-left") + Helper.getCssInt(f, "padding-right");
    var c = Helper.getCssInt(f, "margin-left") + Helper.getCssInt(f, "margin-right");
    var a = Helper.getCssInt(f, "border-left-width") + Helper.getCssInt(f, "border-right-width");
    var b = f.width();
    return b + a + c + d
};
Helper.getFrameHeight = function (d) {
    var c = Helper.getCssInt(d, "padding-top") + Helper.getCssInt(d, "padding-bottom");
    var b = Helper.getCssInt(d, "margin-top") + Helper.getCssInt(d, "margin-bottom");
    var a = Helper.getCssInt(d, "border-top-width") + Helper.getCssInt(d, "border-bottom-width");
    return a + b + c
};
Helper.getFrameWidth = function (d) {
    var c = Helper.getCssInt(d, "padding-left") + Helper.getCssInt(d, "padding-right");
    var b = Helper.getCssInt(d, "margin-left") + Helper.getCssInt(d, "margin-right");
    var a = Helper.getCssInt(d, "border-left-width") + Helper.getCssInt(d, "border-right-width");
    return a + b + c
};
Helper.showMenu = function (o) {
    var s = o.id;
    if (Helper.isNull(s)) {
        s = ""
    }
    var q = o.host;
    var n = 0;
    if (!Helper.isNull(o.mode)) {
        n = o.mode
    }
    if (Helper.isNull(o.fixpos)) {
        o.fixpos = true
    }
    var m = o.rulerObj;
    var g = o.navSysClass;
    if (Helper.isNull(g)) {
        g = ""
    }
    var f = 0;
    if (!Helper.isNull(o.closeMode)) {
        f = o.closeMode
    }
    var l = 0;
    var r = 0;
    if (n == 1) {
        l = q.offset().left + q.width();
        r = q.offset().top
    } else {
        l = q.offset().left;
        r = q.offset().top + q.height()
    }
    var c = $("#g_menu" + s);
    if (c.length != 0) {
        if (f == 0) {
            c.attr("_mouseIn", 1);
            return c
        } else {
            c.attr("_mouseIn", 0);
            Helper.hideMenu();
            return null
        }
    }
    $(".g_menu").each(function () {
        $(this).remove()
    });
    var C = o.data;
    if (o.data == null || o.data == "") {
        return null
    }
    c = $("<div id='g_menu" + s + "' tabindex='0' hidefocus='true' class='g_menu " + o.cls + " " + (o.clsIndex ? o.cls + "Index" + o.clsIndex : "") + " " + g + "' style='display:block;outline:none;'></div>");
    c.appendTo($("body"));
    var v = $("<div class='content contentLayer1'></div>");
    v.appendTo(c);
    Helper.addMenuItem(C, v, o);
    if (o.fixpos) {
        if (r + c.height() + 20 > $(document).height()) {
            r = q.offset().top - c.height()
        }
    }
    c.css("left", l - Helper.getCssInt(v, "border-left-width") + "px");
    c.css("top", r + "px");
    if (f == 0) {
        c.mouseleave(function () {
            c.attr("_mouseIn", 0);
            setTimeout("Helper.hideMenu()", 100)
        });
        c.mouseover(function () {
            c.attr("_mouseIn", 1)
        });
        c.click(function () {
            c.attr("_mouseIn", 0);
            Helper.hideMenu()
        });
        q.mouseleave(function () {
            c.attr("_mouseIn", 0);
            setTimeout("Helper.hideMenu()", 100)
        });
        q.mouseover(function () {
            c.attr("_mouseIn", 1)
        })
    } else {
        q.mousedown(function () {
            c.attr("_mouseIn", 2)
        });
        c.bind("blur", function () {
            if (c.attr("_mouseIn") != 2) {
                c.attr("_mouseIn", 0);
                setTimeout("Helper.hideMenu()", 100)
            }
        });
        c.focus()
    }
    if (typeof g_bindMenuMousewheel == "undefined") {
        g_bindMenuMousewheel = 1;
        $("body").bind("mousewheel", function () {
            $("#g_menu").remove()
        })
    }
    c.attr("_mouseIn", 1);
    c.slideDown(200);
    Helper.calcMenuSize(c, o);
    var t = $("#g_menu" + s + ">div.content>table>tbody>tr>td.center>table.item");
    var x = $("#g_menu" + s);
    var D = (x.outerWidth() - x.width()) + (x.find(".content").outerWidth() - x.find(".content").width()) + (x.find(".content .middle").outerWidth() - x.find(".content .middle").width()) + (x.find(".content .middle .left").outerWidth() - x.find(".content .middle .right").outerWidth()) + (x.find(".content .middle .center").outerWidth() - x.find(".content .middle .center").width());
    var a = t.first().css("clear");
    var b = t.first().outerWidth();
    var k = t.length;
    if (a == "none") {
        if (k > 1 && b > 0) {
            var z = b * k;
            var w = document.documentElement.clientWidth;
            var u = x.offset().left;
            var j = x.offset().right;
            var B = x.width();
            var d = q.offset().left;
            var h = m.outerWidth();
            var y = m.offset().left;
            var p = y + h;
            if (d > w / 2) {
                if (z < w && z > w / 2) {
                    var A = p - z;
                    x.offset({left: A - D});
                    x.find(".content>.middle").width("100%")
                }
                if (z < w && z < w / 2 && (p - u) < z) {
                    var A = p - z;
                    x.offset({left: A - D});
                    x.find(".content>.middle").width("100%")
                }
                if (z > w) {
                    if (w < B) {
                        x.offset({left: 0});
                        x.find(".content>.middle").width("100%")
                    } else {
                        x.offset({left: y});
                        x.find(".content>.middle").width("100%")
                    }
                }
            } else {
                if (z < w && (w - d) < z) {
                    var A = w - z;
                    x.offset({left: A - D});
                    x.find(".content>.middle").width("100%")
                }
                if (z < w && (p - u) < z) {
                    var A = p - z;
                    x.offset({left: A - D});
                    x.find(".content>.middle").width("100%")
                }
                if (z > w) {
                    if (w < B) {
                        x.offset({left: 0});
                        x.find(".content>.middle").width("100%")
                    } else {
                        x.offset({left: y});
                        x.find(".content>.middle").width("100%")
                    }
                }
            }
        }
    }
    return c
};
Helper.addMenuItem = function (t, b, j) {
    if (t.length <= 0) {
        return
    }
    var d = ["<table class='top' cellpadding='0' cellspacing='0'><tr><td class='left'></td><td class='center'></td><td class='right'></td></tr></table>", "<table class='middle' cellpadding='0' cellspacing='0'><tr><td class='left'></td><td class='center'></td><td class='right'></td></tr></table>", "<table class='bottom' cellpadding='0' cellspacing='0'><tr><td class='left'></td><td class='center'></td><td class='right'></td></tr></table>"];
    var n = $(d.join(""));
    n.appendTo(b);
    n = n.parent().find(".middle .center");
    for (var o = 0; o < t.length; ++o) {
        var q = t[o];
        var g = q.sub;
        var p = q.href;
        var u = q.onclick;
        var s = q.target;
        var f = q.disable;
        var c = "";
        if (!p && !u) {
            p = "";
            u = ""
        } else {
            if (!p) {
                p = " href='javascript:;'"
            } else {
                if (f) {
                    p = ""
                } else {
                    p = ' href="' + p + '" style="cursor:pointer;"'
                }
            }
            if (!u) {
                u = ""
            } else {
                u = ' onclick="' + u + '"'
            }
            if (!s) {
                s = ""
            } else {
                s = " target='" + s + "'"
            }
        }
        var l = parseInt(Math.random() * 100000);
        var h = [];
        var r = o + 1;
        h.push("<table class='item itemIndex" + r + "' itemId='");
        h.push(l);
        h.push("' cellpadding='0' cellspacing='0'><tr><td class='itemLeft'></td><td class='itemCenter'><a hidefocus='true' ");
        h.push(p);
        h.push(u);
        h.push(s);
        if (q.title) {
            h.push(" title='" + q.title + "'")
        }
        h.push(">" + q.html + "</a></td><td class='itemRight'></td></tr></table>");
        var k = $(h.join(""));
        if (g) {
            k.addClass("itemPopup")
        }
        if (n.find(" > .subMenu").length >= 1) {
            k.insertBefore(n.find(" > .subMenu").first())
        } else {
            k.appendTo(n)
        }
        if (g) {
            if (g.length == 0) {
            }
            var m = $("<div class='subMenu' itemId='" + l + "'><div class='content contentLayer2'></div></div>");
            m.appendTo(n);
            var a = m.find(" > .content");
            Helper.addMenuItem(g, a, j);
            m.mouseleave(function () {
                $(this).attr("_mouseIn", 0);
                setTimeout(function () {
                    Helper.hideSubMenu()
                }, 100);
                if (j.navBar == true && Helper.isIE()) {
                    var x = $("#g_menu" + j.id);
                    var z = x.find(".contentLayer1");
                    var v = z.outerHeight(true);
                    var y = z.outerWidth(true);
                    var w = z.children(".middle").first().outerWidth(true);
                    x.css({width: y + "px", height: v + "px"});
                    z.css({width: w + "px"})
                }
            });
            m.mouseover(function () {
                $(this).attr("_mouseIn", 1)
            });
            m.click(function () {
                $(this).attr("_mouseIn", 0);
                Helper.hideSubMenu()
            })
        }
        k.hover(function () {
            var B = $(this);
            var L = null;
            $(this).parent().find(" > .subMenu").each(function (N, M) {
                if ($(this).attr("itemId") == B.attr("itemId")) {
                    L = $(this)
                }
            });
            if (L != null && L.length == 1) {
                if (L.css("display") == "none") {
                    if (L.attr("_hadShow") != 1) {
                        var w = B.position().left + B.width();
                        var G = B.position().top;
                        if (j.fixpos) {
                            var H = B.offset().top + L.height() + 20 - $(document).height();
                            if (H > 0) {
                                G = G - H
                            }
                        }
                        L.css("left", w + "px");
                        L.css("top", G + "px");
                        L.slideDown(200);
                        Helper.calcMenuSize(L, j);
                        L.attr("_hadShow", 1)
                    } else {
                        L.slideDown(200)
                    }
                }
                L.attr("_mouseIn", 1);
                if (j.navBar == true && Helper.isIE()) {
                    var v = $("#g_menu" + j.id);
                    var z = v.find(".contentLayer1");
                    var x = L.find(".contentLayer2");
                    var K = z.outerHeight(true);
                    var F = z.outerWidth(true);
                    var E = z.children(".middle").first().outerWidth(true);
                    var J = x.outerHeight(true);
                    var y = x.outerWidth(true);
                    var I = L.position().top;
                    var A = (J + I) - K;
                    var D = A > 0 ? (K + A) : K;
                    var C = F + y;
                    v.css({width: C + "px", height: D + "px"});
                    z.css({width: E + "px"})
                }
            } else {
                if ($(this).parents(".subMenu").length <= 0) {
                    if (j.navBar == true && Helper.isIE()) {
                        var v = $("#g_menu" + j.id);
                        var z = v.find(".contentLayer1");
                        var E = z.children(".middle").first().outerWidth(true);
                        var D = z.outerHeight(true);
                        var C = z.outerWidth(true);
                        v.css({width: C + "px", height: D + "px"});
                        z.css({width: E + "px"})
                    }
                }
            }
            B.addClass("itemHover");
            B.addClass("itemHoverIndex" + (B.index() + 1));
            $(".g_menu").attr("_mouseIn", 1)
        }, function () {
            var v = $(this);
            var w = null;
            $(this).parent().find(" > .subMenu").each(function () {
                if ($(this).attr("itemId") == v.attr("itemId")) {
                    w = $(this)
                }
            });
            if (w != null && w.length == 1) {
                w.attr("_mouseIn", 0);
                setTimeout(function () {
                    Helper.hideSubMenu()
                }, 100)
            } else {
                v.removeClass("itemHover");
                v.removeClass("itemHoverIndex" + (v.index() + 1))
            }
        }).click(function () {
            $(".g_menu").attr("_mouseIn", 0);
            setTimeout("Helper.hideMenu()", 100)
        });
        if (j.closeMode == 1) {
            k.mousedown(function () {
                $(".g_menu").attr("_mouseIn", 2)
            })
        }
    }
};
Helper.calcMenuSize = function (b, a) {
    b.find(" > .content").each(function () {
        var d = $(" > .middle", this);
        var f = 0;
        if (!Helper.isNull(a.minWidth)) {
            f = a.minWidth - Helper.getCssInt(d.find(".left").first(), "width") - Helper.getCssInt(d.find(".right").first(), "width")
        }
        var g = f;
        var c = d.find(" > tbody > tr > .center > .item");
        c.each(function () {
            if ($(this).width() > f) {
                g = $(this).outerWidth();
                f = $(this).width()
            }
        });
        c.width(g);
        c.find(" > tbody > tr > .itemCenter").each(function () {
            var l = $(this);
            var h = l.parent().find(" > .itemLeft");
            var j = l.parent().find(" > .itemRight");
            l.css("width", (f - h.outerWidth() - j.outerWidth() - l.outerWidth() + l.width()) + "px");
            var k = l.find("a");
            k.css("width", (l.width() - k.outerWidth() + k.width()) + "px")
        });
        $(" > .top", this).width(d.width());
        $(" > .bottom", this).width(d.width())
    })
};
Helper.hideSubMenu = function () {
    $(".g_menu .subMenu").each(function () {
        var a = $(this);
        if (a.length != 1) {
            return
        }
        if (a.attr("_mouseIn") == 1) {
            return
        }
        a.css("display", "none");
        a.parent().find(" > .item").each(function () {
            if ($(this).attr("itemId") == a.attr("itemId")) {
                $(this).removeClass("itemHover")
            }
        })
    })
};
Helper.hideMenu = function () {
    $(".g_menu").each(function () {
        var a = $(this);
        if (a.length != 1) {
            return
        }
        if (a.attr("_mouseIn") == 1) {
            return
        }
        a.remove()
    })
};
Helper.calcCtrlWidth = function (a, b) {
    padding = Helper.getCssInt(b, "padding-left") + Helper.getCssInt(b, "padding-right");
    margin = Helper.getCssInt(b, "margin-left") + Helper.getCssInt(b, "margin-right");
    border = Helper.getCssInt(b, "border-left-width") + Helper.getCssInt(b, "border-right-width");
    b.width(a - padding - margin - border)
};
Helper.calcCtrlHeight = function (a, b) {
    padding = Helper.getCssInt(b, "padding-top") + Helper.getCssInt(b, "padding-bottom");
    margin = Helper.getCssInt(b, "margin-top") + Helper.getCssInt(b, "margin-bottom");
    border = Helper.getCssInt(b, "border-top-width") + Helper.getCssInt(b, "border-bottom-width");
    b.height(a - padding - margin - border)
};
Helper.calcGridSize = function (c, a, d, b, l) {
    if (c > 0) {
        var k = Helper.getCssInt(a, "padding-left") + Helper.getCssInt(a, "padding-right");
        var g = Helper.getCssInt(a, "margin-left") + Helper.getCssInt(a, "margin-right");
        var h = Helper.getCssInt(a, "border-left-width") + Helper.getCssInt(a, "border-right-width");
        a.css("overflow-x", "hidden");
        a.width(c - k - h - g)
    }
    var f = 0;
    if (d.css("display") != "none") {
        k = Helper.getCssInt(d, "padding-left") + Helper.getCssInt(d, "padding-right");
        g = Helper.getCssInt(d, "margin-left") + Helper.getCssInt(d, "margin-right");
        h = Helper.getCssInt(d, "border-left-width") + Helper.getCssInt(d, "border-right-width");
        f = d.width() + k + g + h
    }
    var j = 0;
    if (l.css("display") != "none") {
        k = Helper.getCssInt(l, "padding-left") + Helper.getCssInt(l, "padding-right");
        g = Helper.getCssInt(l, "margin-left") + Helper.getCssInt(l, "margin-right");
        h = Helper.getCssInt(l, "border-left-width") + Helper.getCssInt(l, "border-right-width");
        j = l.width() + k + g + h
    }
    Helper.calcCtrlWidth(a.width() - f - j, b);
    k = Helper.getCssInt(b, "padding-top") + Helper.getCssInt(b, "padding-bottom");
    g = Helper.getCssInt(b, "margin-top") + Helper.getCssInt(b, "margin-bottom");
    h = Helper.getCssInt(b, "border-top-width") + Helper.getCssInt(b, "border-bottom-width");
    var m = b.height() + k + g + h;
    Helper.calcCtrlHeight(m, d);
    Helper.calcCtrlHeight(m, l)
};
Helper.removeBgStyle = function (a) {
    if (a.attr("style")) {
        style = a.attr("style").toLowerCase();
        if (style.indexOf("background-image") > -1) {
            style = style.replace(/background-image[^;]*/gi, "")
        }
        if (style.indexOf("background-repeat") > -1) {
            style = style.replace(/background-repeat[^;]*/gi, "")
        }
        if (style.indexOf("background-position") > -1) {
            style = style.replace(/background-position[^;]*/gi, "")
        }
        if (style.indexOf("background-color") > -1) {
            style = style.replace(/background-color[^;]*/gi, "")
        }
        if (style.indexOf("background") > -1) {
            style = style.replace(/background[^;]*/gi, "")
        }
        if (style == "" || style == null) {
            a.removeAttr("style")
        } else {
            a.attr("style", style)
        }
    }
};
Helper.showTip = function (c) {
    var a = new Array();
    if (!c.content) {
        c.content = ""
    }
    a.push("<div class='tip-content'>");
    if (c.closeSwitch) {
        a.push("<div class='tip-content'>");
        a.push("<a class='tip-btnClose'></a>")
    } else {
        a.push("<div class='tip-content'>")
    }
    a.push(c.content);
    a.push("</div>");
    var d = a.join("");
    var b = {
        content: d,
        className: "tip-yellowsimple",
        showTimeout: 1,
        hideTimeout: 0,
        alignTo: "target",
        alignX: "center",
        alignY: "top",
        offsetY: 5,
        showOn: "none",
        hideAniDuration: 0,
        id: "tip-yellowsimple" + parseInt(Math.random() * 10000)
    };
    if (c.id) {
        $.extend(b, {id: c.id})
    }
    if (c.showMode) {
        if (c.showMode == "left") {
            $.extend(b, {alignX: "left", alignY: "center", offsetY: 0, offsetX: 5})
        } else {
            if (c.showMode == "right") {
                $.extend(b, {alignX: "right", alignY: "center", offsetY: 0, offsetX: 5})
            } else {
                if (c.showMode == "top") {
                    $.extend(b, {alignX: "center", alignY: "top", offsetY: 0, offsetX: 5})
                } else {
                    if (c.showMode == "bottom") {
                        $.extend(b, {alignX: "center", alignY: "bottom", offsetY: 0, offsetX: 5})
                    }
                }
            }
        }
    }
    if (c.data) {
        $.extend(b, c.data)
    }
    if (c.appendToId) {
        $.extend(b, {appendToId: c.appendToId})
    }
    if (c.autoLocation) {
        $.extend(b, {autoLocation: c.autoLocation})
    }
    if (c.cusStyle) {
        $.extend(b, {cusStyle: c.cusStyle})
    }
    var f = $(c.tid);
    f.poshytip("destroy");
    f.poshytip(b);
    f.poshytip("show");
    if (c.cls) {
        $("#" + b.id).addClass(c.cls)
    }
    $("#" + b.id).find(".tip-btnClose").live("click", function () {
        if (c.beforeClose) {
            c.beforeClose()
        }
        Helper.closeTip(c.tid)
    });
    if (c.autoTimeout) {
        window.setTimeout(function () {
            if (c.beforeClose) {
                c.beforeClose()
            }
            Helper.closeTip(c.tid)
        }, c.autoTimeout)
    }
};
Helper.closeTip = function (a) {
    if (typeof $(a).poshytip == "function") {
        $(a).poshytip("destroy")
    }
};
Helper.refreshTip = function (a) {
    $(a).poshytip("hide");
    $(a).poshytip("show")
};
Helper.removeCss = function (d, a) {
    var c = new RegExp(a + "[^;]*;", "gi");
    var b = d.attr("style").replace(c, "");
    if (b == "" || b == null) {
        d.removeAttr("style")
    } else {
        d.attr("style", b)
    }
};
Helper.rgb2hex = function (b) {
    if (b.charAt(0) == "#") {
        return b
    }
    var f = Number(b);
    var d = b.split(/\D+/);
    var a = Number(d[1]) * 65536 + Number(d[2]) * 256 + Number(d[3]);
    var c = a.toString(16);
    while (c.length < 6) {
        c = "0" + c
    }
    return "#" + c
};
Helper.int2hex = function (a) {
    var b = a.toString(16);
    while (b.length < 6) {
        b = "0" + b
    }
    return "#" + b
};
Helper.setCtrlStyleCss = function (c, b, l, k, j) {
    var a = $("#" + c);
    var h = new Array();
    if (a.length == 1) {
        var g = a.html();
        g = g.replace(/{\r\n/g, "{").replace(/\t/g, "").replace(/\r\n}/g, ";}");
        h = g.split("\n");
        a.remove()
    }
    var d = new RegExp("#" + b + " +" + fixRegSpecialCharacter(l) + " *{ *" + k + "s*:[^;]*;", "gi");
    if (b == "" || b == "undefined") {
        d = new RegExp(fixRegSpecialCharacter(l) + " *{ *" + k + "s*:[^;]*;", "gi")
    }
    for (var f = h.length - 1; f >= 0; --f) {
        var m = h[f];
        if (m.length == 0 || /^\s$/.test(m) || d.test(m)) {
            h.splice(f, 1)
        }
    }
    if (b == "" || b == "undefined") {
        h.push(l + "{" + k + ":" + j + ";}")
    } else {
        h.push("#" + b + " " + l + "{" + k + ":" + j + ";}")
    }
    $("head").append('<style type="text/css" id="' + c + '">' + h.join("\n") + "</style>")
};
Helper.setCtrlStyleCssList = function (c, b, j, p) {
    var a = $("#" + c);
    var k = new Array();
    if (a.length == 1) {
        var h = a.html();
        h = h.replace(/{\r\n/g, "{").replace(/\t/g, "").replace(/\r\n}/g, ";}");
        k = h.split("\n");
        a.remove()
    }
    for (var g = k.length - 1; g >= 0; --g) {
        var o = k[g];
        for (var f = 0; f < j.length; ++f) {
            var m = j[f].cls;
            var l = j[f].key;
            var d = new RegExp("#" + b + " +" + fixRegSpecialCharacter(m) + " *{ *" + l + "s*:[^;]*;", "gi");
            if (b == "" || b == "undefined") {
                d = new RegExp(fixRegSpecialCharacter(m) + " *{ *" + l + "s*:[^;]*;", "gi")
            }
            if (o.length == 0 || /^\s$/.test(o) || d.test(o)) {
                k.splice(g, 1);
                break
            }
        }
    }
    for (var f = 0; f < j.length; ++f) {
        if (b == "" || b == "undefined") {
            k.push(j[f].cls + "{" + j[f].key + ":" + j[f].value + ";}")
        } else {
            k.push("#" + b + " " + j[f].cls + "{" + j[f].key + ":" + j[f].value + ";}")
        }
    }
    if (p && p.rev) {
        k.reverse()
    }
    $("head").append('<style type="text/css" id="' + c + '">' + k.join("\n") + "</style>")
};
Helper.getCtrlStyleCss = function (d, c, k, j) {
    var b = $("#" + d);
    if (b.length == 0) {
        return ""
    }
    var h = b.html().split("\n");
    var f = new RegExp("#" + c + " +" + fixRegSpecialCharacter(k) + " *{ *" + j + "[^;]*;", "gi");
    if (c == "" || c == "undefined") {
        f = new RegExp(fixRegSpecialCharacter(k) + " *{ *" + j + "[^;]*;", "gi")
    }
    for (var g = h.length - 1; g >= 0; --g) {
        var l = h[g];
        var a = l.match(f);
        if (a && a.length >= 2) {
            return a[1]
        }
    }
    return ""
};
Helper.removeCtrlStyleCss = function (c, b, k, j) {
    var a = $("#" + c);
    var h = new Array();
    if (a.length == 1) {
        var g = a.html();
        g = g.replace(/{\r\n/g, "{").replace(/\t/g, "").replace(/\r\n}/g, ";}");
        h = g.split("\n");
        a.remove()
    }
    var d = new RegExp("#" + b + " +" + fixRegSpecialCharacter(k) + " *{ *" + j + "s*:[^;]*;", "gi");
    if (b == "" || b == "undefined") {
        d = new RegExp(fixRegSpecialCharacter(k) + " *{ *" + j + "s*:[^;]*;", "gi")
    }
    for (var f = h.length - 1; f >= 0; --f) {
        var l = h[f];
        if (l.length == 0 || /^\s$/.test(l) || d.test(l)) {
            h.splice(f, 1)
        }
    }
    $("head").append('<style type="text/css" id="' + c + '">' + h.join("\n") + "</style>")
};
Helper.removeCtrlStyleCssList = function (c, b, j) {
    var a = $("#" + c);
    var k = new Array();
    if (a.length == 1) {
        var h = a.html();
        h = h.replace(/{\r\n/g, "{").replace(/\t/g, "").replace(/\r\n}/g, ";}");
        k = h.split("\n");
        a.remove()
    }
    for (var g = k.length - 1; g >= 0; --g) {
        var o = k[g];
        for (var f = 0; f < j.length; ++f) {
            var m = j[f].cls;
            var l = j[f].key;
            var d = new RegExp("#" + b + " +" + fixRegSpecialCharacter(m) + " *{ *" + l + "s*:[^;]*;", "gi");
            if (b == "" || b == "undefined") {
                d = new RegExp(fixRegSpecialCharacter(m) + " *{ *" + l + "s*:[^;]*;", "gi")
            }
            if (o.length == 0 || /^\s$/.test(o) || d.test(o)) {
                k.splice(g, 1);
                break
            }
        }
    }
    $("head").append('<style type="text/css" id="' + c + '">' + k.join("\n") + "</style>")
};
function fixRegSpecialCharacter(b) {
    var d = ["\\", ".", "?", "$", "*", "^", "[", "]", "{", "}", "|", "(", ")", "/"];
    for (var c = 0, a = d.length; c < a; c++) {
        b = b.replace(d[c], "\\" + d[c])
    }
    return b
}
Helper.addCtrlStyle = function (a, d) {
    var c = $("#" + a);
    var f = new Array();
    if (c.length == 1) {
        var b = c.html();
        b = b.replace(/{\r\n/g, "{").replace(/\t/g, "").replace(/\r\n}/g, ";}");
        f = b.split("\n");
        c.remove()
    }
    f.push(d);
    $("head").append('<style type="text/css" id="' + a + '">' + f.join("\n") + "</style>")
};
Helper.scrollTop = function () {
    Helper.top.$("#g_main").animate({scrollTop: 0}, {duration: 500, easing: "swing"});
    Helper.top.$("html, body").animate({scrollTop: 0}, {duration: 500, easing: "swing"})
};
Helper.addBookmark = function (d, a) {
    d = d == "" ? document.title : d;
    a = a == "" ? "http://" + window.location.host : a;
    try {
        try {
            window.sidebar.addPanel(d, a, "")
        } catch (b) {
            window.external.AddFavorite(a, d)
        }
    } catch (c) {
        alert("收藏网站失败，请使用Ctrl+D进行添加。")
    }
};
Helper.setHomePage = function (c) {
    if (typeof(c) == "undefined") {
        c = location.protocol + "//" + location.host
    }
    var b = false;
    if (typeof LS == "undefined" || typeof LS.setHomePageSuccess == "undefined") {
        b = true
    }
    if ($.browser.msie) {
        try {
            document.body.style.behavior = "url(#default#homepage)";
            document.body.setHomePage(c)
        } catch (g) {
            var f = "您的浏览器暂时不支持自动设为首页，请手动添加。";
            if (!b) {
                d = LS.setHomePageNotSupport
            }
            alert(f)
        }
    } else {
        if ($.browser.mozilla) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch).setCharPref("browser.startup.homepage", c);
                var a = "添加首页成功。";
                if (!b) {
                    a = LS.setHomePageSuccess
                }
                alert(a)
            } catch (g) {
                var d = "设置失败，请手动添加。";
                if (!b) {
                    d = LS.setHomePageError
                }
                alert(d)
            }
        } else {
            var f = "您的浏览器暂时不支持自动设为首页，请手动添加。";
            if (!b) {
                f = LS.setHomePageNotSupport
            }
            alert(f)
        }
    }
};
Helper.singleTextAreaAddMaxLength = function (b, a) {
    if (typeof b != "undefined" && typeof a != "undefined") {
        var c = $("#" + b);
        c.attr("maxlength", a);
        c.bind("keydown keyup change blur", function () {
            textAreaObjVal = c.val();
            textAreaObjLength = textAreaObjVal.length;
            if (textAreaObjLength > a) {
                var d = textAreaObjVal.substr(0, a);
                c.val(d)
            }
        })
    }
};
Helper.parseFileSize = function (c) {
    if (typeof c != "undefined" && typeof c == "number") {
        var b;
        if (c < 1024) {
            b = c + "B"
        } else {
            if (c < 1024 * 1024) {
                var a = c / 1024;
                b = a.toFixed(2) + "KB"
            } else {
                var a = c / (1024 * 1024);
                b = a.toFixed(2) + "MB"
            }
        }
        return b
    } else {
        return "-"
    }
};
Helper.compareObj = function (c, a, b) {
    if (c === "") {
        if (a === "") {
            return 0
        }
        return 1
    }
    if (a === "") {
        return -1
    }
    if (!isNaN(c)) {
        if (!isNaN(a)) {
            c = Math.floor(c);
            a = Math.floor(a)
        } else {
            if (b) {
                return 1
            } else {
                return -1
            }
        }
    } else {
        if (!isNaN(a)) {
            if (b) {
                return -1
            } else {
                return 1
            }
        }
    }
    if (c > a) {
        if (b) {
            return -1
        } else {
            return 1
        }
    } else {
        if (c == a) {
            return 0
        } else {
            if (b) {
                return 1
            } else {
                return -1
            }
        }
    }
};
Helper.getScrollWidth = function () {
    var a;
    var b = document.createElement("div");
    b.style.cssText = "overflow:scroll; width:100px; height:100px; background:transparent;";
    document.body.appendChild(b);
    a = b.offsetWidth - b.clientWidth;
    document.body.removeChild(b);
    return a
};
Helper.calculate = function (h, f, j) {
    var l = 0, d = 0, b = 0;
    try {
        d = h.toString().split(".")[1].length
    } catch (c) {
        d = 0
    }
    try {
        b = f.toString().split(".")[1].length
    } catch (c) {
        b = 0
    }
    switch (j) {
        case 0:
            var k = Math.pow(10, Math.max(d, b));
            l = (h * k + f * k) / k;
            break;
        case 1:
            var a;
            var k = Math.pow(10, Math.max(d, b));
            a = (d >= b) ? d : b;
            l = ((h * k - f * k) / k).toFixed(a);
            break;
        case 2:
            l = g(h, f);
            break;
        case 3:
            l = g((Number(h.toString().replace(".", "")) / Number(f.toString().replace(".", ""))), Math.pow(10, b - d));
            break
    }
    return l;
    function g(o, m) {
        var n = 0;
        try {
            n += o.toString().split(".")[1].length
        } catch (p) {
        }
        try {
            n += m.toString().split(".")[1].length
        } catch (p) {
        }
        return Number(o.toString().replace(".", "")) * Number(m.toString().replace(".", "")) / Math.pow(10, n)
    }
};
(function (a) {
    a.cookie = function (f, g, c) {
        if (arguments.length > 1 && (g === null || typeof g !== "object")) {
            c = a.extend({}, c);
            if (g === null) {
                c.expires = -1
            }
            if (typeof c.expires === "number") {
                var j = c.expires, d = c.expires = new Date();
                d.setDate(d.getDate() + j)
            }
            return (document.cookie = [encodeURIComponent(f), "=", c.raw ? String(g) : encodeURIComponent(String(g)), c.expires ? "; expires=" + c.expires.toUTCString() : "", c.path ? "; path=" + c.path : "", c.domain ? "; domain=" + c.domain : "", c.secure ? "; secure" : ""].join(""))
        }
        c = g || {};
        var b, h = c.raw ? function (k) {
            return k
        } : decodeURIComponent;
        return (b = new RegExp("(?:^|; )" + encodeURIComponent(f) + "=([^;]*)").exec(document.cookie)) ? h(b[1]) : null
    }
})(jQuery);
(function (c) {
    var b = /["\\\x00-\x1f\x7f-\x9f]/g,
        d = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\"},
        a = Object.prototype.hasOwnProperty;
    c.toJSON = function (h) {
        if (h === null) {
            return "null"
        }
        var g, m, f, j, r = c.type(h);
        if (r === "undefined") {
            return undefined
        }
        if (r === "number" || r === "boolean") {
            return String(h)
        }
        if (r === "string") {
            return c.quoteString(h)
        }
        if (typeof h.toJSON === "function") {
            return c.toJSON(h.toJSON())
        }
        if (r === "date") {
            var p = h.getUTCMonth() + 1, s = h.getUTCDate(), q = h.getUTCFullYear(), t = h.getUTCHours(),
                l = h.getUTCMinutes(), u = h.getUTCSeconds(), n = h.getUTCMilliseconds();
            if (p < 10) {
                p = "0" + p
            }
            if (s < 10) {
                s = "0" + s
            }
            if (t < 10) {
                t = "0" + t
            }
            if (l < 10) {
                l = "0" + l
            }
            if (u < 10) {
                u = "0" + u
            }
            if (n < 100) {
                n = "0" + n
            }
            if (n < 10) {
                n = "0" + n
            }
            return '"' + q + "-" + p + "-" + s + "T" + t + ":" + l + ":" + u + "." + n + 'Z"'
        }
        g = [];
        if (c.isArray(h)) {
            for (m = 0; m < h.length; m++) {
                g.push(c.toJSON(h[m]) || "null")
            }
            return "[" + g.join(",") + "]"
        }
        if (typeof h === "object") {
            for (m in h) {
                if (a.call(h, m)) {
                    r = typeof m;
                    if (r === "number") {
                        f = '"' + m + '"'
                    } else {
                        if (r === "string") {
                            f = c.quoteString(m)
                        } else {
                            continue
                        }
                    }
                    r = typeof h[m];
                    if (r !== "function" && r !== "undefined") {
                        j = c.toJSON(h[m]);
                        g.push(f + ":" + j)
                    }
                }
            }
            return "{" + g.join(",") + "}"
        }
    };
    c.evalJSON = typeof JSON === "object" && JSON.parse ? JSON.parse : function (f) {
        return Helper.fkEval("(" + f + ")")
    };
    c.secureEvalJSON = typeof JSON === "object" && JSON.parse ? JSON.parse : function (g) {
        var f = g.replace(/\\["\\\/bfnrtu]/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "");
        if (/^[\],:{}\s]*$/.test(f)) {
            return Helper.fkEval("(" + g + ")")
        }
        throw new SyntaxError("Error parsing JSON, source is not valid.")
    };
    c.quoteString = function (f) {
        if (f.match(b)) {
            return '"' + f.replace(b, function (g) {
                    var h = d[g];
                    if (typeof h === "string") {
                        return h
                    }
                    h = g.charCodeAt();
                    return "\\u00" + Math.floor(h / 16).toString(16) + (h % 16).toString(16)
                }) + '"'
        }
        return '"' + f + '"'
    }
}(jQuery));
(function (f) {
    var b = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var d = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var c = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var a = [];
    a.Jan = "01";
    a.Feb = "02";
    a.Mar = "03";
    a.Apr = "04";
    a.May = "05";
    a.Jun = "06";
    a.Jul = "07";
    a.Aug = "08";
    a.Sep = "09";
    a.Oct = "10";
    a.Nov = "11";
    a.Dec = "12";
    f.format = (function () {
        function k(m) {
            return b[parseInt(m, 10)] || m
        }

        function l(n) {
            var m = parseInt(n, 10) - 1;
            return d[m] || n
        }

        function j(n) {
            var m = parseInt(n, 10) - 1;
            return c[m] || n
        }

        var g = function (m) {
            return a[m] || m
        };
        var h = function (p) {
            var q = p;
            var n = "";
            if (q.indexOf(".") !== -1) {
                var o = q.split(".");
                q = o[0];
                n = o[1]
            }
            var m = q.split(":");
            if (m.length === 3) {
                hour = m[0];
                minute = m[1];
                second = m[2];
                return {time: q, hour: hour, minute: minute, second: second, millis: n}
            } else {
                return {time: "", hour: "", minute: "", second: "", millis: ""}
            }
        };
        return {
            date: function (z, y) {
                try {
                    var n = null;
                    var v = null;
                    var t = null;
                    var B = null;
                    var o = null;
                    var m = null;
                    if (typeof z.getFullYear === "function") {
                        v = z.getFullYear();
                        t = z.getMonth() + 1;
                        B = z.getDate();
                        o = z.getDay();
                        m = h(z.toTimeString())
                    } else {
                        if (z.search(/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.?\d{0,3}[-+]?\d{2}:?\d{2}/) != -1) {
                            var A = z.split(/[T\+-]/);
                            v = A[0];
                            t = A[1];
                            B = A[2];
                            m = h(A[3].split(".")[0]);
                            n = new Date(v, t - 1, B);
                            o = n.getDay()
                        } else {
                            var A = z.split(" ");
                            switch (A.length) {
                                case 6:
                                    v = A[5];
                                    t = g(A[1]);
                                    B = A[2];
                                    m = h(A[3]);
                                    n = new Date(v, t - 1, B);
                                    o = n.getDay();
                                    break;
                                case 2:
                                    var x = A[0].split("-");
                                    v = x[0];
                                    t = x[1];
                                    B = x[2];
                                    m = h(A[1]);
                                    n = new Date(v, t - 1, B);
                                    o = n.getDay();
                                    break;
                                case 7:
                                case 9:
                                case 10:
                                    v = A[3];
                                    t = g(A[1]);
                                    B = A[2];
                                    m = h(A[4]);
                                    n = new Date(v, t - 1, B);
                                    o = n.getDay();
                                    break;
                                default:
                                    return z
                            }
                        }
                    }
                    var u = "";
                    var r = "";
                    for (var q = 0; q < y.length; q++) {
                        var w = y.charAt(q);
                        u += w;
                        switch (u) {
                            case"ddd":
                                r += k(o);
                                u = "";
                                break;
                            case"dd":
                                if (y.charAt(q + 1) == "d") {
                                    break
                                }
                                if (String(B).length === 1) {
                                    B = "0" + B
                                }
                                r += B;
                                u = "";
                                break;
                            case"MMMM":
                                r += j(t);
                                u = "";
                                break;
                            case"MMM":
                                if (y.charAt(q + 1) === "M") {
                                    break
                                }
                                r += l(t);
                                u = "";
                                break;
                            case"MM":
                                if (y.charAt(q + 1) == "M") {
                                    break
                                }
                                if (String(t).length === 1) {
                                    t = "0" + t
                                }
                                r += t;
                                u = "";
                                break;
                            case"yyyy":
                                r += v;
                                u = "";
                                break;
                            case"yy":
                                if (y.charAt(q + 1) == "y" && y.charAt(q + 2) == "y") {
                                    break
                                }
                                r += String(v).slice(-2);
                                u = "";
                                break;
                            case"HH":
                                r += m.hour;
                                u = "";
                                break;
                            case"hh":
                                var p = (m.hour == 0 ? 12 : m.hour < 13 ? m.hour : m.hour - 12);
                                p = String(p).length == 1 ? "0" + p : p;
                                r += p;
                                u = "";
                                break;
                            case"h":
                                if (y.charAt(q + 1) == "h") {
                                    break
                                }
                                var p = (m.hour == 0 ? 12 : m.hour < 13 ? m.hour : m.hour - 12);
                                r += p;
                                u = "";
                                break;
                            case"mm":
                                r += m.minute;
                                u = "";
                                break;
                            case"ss":
                                r += m.second.substring(0, 2);
                                u = "";
                                break;
                            case"SSS":
                                r += m.millis.substring(0, 3);
                                u = "";
                                break;
                            case"a":
                                r += m.hour >= 12 ? "PM" : "AM";
                                u = "";
                                break;
                            case" ":
                                r += w;
                                u = "";
                                break;
                            case"/":
                                r += w;
                                u = "";
                                break;
                            case":":
                                r += w;
                                u = "";
                                break;
                            default:
                                if (u.length === 2 && u.indexOf("y") !== 0 && u != "SS") {
                                    r += u.substring(0, 1);
                                    u = u.substring(1, 2)
                                } else {
                                    if ((u.length === 3 && u.indexOf("yyy") === -1)) {
                                        u = ""
                                    }
                                }
                        }
                    }
                    return r
                } catch (s) {
                    return z
                }
            }
        }
    }())
}(jQuery));
(function (b, a) {
    $window = b(a);
    b.fn.lazyload = function (c) {
        var d = {
            threshold: 0,
            failure_limit: 0,
            event: "scroll",
            effect: "show",
            container: a,
            data_attribute: "original",
            skip_invisible: true,
            appear: null,
            load: null,
            lazyRemoveclass: ""
        };
        if (c) {
            if (undefined !== c.failurelimit) {
                c.failure_limit = c.failurelimit;
                delete c.failurelimit
            }
            if (undefined !== c.effectspeed) {
                c.effect_speed = c.effectspeed;
                delete c.effectspeed
            }
            b.extend(d, c)
        }
        var f = this;
        if (0 == d.event.indexOf("scroll")) {
            b(d.container).off("scroll.lazy");
            b(d.container).on("scroll.lazy", function (h) {
                var g = 0;
                f.each(function () {
                    $this = b(this);
                    if (d.skip_invisible && !$this.is(":visible")) {
                        return
                    }
                    if (b.abovethetop(this, d) || b.leftofbegin(this, d)) {
                    } else {
                        if (!b.belowthefold(this, d) && !b.rightoffold(this, d)) {
                            $this.trigger("appear")
                        } else {
                            if (++g > d.failure_limit) {
                            }
                        }
                    }
                })
            })
        }
        this.each(function () {
            var g = this;
            var h = b(g);
            g.loaded = false;
            h.one("appear", function () {
                if (!this.loaded) {
                    if (d.appear) {
                        var j = f.length;
                        d.appear.call(g, j, d)
                    }
                    b("<img />").bind("load", function () {
                        h.hide().removeClass(d.lazyRemoveclass).attr("src", h.data(d.data_attribute))[d.effect](d.effect_speed);
                        g.loaded = true;
                        var k = b.grep(f, function (m) {
                            return !m.loaded
                        });
                        f = b(k);
                        if (d.load) {
                            var l = f.length;
                            d.load.call(g, l, d)
                        }
                    }).attr("src", h.data(d.data_attribute)).removeClass(d.lazyRemoveclass)
                }
            });
            if (0 != d.event.indexOf("scroll")) {
                h.bind(d.event, function (j) {
                    if (!g.loaded) {
                        h.trigger("appear")
                    }
                })
            }
        });
        $window.bind("resize", function (g) {
            b(d.container).trigger(d.event)
        });
        b(d.container).trigger(d.event);
        return this
    };
    b.belowthefold = function (d, f) {
        if (f.container === undefined || f.container === a) {
            var c = $window.height() + $window.scrollTop()
        } else {
            var c = b(f.container).offset().top + b(f.container).height()
        }
        return c <= b(d).offset().top - f.threshold
    };
    b.rightoffold = function (d, f) {
        if (f.container === undefined || f.container === a) {
            var c = $window.width() + $window.scrollLeft()
        } else {
            var c = b(f.container).offset().left + b(f.container).width()
        }
        return c <= b(d).offset().left - f.threshold
    };
    b.abovethetop = function (d, f) {
        if (f.container === undefined || f.container === a) {
            var c = $window.scrollTop()
        } else {
            var c = b(f.container).offset().top
        }
        return c >= b(d).offset().top + f.threshold + b(d).height()
    };
    b.leftofbegin = function (d, f) {
        if (f.container === undefined || f.container === a) {
            var c = $window.scrollLeft()
        } else {
            var c = b(f.container).offset().left
        }
        return c >= b(d).offset().left + f.threshold + b(d).width()
    };
    b.inviewport = function (c, d) {
        return !b.rightofscreen(c, d) && !b.leftofscreen(c, d) && !b.belowthefold(c, d) && !b.abovethetop(c, d)
    };
    b.extend(b.expr[":"], {
        "below-the-fold": function (c) {
            return b.belowthefold(c, {threshold: 0, container: a})
        }, "above-the-top": function (c) {
            return !b.belowthefold(c, {threshold: 0, container: a})
        }, "right-of-screen": function (c) {
            return b.rightoffold(c, {threshold: 0, container: a})
        }, "left-of-screen": function (c) {
            return !b.rightoffold(c, {threshold: 0, container: a})
        }, "in-viewport": function (c) {
            return !b.inviewport(c, {threshold: 0, container: a})
        }, "above-the-fold": function (c) {
            return !b.belowthefold(c, {threshold: 0, container: a})
        }, "right-of-fold": function (c) {
            return b.rightoffold(c, {threshold: 0, container: a})
        }, "left-of-fold": function (c) {
            return !b.rightoffold(c, {threshold: 0, container: a})
        }
    })
})(jQuery, window);
(function (a) {
    a.flag = function (k, m, j) {
        if (typeof k != "number") {
            return null
        }
        var f = {key: 0, digit: 32, options: "", setBoolean: ""};
        if (m || m === 0) {
            a.extend(f, {key: k, options: m, setBoolean: j});
            if (typeof m === "number") {
                if (typeof f.setBoolean != "boolean") {
                    if (f.options >= 0 && f.options < f.digit) {
                        var c = 1 << f.options;
                        return (f.key & c) == c
                    } else {
                        return false
                    }
                } else {
                    if (f.options >= 0 && f.options < f.digit) {
                        var c = 1 << f.options;
                        if (f.setBoolean) {
                            k |= c
                        } else {
                            k &= ~c
                        }
                    }
                    return k
                }
            } else {
                if (typeof m === "object") {
                    var g = m["true"];
                    var l = m["false"];
                    if ((typeof g != "undefined" || typeof l != "undefined") && (g.length > 0 || l.length > 0)) {
                        var b = {};
                        for (var h = 0; h < g.length; h++) {
                            b[g[h]] = true
                        }
                        for (var h = 0; h < l.length; h++) {
                            b[l[h]] = false
                        }
                        a.extend(f, {options: b})
                    }
                    for (var h = 0; h < f.digit; h++) {
                        var d = f.options[h];
                        var c = 1 << h;
                        if (typeof d != "undefined" && typeof d === "boolean") {
                            if (d) {
                                k |= c
                            } else {
                                k &= ~c
                            }
                        }
                    }
                    return k
                }
            }
        } else {
            return null
        }
    }
})(jQuery);
(function (g) {
    function p(v, A) {
        var z = (v & 65535) + (A & 65535), w = (v >> 16) + (A >> 16) + (z >> 16);
        return (w << 16) | (z & 65535)
    }

    function t(v, w) {
        return (v << w) | (v >>> (32 - w))
    }

    function c(B, y, w, v, A, z) {
        return p(t(p(p(y, B), p(v, z)), A), w)
    }

    function b(y, w, C, B, v, A, z) {
        return c((w & C) | ((~w) & B), y, w, v, A, z)
    }

    function j(y, w, C, B, v, A, z) {
        return c((w & B) | (C & (~B)), y, w, v, A, z)
    }

    function o(y, w, C, B, v, A, z) {
        return c(w ^ C ^ B, y, w, v, A, z)
    }

    function a(y, w, C, B, v, A, z) {
        return c(C ^ (w | (~B)), y, w, v, A, z)
    }

    function d(G, B) {
        G[B >> 5] |= 128 << ((B) % 32);
        G[(((B + 64) >>> 9) << 4) + 14] = B;
        var y, A, z, w, v, F = 1732584193, E = -271733879, D = -1732584194, C = 271733878;
        for (y = 0; y < G.length; y += 16) {
            A = F;
            z = E;
            w = D;
            v = C;
            F = b(F, E, D, C, G[y], 7, -680876936);
            C = b(C, F, E, D, G[y + 1], 12, -389564586);
            D = b(D, C, F, E, G[y + 2], 17, 606105819);
            E = b(E, D, C, F, G[y + 3], 22, -1044525330);
            F = b(F, E, D, C, G[y + 4], 7, -176418897);
            C = b(C, F, E, D, G[y + 5], 12, 1200080426);
            D = b(D, C, F, E, G[y + 6], 17, -1473231341);
            E = b(E, D, C, F, G[y + 7], 22, -45705983);
            F = b(F, E, D, C, G[y + 8], 7, 1770035416);
            C = b(C, F, E, D, G[y + 9], 12, -1958414417);
            D = b(D, C, F, E, G[y + 10], 17, -42063);
            E = b(E, D, C, F, G[y + 11], 22, -1990404162);
            F = b(F, E, D, C, G[y + 12], 7, 1804603682);
            C = b(C, F, E, D, G[y + 13], 12, -40341101);
            D = b(D, C, F, E, G[y + 14], 17, -1502002290);
            E = b(E, D, C, F, G[y + 15], 22, 1236535329);
            F = j(F, E, D, C, G[y + 1], 5, -165796510);
            C = j(C, F, E, D, G[y + 6], 9, -1069501632);
            D = j(D, C, F, E, G[y + 11], 14, 643717713);
            E = j(E, D, C, F, G[y], 20, -373897302);
            F = j(F, E, D, C, G[y + 5], 5, -701558691);
            C = j(C, F, E, D, G[y + 10], 9, 38016083);
            D = j(D, C, F, E, G[y + 15], 14, -660478335);
            E = j(E, D, C, F, G[y + 4], 20, -405537848);
            F = j(F, E, D, C, G[y + 9], 5, 568446438);
            C = j(C, F, E, D, G[y + 14], 9, -1019803690);
            D = j(D, C, F, E, G[y + 3], 14, -187363961);
            E = j(E, D, C, F, G[y + 8], 20, 1163531501);
            F = j(F, E, D, C, G[y + 13], 5, -1444681467);
            C = j(C, F, E, D, G[y + 2], 9, -51403784);
            D = j(D, C, F, E, G[y + 7], 14, 1735328473);
            E = j(E, D, C, F, G[y + 12], 20, -1926607734);
            F = o(F, E, D, C, G[y + 5], 4, -378558);
            C = o(C, F, E, D, G[y + 8], 11, -2022574463);
            D = o(D, C, F, E, G[y + 11], 16, 1839030562);
            E = o(E, D, C, F, G[y + 14], 23, -35309556);
            F = o(F, E, D, C, G[y + 1], 4, -1530992060);
            C = o(C, F, E, D, G[y + 4], 11, 1272893353);
            D = o(D, C, F, E, G[y + 7], 16, -155497632);
            E = o(E, D, C, F, G[y + 10], 23, -1094730640);
            F = o(F, E, D, C, G[y + 13], 4, 681279174);
            C = o(C, F, E, D, G[y], 11, -358537222);
            D = o(D, C, F, E, G[y + 3], 16, -722521979);
            E = o(E, D, C, F, G[y + 6], 23, 76029189);
            F = o(F, E, D, C, G[y + 9], 4, -640364487);
            C = o(C, F, E, D, G[y + 12], 11, -421815835);
            D = o(D, C, F, E, G[y + 15], 16, 530742520);
            E = o(E, D, C, F, G[y + 2], 23, -995338651);
            F = a(F, E, D, C, G[y], 6, -198630844);
            C = a(C, F, E, D, G[y + 7], 10, 1126891415);
            D = a(D, C, F, E, G[y + 14], 15, -1416354905);
            E = a(E, D, C, F, G[y + 5], 21, -57434055);
            F = a(F, E, D, C, G[y + 12], 6, 1700485571);
            C = a(C, F, E, D, G[y + 3], 10, -1894986606);
            D = a(D, C, F, E, G[y + 10], 15, -1051523);
            E = a(E, D, C, F, G[y + 1], 21, -2054922799);
            F = a(F, E, D, C, G[y + 8], 6, 1873313359);
            C = a(C, F, E, D, G[y + 15], 10, -30611744);
            D = a(D, C, F, E, G[y + 6], 15, -1560198380);
            E = a(E, D, C, F, G[y + 13], 21, 1309151649);
            F = a(F, E, D, C, G[y + 4], 6, -145523070);
            C = a(C, F, E, D, G[y + 11], 10, -1120210379);
            D = a(D, C, F, E, G[y + 2], 15, 718787259);
            E = a(E, D, C, F, G[y + 9], 21, -343485551);
            F = p(F, A);
            E = p(E, z);
            D = p(D, w);
            C = p(C, v)
        }
        return [F, E, D, C]
    }

    function q(w) {
        var x, v = "";
        for (x = 0; x < w.length * 32; x += 8) {
            v += String.fromCharCode((w[x >> 5] >>> (x % 32)) & 255)
        }
        return v
    }

    function k(w) {
        var x, v = [];
        v[(w.length >> 2) - 1] = undefined;
        for (x = 0; x < v.length; x += 1) {
            v[x] = 0
        }
        for (x = 0; x < w.length * 8; x += 8) {
            v[x >> 5] |= (w.charCodeAt(x / 8) & 255) << (x % 32)
        }
        return v
    }

    function l(v) {
        return q(d(k(v), v.length * 8))
    }

    function f(x, A) {
        var w, z = k(x), v = [], y = [], B;
        v[15] = y[15] = undefined;
        if (z.length > 16) {
            z = d(z, x.length * 8)
        }
        for (w = 0; w < 16; w += 1) {
            v[w] = z[w] ^ 909522486;
            y[w] = z[w] ^ 1549556828
        }
        B = d(v.concat(k(A)), 512 + A.length * 8);
        return q(d(y.concat(B), 512 + 128))
    }

    function u(y) {
        var A = "0123456789abcdef", w = "", v, z;
        for (z = 0; z < y.length; z += 1) {
            v = y.charCodeAt(z);
            w += A.charAt((v >>> 4) & 15) + A.charAt(v & 15)
        }
        return w
    }

    function n(v) {
        return unescape(encodeURIComponent(v))
    }

    function r(v) {
        return l(n(v))
    }

    function m(v) {
        return u(r(v))
    }

    function h(v, w) {
        return f(n(v), n(w))
    }

    function s(v, w) {
        return u(h(v, w))
    }

    g.md5 = function (w, x, v) {
        if (!x) {
            if (!v) {
                return m(w)
            } else {
                return r(w)
            }
        }
        if (!v) {
            return s(x, w)
        } else {
            return h(x, w)
        }
    }
}(typeof jQuery === "function" ? jQuery : this));
(function (a) {
    a.openURL = function (c, d, b) {
        if (d) {
            window.open(c, d, b)
        } else {
            window.open(c)
        }
    }
})(jQuery);
(function (a) {
    a.jumpURL = function (b) {
        window.location.href = b
    }
})(jQuery);
(function (a) {
    a.hash = function (h, k) {
        function c(l) {
            return typeof l == "string" || Object.prototype.toString.call(l) === "[object String]"
        }

        if (!c(h) || h == "") {
            return
        }
        var g = new RegExp("(&" + h + "=[^&]*)|(\\b" + h + "=[^&]*&)|(\\b" + h + "=[^&]*)", "ig");
        var j = new RegExp("&*\\b" + h + "=[^&]*", "i");
        if (typeof k == "undefined") {
            var b = window.location.hash.match(j);
            var f = b ? b[0].indexOf("=") : -1;
            if (f != -1) {
                b = a.trim(b[0].substring(f + 1, b[0].length))
            } else {
                return null
            }
            return Helper.isMozilla() ? b : decodeURIComponent(b)
        } else {
            if (k === null) {
                window.location.hash = window.location.hash.replace(g, "")
            } else {
                k = k + "";
                var d = window.location.hash.replace(g, "");
                d += ((d.indexOf("=") != -1) ? "&" : "") + h + "=" + encodeURIComponent(k);
                window.location.hash = d
            }
        }
    }
})(jQuery);
(function (a) {
    a.fn.numeric = function (d, f) {
        if (typeof d === "boolean") {
            d = {decimal: d}
        }
        d = d || {};
        if (typeof d.negative == "undefined") {
            d.negative = true
        }
        var b = (d.decimal === false) ? "" : d.decimal || ".";
        var c = (d.negative === true) ? true : false;
        f = (typeof(f) == "function" ? f : function () {
        });
        return this.data("numeric.decimal", b).data("numeric.negative", c).data("numeric.callback", f).keypress(a.fn.numeric.keypress).keyup(a.fn.numeric.keyup).blur(a.fn.numeric.blur)
    };
    a.fn.numeric.keypress = function (h) {
        var b = a.data(this, "numeric.decimal");
        var c = a.data(this, "numeric.negative");
        var d = h.charCode ? h.charCode : h.keyCode ? h.keyCode : 0;
        if (d == 13 && this.nodeName.toLowerCase() == "input") {
            return true
        } else {
            if (d == 13) {
                return false
            }
        }
        var f = false;
        if ((h.ctrlKey && d == 97) || (h.ctrlKey && d == 65)) {
            return true
        }
        if ((h.ctrlKey && d == 120) || (h.ctrlKey && d == 88)) {
            return true
        }
        if ((h.ctrlKey && d == 99) || (h.ctrlKey && d == 67)) {
            return true
        }
        if ((h.ctrlKey && d == 122) || (h.ctrlKey && d == 90)) {
            return true
        }
        if ((h.ctrlKey && d == 118) || (h.ctrlKey && d == 86) || (h.shiftKey && d == 45)) {
            return true
        }
        if (d < 48 || d > 57) {
            var g = a(this).val();
            if (g.indexOf("-") !== 0 && c && d == 45 && (g.length === 0 || parseInt(a.fn.getSelectionStart(this), 10) === 0)) {
                return true
            }
            if (b && d == b.charCodeAt(0) && g.indexOf(b) != -1) {
                f = false
            }
            if (d != 8 && d != 9 && d != 13 && d != 35 && d != 36 && d != 37 && d != 39 && d != 46) {
                f = false
            } else {
                if (typeof h.charCode != "undefined") {
                    if (h.keyCode == h.which && h.which !== 0) {
                        f = true;
                        if (h.which == 46) {
                            f = false
                        }
                    } else {
                        if (h.keyCode !== 0 && h.charCode === 0 && h.which === 0) {
                            f = true
                        }
                    }
                }
            }
            if (b && d == b.charCodeAt(0)) {
                if (g.indexOf(b) == -1) {
                    f = true
                } else {
                    f = false
                }
            }
        } else {
            f = true
        }
        return f
    };
    a.fn.numeric.keyup = function (r) {
        var l = a(this).val();
        if (l && l.length > 0) {
            var f = a.fn.getSelectionStart(this);
            var u = a.fn.getSelectionEnd(this);
            var q = a.data(this, "numeric.decimal");
            var n = a.data(this, "numeric.negative");
            if (q !== "" && q !== null) {
                var d = l.indexOf(q);
                if (d === 0) {
                    this.value = "0" + l
                }
                if (d == 1 && l.charAt(0) == "-") {
                    this.value = "-0" + l.substring(1)
                }
                l = this.value
            }
            var c = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "-", q];
            var h = l.length;
            for (var p = h - 1; p >= 0; p--) {
                var b = l.charAt(p);
                if (p !== 0 && b == "-") {
                    l = l.substring(0, p) + l.substring(p + 1)
                } else {
                    if (p === 0 && !n && b == "-") {
                        l = l.substring(1)
                    }
                }
                var g = false;
                for (var o = 0; o < c.length; o++) {
                    if (b == c[o]) {
                        g = true;
                        break
                    }
                }
                if (!g || b == " ") {
                    l = l.substring(0, p) + l.substring(p + 1)
                }
            }
            var s = l.indexOf(q);
            if (s > 0) {
                for (var m = h - 1; m > s; m--) {
                    var t = l.charAt(m);
                    if (t == q) {
                        l = l.substring(0, m) + l.substring(m + 1)
                    }
                }
            }
            this.value = l;
            a.fn.setSelection(this, [f, u])
        }
    };
    a.fn.numeric.blur = function () {
        var b = a.data(this, "numeric.decimal");
        var f = a.data(this, "numeric.callback");
        var d = this.value;
        if (d !== "") {
            var c = new RegExp("^\\d+$|^\\d*" + b + "\\d+$");
            if (!c.exec(d)) {
                f.apply(this)
            }
        }
    };
    a.fn.removeNumeric = function () {
        return this.data("numeric.decimal", null).data("numeric.negative", null).data("numeric.callback", null).unbind("keypress", a.fn.numeric.keypress).unbind("blur", a.fn.numeric.blur)
    };
    a.fn.getSelectionStart = function (c) {
        if (c.createTextRange) {
            var b = document.selection.createRange().duplicate();
            b.moveEnd("character", c.value.length);
            if (b.text === "") {
                return c.value.length
            }
            return c.value.lastIndexOf(b.text)
        } else {
            return c.selectionStart
        }
    };
    a.fn.getSelectionEnd = function (c) {
        if (c.createTextRange) {
            var b = document.selection.createRange().duplicate();
            b.moveStart("character", -c.value.length);
            return b.text.length
        } else {
            return c.selectionEnd
        }
    };
    a.fn.setSelection = function (d, c) {
        if (typeof c == "number") {
            c = [c, c]
        }
        if (c && c.constructor == Array && c.length == 2) {
            if (d.createTextRange) {
                var b = d.createTextRange();
                b.collapse(true);
                b.moveStart("character", c[0]);
                b.moveEnd("character", c[1]);
                b.select()
            } else {
                if (d.setSelectionRange) {
                    d.focus();
                    d.setSelectionRange(c[0], c[1])
                }
            }
        }
    }
})(jQuery);
(function (d, c, f) {
    var a, b;
    d.uaMatch = function (j) {
        j = j.toLowerCase();
        var h = /(opr)[\/]([\w.]+)/.exec(j) || /(chrome)[ \/]([\w.]+)/.exec(j) || /(webkit)[ \/]([\w.]+)/.exec(j) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(j) || /(msie) ([\w.]+)/.exec(j) || j.indexOf("trident") >= 0 && /(rv)(?::| )([\w.]+)/.exec(j) || j.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(j) || [];
        var g = /(ipad)/.exec(j) || /(iphone)/.exec(j) || /(android)/.exec(j) || [];
        return {browser: h[1] || "", version: h[2] || "0", platform: g[0] || ""}
    };
    a = d.uaMatch(c.navigator.userAgent);
    b = {};
    if (a.browser) {
        b[a.browser] = true;
        b.version = a.version
    }
    if (a.platform) {
        b[a.platform] = true
    }
    if (b.chrome || b.opr) {
        b.webkit = true
    } else {
        if (b.webkit) {
            b.safari = true
        }
    }
    if (b.rv) {
        b.msie = true
    }
    if (b.opr) {
        b.opera = true
    }
    d.browser = b
})(jQuery, window);
(function (a) {
    a.fn.removeCss = function (c) {
        var b = [];
        var d = a.type(c);
        if (d === "array") {
            b = c
        } else {
            if (d === "object") {
                for (var f in c) {
                    b.push(f)
                }
            } else {
                if (d === "string") {
                    b = c.replace(/,$/, "").split(",")
                }
            }
        }
        return this.each(function () {
            var g = a(this);
            a.map(b, function (h) {
                g.css(h, "")
            })
        })
    }
})(jQuery);
(function (a) {
    a.extend(a.fn, {
        livequery: function (f, d, c) {
            var b = this, g;
            if (a.isFunction(f)) {
                c = d, d = f, f = undefined
            }
            a.each(a.livequery.queries, function (h, j) {
                if (b.selector == j.selector && b.context == j.context && f == j.type && (!d || d.$lqguid == j.fn.$lqguid) && (!c || c.$lqguid == j.fn2.$lqguid)) {
                    return (g = j) && false
                }
            });
            g = g || new a.livequery(this.selector, this.context, f, d, c);
            g.stopped = false;
            g.run();
            return this
        }, expire: function (f, d, c) {
            var b = this;
            if (a.isFunction(f)) {
                c = d, d = f, f = undefined
            }
            a.each(a.livequery.queries, function (g, h) {
                if (b.selector == h.selector && b.context == h.context && (!f || f == h.type) && (!d || d.$lqguid == h.fn.$lqguid) && (!c || c.$lqguid == h.fn2.$lqguid) && !this.stopped) {
                    a.livequery.stop(h.id)
                }
            });
            return this
        }
    });
    a.livequery = function (b, d, g, f, c) {
        this.selector = b;
        this.context = d;
        this.type = g;
        this.fn = f;
        this.fn2 = c;
        this.elements = [];
        this.stopped = false;
        this.id = a.livequery.queries.push(this) - 1;
        f.$lqguid = f.$lqguid || a.livequery.guid++;
        if (c) {
            c.$lqguid = c.$lqguid || a.livequery.guid++
        }
        return this
    };
    a.livequery.prototype = {
        stop: function () {
            var b = this;
            if (this.type) {
                this.elements.unbind(this.type, this.fn)
            } else {
                if (this.fn2) {
                    this.elements.each(function (c, d) {
                        b.fn2.apply(d)
                    })
                }
            }
            this.elements = [];
            this.stopped = true
        }, run: function () {
            if (this.stopped) {
                return
            }
            var d = this;
            var f = this.elements, c = a(this.selector, this.context), b = c.not(f);
            this.elements = c;
            if (this.type) {
                b.bind(this.type, this.fn);
                if (f.length > 0) {
                    a.each(f, function (g, h) {
                        if (a.inArray(h, c) < 0) {
                            a.event.remove(h, d.type, d.fn)
                        }
                    })
                }
            } else {
                b.each(function () {
                    d.fn.apply(this)
                });
                if (this.fn2 && f.length > 0) {
                    a.each(f, function (g, h) {
                        if (a.inArray(h, c) < 0) {
                            d.fn2.apply(h)
                        }
                    })
                }
            }
        }
    };
    a.extend(a.livequery, {
        guid: 0, queries: [], queue: [], running: false, timeout: null, checkQueue: function () {
            if (a.livequery.running && a.livequery.queue.length) {
                var b = a.livequery.queue.length;
                while (b--) {
                    a.livequery.queries[a.livequery.queue.shift()].run()
                }
            }
        }, pause: function () {
            a.livequery.running = false
        }, play: function () {
            a.livequery.running = true;
            a.livequery.run()
        }, registerPlugin: function () {
            a.each(arguments, function (c, d) {
                if (!a.fn[d]) {
                    return
                }
                var b = a.fn[d];
                a.fn[d] = function () {
                    var f = b.apply(this, arguments);
                    a.livequery.run();
                    return f
                }
            })
        }, run: function (b) {
            if (b != undefined) {
                if (a.inArray(b, a.livequery.queue) < 0) {
                    a.livequery.queue.push(b)
                }
            } else {
                a.each(a.livequery.queries, function (c) {
                    if (a.inArray(c, a.livequery.queue) < 0) {
                        a.livequery.queue.push(c)
                    }
                })
            }
            if (a.livequery.timeout) {
                clearTimeout(a.livequery.timeout)
            }
            a.livequery.timeout = setTimeout(a.livequery.checkQueue, 20)
        }, stop: function (b) {
            if (b != undefined) {
                a.livequery.queries[b].stop()
            } else {
                a.each(a.livequery.queries, function (c) {
                    a.livequery.queries[c].stop()
                })
            }
        }
    });
    a.livequery.registerPlugin("append", "prepend", "after", "before", "wrap", "attr", "removeAttr", "addClass", "removeClass", "toggleClass", "empty", "remove", "html");
    a(function () {
        a.livequery.play()
    })
})(jQuery);
Helper.compareObjNew = function (h, g, a, d, f) {
    var c = h[d];
    var b = g[d];
    if (typeof c === "undefined" || typeof b === "undefined") {
        return
    }
    if (f === "number") {
        return Helper.ber(c, b, a)
    }
    if (Helper.isIE6() || Helper.isIE7() || Helper.isIE8()) {
        return Helper.compareObjLocale(h, g, a, d)
    }
    return Helper.compareStrings(c, b, a)
};
Helper.compareObjLocale = function (g, f, a, d) {
    var c = g[d];
    var b = f[d];
    if (typeof c === "number" || typeof c === "boolean") {
        c = c.toString()
    }
    if (typeof b === "number" || typeof b === "boolean") {
        b = b.toString()
    }
    if (a == "asc") {
        return c.localeCompare(b)
    } else {
        return b.localeCompare(c)
    }
};
Helper.ber = function (c, b, a) {
    if (c === b) {
        return 0
    }
    if (a == "asc") {
        return c > b ? 1 : -1
    } else {
        return c > b ? -1 : 1
    }
};
Helper.compareStrings = function (j, h, c) {
    if (typeof j === "number" || typeof j === "boolean") {
        j = j.toString()
    }
    if (typeof h === "number" || typeof h === "boolean") {
        h = h.toString()
    }
    var k = {str1: j, str2: h, len1: j.length, len2: h.length, pos1: 0, pos2: 0};
    var l = 0;
    while (l == 0 && k.pos1 < k.len1 && k.pos2 < k.len2) {
        var b = k.str1.charAt(k.pos1);
        var a = k.str2.charAt(k.pos2);
        if (Helper.isDigit(b)) {
            l = Helper.isDigit(a) ? g(k) : -1
        } else {
            if (Helper.isChinese(b)) {
                l = Helper.isChinese(a) ? d(k) : 1
            } else {
                if (Helper.isLetter(b)) {
                    l = (Helper.isLetter(a) || Helper.isChinese(a)) ? f(k, true) : 1
                } else {
                    l = Helper.isDigit(a) ? 1 : (Helper.isLetter(a) || Helper.isChinese(a)) ? -1 : f(k, false)
                }
            }
        }
        k.pos1++;
        k.pos2++
    }
    if (c == "asc") {
        return l == 0 ? k.len1 - k.len2 : l
    } else {
        return -(l == 0 ? k.len1 - k.len2 : l)
    }
    function g(o) {
        var n = o.pos1 + 1;
        while (n < o.len1 && Helper.isDigit(o.str1.charAt(n))) {
            n++
        }
        var q = n - o.pos1;
        while (o.pos1 < n && o.str1.charAt(o.pos1) == "0") {
            o.pos1++
        }
        var m = o.pos2 + 1;
        while (m < o.len2 && Helper.isDigit(o.str2.charAt(m))) {
            m++
        }
        var p = m - o.pos2;
        while (o.pos2 < m && o.str2.charAt(o.pos2) == "0") {
            o.pos2++
        }
        var r = (n - o.pos1) - (m - o.pos2);
        if (r != 0) {
            return r
        }
        while (o.pos1 < n && o.pos2 < m) {
            r = o.str1.charCodeAt(o.pos1++) - o.str2.charCodeAt(o.pos2++);
            if (r != 0) {
                return r
            }
        }
        o.pos1--;
        o.pos2--;
        return p - q
    }

    function f(o, m) {
        var p = o.str1.charAt(o.pos1);
        var n = o.str2.charAt(o.pos2);
        if (p == n) {
            return 0
        }
        if (m) {
            p = p.toUpperCase();
            n = n.toUpperCase();
            if (p != n) {
                p = p.toLowerCase();
                n = n.toLowerCase()
            }
        }
        return p.charCodeAt(0) - n.charCodeAt(0)
    }

    function d(n) {
        var o = n.str1.charAt(n.pos1);
        var m = n.str2.charAt(n.pos2);
        if (o == m) {
            return 0
        }
        var q, p;
        if (typeof Pinyin != "undefined") {
            q = Pinyin.getStringStriped(o, Pinyin.mode.LOWERCASE, true);
            p = Pinyin.getStringStriped(m, Pinyin.mode.LOWERCASE, true);
            return -Helper.compareStrings(q, p)
        } else {
            q = o.charCodeAt(0);
            p = m.charCodeAt(0);
            if (q == p) {
                return 0
            } else {
                return q - p
            }
        }
    }
};
Helper.compareRandom = function (b, a) {
    return Math.random() > 0.5 ? (-1) : 1
};
Helper.punycode = function () {
    var p = 2147483647, t = Math.floor, j = 36, l = 1, n = 26, m = 72, s = String.fromCharCode, k = 700, w = "-",
        d = j - l, f = 38, c = 128, o = {
            overflow: "Overflow: input needs wider integers to process",
            "not-basic": "Illegal input >= 0x80 (not a basic code point)",
            "invalid-input": "Invalid input"
        };

    function a(A, y, z) {
        var x = 0;
        A = z ? t(A / k) : A >> 1;
        A += t(A / y);
        for (; A > d * n >> 1; x += j) {
            A = t(A / d)
        }
        return t(x + (d + 1) * A / (A + f))
    }

    function h(A) {
        var z = [], y = 0, B = A.length, C, x;
        while (y < B) {
            C = A.charCodeAt(y++);
            if (C >= 55296 && C <= 56319 && y < B) {
                x = A.charCodeAt(y++);
                if ((x & 64512) == 56320) {
                    z.push(((C & 1023) << 10) + (x & 1023) + 65536)
                } else {
                    z.push(C);
                    y--
                }
            } else {
                z.push(C)
            }
        }
        return z
    }

    function r(y, x) {
        return y + 22 + 75 * (y < 26) - ((x != 0) << 5)
    }

    function b(x) {
        if (x - 48 < 10) {
            return x - 22
        }
        if (x - 65 < 26) {
            return x - 65
        }
        if (x - 97 < 26) {
            return x - 97
        }
        return j
    }

    function v(x) {
        return u(x, function (z) {
            var y = "";
            if (z > 65535) {
                z -= 65536;
                y += s(z >>> 10 & 1023 | 55296);
                z = 56320 | z & 1023
            }
            y += s(z);
            return y
        }).join("")
    }

    function u(z, x) {
        var y = z.length;
        while (y--) {
            z[y] = x(z[y])
        }
        return z
    }

    function g(J) {
        var A, L, G, y, H, F, B, x, E, N, K, z = [], D, C, M, I;
        J = h(J);
        D = J.length;
        A = c;
        L = 0;
        H = m;
        for (F = 0; F < D; ++F) {
            K = J[F];
            if (K < 128) {
                z.push(s(K))
            }
        }
        G = y = z.length;
        if (y) {
            z.push(w)
        }
        while (G < D) {
            for (B = p, F = 0; F < D; ++F) {
                K = J[F];
                if (K >= A && K < B) {
                    B = K
                }
            }
            C = G + 1;
            if (B - A > t((p - L) / C)) {
                throw RangeError(o.overflow)
            }
            L += (B - A) * C;
            A = B;
            for (F = 0; F < D; ++F) {
                K = J[F];
                if (K < A && ++L > p) {
                    throw RangeError(o.overflow)
                }
                if (K == A) {
                    for (x = L, E = j; ; E += j) {
                        N = E <= H ? l : (E >= H + n ? n : E - H);
                        if (x < N) {
                            break
                        }
                        I = x - N;
                        M = j - N;
                        z.push(s(r(N + I % M, 0)));
                        x = t(I / M)
                    }
                    z.push(s(r(x, 0)));
                    H = a(L, C, G == y);
                    L = 0;
                    ++G
                }
            }
            ++L;
            ++A
        }
        return z.join("")
    }

    function q(J) {
        var z = [], C = J.length, E, F = 0, y = c, G = m, B, D, H, x, K, A, I, M, L;
        B = J.lastIndexOf(w);
        if (B < 0) {
            B = 0
        }
        for (D = 0; D < B; ++D) {
            if (J.charCodeAt(D) >= 128) {
                throw RangeError(o["not-basic"])
            }
            z.push(J.charCodeAt(D))
        }
        for (H = B > 0 ? B + 1 : 0; H < C;) {
            for (x = F, K = 1, A = j; ; A += j) {
                if (H >= C) {
                    throw RangeError(o["invalid-input"])
                }
                I = b(J.charCodeAt(H++));
                if (I >= j || I > t((p - F) / K)) {
                    throw RangeError(o.overflow)
                }
                F += I * K;
                M = A <= G ? l : (A >= G + n ? n : A - G);
                if (I < M) {
                    break
                }
                L = j - M;
                if (K > t(p / L)) {
                    throw RangeError(o.overflow)
                }
                K *= L
            }
            E = z.length + 1;
            G = a(F - x, E, x == 0);
            if (t(F / E) > p - y) {
                throw RangeError(o.overflow)
            }
            y += t(F / E);
            F %= E;
            z.splice(F++, 0, y)
        }
        return v(z)
    }

    return {encode: g, decode: q}
}();
Helper.isFontIcon = function (a) {
    if (!a || a.length == 0 || a.length < "FontIcon_".length) {
        return false
    }
    return a.substring(0, "FontIcon_".length) == "FontIcon_"
};
Helper.changeHeight = function () {
    var c = $(window).height() - 40 + "px";
    $(".tableContainer").css("min-height", c);
    $(".tablePanel , .panel").css("min-height", c);
    if ($.browser.msie) {
        $(".tableContainer").css("margin-top", "0px");
        $(".tablePanel,.panel").css("margin-top", "20px");
        $(".tableContainer").css("padding-bottom", "20px");
        $(".tableContainer").css("height", "auto");
        $(".tableContainer").css("padding-bottom", "0px");
        var a = parseInt($(".tablePanel").height());
        var b = a + "px";
        if (a > parseInt(c)) {
            $(".tableContainer").css("height", a);
            $(".tableContainer").css("padding-bottom", "40px")
        }
    }
};
Helper.formatPriceEn = function (g, c, b) {
    c = ((c >= 0 && c <= 20) ? c : 2);
    if (b) {
        g = parseFloat((g + "").replace(/[^\d\.-]/g, "")) + ""
    } else {
        g = parseFloat((g + "").replace(/[^\d\.-]/g, "")).toFixed(c) + ""
    }
    if (c == 0 || (b && g.indexOf(".") < 0)) {
        var h = g.split("").reverse(), f = "";
        for (i = 0; i < h.length; i++) {
            f += h[i] + ((i + 1) % 3 == 0 && (i + 1) != h.length ? "," : "")
        }
        return f.split("").reverse().join("")
    } else {
        var d = g.split(".")[0].split("").reverse(), a = g.split(".")[1];
        res2 = "";
        for (i = 0; i < d.length; i++) {
            res2 += d[i] + ((i + 1) % 3 == 0 && (i + 1) != d.length ? "," : "")
        }
        return res2.split("").reverse().join("") + "." + a
    }
};