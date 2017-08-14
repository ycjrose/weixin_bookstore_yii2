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