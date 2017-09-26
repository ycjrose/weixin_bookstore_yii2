var p = 1;
var sort = '';
var sort_field = 'default';
//排序和搜索函数
function search(sort_field2 = 'default',sort2 = ''){
	var params = {
            'kw':$(".search_header input[name=kw]").val(),
            'sort_field':sort_field2,
            'sort':sort2,
        };
    window.location.href = common_ops.buildMUrl("/product/index",params);
}
//关键字排序
$(".search_header .search_icon").click( function(){
    search();
});
//其他排序
$(".sort_box .sort_list li a").click( function(){
    var sort_field = $(this).attr("data");
    if( $(this).find("i").hasClass("high_icon")  ){
        sort = "asc";
    }else{
        sort = "desc";
    }
    search(sort_field,sort);
});
//实现惰性加载
process = true;

$( window ).scroll( function() {
    if( ( ( $(window).height() + $(window).scrollTop() ) > $(document).height() - 20 ) && process ){
        process = false;
        p += 1;
        $(".sort_box .sort_list li a").each(function(e){
            if($(this).hasClass('aon')){
                sort_field = $(this).attr('data');
                if($(this).find('i').hasClass('high_icon')){
                    sort = 'desc';
                }
                if($(this).find('i').hasClass('lowly_icon')){
                    sort = 'asc';
                }  
            }
        });
        var data = {
            'kw':$(".search_header input[name=kw]").val(),
            'sort_field':sort_field,
            'sort':sort,
            'p':p
        };
        //console.log(data);
        var url = common_ops.buildMUrl( "/product/search" );
        $.get(url,data,function(result){
            process = true;
            if( result.code != 200 ){
                return;
            }
            var html = "";
            for( idx in result.data.books ){
                var info = result.data.books[ idx ];
                html += '<li> <a href="' + common_ops.buildMUrl( "/product/info",{ id:info['id'] } ) + '"> <i><img src="'+ common_ops.buildPicUrl('book',info['main_image']) +'"  style="width: 100%;height: 200px;"/></i> <span>'+ info['name'] +'</span> <b><label>月销量' + info['month_count'] +'</label>¥' + info['price'] +'</b> </a> </li>';
            }

            $(".probox ul.prolist").append( html );
            if( !result.data.has_next ){
                process = false;
            }
        },'JSON');
        
    }
});
