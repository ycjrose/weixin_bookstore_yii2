var province_infos = {};

//1级焦点脱离
$("#province_id").change(function(){
    var id = $(this).val();
    if(id <= 0){
        return;
    }
    for(var key in province_infos){
        if(key == id){
            province_cascade();
            return;
        }
    }
    $.ajax({
        url :common_ops.buildWwwUrl("/default/cascade"),
        data:{'id':id},
        dataType:'json',
        async: false,
        success:function(res){
            if(res.code == 200){
                province_infos[id] = res.data;
                province_cascade();
            }else{
                alert(res.msg);
            }
        }
    })
});
//2级焦点脱离
$("#city_id").change(function(){
    city_cascade();
});
//2级展现
function province_cascade(){
    var id = $("#province_id").val();
    var province_info = this.province_infos[id];
    var city_info = province_info.city;
    if(id<=0){
        return;
    }
    $("#city_id").html("");
    $("#city_id").append("<option value='0'>请选择市</option>");
    for(var idx in city_info){
        if( parseInt($("#city_id_before").val()) == city_info[idx]['id']){
            $("#city_id").append("<option value='"+city_info[idx]['id']+"' selected='select'>"+
                city_info[idx]['name']+"</option>");
            continue;
        }
        $("#city_id").append("<option value='"+city_info[idx]['id']+"'>"+city_info[idx]['name']+"</option>");
    }
}
//3级展现
function city_cascade(){
    var id = $("#province_id").val();
    var province_info = this.province_infos[id];
    var city_id =$("#city_id").val();
    var district_info = province_info.district[city_id];
    if(id<=0 || city_id<=0){
        return;
    }
    $("#area_id").html("");
    $("#area_id").append("<option value='0'>请选择区</option>");
    for(var idx in district_info){
        if( parseInt( $("#area_id_before").val() ) == district_info[idx]['id'] ){
            $("#area_id").append("<option value='"+district_info[idx]['id']+"' selected='select'>"+
                district_info[idx]['name']+"</option>");
            continue;
        }
        $("#area_id").append("<option value='"+district_info[idx]['id']+"'>"+district_info[idx]['name']+"</option>");
    }
}

//编辑页面的自动填充
function readyfull(){
    if($("#province_id").val() > 0){
        $("#province_id").change();
    }
    if($("#city_id").val() > 0){
        $("#city_id").change();
    }
}
readyfull();

//提交地址设置的数据
$(".op_box .save").click( function(){
    var btn_target = $(this);
    if( btn_target.hasClass("disabled") ){
        alert("正在处理!!请不要重复提交");
        return;
    }

    var nickname = $(".addr_form_box input[name=nickname]").val();
    var mobile = $(".addr_form_box input[name=mobile]").val();
    var province_id = $(".addr_form_box #province_id").val();
    var city_id = $(".addr_form_box #city_id").val();
    var area_id = $(".addr_form_box #area_id").val();
    var address = $(".addr_form_box textarea[name=address]").val();

    if( !nickname || nickname.length < 1 ){
        alert("请输入符合规范的收货人姓名~~");
        return;
    }

    if( !/^[1-9]\d{10}$/.test( mobile ) ){
        alert("请输入符合规范的收货人手机号码~~");
        return;
    }

    if( province_id < 1 ){
        alert("请选择省~~");
        return;
    }

    if( city_id < 1 ){
        alert("请选择市~~");
        return;
    }

    if( area_id < 1 ){
        alert("请选择区~~");
        return;
    }

    if( !address || address.length < 3 ){
        alert("请输入符合规范的收货人详细地址~~");
        return;
    }

    btn_target.addClass("disabled");
    var  data = {
        id:$(".hide_wrap input[name=id]").val(),
        nickname:nickname,
        mobile:mobile,
        province_id:province_id,
        city_id:city_id,
        area_id:area_id,
        address:address
    };

    $.ajax({
        url :common_ops.buildMUrl("/user/address_set"),
        type:'POST',
        data: data,
        dataType:'json',
        async: false,
        success:function(res){
            btn_target.removeClass("disabled");
            alert(res.msg);
            if( res.code == 200 ){
                window.location.href = common_ops.buildMUrl("/user/address");
            }

        }
    })

});