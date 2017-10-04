cal_price();
//数量递减
$(".quantity-form .icon_lower").click(function () {
    var num = parseInt($(this).next(".input_quantity").val());
    if (num > 1) {
        $(this).next(".input_quantity").val(num - 1);
        setItem( $(this).attr("data-book_id"), $(this).next(".input_quantity").val() );
    }
    cal_price();
});
//数量递增
$(".quantity-form .icon_plus").click(function () {
    var num = parseInt($(this).prev(".input_quantity").val());
    var max = parseInt($(this).prev(".input_quantity").attr("max"));
    if (num < max) {
        $(this).prev(".input_quantity").val(num + 1);
        setItem( $(this).attr("data-book_id"), $(this).prev(".input_quantity").val() );
    }
    cal_price();

});
//删除
$(".delC_icon").click(function () {

    $.ajax({
        url:common_ops.buildMUrl("/product/cart"),
        type:'POST',
        data:{
            id:$(this).attr("data"),
            act:'del'
        },
        dataType:'json',
        success:function( res ){
            if( res.code != 200 ){
                alert( res.msg );
            }
        }
    });
    $(this).parent().parent().remove();
    cal_price();
});
//实时改变购物车数据表的函数
function setItem(book_id,quantity){
	$.ajax({
	    url:common_ops.buildMUrl("/product/cart"),
	    type:'POST',
	    data:{
	        book_id:book_id,
	        quantity:quantity,
	        act:'set'
	    },
	    dataType:'json',
	    success:function( res ){
	        if( res.code != 200 ){
	            alert( res.msg );
	        }

	    }
	});
}
//实时计算购物车总价的函数
function cal_price(){
	var pay_price = 0;
	$(".order_pro_list li").each( function(){
	    var tmp_price = parseFloat( $(this).attr("data-price") );
	    var tmp_quantity = $(this).find(".input_quantity").val();
	    pay_price += tmp_price * parseInt( tmp_quantity );
	});
	$(".cart_fixed #price").html( pay_price.toFixed(2) );
}