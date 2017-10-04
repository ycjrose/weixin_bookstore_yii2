$('.order_header').click(function(){
	$(this).next().toggle(100);
});
$('.close').click(function(){
	if(!confirm('是否取消订单')){
		return;
	} 
	var url = common_ops.buildMUrl('/order/ops');
	var data = {action:'remove',id:$(this).attr('data')};
	$.post(url,data,function(result){
		if(result.code == -1){
			alert(result.msg);
		}
		if(result.code == 200){
			
			window.location.href = window.location.href+"?id="+10000*Math.random();
		}
	},'JSON');
});
$('.confirm_express').click(function(){
	if(!confirm('是否确认收货')){
		return;
	}
	var url = common_ops.buildMUrl('/order/ops');
	var data = {action:'confirm_express',id:$(this).attr('data')};
	$.post(url,data,function(result){
		if(result.code == -1){
			alert(result.msg);
		}
		if(result.code == 200){
			alert(result.msg);
			window.location.href = common_ops.buildMUrl('/user/order');
		}
	},'JSON');
});