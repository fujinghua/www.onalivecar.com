function showYuanDanWindow() {
    var hasShowYuanDanLuckyGuy = $.cookie("hasShowYuanDanLuckyGuy", {path: "/"});
    var _hasGetYuanDanLuckyGuy_ = $.cookie("_hasGetYuanDanLuckyGuy_", {path: "/"});
    var _openbigImg = false;

    if (_openbigImg == true) {
        if (hasShowYuanDanLuckyGuy == 'true' || _hasGetYuanDanLuckyGuy_ != 'true') {
            Run.isShowThreeDaysLuckyGuy = "true";
            Run.isTenMinutes = 'true';
            $.cookie("hasShowYuanDanLuckyGuy", false, {path: "/"});
            Run.drawLottery();
        } else {

            Run.sendResult2();
        }
    }
}


var fk_sale = new Object();
fk_sale.cid = 13775917;
fk_sale.siteVer = 10;
fk_sale.popupWindowRunVer = 10;
fk_sale.isLuckyGuyFlag = false;
fk_sale.popupWindowEndYear = 2017;
fk_sale.popupWindowEndMonth = 5;
fk_sale.popupWindowEndDay = 31;
fk_sale.popupWindowDays = 15;
fk_sale.popupWindowMs = 1346686515;
fk_sale.openDays = 0;
fk_sale.openMinutes = 1402;
fk_sale.popupWindowEndSignupHours = 720.0;
fk_sale.url = '';
fk_sale.showDomainWindowFlag = false;
fk_sale.cacct = '';
fk_sale.imgBigSrc = '';
fk_sale.imgBigBtn = '';
fk_sale.imgClose = '';
fk_sale.siteFirstLogin = true;
fk_sale.isShowAdvertisementWindowThreeMinute = false;
fk_sale.textUrl = '';
fk_sale.domainImgBigBg = '';
fk_sale.domainImgClose = '';
fk_sale.siteBizBigClose = '';
fk_sale.siteBizBigBg = '';
fk_sale.showRunBizWindow = false;
fk_sale.showRunPopWindow = false;
fk_sale.openFlyer = false;


// 为了避免用户误操作，在域名结尾输入多余字符导致cookie失效问题，这里校验一下浏览器的host是否与后台拿到的host一致
//if (window.location.host != 'www.ot13227318.icoc.me') { window.location.href = 'http://' + 'www.ot13227318.icoc.me'; }
//console.log(window.location.host.lastIndexOf("."));
var _jsErrCahche = [];
window.onerror = function (sMsg, sUrl, sLine) {
    if (typeof Run === 'undefined') {
        alert('您的网页未加载完成，请尝试按“CTRL+功能键F5”重新加载。');
    }
    if (sLine < 1 || typeof sMsg != 'string' || sMsg.length < 1) {
        return;
    }

    var log = "Error:" + sMsg + ";Line:" + sLine + ";Url:" + sUrl;
    var alertLog = "Error:" + sMsg + "\n" + "Line:" + sLine + "\n" + "Url:" + sUrl + "\n";
    var encodeUrl = function (url) {
        return typeof url === "undefined" ? "" : encodeURIComponent(url);
    };

    var ajax = true;
    var obj = {'m': sMsg, 'u': sUrl, 'l': sLine};
    for (var i = 0; i < _jsErrCahche.length; i++) {
        if (_jsErrCahche[i].m == obj.m && _jsErrCahche[i].u == obj.u && _jsErrCahche[i].l == obj.l) {
            ajax = false;
            break;
        }
    }

    if (ajax) {
        _jsErrCahche.push(obj);
        _faiAjax.ajax({
            type: "post",
            url: "/",
            data: 'msg=' + encodeUrl(log)
        });
    }
    if (false) {
        alert(alertLog);
    }
};


Helper.top = window;
var bgmCloseToOpen = false;
var _debug = false;
var _isPre = false;
var _devMode = false;
var _colOtherStyleData = {"independentList": [], "y": 0, "h": 0, "layout4Width": 0, "layout5Width": 0};						// 当前页面的数据
var _templateOtherStyleData = {"independentList": [], "h": 540, "y": 0, "layout4Width": 0, "layout5Width": 0};						// 全局的数据


var _templateDefLayout = {
    BANNER_NAV: 0,
    NAV_FLOAT: 1,
    LEFT_NAV_BANNER_RIGHT_HIDE: 3,
    LEFT_NAV_CENTER_BANNER_RIGHT_HIDE: 4,
    LEFT_BANNER_NAV_RIGHT_HIDE: 5,
    LEFT_NAV_EXPEND_CENTER_BANNER: 6,
    CENTER_TOP_BANNER_RIGHT_HIDE: 7,
    LEFT_HIDE_CENTER_TOP_BANNER: 8,
    NAV_FLOAT_ON_BANNER: 9,
    NAV_BANNER: 10
};


