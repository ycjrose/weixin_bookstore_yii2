<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/m/user/cart.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<div class="order_pro_box">
		<?php if( $carts ):?>
    	<ul class="order_pro_list">
    		<?php foreach($carts as $_item):?>
        	<li data-price="<?=$_item['book_price'];?>">
				<a href="<?=UrlService::buildMUrl('/m/product/info',['id' => $_item['book_id']]);?>" class="pic" >
	                <img src="<?=UrlService::buildPicUrl('book',$_item['main_image']);?>" style="height: 150px;width: 100px;"/>
	            </a>
				<h2><a href="<?=UrlService::buildMUrl('/m/product/info',['id' => $_item['book_id']]);?>"><?=UtilService::encode($_item['book_name']);?></a></h2>
				<div class="order_c_op">
					<b>¥<?=$_item['book_price'];?></b>
					<span class="delC_icon" data="<?=$_item['id'];?>" data-book_id="<?=$_item['book_id'];?>"></span>
					<div class="quantity-form">
						<a class="icon_lower" data-book_id="<?=$_item['book_id'];?>" ></a>
						<input type="text" name="quantity" class="input_quantity" value="<?=$_item['quantity'];?>" readonly="readonly" max="<?=$_item['book_stock'];?>" />
						<a class="icon_plus" data-book_id="<?=$_item['book_id'];?>"></a>
					</div>
				</div>
			</li>
			<?php endforeach;?>
        </ul>
    	<?php else:?>
    		<p>好可怜，购物车什么都没有~~</p>
    	<?php endif;?>
    </div>
<div class="cart_fixed">
	<a href="<?=UrlService::buildMUrl('/product/order',['sc' => 'cart'])?>" class="billing_btn">结算</a>
	<b>合计：<strong>¥</strong><font id="price">0.00</font></b>
</div>
</div>