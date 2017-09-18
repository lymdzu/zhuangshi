;(function(n,t){"use strict";function r(n){a[a.length]=n}function k(n){var t=new RegExp(" ?\\b"+n+"\\b");c.className=c.className.replace(t,"")}function p(n,t){for(var i=0,r=n.length;i<r;i++)t.call(n,n[i],i)}function tt(){var t,e,f,o;c.className=c.className.replace(/ (w-|eq-|gt-|gte-|lt-|lte-|portrait|no-portrait|landscape|no-landscape)\d+/g,"");t=n.innerWidth||c.clientWidth;e=n.outerWidth||n.screen.width;u.screen.innerWidth=t;u.screen.outerWidth=e;r("w-"+t);p(i.screens,function(n){t>n?(i.screensCss.gt&&r("gt-"+n),i.screensCss.gte&&r("gte-"+n)):t<n?(i.screensCss.lt&&r("lt-"+n),i.screensCss.lte&&r("lte-"+n)):t===n&&(i.screensCss.lte&&r("lte-"+n),i.screensCss.eq&&r("e-q"+n),i.screensCss.gte&&r("gte-"+n))});f=n.innerHeight||c.clientHeight;o=n.outerHeight||n.screen.height;u.screen.innerHeight=f;u.screen.outerHeight=o;u.feature("portrait",f>t);u.feature("landscape",f<t)}function it(){n.clearTimeout(b);b=n.setTimeout(tt,50)}var y=n.document,rt=n.navigator,ut=n.location,c=y.documentElement,a=[],i={screens:[240,320,480,640,768,800,1024,1280,1440,1680,1920],screensCss:{gt:!0,gte:!1,lt:!0,lte:!1,eq:!1},browsers:[{ie:{min:6,max:11}}],browserCss:{gt:!0,gte:!1,lt:!0,lte:!1,eq:!0},html5:!0,page:"-page",section:"-section",head:"head"},v,u,s,w,o,h,l,d,f,g,nt,e,b;if(n.head_conf)for(v in n.head_conf)n.head_conf[v]!==t&&(i[v]=n.head_conf[v]);u=n[i.head]=function(){u.ready.apply(null,arguments)};u.feature=function(n,t,i){return n?(Object.prototype.toString.call(t)==="[object Function]"&&(t=t.call()),r((t?"":"no-")+n),u[n]=!!t,i||(k("no-"+n),k(n),u.feature()),u):(c.className+=" "+a.join(" "),a=[],u)};u.feature("js",!0);s=rt.userAgent.toLowerCase();w=/mobile|android|kindle|silk|midp|phone|(windows .+arm|touch)/.test(s);u.feature("mobile",w,!0);u.feature("desktop",!w,!0);s=/(chrome|firefox)[ \/]([\w.]+)/.exec(s)||/(iphone|ipad|ipod)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(android)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(webkit|opera)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(msie) ([\w.]+)/.exec(s)||/(trident).+rv:(\w.)+/.exec(s)||[];o=s[1];h=parseFloat(s[2]);switch(o){case"msie":case"trident":o="ie";h=y.documentMode||h;break;case"firefox":o="ff";break;case"ipod":case"ipad":case"iphone":o="ios";break;case"webkit":o="safari"}for(u.browser={name:o,version:h},u.browser[o]=!0,l=0,d=i.browsers.length;l<d;l++)for(f in i.browsers[l])if(o===f)for(r(f),g=i.browsers[l][f].min,nt=i.browsers[l][f].max,e=g;e<=nt;e++)h>e?(i.browserCss.gt&&r("gt-"+f+e),i.browserCss.gte&&r("gte-"+f+e)):h<e?(i.browserCss.lt&&r("lt-"+f+e),i.browserCss.lte&&r("lte-"+f+e)):h===e&&(i.browserCss.lte&&r("lte-"+f+e),i.browserCss.eq&&r("eq-"+f+e),i.browserCss.gte&&r("gte-"+f+e));else r("no-"+f);r(o);r(o+parseInt(h,10));i.html5&&o==="ie"&&h<9&&p("abbr|article|aside|audio|canvas|details|figcaption|figure|footer|header|hgroup|main|mark|meter|nav|output|progress|section|summary|time|video".split("|"),function(n){y.createElement(n)});p(ut.pathname.split("/"),function(n,u){if(this.length>2&&this[u+1]!==t)u&&r(this.slice(u,u+1).join("-").toLowerCase()+i.section);else{var f=n||"index",e=f.indexOf(".");e>0&&(f=f.substring(0,e));c.id=f.toLowerCase()+i.page;u||r("root"+i.section)}});u.screen={height:n.screen.height,width:n.screen.width};tt();b=0;n.addEventListener?n.addEventListener("resize",it,!1):n.attachEvent("onresize",it)})(window);
/*! head.css3 - v1.0.0 */
(function(n,t){"use strict";function a(n){for(var r in n)if(i[n[r]]!==t)return!0;return!1}function r(n){var t=n.charAt(0).toUpperCase()+n.substr(1),i=(n+" "+c.join(t+" ")+t).split(" ");return!!a(i)}var h=n.document,o=h.createElement("i"),i=o.style,s=" -o- -moz- -ms- -webkit- -khtml- ".split(" "),c="Webkit Moz O ms Khtml".split(" "),l=n.head_conf&&n.head_conf.head||"head",u=n[l],f={gradient:function(){var n="background-image:";return i.cssText=(n+s.join("gradient(linear,left top,right bottom,from(#9f9),to(#fff));"+n)+s.join("linear-gradient(left top,#eee,#fff);"+n)).slice(0,-n.length),!!i.backgroundImage},rgba:function(){return i.cssText="background-color:rgba(0,0,0,0.5)",!!i.backgroundColor},opacity:function(){return o.style.opacity===""},textshadow:function(){return i.textShadow===""},multiplebgs:function(){i.cssText="background:url(https://),url(https://),red url(https://)";var n=(i.background||"").match(/url/g);return Object.prototype.toString.call(n)==="[object Array]"&&n.length===3},boxshadow:function(){return r("boxShadow")},borderimage:function(){return r("borderImage")},borderradius:function(){return r("borderRadius")},cssreflections:function(){return r("boxReflect")},csstransforms:function(){return r("transform")},csstransitions:function(){return r("transition")},touch:function(){return"ontouchstart"in n},retina:function(){return n.devicePixelRatio>1},fontface:function(){var t=u.browser.name,n=u.browser.version;switch(t){case"ie":return n>=9;case"chrome":return n>=13;case"ff":return n>=6;case"ios":return n>=5;case"android":return!1;case"webkit":return n>=5.1;case"opera":return n>=10;default:return!1}}};for(var e in f)f[e]&&u.feature(e,f[e].call(),!0);u.feature()})(window);
/*! head.load - v1.0.3 */
(function(n,t){"use strict";function w(){}function u(n,t){if(n){typeof n=="object"&&(n=[].slice.call(n));for(var i=0,r=n.length;i<r;i++)t.call(n,n[i],i)}}function it(n,i){var r=Object.prototype.toString.call(i).slice(8,-1);return i!==t&&i!==null&&r===n}function s(n){return it("Function",n)}function a(n){return it("Array",n)}function et(n){var i=n.split("/"),t=i[i.length-1],r=t.indexOf("?");return r!==-1?t.substring(0,r):t}function f(n){(n=n||w,n._done)||(n(),n._done=1)}function ot(n,t,r,u){var f=typeof n=="object"?n:{test:n,success:!t?!1:a(t)?t:[t],failure:!r?!1:a(r)?r:[r],callback:u||w},e=!!f.test;return e&&!!f.success?(f.success.push(f.callback),i.load.apply(null,f.success)):e||!f.failure?u():(f.failure.push(f.callback),i.load.apply(null,f.failure)),i}function v(n){var t={},i,r;if(typeof n=="object")for(i in n)!n[i]||(t={name:i,url:n[i]});else t={name:et(n),url:n};return(r=c[t.name],r&&r.url===t.url)?r:(c[t.name]=t,t)}function y(n){n=n||c;for(var t in n)if(n.hasOwnProperty(t)&&n[t].state!==l)return!1;return!0}function st(n){n.state=ft;u(n.onpreload,function(n){n.call()})}function ht(n){n.state===t&&(n.state=nt,n.onpreload=[],rt({url:n.url,type:"cache"},function(){st(n)}))}function ct(){var n=arguments,t=n[n.length-1],r=[].slice.call(n,1),f=r[0];return(s(t)||(t=null),a(n[0]))?(n[0].push(t),i.load.apply(null,n[0]),i):(f?(u(r,function(n){s(n)||!n||ht(v(n))}),b(v(n[0]),s(f)?f:function(){i.load.apply(null,r)})):b(v(n[0])),i)}function lt(){var n=arguments,t=n[n.length-1],r={};return(s(t)||(t=null),a(n[0]))?(n[0].push(t),i.load.apply(null,n[0]),i):(u(n,function(n){n!==t&&(n=v(n),r[n.name]=n)}),u(n,function(n){n!==t&&(n=v(n),b(n,function(){y(r)&&f(t)}))}),i)}function b(n,t){if(t=t||w,n.state===l){t();return}if(n.state===tt){i.ready(n.name,t);return}if(n.state===nt){n.onpreload.push(function(){b(n,t)});return}n.state=tt;rt(n,function(){n.state=l;t();u(h[n.name],function(n){f(n)});o&&y()&&u(h.ALL,function(n){f(n)})})}function at(n){n=n||"";var t=n.split("?")[0].split(".");return t[t.length-1].toLowerCase()}function rt(t,i){function e(t){t=t||n.event;u.onload=u.onreadystatechange=u.onerror=null;i()}function o(f){f=f||n.event;(f.type==="load"||/loaded|complete/.test(u.readyState)&&(!r.documentMode||r.documentMode<9))&&(n.clearTimeout(t.errorTimeout),n.clearTimeout(t.cssTimeout),u.onload=u.onreadystatechange=u.onerror=null,i())}function s(){if(t.state!==l&&t.cssRetries<=20){for(var i=0,f=r.styleSheets.length;i<f;i++)if(r.styleSheets[i].href===u.href){o({type:"load"});return}t.cssRetries++;t.cssTimeout=n.setTimeout(s,250)}}var u,h,f;i=i||w;h=at(t.url);h==="css"?(u=r.createElement("link"),u.type="text/"+(t.type||"css"),u.rel="stylesheet",u.href=t.url,t.cssRetries=0,t.cssTimeout=n.setTimeout(s,500)):(u=r.createElement("script"),u.type="text/"+(t.type||"javascript"),u.src=t.url);u.onload=u.onreadystatechange=o;u.onerror=e;u.async=!1;u.defer=!1;t.errorTimeout=n.setTimeout(function(){e({type:"timeout"})},7e3);f=r.head||r.getElementsByTagName("head")[0];f.insertBefore(u,f.lastChild)}function vt(){for(var t,u=r.getElementsByTagName("script"),n=0,f=u.length;n<f;n++)if(t=u[n].getAttribute("data-headjs-load"),!!t){i.load(t);return}}function yt(n,t){var v,p,e;return n===r?(o?f(t):d.push(t),i):(s(n)&&(t=n,n="ALL"),a(n))?(v={},u(n,function(n){v[n]=c[n];i.ready(n,function(){y(v)&&f(t)})}),i):typeof n!="string"||!s(t)?i:(p=c[n],p&&p.state===l||n==="ALL"&&y()&&o)?(f(t),i):(e=h[n],e?e.push(t):e=h[n]=[t],i)}function e(){if(!r.body){n.clearTimeout(i.readyTimeout);i.readyTimeout=n.setTimeout(e,50);return}o||(o=!0,vt(),u(d,function(n){f(n)}))}function k(){r.addEventListener?(r.removeEventListener("DOMContentLoaded",k,!1),e()):r.readyState==="complete"&&(r.detachEvent("onreadystatechange",k),e())}var r=n.document,d=[],h={},c={},ut="async"in r.createElement("script")||"MozAppearance"in r.documentElement.style||n.opera,o,g=n.head_conf&&n.head_conf.head||"head",i=n[g]=n[g]||function(){i.ready.apply(null,arguments)},nt=1,ft=2,tt=3,l=4,p;if(r.readyState==="complete")e();else if(r.addEventListener)r.addEventListener("DOMContentLoaded",k,!1),n.addEventListener("load",e,!1);else{r.attachEvent("onreadystatechange",k);n.attachEvent("onload",e);p=!1;try{p=!n.frameElement&&r.documentElement}catch(wt){}p&&p.doScroll&&function pt(){if(!o){try{p.doScroll("left")}catch(t){n.clearTimeout(i.readyTimeout);i.readyTimeout=n.setTimeout(pt,50);return}e()}}()}i.load=i.js=ut?lt:ct;i.test=ot;i.ready=yt;i.ready(r,function(){y()&&u(h.ALL,function(n){f(n)});i.feature&&i.feature("domloaded",!0)})})(window);

