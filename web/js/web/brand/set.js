$('input[name="name"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('品牌名称不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="mobile"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('品牌电话不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="address"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('品牌地址不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('textarea[name="description"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('品牌介绍不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
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
		var html = '<img src="'+common_ops.buildPicUrl('brand',image_key)+
		'"><input type="hidden" name="logo" value="'+image_key+
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