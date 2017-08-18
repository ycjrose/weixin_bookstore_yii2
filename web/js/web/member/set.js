$('input[name="nickname"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('会员名不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="mobile"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('手机不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});