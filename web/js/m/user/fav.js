$(".fav_list li .del_fav").click(function () {
    $(this).parent().remove();
    $.ajax({
        url:common_ops.buildMUrl("/product/fav"),
        type:'POST',
        data:{
            id:$(this).attr("data"),
            act:'del'
        },
        dataType:'json',
        success:function( res ){
        }
    });
});