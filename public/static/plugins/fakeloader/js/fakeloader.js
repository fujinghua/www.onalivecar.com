/*--------------------------------------------------------------------
 *JAVASCRIPT "FakeLoader.js"
 *Version:    1.1.0 - 2014
 *author:     João Pereira
 *website:    http://www.joaopereira.pt
 *Licensed MIT 
-----------------------------------------------------------------------*/
(function ($) {
 
    $.fn.fakeLoader = function(options) {

        var _loader = $(".fakeloader");
        if (_loader.length >0){
            var _div = document.createElement('div');
            _div.className = 'fakeloader';
            $('body').append(_div);
        }

        //Defaults
        var settings = $.extend({
            timeToHide:1200, // Default Time to hide fakeLoader
            pos:'fixed',// Default Position
            top:'0px',  // Default Top value
            left:'0px', // Default Left value
            width:'100%', // Default width 
            height:'100%', // Default Height
            zIndex: '999999',  // Default zIndex
            bgColor: '#2ecc71', // Default background color
            bgLoading: '#fff', // Default background color
            spinner:'spinner7', // Default Spinner
            imagePath:'', // Default Path custom image
            loadCss:{
                position:'absolute',
                top:'40%',
                left:'50%'
            }
        }, options);

        var f1Css = '';
        for (var i in settings.loadCss){
            f1Css += i + ':' + settings.loadCss[i] + ';'
        }
        var loadingCss = 'style="background:'+settings.bgLoading+';"';

        //Customized Spinners
        var spinner01 = '<div class="fl spinner1" style="'+f1Css+'"><div class="double-bounce1" '+loadingCss+'></div><div class="double-bounce2" '+loadingCss+'></div></div>';
        var spinner02 = '<div class="fl spinner2" style="'+f1Css+'"><div class="spinner-container container1"><div class="circle1" '+loadingCss+'></div><div class="circle2" '+loadingCss+'></div><div class="circle3" '+loadingCss+'></div><div class="circle4" '+loadingCss+'></div></div><div class="spinner-container container2"><div class="circle1" '+loadingCss+'></div><div class="circle2" '+loadingCss+'></div><div class="circle3" '+loadingCss+'></div><div class="circle4" '+loadingCss+'></div></div><div class="spinner-container container3"><div class="circle1" '+loadingCss+'></div><div class="circle2" '+loadingCss+'></div><div class="circle3" '+loadingCss+'></div><div class="circle4" '+loadingCss+'></div></div></div>';
        var spinner03 = '<div class="fl spinner3" style="'+f1Css+'"><div class="dot1" '+loadingCss+'></div><div class="dot2" '+loadingCss+'></div></div>';
        var spinner04 = '<div class="fl spinner4" style="'+f1Css+'"></div>';
        var spinner05 = '<div class="fl spinner5" style="'+f1Css+'"><div class="cube1" '+loadingCss+'></div><div class="cube2" '+loadingCss+'></div></div>';
        var spinner06 = '<div class="fl spinner6" style="'+f1Css+'"><div class="rect1" '+loadingCss+'></div><div class="rect2" '+loadingCss+'></div><div class="rect3" '+loadingCss+'></div><div class="rect4" '+loadingCss+'></div><div class="rect5" '+loadingCss+'></div></div>';
        var spinner07 = '<div class="fl spinner7" style="'+f1Css+'"><div class="circ1" '+loadingCss+'></div><div class="circ2" '+loadingCss+'></div><div class="circ3" '+loadingCss+'></div><div class="circ4" '+loadingCss+'></div></div>';

        //The target
        var el = $(this);

        //Init styles
        var initStyles = {
            'position':settings.pos,
            'width':settings.width,
            'height':settings.height,
            'top':settings.top,
            'left':settings.left
        };

        //Apply styles
        el.css(initStyles);

        //Each
        el.each(function() {
            var a = settings.spinner;
            //console.log(a)
                switch (a) {
                    case 'spinner1':
                            el.html(spinner01);
                        break;
                    case 'spinner2':
                            el.html(spinner02);
                        break;
                    case 'spinner3':
                            el.html(spinner03);
                        break;
                    case 'spinner4':
                            el.html(spinner04);
                        break;
                    case 'spinner5':
                            el.html(spinner05);
                        break;
                    case 'spinner6':
                            el.html(spinner06);
                        break;
                    case 'spinner7':
                            el.html(spinner07);
                        break;
                    default:
                        el.html(spinner01);
                    }

                //Add customized loader image

                if (settings.imagePath !='') {
                    el.html('<div class="fl"><img src="'+settings.imagePath+'"></div>');
                    centerLoader();
                }
        });

        //Time to hide fakeLoader
        setTimeout(function(){
            $(el).fadeOut();
        }, settings.timeToHide);

        //Return Styles 
        return this.css({
            'backgroundColor':settings.bgColor,
            'zIndex':settings.zIndex
        });

 
    }; // End Fake Loader
 

        //Center Spinner
        function centerLoader() {

            var winW = $(window).width();
            var winH = $(window).height();

            var spinnerW = $('.fl').outerWidth();
            var spinnerH = $('.fl').outerHeight();

        }

        $(window).load(function(){
                centerLoader();
              $(window).resize(function(){
                centerLoader();
              });
        });


}(jQuery));




