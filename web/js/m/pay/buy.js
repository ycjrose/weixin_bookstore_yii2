$('.do_pay').click(function(){
	var btn_target = $(this);
	if( btn_target.hasClass("disabled") ){
	    alert("正在处理!!请不要重复提交");
	    return;
	}
	btn_target.addClass("disabled");
	var url = common_ops.buildMUrl('/pay/prepare2');
	var data = {'order_id':$('.hide_wrap input[name="order_id"]').val()};
	$.post(url,data,function(result){
		btn_target.removeClass("disabled");
		if(result.code === -1){
			alert(result.msg);
		}
		if(result.code === 200){
			//调用wx支付js接口
			// var data = result.data;
			// var json_obj = {
			// 	timestamp: data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
			// 	nonceStr: data.nonceStr, // 支付签名随机串，不长于 32 位
			// 	package: data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
			// 	signType: data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
			// 	paySign: data.paySign, // 支付签名
			// 	success: function (res) {
			// 	    // 支付成功后的回调函数
			// 	    alert('支付成功！');
			// 	    window.location.href = common_ops.buildMUrl('/user/order');
			// 	},
			// 	cancel: function (){
			// 		alert('取消了支付~');
			// 	},
			// 	fail: function (){
			// 		alert('支付失败~');
			// 	}
			// };
			// wx_pay(json_obj);
			alert(result.msg);
			window.location.href = common_ops.buildMUrl('/user/order');
		}
	},'JSON');
});