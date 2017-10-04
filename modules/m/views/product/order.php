<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/m/product/order.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<div class="page_title clearfix">
    <span>订单提交</span>
</div>
<div class="order_box">
    <div class="order_header">
        <h2>确认收货地址</h2>
    </div>

    <ul class="address_list">
		<?php foreach($address_info as $_item):?>                     
        <li style="padding: 5px 5px;">
            <label>
                <input style="display: inline;" type="radio" name="address_id" value="<?=$_item['id'];?>"  <?php if($_item['is_default']):?>checked<?php endif;?> >
                    <?=UtilService::encode($_item['really_address']);?>                
            </label>
        </li>
        <?php endforeach;?>
    </ul>


	<div class="order_header">
		<h2>确认订单信息</h2>
	</div>
		<ul class="order_list">
			<?php foreach($books as $book_info):?>
			<li data="<?=$book_info['id']?>" data-quantity="<?=$book_info['quantity']?>">
			<a href="<?=UrlService::buildMUrl('/product/info',['id' => $book_info['id']]);?>">
				<i class="pic">
					<img src="<?=UrlService::buildPicUrl('book',$book_info['main_image']);?>" style="width: 100px;height: 100px;"/>
				</i>
				<h2><?=UtilService::encode($book_info['name']);?> x <?=$book_info['quantity'];?></h2>
				<h4>&nbsp;</h4>
				<b>¥ <?=$book_info['price']?></b>
			</a>
			</li>
			<?php endforeach;?>
		</ul>
		<div class="order_header" style="border-top: 1px dashed #ccc;">
		<h2>总计：<?=$total_price;?></h2>
	</div>
</div>
<div class="op_box">
    <input type="hidden" name="sc" value="<?=$sc;?>">
	<input style="width: 100%;" type="button" value="确定下单" class="red_btn do_order"  />
</div>
</div>