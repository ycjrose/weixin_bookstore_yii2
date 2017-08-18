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
//上传图片操作
$('.upload_pic_wrap input[name=pic]').change(function(){
	$('.upload_pic_wrap').submit();
});
//图片上 的删除按钮功能
$('.del_image').unbind().click(function(){
	$(this).parent().remove();
});
var upload = {
	error:function(msg){
		dialog.error(msg);
	},
	success:function(image_key){
		var html = '<img src="'+common_ops.buildPicUrl('avatar',image_key)+
		'"><input type="hidden" name="avatar" value="'+image_key+
		'" /><span class="fa fa-times-circle del del_image" ><i></i></span>';
		if($('.pic-each').size() > 0){
			$('.pic-each').html(html);
		}else{
			$('.upload_pic_wrap').append('<span class="pic-each">' + html + '</span>');
		}
		$('.del_image').unbind().click(function(){
			$(this).parent().remove();
		});
	}
}