$(function () {

    //Run.changeTheLogoSize();
    Run.showOrHideMailBox();


    Run.loginRunInit('', '', false, "");

    //topBarMember


    // 绑定退出事件
    Run.bindBeforeUnloadEvent(false, false, false);

    Run.initTemplateLayout(_templateDefLayout.NAV_FLOAT, true, false);
    // spider统计


    // ajax统计
    Run.total({colId: 108, pdId: -1, ndId: -1, sc: 0, rf: "/home/house/newhouse.html"});
    //前端性能数据上报
    //Run.report();
    //保留旧用户的初始化版式区域4 和区域5 中，区域4的padding-right空间
    Run.colLayout45Width();
    //各个模块inc吐出脚本
    Run.initCorpTitleJump();
    Run.initBanner({
        "_open": true,
        "data": [{
            "title": "",
            "desc": "",
            "imgWidth": 1920,
            "imgHeight": 300,
            "ot": 1,
            "src": "/static/images/theme/theme-banner2.jpg",
            "edgeLeft": "",
            "edgeRight": ""
        }],
        "width": 1920,
        "height": 300,
        "playTime": 4000,
        "animateTime": 1500,
        "from": "banner",
        "btnType": 1,
        "wideScreen": false
    }, {"_open": false}, 4);
    Run.loadNewsList(529, {"y": 0, "s": 0, "w": 1}, false);
    Run.loadNewsNewStyle(529, false, false, false, true, false, true, false, false, false);

    Run.setFullMeasureBgHightInIe6(388);
    ;
    $(function () {
        Run.inFullmeasueAnimation.animateFm(388, 0, 1, {"c": 0, "d": 4.0, "s": 1.0});
    });
    Run.loadNewsList(385, {"y": 0, "s": 0, "w": 1}, false);
    Run.loadNewsNewStyle(385, false, false, false, true, true, false, false, false, false);

    jzUtils.run({"name": "ImageEffect.FUNC.BASIC.Init", "callMethod": true}, {
        "moduleId": 555,
        "imgEffOption": {
            "effType": 1,
            "borderType": false,
            "borderColor": "#000",
            "borderWidth": 1,
            "borderStyle": 1,
            "hoverPicPath": "/static/images/floatImgHoverDef.png",
            "openHoverPic": false,
            "isFontIcon": false,
            "ishovFont": false,
            "hovFont": "faisco-icons-contact2",
            "hovFontColor": "#222222",
            "picPath": "/static/images/shadow-buy-house.png",
            "isInit": false
        },
        "tagetOption": {"targetParent": "floatImg_J", "target": "floatImg_J"},
        "callback": Run.createImageEffectContent_photo,
        "callbackArgs": []
    });
    jzUtils.run({"name": "ImageEffect.FUNC.BASIC.Init", "callMethod": true}, {
        "moduleId": 557,
        "imgEffOption": {
            "effType": 1,
            "borderType": false,
            "borderColor": "#000",
            "borderWidth": 1,
            "borderStyle": 1,
            "hoverPicPath": "/static/images/floatImgHoverDef.png",
            "openHoverPic": false,
            "isFontIcon": false,
            "ishovFont": false,
            "hovFont": "faisco-icons-contact2",
            "hovFontColor": "#222222",
            "picPath": "/static/images/shadow-sale-house.png",
            "isInit": false
        },
        "tagetOption": {"targetParent": "floatImg_J", "target": "floatImg_J"},
        "callback": Run.createImageEffectContent_photo,
        "callbackArgs": []
    });
    jzUtils.run({"name": "ImageEffect.FUNC.BASIC.Init", "callMethod": true}, {
        "moduleId": 558,
        "imgEffOption": {
            "effType": 1,
            "borderType": false,
            "borderColor": "#000",
            "borderWidth": 1,
            "borderStyle": 1,
            "hoverPicPath": "/static/images/floatImgHoverDef.png",
            "openHoverPic": false,
            "isFontIcon": false,
            "ishovFont": false,
            "hovFont": "faisco-icons-contact2",
            "hovFontColor": "#222222",
            "picPath": "/static/images/shadow-rent-house.png",
            "isInit": false
        },
        "tagetOption": {"targetParent": "floatImg_J", "target": "floatImg_J"},
        "callback": Run.createImageEffectContent_photo,
        "callbackArgs": []
    });
    jzUtils.run({"name": "ImageEffect.FUNC.BASIC.Init", "callMethod": true}, {
        "moduleId": 559,
        "imgEffOption": {
            "effType": 1,
            "borderType": false,
            "borderColor": "#000",
            "borderWidth": 1,
            "borderStyle": 1,
            "hoverPicPath": "/static/images/floatImgHoverDef.png",
            "openHoverPic": false,
            "isFontIcon": false,
            "ishovFont": false,
            "hovFont": "faisco-icons-contact2",
            "hovFontColor": "#222222",
            "picPath": "/static/images/shadow-manage-money.png",
            "isInit": false
        },
        "tagetOption": {"targetParent": "floatImg_J", "target": "floatImg_J"},
        "callback": Run.createImageEffectContent_photo,
        "callbackArgs": []
    });


    Run.initPage();	// 这个要放在最后，因为模块组初始化时会把一些模块隐藏，导致没有高度，所以要放最后执行


    setTimeout(afterModuleLoaded, 0);


    Run.triggerGobalEvent("siteReadyLoad");


});

