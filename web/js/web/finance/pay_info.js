//发货弹出框
$(".express_send").click(function(){
    $('#express_wrap').modal('show');
});
//发货的ajax请求
$('#express_wrap .save').click( function(){
    var btn_target = $(this);
    if( btn_target.hasClass("disabled") ){
        common_ops.alert("正在处理!!请不要重复提交~~");
        return;
    }

    var express_info_target = $('#express_wrap input[name=express_info]');
    var express_info = express_info_target.val();

    if( express_info.length < 1 ){
        common_ops.tip( "请输入符合要求的快递信息~~" ,express_info_target );
        return;
    }

    btn_target.addClass("disabled");

    $.ajax({
        url:common_ops.buildWebUrl("/finance/express") ,
        type:'POST',
        data:{
            id:$('#express_wrap input[name=pay_order_id]').val(),
            express_info:express_info
        },
        dataType:'json',
        success:function(res){
            btn_target.removeClass("disabled");
            var callback = null;
            if( res.code == 200 ){
                callback = function(){
                    window.location.href = window.location.href+"?id="+10000*Math.random();
                }
            }
            common_ops.alert( res.msg,callback );
        }
    });
});