(function(){window.$clamp=function(c,d){function s(a,b){n.getComputedStyle||(n.getComputedStyle=function(a,b){this.el=a;this.getPropertyValue=function(b){var c=/(\-([a-z]){1})/g;"float"==b&&(b="styleFloat");c.test(b)&&(b=b.replace(c,function(a,b,c){return c.toUpperCase()}));return a.currentStyle&&a.currentStyle[b]?a.currentStyle[b]:null};return this});return n.getComputedStyle(a,null).getPropertyValue(b)}function t(a){a=a||c.clientHeight;var b=u(c);return Math.max(Math.floor(a/b),0)}function x(a){return u(c)*
a}function u(a){var b=s(a,"line-height");"normal"==b&&(b=1.2*parseInt(s(a,"font-size")));return parseInt(b)}function l(a){if(a.lastChild.children&&0<a.lastChild.children.length)return l(Array.prototype.slice.call(a.children).pop());if(a.lastChild&&a.lastChild.nodeValue&&""!=a.lastChild.nodeValue&&a.lastChild.nodeValue!=b.truncationChar)return a.lastChild;a.lastChild.parentNode.removeChild(a.lastChild);return l(c)}function p(a,d){if(d){var e=a.nodeValue.replace(b.truncationChar,"");f||(h=0<k.length?
k.shift():"",f=e.split(h));1<f.length?(q=f.pop(),r(a,f.join(h))):f=null;m&&(a.nodeValue=a.nodeValue.replace(b.truncationChar,""),c.innerHTML=a.nodeValue+" "+m.innerHTML+b.truncationChar);if(f){if(c.clientHeight<=d)if(0<=k.length&&""!=h)r(a,f.join(h)+h+q),f=null;else return c.innerHTML}else""==h&&(r(a,""),a=l(c),k=b.splitOnChars.slice(0),h=k[0],q=f=null);if(b.animate)setTimeout(function(){p(a,d)},!0===b.animate?10:b.animate);else return p(a,d)}}function r(a,c){a.nodeValue=c+b.truncationChar}d=d||{};
var n=window,b={clamp:d.clamp||2,useNativeClamp:"undefined"!=typeof d.useNativeClamp?d.useNativeClamp:!0,splitOnChars:d.splitOnChars||[".","-","\u2013","\u2014"," "],animate:d.animate||!1,truncationChar:d.truncationChar||"\u2026",truncationHTML:d.truncationHTML},e=c.style,y=c.innerHTML,z="undefined"!=typeof c.style.webkitLineClamp,g=b.clamp,v=g.indexOf&&(-1<g.indexOf("px")||-1<g.indexOf("em")),m;b.truncationHTML&&(m=document.createElement("span"),m.innerHTML=b.truncationHTML);var k=b.splitOnChars.slice(0),
h=k[0],f,q;"auto"==g?g=t():v&&(g=t(parseInt(g)));var w;z&&b.useNativeClamp?(e.overflow="hidden",e.textOverflow="ellipsis",e.webkitBoxOrient="vertical",e.display="-webkit-box",e.webkitLineClamp=g,v&&(e.height=b.clamp+"px")):(e=x(g),e<=c.clientHeight&&(w=p(l(c),e)));return{original:y,clamped:w}}})();

