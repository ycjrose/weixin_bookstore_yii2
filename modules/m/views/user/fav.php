<?php
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/m/user/fav.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<?php if( $favs ):?>
	<ul class="fav_list">
		<?php foreach($favs as $_item):?>
		<li>
			<a href="<?=UrlService::buildMUrl('/product/info',['id' => $_item['book_id']]);?>">
				<i class="pic"><img src="<?=UrlService::buildPicUrl('book',$_item['main_image']);?>" style="height: 150px;width: 100px;" /></i>
				<h2><?=UtilService::encode($_item['book_name']);?></h2>
				<b>¥ <?=$_item['book_price'];?></b>
			</a>
			<span class="del_fav" data="<?=$_item['id'];?>"><i class="del_fav_icon"></i></span>
		</li>
		<?php endforeach;?>
	</ul>
	<?php else:?>
		<p>您还没有收藏任何东西~~</p>
	<?php endif;?>	
</div>