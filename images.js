
$(".set_pic").click(function(){
    $('#brand_image_wrap').modal('show');
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
		'"><input type="hidden" name="image_key" value="'+image_key+
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