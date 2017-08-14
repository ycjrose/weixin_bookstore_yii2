$('input[name="old_pwd"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('密码不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="new_pwd"]').blur(function(){
	if($(this).val().length < 6){
		dialog.tips('新密码不能少于6位',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});