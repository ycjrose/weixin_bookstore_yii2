$('input[name="mobile"]').blur(function(){
	var mobile = $(this).val();
	var reg = /^1[0-9]{10}$/;
	if(!reg.test(mobile)){
		dialog.tips('请填写正确的手机号',this);
		$('#captcha-submit').attr("disabled", true);
		$('#button-submit').attr("disabled", true);   
	}else{
		$('#captcha-submit').attr("disabled", false);
		$('#button-submit').attr("disabled", false);
	}
});
$('#captcha-submit').click(function(){
	var mobile = $('input[name="mobile"]').val();
	var url = SCOPE.captcha_url;
	var data = {'mobile':mobile};
	$.post(url,data,function(res){
		if(res.code == -1){
			dialog.tips(res.msg,'input[name="captcha_code"]');
		}else{
			dialog.tips(res.msg,'input[name="captcha_code"]');
		}
	},'JSON');
});