function afterModuleLoaded() {

    Run.initPage2();


    //Run.mallCartInit(_colId);
    //Run.mobiWebInit();


} // afterModuleLoaded end

var _lcid = 2052;
var _siteDomain = '';
var _resRoot = '';
var _colId = 108;
var _fromColId = -1;
var _designAuth = false;
var _manageMode = false;
var _oem = false;
var _siteVer = 10;
var _manageStatus = false;
var _webRightBar = false;
var _isMemberLogin = false;// 会员是否登录了


var nav2SubMenu = [];
var nav109SubMenu = [];
var nav106SubMenu = [];
var nav105SubMenu = [];
var nav108SubMenu = [{
    "hidden": false,
    "colId": 9,
    "href": "/home/contact/contact.html",
    "target": " target='_self'",
    "colName": "Contact",
    "html": "<span class='itemName0'>Contact<\/span>",
    "title": "",
    "onclick": "return true;"
}];

var _customBackgroundData = {
    "id": "",
    "sw": 1200,
    "bBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "cBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "cmBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "s": true,
    "h": false,
    "r": 3,
    "o": "",
    "e": 0,
    "wbh": -1,
    "wbw": -1,
    "clw": -1,
    "crw": -1,
    "wbws": -1,
    "wbs": 0,
    "p": "",
    "bm": {},
    "cm": {},
    "cp": {"y": 0}
};          //自定义的数据
var _templateBackgroundData = {
    "id": "",
    "bBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "cBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "cmBg": {"c": "#000", "f": "", "r": 3, "p": "", "y": 0},
    "sw": 1200,
    "s": true,
    "h": false,
    "r": 3,
    "o": "",
    "e": 0,
    "wbh": -1,
    "wbw": -1,
    "clw": -1,
    "crw": -1,
    "wbws": -1,
    "wbs": 1,
    "p": "",
    "bm": {},
    "cm": {},
    "cp": {"y": 0}
};// 模版的数据

var _templateBannerData = {
    "ce": {},
    "pl": 0,
    "l": [{
        "i": "",
        "p": "/static/images/theme/theme-banner1.jpg",
        "w": 1920,
        "h": 780,
        "tp": "/static/images/theme/theme-banner1-icon.jpg"
    }, {
        "i": "",
        "p": "/static/images/theme/theme-banner1.jpg",
        "w": 1920,
        "h": 780,
        "tp": "/static/images/theme/theme-banner1-icon.jpg"
    }, {
        "i": "",
        "p": "/static/images/theme/theme-banner3.jpg",
        "w": 1920,
        "h": 780,
        "tp": "/static/images/theme/theme-banner-icon.jpg"
    }],
    "n": [{
        "t": 1,
        "i": "",
        "e": 0,
        "u": "",
        "p": "/static/images/theme/theme-banner3.jpg",
        "w": 1920,
        "h": 780,
        "el": "",
        "er": ""
    }, {
        "t": 1,
        "i": "",
        "e": 0,
        "u": "",
        "p": "/static/images/theme/theme-banner-icon.jpg",
        "w": 1920,
        "h": 780,
        "el": "",
        "er": ""
    }],
    "s": 4,
    "bt": 0,
    "at": 2,
    "i": 4000,
    "a": 1500,
    "h": false,
    "o": false,
    "t": 1,
    "p": 0,
    "pt": 0,
    "ws2": false,
    "f": {},
    "c": 3,
    "ws": false
};		// 模版的数据

var _pageBannerData = {
    "ce": {},
    "pl": 0,
    "l": [{
        "i": "",
        "p": "/static/images/theme/theme-page-banner1.jpg",
        "w": 1920,
        "h": 300,
        "tp": "/static/images/theme/theme-banner-icon.jpg"
    }, {
        "i": "",
        "p": "/static/images/theme/theme-page-banner2.jpg",
        "w": 1920,
        "h": 300,
        "tp": "/static/images/theme/theme-page-banner2.jpg"
    }, {
        "i": "",
        "p": "/static/images/theme/theme-page-banner3.jpg",
        "w": 1920,
        "h": 300,
        "tp": "/static/images/theme/theme-page-banner3.jpg"
    }],
    "n": [{
        "t": 1,
        "i": "",
        "e": 0,
        "u": "",
        "p": "/static/images/theme/theme-page-banner2.jpg",
        "w": 1920,
        "h": 300,
        "el": "",
        "er": ""
    }],
    "s": 4,
    "i": 4000,
    "a": 1500,
    "h": false,
    "o": false,
    "t": 1,
    "p": 0,
    "pt": 0,
    "bt": 1,
    "ws2": false,
    "f": {},
    "c": 3,
    "at": 0,
    "ws": false
};					// 当前页面的自定义数据（页面独立样式设置）
var _bannerData = _pageBannerData;

