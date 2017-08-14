$('input[name="nickname"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('名字不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="login_name"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('用户名不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="login_pwd"]').blur(function(){
	if($.trim($(this).val()).length < 6){
		dialog.tips('密码不能小于6位',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="email"]').blur(function(){
	var email = $(this).val();
	var reg = /\w+[@]{1}\w+[.]\w+/;
	if(reg.test(email)){
		dialog.tips('邮箱格式合法',this);
		$('#button-submit').attr("disabled", false);
	}else{
		dialog.tips('邮箱格式错误',this);
		$('#button-submit').attr("disabled", true);
	}
});