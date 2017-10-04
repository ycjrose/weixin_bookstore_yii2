$(function () {
    $('.star').raty({
        readOnly: true,
        score:function(  ){
            return $(this).attr("data-score")/2;
        },
        width:200
    });
});