;

var common_ops = {
    init:function(){
        this.eventBind();
        this.setIconLight();
    },
    eventBind:function(){

    },
    setIconLight:function(){
        var pathname = window.location.pathname;
        var nav_name = null;

        if(  pathname.indexOf("/m/default") > -1 || pathname == "/m" || pathname == "/m/" ){
            nav_name = "default";
        }

        if(  pathname.indexOf("/m/product") > -1  ){
            nav_name = "product";
        }

        if(  pathname.indexOf("/m/user") > -1  ){
            nav_name = "user";
        }

        if( nav_name == null ){
            return;
        }

        $(".footer_fixed ."+nav_name).addClass("aon");
    },
    buildMUrl:function( path ,params){
        var url =   "/m" + path;
        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }
        return url + _paramUrl

    },
    buildWwwUrl:function( path ,params){
        var url =    path;
        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }
        return url + _paramUrl

    },
    buildPicUrl:function( bucket,img_key ){
        var upload_config = eval( '(' + $(".hidden_layout_warp input[name=upload_config]").val() +')' );
        var domain = "http://" + window.location.hostname;
        return domain + upload_config[ bucket ] + "/" + img_key;
    },
    notlogin:function( referer ){
        if ( ! referer) {
            alert('授权过期,系统将引导您重新授权');
            referer = location.pathname + location.search;
        }
        window.location.href = this.buildMUrl("/user/bind",{ referer:referer });
    }
};

$(document).ready( function() {
    common_ops.init();
});


// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function(fmt)
{ //author: meizz
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
};