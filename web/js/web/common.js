;
function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 200);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 100);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}

// Full height of sidebar
function fix_height() {
    var heightWithoutNavbar = $("body > #wrapper").height() - 61;
    $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

    var navbarHeigh = $('nav.navbar-default').height();
    var wrapperHeigh = $('#page-wrapper').height();

    if (navbarHeigh > wrapperHeigh) {
        $('#page-wrapper').css("min-height", navbarHeigh + "px");
    }

    if (navbarHeigh < wrapperHeigh) {
        $('#page-wrapper').css("min-height", $(window).height() + "px");
    }

    if ($('body').hasClass('fixed-nav')) {
        if (navbarHeigh > wrapperHeigh) {
            $('#page-wrapper').css("min-height", navbarHeigh - 60 + "px");
        } else {
            $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
        }
    }

}

var common_ops = {
    init:function(){
        this.eventBind();
        //this.setMenuIconHighLight();
    },
    eventBind:function(){
        $('.navbar-minimalize').click(function () {
            $("body").toggleClass("mini-navbar");
            SmoothlyMenu();
        });

        $(window).bind("load resize scroll", function () {
            if (!$("body").hasClass('body-small')) {
                fix_height();
            }
        });
    },
    setMenuIconHighLight:function(){
        if( $("#side-menu li").size() < 1 ){
            return;
        }
        var pathname = window.location.pathname;
        var nav_name = null;

        if(  pathname.indexOf("/web/dashboard") > -1 || pathname == "/web" || pathname == "/web/" ){
            nav_name = "dashboard";
        }

        if(  pathname.indexOf("/web/account") > -1  ){
            nav_name = "account";
        }

        if(  pathname.indexOf("/web/brand") > -1  ){
            nav_name = "brand";
        }

        if(  pathname.indexOf("/web/book") > -1  ){
            nav_name = "book";
        }

        if(  pathname.indexOf("/web/member") > -1  ){
            nav_name = "member";
        }

        if(  pathname.indexOf("/web/market") > -1  ){
            nav_name = "market";
        }

        if(  pathname.indexOf("/web/finance") > -1  ){
            nav_name = "finance";
        }

        if(  pathname.indexOf("/web/qrcode") > -1  ){
            nav_name = "market";
        }

        if(  pathname.indexOf("/web/stat") > -1  ){
            nav_name = "stat";
        }

        if( nav_name == null ){
            return;
        }

        $("#side-menu li."+nav_name).addClass("active");
    },
    buildWebUrl:function( path ,params){
        var url =   "/web" + path;
        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }
        return url + _paramUrl

    },
    /**
    * 获取图片路径函数
    */

    buildPicUrl:function( bucket,img_key ){
        var upload_config = eval( '(' + $(".hidden_layout_wrap input[name=upload_config]").val() +')' );
        return upload_config[ bucket ] + "/" + img_key;
    },
    alert:function( msg ,cb ){
        layer.alert( msg,{
            yes:function( index ){
                if( typeof cb == "function" ){
                    cb();
                }
                layer.close( index );
            }
        });
    },
    confirm:function( msg,callback ){
        callback = ( callback != undefined )?callback: { 'ok':null, 'cancel':null };
        layer.confirm( msg , {
            btn: ['确定','取消'] //按钮
        }, function( index ){
            //确定事件
            if( typeof callback.ok == "function" ){
                callback.ok();
            }
            layer.close( index );
        }, function( index ){
            //取消事件
            if( typeof callback.cancel == "function" ){
                callback.cancel();
            }
            layer.close( index );
        });
    },
    tip:function( msg,target ){
        layer.tips( msg, target, {
            tips: [ 3, '#e5004f']
        });
        $('html, body').animate({
            scrollTop: target.offset().top - 10
        }, 100);
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

/**
 * 提交表单操作
 */ 
$('#button-submit').click(function(){

    var postData = {};
    $('#weixin-form :input').each(function(){
       postData[$(this).attr('name')] = $(this).val();
    });

    //将获得的post传到服务器
    //console.log(postData);
    var url = SCOPE.save_url;
    var jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.code === -1){
            //失败
            dialog.error(result.msg);
        }
        if(result.code === 200){
            //成功
            dialog.success(result.msg,jump_url);
        }
        
    },'JSON');
});

/**
*删除或恢复操作
*/
$('.button-ops').click(function(){
    var action = $(this).attr('attr-action');
    var uid = $(this).attr('data');
    var message = $(this).attr('attr-message');
    var url = SCOPE.ops_url;
    var postData = {'action':action,'uid':uid};
    layer.open({
            content : message,
            icon:3,
            btn : ['是','否'],
            yes : function(){
                //异步传输
                todelete(url,postData);
            },
    });
});
/**
*异步传输函数
*/
function todelete(url,data){
    $.post(url,data,function(result){
        if(result.code === -1){
            //失败
            dialog.error(result.msg);
        }
        if(result.code === 200){
            //成功
            dialog.success(result.msg,'');
        }
        
    },'JSON');
}

