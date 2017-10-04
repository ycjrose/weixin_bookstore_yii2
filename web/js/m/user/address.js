$(".del").click(function () {
    $(this).parent().parent().remove();

    $.ajax({
        url:common_ops.buildMUrl("/user/address_ops"),
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

$(".set_default").click(function () {
    $.ajax({
        url:common_ops.buildMUrl("/user/address_ops"),
        type:'POST',
        data:{
            id:$(this).attr("data"),
            act:'set_default'
        },
        dataType:'json',
        success:function( res ){
            alert( res.msg );
            if( res.code == 200 ){
                window.location.href = window.location.href;
            }
        }
    });
});