Date.prototype.pattern=function(fmt){var o={"M+":this.getMonth()+1,"d+":this.getDate(),"h+":this.getHours()%12==0?12:this.getHours()%12,"H+":this.getHours(),"m+":this.getMinutes(),"s+":this.getSeconds(),"q+":Math.floor((this.getMonth()+3)/3),"S":this.getMilliseconds()};var week={"0":"/u65e5","1":"/u4e00","2":"/u4e8c","3":"/u4e09","4":"/u56db","5":"/u4e94","6":"/u516d"};if(/(y+)/.test(fmt)){fmt=fmt.replace(RegExp.$1,(this.getFullYear()+"").substr(4-RegExp.$1.length))}if(/(E+)/.test(fmt)){fmt=fmt.replace(RegExp.$1,((RegExp.$1.length>1)?(RegExp.$1.length>2?"/u661f/u671f":"/u5468"):"")+week[this.getDay()+""])}for(var k in o){if(new RegExp("("+k+")").test(fmt)){fmt=fmt.replace(RegExp.$1,(RegExp.$1.length==1)?(o[k]):(("00"+o[k]).substr((""+o[k]).length)))}}return fmt};

function preloadimages(arr){
    var newimages=[], loadedimages=0
    var postaction=function(){}
    var arr=(typeof arr!="object")? [arr] : arr
    function imageloadpost(){
        loadedimages++
        if (loadedimages==arr.length){
            postaction(newimages) //call postaction and pass in newimages array as parameter
        }
    }
    for (var i=0; i<arr.length; i++){
        newimages[i]=new Image()
        newimages[i].src=arr[i]
        newimages[i].onload=function(){
            imageloadpost()
        }
        newimages[i].onerror=function(){
            imageloadpost()
        }
    }
    return { //return blank object with done() method
        done:function(f){
            postaction=f || postaction //remember user defined callback functions to be called when images load
        }
    }
}

