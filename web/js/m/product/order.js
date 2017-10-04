$('.do_order').click(function(){
	var address_id = $('input[name=address_id]:checked').val();
	var data = [];
	$('.order_list li').each(function(i){
		var tmp_book_id = $(this).attr("data");
		var tmp_quantity = $(this).attr("data-quantity");
		data.push( tmp_book_id + "#" + tmp_quantity );

	});
	if( data.length < 1 ){
	    alert("请选择了商品再提交");
	    return;
	}
	
	var url = common_ops.buildMUrl('/product/order');
	var postData = {
		'product_item':data,
		'address_id':address_id,
		'sc':$('.op_box input[name=sc]').val(),
	};

	$.post(url,postData,function(result){
		if(result.code === -1){
			alert(result.msg);
		}
		if(result.code === 200){
			alert(result.msg);
			window.location.href = result.data.url;
		}
		
	},'JSON');
});