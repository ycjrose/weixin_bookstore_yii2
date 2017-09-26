//加减效果 start
$(".quantity-form .icon_lower").click(function () {
    var num = parseInt($(this).next(".input_quantity").val());
    if (num > 1) {
        $(this).next(".input_quantity").val(num - 1);
    }
});
$(".quantity-form .icon_plus").click(function () {
    var num = parseInt($(this).prev(".input_quantity").val());
    var max = parseInt($(this).prev(".input_quantity").attr("max"));
    if (num < max) {
        $(this).prev(".input_quantity").val(num + 1);
    }
});
//加减效果end
//收藏功能
$(".fav").click( function(){

    if( $(this).hasClass("has_faved") ){
        return false;
    }
    var url = common_ops.buildMUrl("/product/fav");
    var jump_url = SCOPE.jump_url;
    var data = {
        'book_id':$(this).attr("data"),
        'act':'set',
    }
    $.post(url,data,function(result){
    	if( result.code == -302 ){
    	    common_ops.notlogin( );
    	    return;
    	}
    	dialog.success(result.msg,jump_url );
    },'JSON');
});
//购物车功能
$(".add_cart_btn").click( function() {
	var url = common_ops.buildMUrl("/product/cart");
	var data = {
	    'book_id':$(this).attr("data"),
	    'act':'set',
	    'quantity':$('.quantity-form input[name=quantity]').val(),
	}
	$.post(url,data,function(result){
		if( result.code == -302 ){
		    common_ops.notlogin( );
		    return;
		}
		dialog.tips('加入购物车成功，快去看一下吧~','.pro_warp');
	},'JSON');
           
});
//立即购买
$(".order_now_btn").click( function(){
    window.location.href = common_ops.buildMUrl("/product/order",{ 'id':$(this).attr("data"), quantity:$(".quantity-form input[name=quantity]").val() } )
});
//记录浏览次数
$(document).ready(function(){
    var url = common_ops.buildMUrl('/product/ops');
    var data = {
        'book_id':$('.add_cart_btn').attr('data'),
        'act':'view_count',
    }
    $.post(url,data,function(result){
        
    },'JSON');
});