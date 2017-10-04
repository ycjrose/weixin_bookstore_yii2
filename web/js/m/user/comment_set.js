var all_score = 0;

$('#star').raty({
    half: true,
    click:function( score, evt ){
        all_score = score;
    }
});
$('.op_box .save').click( function(){
    var btn_target = $(this);
    if( btn_target.hasClass("disabled") ){
        alert("正在处理!!请不要重复提交");
        return;
    }

    var score = all_score;
    console.log(score);
    var content = $(".addr_form_box textarea[name=content]").val();

    if( score <= 0 ){
        alert("请打分~~");
        return;
    }


    btn_target.addClass("disabled");

    $.ajax({
        url :common_ops.buildMUrl("/user/comment_set"),
        type:'POST',
        data: {
            pay_order_id:$(".op_box input[name=pay_order_id]").val(),
            book_id:$(".op_box input[name=book_id]").val(),
            score:score,
            content:content
        },
        dataType:'json',
        async: false,
        success:function(res){
            btn_target.removeClass("disabled");
            alert(res.msg);
            if( res.code == 200 ){
                window.location.href = common_ops.buildMUrl("/user/comment") ;
            }

        }
    })

});