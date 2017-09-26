$('input[name="name"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('图书名称不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="price"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('图书价格不能为空',this);
		$('#button-submit').attr("disabled", true);
	}else{
		$('#button-submit').attr("disabled", false);
	}
});
$('input[name="price"]').blur(function(){
	if($.trim($(this).val()) == ''){
		dialog.tips('图书价格不能为空',this);
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
		var html = '<img src="'+common_ops.buildPicUrl('book',image_key)+
		'"><input type="hidden" name="main_image" value="'+image_key+
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
//图片下拉搜索框
$('.wrap_book_set select[name=catid]').select2({
	language:'zh-CN',
	width:'100%',
});
//标签插件
$('.wrap_book_set input[name=tags]').tagsInput({
	width:'auto',
	height:40,
	onAddTag:function(tag){

	},
	onRemoveTag:function(tag){

	},
});
//UEditor
var ue = UE.getEditor('editor',{
	enableAutoSave:true,
	saveInterval:60000,
	elementPathEnabled:false,
	zIndex:4,
});
/**
 * 提交表单操作
 */ 
$('#button-submit2').click(function(){

    var postData = {};
    $('#weixin-form :input').each(function(){
       postData[$(this).attr('name')] = $(this).val();
    });
    postData['summary'] = $.trim(ue.getContent());
    //将获得的post传到服务器
    console.log(postData);
    var url = SCOPE.save_url;
    var jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.code === -1){
            //失败
            dialog.error(result.msg);
        }
        if(result.code === 200){
            //成功
            dialog.success(result.msg,jump_url);
        }
        
    },'JSON');
});