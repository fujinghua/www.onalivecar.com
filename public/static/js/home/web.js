var data = [
    {
        "title": "图片1",
        "desc": "文字1",
        "target": "",
        "src": "/static/images/theme/theme-banner1.jpg",
    },
    {
        "title": "图片2",
        "desc": "文字2",
        "target": "",
        "src": "/static/images/theme/theme-banner2.jpg",
    },
];
var options = {
    "width": 1200,
    "height": 487,
    "playTime": 4000,
    "animateTime": 1500,
    "btnType": 1,
    "targetType":"2",
    "wideScreen": false
};

Site.initBanner(data,options);

if (typeof Run === 'undefined'){
    Run = {};
}

Run.initBanner = function (v, e, g) {
    if (Helper.top._uiMode) {
        return
    }
    var o = false;
    var a = Helper.top.$("#webBanner");
    var c = Helper.top.$("#banner");
    var x = Run.getWebBackgroundData();
    var u = x.wbs ? true : false;
    if (Helper.top._templateLayout == 0 || Helper.top._templateLayout == 1 || Helper.top._templateLayout == 9 || Helper.top._templateLayout == 10) {
        var p = Helper.top._manageMode ? Run.getWebBackgroundData().wbh : Helper.top._webBannerHeight;
        if (p > -1) {
            v.height = p;
            if (c.siblings(".nav").length > 0) {
                var q = c.attr("normalheight");
                if (q == -1) {
                    q = c.css("height").replace("px", "")
                }
                var s = c.siblings(".nav").height() || 0;
                if (p > -1) {
                    v.height = a.height() - s;
                    if (a.height() < (parseInt(q) + parseInt(s))) {
                        o = true
                    }
                }
                if (c.attr("normalheight") == -1 && o) {
                    c.css({height: v.height + "px"})
                }
            }
        }
    }
    if (v._open) {
        if (e._open && Helper.flashChecker().f) {
            v.mouseoverId = "bannerGetHref"
        } else {
            v.mouseoverId = "switchGroup"
        }
        c.children().remove();
        c.bannerImageSwitch(v);
        var r = c.width();
        var b = c.height();
        var i = Helper.top._manageMode ? Helper.getScrollWidth() : 0;
        var n = Helper.top.$(window).width() > Helper.top.$("#web").width() ? Helper.top.$(window).width() : Helper.top.$("#web").width();
        if (o) {
            b = v.height
        }
        if (u) {
            r = Helper.top.$("#webBanner").width();
            r = (r > n) ? n - i : r;
            b = Helper.top.$("#webBanner").height()
        }
        var d = (Helper.top.$("#containerFormsCenter").find("div").eq(0).attr("id"));
        var l = Helper.top.$("#banner .switchGroup");
        Helper.top.$("#banner .switchGroup .J_bannerItem").each(function (A) {
            if (!v.data[A]) {
                return false
            }
            var z = v.data[A] ? v.data[A].imgWidth : 0;
            if (r >= z && !u) {
                $(this).css("width", v.data[A].imgWidth);
                var y = l.width() - v.data[A].imgWidth;
                if (Helper.isIE()) {
                    y = y + (y % 2)
                }
                $(this).siblings(".J_bannerEdge").css("width", y / 2)
            } else {
                $(this).css("width", r);
                var y = l.width() - r;
                if (Helper.isIE()) {
                    y = y + (y % 2)
                }
                $(this).siblings(".J_bannerEdge").css("width", y / 2)
            }
            if (b >= v.data[A].imgHeight && !u) {
                if (Run.checkBrowser() && Helper.top._bannerData.at > 0) {
                    $(this).css("height", b)
                } else {
                    $(this).css("height", v.data[A].imgHeight);
                    $(this).parent().css("paddingTop", (b - v.data[A].imgHeight) / 2 + "px")
                }
                $(this).siblings(".J_bannerEdge").height(v.data[A].imgHeight);
                $(this).siblings(".J_bannerEdge").css("top", (b - v.data[A].imgHeight) / 2 + "px")
            } else {
                $(this).css("height", b);
                $(this).siblings(".J_bannerEdge").height(b)
            }
        });
        c.css("background", "none")
    } else {
        Run.refreshDefaultBannerEdge()
    }
    if (e._open && Helper.flashChecker().f) {
        if ($.browser.mozilla && $.browser.version == "49.0") {
            return
        }
        var h = 0;
        var w = 0;
        var f = Helper.top.$("#banner").width();
        if (f > 960) {
            f = 960;
            h = parseInt((Helper.top.$("#webBanner").width() - 960) / 2)
        }
        var j = Helper.top.$("#banner").height();
        if (e.position == 1) {
            f = f / 2
        } else {
            if (e.position == 2) {
                f = f / 2;
                h += f
            } else {
                if (e.position == 3) {
                    h += e.positionLeft;
                    w = e.positionTop
                }
            }
        }
        if (typeof Helper != "undefined" && typeof Helper.top._resRoot != "undefined") {
            resRoot = Helper.top._resRoot
        }
        if (typeof e.color1 == "undefined") {
            e.color1 = "#000"
        }
        if (typeof e.color2 == "undefined") {
            e.color2 = "#FFFFFF"
        }
        var t = "text1=" + Helper.encodeUrl(e.text1) + "&text2=" + Helper.encodeUrl(e.text2) + "&size1=" + e.size1 + "&size2=" + e.size2 + "&color1=0x" + e.color1.substr(1, e.color1.length - 1) + "&color2=0x" + e.color2.substr(1, e.color2.length - 1) + "&style1=" + e.style1 + "&style2=" + e.style2;
        var m = ['<div class="effectShow" id="effectShow" style="position:absolute; top:' + w + "px; left:" + h + 'px; z-index:1;">', '<object id="effectShow_swf" type="application/x-shockwave-flash"  data="' + Helper.top._resRoot + "/image/site/effects/" + e.type + ".swf?" + $.md5(t) + '" width="' + f + '" height="' + j + '">', '<param name="movie" value="' + Helper.top._resRoot + "/image/site/effects/" + e.type + ".swf?" + $.md5(t) + '"/>', '<param name="quality" value="high" />', '<param name="wmode" value="transparent" />', '<param name="flashvars" value="' + t + '"/>', '<embed id="effectShowEmbed" name="effectShow_swf" type="application/x-shockwave-flash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-4445535400000" src="', Helper.top._resRoot, "/image/site/effects/" + e.type + ".swf?" + $.md5(t) + '" wmode="transparent" quality="high" menu="false" play="true" loop="true" width="', f, '" height="', j, 'flashvars="' + t + '"', '" >', "</embed>", "</object>", "</div>"];
        m = m.join("");
        if (g == 4) {
            var k = '<div id="bannerGetHref" class="bannerGetHref" style="position:absolute; top:0; left:' + h + "px; width:" + f + "px; height:100%; z-index:1; background:url('" + resRoot + '/image/site/transpace.png\');" onmouseover="Run.bannerInitHref();"  onmouseout="Run.startBannerInterval();" onclick="Run.bannerGetHref();"></div>';
            m = m + k
        }
        c.append(m);
        var b = c.height();
        var r = c.width();
        if (v.data && v.data.length > 0) {
            Helper.top.$("#bannerGetHref").css({
                width: v.data[0].imgWidth,
                height: v.data[0].imgHeight,
                left: (r - v.data[0].imgWidth) / 2 + "px",
                top: (b - v.data[0].imgHeight) / 2 + "px"
            })
        }
    }
    if (v._open && $(".webBanner").width() != 960) {
        $(window).resize(function (y) {
            if (!y.target.nodeType || y.target.nodeType == 9) {
                setTimeout(function () {
                    Run.refreshBanner(0)
                }, 500)
            }
        })
    }
    Run.adjustBannerFlash()
};


