<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/m/pay/buy.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<div class="order_box">
	<div class="order_header">
		<h2 style="text-align: center;">支付</h2>
	</div>
	<div class="fastway_list_box" style="padding-left: 20px;padding-bottom: 0px;">
		<ul class="fastway_list">
			<li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>支付金额：<?=$order_info['pay_price']?></span></a></li>
			<li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>支付备注：<?=$order_info['note']?></span></a></li>
		</ul>
	</div>
</div>
<div class="op_box">
	<input style="width: 100%;" type="button" value="微信支付" class="red_btn do_pay"  />
</div>

<div class="hide_wrap hidden">
	<input type="hidden" name="order_id" value="<?=$order_info['id'];?>">
</div>
</div>