var doImgCovers;

function tabChange(className, index) {
    var tab = $('.tabText_tab_'+className);
    tab.removeClass('cur');
    $(tab.get(index)).addClass('cur');
    var text = $('.tabText_text_'+className);
    text.hide();
    $(text.get(index)).show();
    doImgCovers(true);
}
function computeImg(obj){
    var obj = $(obj),
        _img = obj.find("img"),
        _src = _img.attr("src"),
        parentW = obj.width(),
        parentH = obj.height();
    preloadimages(_src).done(function(images){
        //call back codes, for example:
        $.each(images,function(i,v){
            var mediaW= v.width,
            mediaH = v.height,
            sw = (parentW/mediaW),
            sh = (parentH/mediaH),
            s = Math.max (sw,sh);
            var _left = (((parentW - (mediaW * s))/2 ) / parentW) * 100;
            var _top = (((parentH - (mediaH * s))/2) / parentW) * 100;

            var cssJson = {
                marginLeft:_left+'%',
                marginTop:_top+'%',
                width:((mediaW/parentW)*s)*100 +"%",
                height:((mediaH/parentH)*s)*100 +'%'
            }
            _img.css(cssJson)
        })
    });
}
function upScrollLoading(obj){
	var obj = obj || ".scrollLoading"
	$(obj).scrollLoading(
            {
                callback:function() {
                    $(this).addClass("loadingEnd")
                    var cover = $(this).closest(".do-img-cover");
                    if(cover.length){
                        computeImg(cover);
                    }
                    // computeImg(this);
                }
            }
        )
}
$(function(){
        // 手机导航
        var doNavM = $(".do-nav-m"),
            doHeader = $(".do-header"),
            body = $("body");
        $(".do-nav-menu,.do-nav-btn,.do-subNav-btn").on("click",function(){
            if (!body.hasClass("navOpen")) {
                body.addClass("navOpen")
            }else{
                body.removeClass("navOpen")
            };
        })
        $(".do-close").on("click",function(){
            doNavM.removeClass("open")
        })

        var waypointObj = $(".do-banner").length ? $(".do-banner") : false || $(".do-body").length ? $(".do-body") : $("#fullpage").find(".do-area").first();
        if(waypointObj.length){
            var waypoint = new Waypoint({
                element: waypointObj,
                handler: function(direction) {
                    if(direction=="down"){
                        doHeader.addClass("open");
                        doNavM.addClass("open");
                    } else {
                        doHeader.removeClass("open");
                        doNavM.removeClass("open");
                    }
                },
                offset: '-100px'
            })
        }

        // 字符截取
        $(".js-toclamp").each(function(){
            var num = parseInt($(this).data("clampnum")) || 2;
            if(num == 1){
                $(this).addClass("do-ellipsis");
            }else{
                $clamp($(this)[0], {clamp: num, useNativeClamp: false, animate: true});
            }
        })

        // 横屏提醒
        body.append('<div class="do-tip-horizontal">\
                                <div class="do-middle">\
                                    <div class="do-middle-center">\
                                        <h4>建议使用竖屏浏览，以获得最佳体验</h4>\
                                        <p>如需横屏浏览，请点击 <span class="tipbtn">继续访问</span></p>\
                                    </div>\
                                </div>\
                            </div>');
        var doTipHorizontal = $(".do-tip-horizontal");
        doTipHorizontal.on("click",".tipbtn",function(){
            doTipHorizontal.hide();
        });
        
        if((window.orientation == 90 || window.orientation == -90) && screen.width < 1024){
            doTipHorizontal.show();
        }

        if(window.addEventListener){
            window.addEventListener('orientationchange', function(event){
                if ( window.orientation == 180 || window.orientation==0 ) {
                    doTipHorizontal.hide();
                }
                if((window.orientation == 90 || window.orientation == -90) && screen.width < 1024) {
                    doTipHorizontal.show();
                }
            });
        }

        doImgCovers = function doImgCovers(isup){
            var doImgCover = $(".do-img-cover");
            if(doImgCover.length){
                doImgCover.each(function(){
                    if($(this).find(".scrollLoading").length && !isup) return;
                    computeImg(this);
                })
            }
        }
        doImgCovers();
        upScrollLoading();

        // 返回顶部
        var winHeight = $(window).height();
        var win = $(window);
        var doGotop  = $(".do-gotop");
        win.scroll(function() {
            var $top = $(this).scrollTop();
            if($top > winHeight/2) {
                doGotop.show();
            } else {
                doGotop.hide();
            }
        });
        doGotop.on("click",function(){
            win.scrollTop(0);
        })
    // 视频播放
    head.ready(document, function() {
    	var doVideo = $(".do-element-video-content");
    	if(doVideo.length){
    		doVideo.on("click",".do-playbtn",function(){
                var videoUrl = $(this).data("video"),
                    vodtpl = '';
                if(videoUrl.indexOf(".mp4")!==-1){
                    vodtpl = '<video style="width:100%;height:100%" controls="" autoplay="" name="media"><source src="'+videoUrl+'" type="video/mp4"></video>';
                }else{
                    vodtpl = '<iframe height="100%" width="100%" src="'+videoUrl+'" frameborder="0" allowfullscreen=""></iframe>';
                }
                layer.alert(vodtpl,{
                    title:false,
                    btn:false,
                    skin:'do-video-alert'
                });
            })
    	}
        // 表单地区选择
        var formArea = $(".form-area");
        if(formArea.length){
            head.load([StaticUrl+"editor/js/jquery.cityselect.js"], function() {
                formArea.each(function(){
                    var _this = $(this),
                        _area = _this.find(".form-area-val").val().split(",");

                    $(this).citySelect({
                        prov: _area[0]||null,
                        city: _area[1]||null,
                        dist: _area[2]||null,
                        nodata: "none",
                        url:StaticUrl+"editor/js/city.js"
                    });
                });
            });
        }
        // 二维码组件
        var doQrcode=$(".do-element-qrcode");
        if(doQrcode.length){
            head.load([StaticUrl+"editor/js/qrcode.js"], function() {
                var qrcodeArr = [];
                doQrcode.each(function(i){
                    var _text = $(this).data('text'),
                        _conf = $(this).data('conf')
                    var showQrcodeClass = $(this).find('.showQrcode');
                    showQrcodeClass.html('');
                    qrcodeArr[i] = new QRCode(showQrcodeClass.get(0), {
                        text: _text,
                        width: _conf.width,
                        height: _conf.width,
                        colorDark: _conf.colorDark,
                        colorLight: _conf.colorLight,
                        correctLevel: QRCode.CorrectLevel.H
                    });
                });
            });
        }
	});
    // 浏览器发生变化
    var isWidth=false;
    var resizeConf = {
        isWidth:false,
        doOnlineService:$("#do-online-service").length ? $("#do-online-service") : false
    }
    // 小图变大
    var listImgPreview = $(".do-listImgPreview");
    if(listImgPreview.length){

        listImgPreview.each(function() {
            var _this = $(this),
                imgObjArr = _this.find("li").length ? _this.find("li") : _this.find(".do-element-image-content");

            _this.on("click", ".scrollLoading", function(e) {
                e.stopPropagation();
                e.preventDefault();
                var imgs = '', imgIndex = 0;
                if(imgObjArr.selector == "li"){
                    var imgObjParent = $(this).closest("li");
                    imgIndex = imgObjParent.index();
                    $.map(imgObjArr, function(item, index) {
                        var _src = $(item).find("img").data("src").split("?")[0],
                            _src = _src+"?imageView2/2/w/1500";

                        var _title = $(item).find(".title p").text();
                        var _des = $(item).find(".des p").text();
                        if(_title=='输入内容'){
                            _title = '';
                        }
                        if(_des=='输入内容'){
                            _title = '';
                        }
                        imgs += '<div class="swiper-slide"><div class="do-middle"><div class="do-middle-center"><img src="' + _src + '"><div class="title"><h5>'+_title+'</h5><p>'+_des+'</p></div></div></div></div>'
                    });
                }else{
                    imgIndex = 0;
                    var _src = imgObjArr.find("img").data("src");
                    var _title = imgObjArr.closest('.do-element-image').find(".title p").text();
                    var _des = imgObjArr.closest('.do-element-image').find(".des p").text();
                    if(_title=='输入内容'){
                        _title = '';
                    }
                    if(_des=='输入内容'){
                        _title = '';
                    }
                    imgs = '<div class="swiper-slide"><div class="do-middle"><div class="do-middle-center"><img src="' + _src + '"><div class="title"><h5>'+_title+'</h5><p>'+_des+'</p></div></div></div></div>';
                }

                var tpl = '<div class="do-swiperImgPreview"><div class="swiper-container"><div class="swiper-wrapper">\
                ' + imgs + '</div><div class="swiper-pagination"></div><div class="swiper-button-next swiper-button-white"></div>\
                <div class="swiper-button-prev swiper-button-white"></div></div><div class="do-swiper-button-close"><i class="icon-close"></i></div></div>';

                $(".do-swiperImgPreview").remove();
                $('body').append(tpl);

                var mySwiper = new Swiper('.do-swiperImgPreview .swiper-container', {
                    initialSlide: imgIndex,
                    slidesPerView: 1,
                    spaceBetween: 0,
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    grabCursor: true
                });
                $('.do-swiper-button-close').click(function() {
                    $(".do-swiperImgPreview").remove();
                });
            })
        });
    }

    $(window).resize(function(){
        if(this.innerWidth<640){
            if(!resizeConf.isWidth){
                if(resizeConf.doOnlineService) resizeConf.doOnlineService.prop("checked",false);
            }
            resizeConf.isWidth=true;
        }else{
            if(resizeConf.isWidth){

            }
            resizeConf.isWidth=false;
        }
    });

    if((head.browser.ie && head.browser.version <= 9)){
        var doOnlineService = $(".do-online-service");
        doOnlineService.on("click",".do-open",function(){
            doOnlineService.addClass("open")
        })
        doOnlineService.on("click",".do-close",function(){
            doOnlineService.removeClass("open");
        })
    }


    // sns
    $(".do-alertOpen").click(function(){
        var json = (new Function("return " + $(this).data("json")))();
        layer.alert(json.content,json.conf);
    })

})