var _useTemplateHeaderZone = true;				// 是否使用全局模版
var _useTemplateFooterZone = true;				// 是否使用全局模版

var _mallOpen = false;
var _couponOpen = false

var toolBoxShowView = false;
var toolBoxShowSet = false;


var _navStyleData = {
    "no": true,
    "s": 0,
    "ns": {"w": 601, "h": -1},
    "cs": {"w": 601, "h": -1},
    "np": {},
    "ncp": {"y": 1, "t": 45, "r": -1, "b": -1, "l": 493, "hl": 351, "ht": 80},
    "cp": {"y": 0, "t": -1, "l": -1},
    "nis": {"w": -1, "h": -1},
    "gt": {"f": "微软雅黑", "s": 16, "w": 0, "c": "#4d4d4d", "y": 0},
    "ht": {"c": "#ffffff", "y": 0},
    "nb": {"c": "#000", "f": "", "r": 0, "p": "", "y": 0},
    "cb": {"c": "#000", "f": "", "r": 0, "p": "", "y": 0},
    "nib": {"c": "#000", "r": 0, "f": "", "p": "", "y": 0},
    "nihb": {
        "c": "#fe8300",
        "f": "",
        "r": -1,
        "p": "/static/images/bg.png",
        "y": 0
    },
    "niss": {"w": -1, "h": -1},
    "nisb": {"c": "#000", "r": 0, "f": "", "p": "", "y": 0},
    "nsigt": {"f": "微软雅黑", "s": 16, "w": 0, "c": "#ffffff", "ta": 0, "y": 0},
    "nsiht": {"c": "#ffffff", "y": 0},
    "nsis": {"w": -1, "h": -1},
    "nsib": {"c": "#fe8300", "r": 0, "f": "", "p": "", "y": 0},
    "nsihb": {"c": "#ff9626", "r": 0, "f": "", "p": "", "y": 0},
    "nsiho": 0,
    "nmove": 1,
    "v": 2,
    "ntf": {"t": 0, "ht": 0},
    "nsmt": {},
    "nsmb": {},
    "nrs": {
        "n": {"t": 0},
        "c": {"t": 0},
        "i": {"t": 0},
        "smt": {"t": 0},
        "smb": {"t": 0},
        "si": {"t": 0},
        "is": {"t": 0},
        "sis": {"t": 0},
        "tmt": {"t": 0},
        "tmb": {"t": 0},
        "ti": {"t": 0},
        "tis": {"t": 0}
    },
    "nsw": {
        "n": {"t": 0},
        "c": {"t": 0},
        "i": {"t": 0},
        "sm": {"t": 0},
        "si": {"t": 0},
        "is": {"t": 0},
        "sis": {"t": 0}
    },
    "nbr": {
        "n": {"t": 0},
        "i": {"t": 0},
        "sm": {"t": 0},
        "si": {"t": 0},
        "is": {"t": 0},
        "sis": {"t": 0},
        "tm": {"t": 0},
        "ti": {"t": 0},
        "tis": {"t": 0}
    },
    "ntmt": {},
    "ntmb": {}
};				      // 栏目导航样式
var _navHidden = false;                                  //true:隐藏；flase：显示
var _siteDemo = false;


var _backToTop = true;
var _aid = 13775917;
var _templateLayout = _templateDefLayout.NAV_FLOAT;
var _webBannerHeight = -1;
var _isTemplateVersion2 = true;
var _uiMode = false;

var _choiceCurrencyVal = "￥";
var _moduleAnimationPercent = -1;

var _useTemplateBackground = true;		// 是否使用模版

var fk_old_onload = window.onload;
window.onload = function () {


    Run.cacheModuleFunc.runRunInit();


    if (typeof fk_old_onload == "function") {
        fk_old_onload.apply(this, arguments);
    }


    Run.pageOnload();


};


Run.beforeUnloadFunc();


$LAB.script("/static/js/home/photoSlide.min.js?v=201702271724");
$LAB.script("/static/js/home/imageEffect.min.js?v=201703131745")
    .wait(function () {
        jzUtils.trigger({
            "name": "ImageEffect.FUNC.BASIC.Init",
            "base": Run
        });
    });
