$(document).ready(function(){
	var url = '/weixin/jssdk/index?url='+ encodeURIComponent(location.href.split('#')[0]);
	var data = {};
	$.get(url,data,function(result){
		//console.log(result);
		if(result.code != 200){
			return ;
		}
		var data = result.data;
		wx.config({
		    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		    appId: data['appId'], // 必填，公众号的唯一标识
		    timestamp:data['timestamp'] , // 必填，生成签名的时间戳
		    nonceStr: data['nonceStr'], // 必填，生成签名的随机串
		    signature: data['signature'],// 必填，签名，见附录1
		    jsApiList: [
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'chooseWXPay',
		    ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});
		//配置成功执行的方法
		wx.ready(function(){
			var share_info = eval( '('+ $('#share_info').val() +')' );
			//分享到朋友圈
			wx.onMenuShareTimeline({
			    title: share_info.title, // 分享标题
			    link: location.href.split('#')[0], // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			    imgUrl: share_info.img_url, // 分享图标
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			        wx_share_history();
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			//分享到微信好友
			wx.onMenuShareAppMessage({
			    title: share_info.title, // 分享标题
			    desc: share_info.desc, // 分享描述
			    link: location.href.split('#')[0], // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			    imgUrl: share_info.img_url, // 分享图标
			    type: 'link', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			        wx_share_history();
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			//分享到qq好友
			wx.onMenuShareQQ({
			    title: share_info.title, // 分享标题
			    desc: share_info.desc, // 分享描述
			    link: location.href.split('#')[0], // 分享链接
			    imgUrl: share_info.img_url, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			       wx_share_history();
			    },
			    cancel: function () { 
			       // 用户取消分享后执行的回调函数
			    }
			});
			//分享到qq空间
			wx.onMenuShareQZone({
			    title: share_info.title, // 分享标题
			    desc: share_info.desc, // 分享描述
			    link: location.href.split('#')[0], // 分享链接
			    imgUrl: share_info.img_url, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			       wx_share_history();
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
		});
		//配置失败执行的方法
		wx.error(function(){
			
		});

	},'JSON');
});
//wx支付函数
function wx_pay(json_obj){
	wx.ready(function(){
		wx.chooseWXPay(json_obj);
	});
}
function wx_share_history(){
	var url = common_ops.buildMUrl('/default/share');
	var data = {
		'share_url':location.href.split('#')[0],
	}
	$.post(url,data,function(result){

	},'JSON');
}