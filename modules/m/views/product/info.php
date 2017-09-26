<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/m/product/info.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<div class="pro_tab clearfix">
    <span>图书详情</span>
</div>
<div class="proban">
    <div id="slideBox" class="slideBox">
        <div class="bd">
            <ul>
                <li><img src="<?=UrlService::buildPicUrl('book',$book_info['main_image']);?>" width="100%"/></li>
            </ul>
        </div>
    </div>
</div>
<div class="pro_header">
    <div class="pro_tips">
        <h2><?=UtilService::encode($book_info['name']);?></h2>
        <h3><b>¥<?=$book_info['price'];?></b><font>库存量：<?=$book_info['stock'];?></font></h3>
    </div>
    <span class="share_span"><i class="share_icon"></i><b>分享商品</b></span>
</div>
<div class="pro_express">月销量：<?=$book_info['month_count'];?><b>累计评价：<?=$book_info['comment_count'];?></b></div>
<div class="pro_virtue">
    <div class="pro_vlist">
        <b>数量</b>
        <div class="quantity-form">
            <a class="icon_lower"></a>
            <input type="text" name="quantity" class="input_quantity" value="1" readonly="readonly" max="<?=$book_info['stock'];?>"/>
            <a class="icon_plus"></a>
        </div>
    </div>
</div>
<div class="pro_warp">
	<?=$book_info['summary'];?>
</div>
<div class="pro_fixed clearfix">
    <a href="/m/"><i class="sto_icon"></i><span>首页</span></a>
    <a class="fav <?php if($has_faved):?> has_faved <?php endif;?>" href="javascript:void(0);"  data="<?=$book_info['id'];?>"><i class="keep_icon"></i><span>收藏</span></a>
    <input type="button" value="立即订购" class="order_now_btn" data="<?=$book_info['id'];?>"/>
    <input type="button" value="加入购物车" class="add_cart_btn" data="<?=$book_info['id'];?>"/>
    <input type="hidden" name="id" value="4">
</div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'jump_url' : '<?=UrlService::buildMUrl('/product/info',['id' => $book_info['id']]);?>',
    }
</script>