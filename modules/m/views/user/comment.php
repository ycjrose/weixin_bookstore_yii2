<?php
use \app\common\services\UtilService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/plugins/raty/jquery.raty.min.js",\app\assets\MAsset::className() );
StaticService::includeAppJs( "/js/m/user/comment.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
<div class="page_title clearfix">
    <span>我的评论</span>
</div>
    <?php if( $list ):?>
    <ul class="address_list">
        <?php foreach( $list as $_item ):?>
		    <li> 
                <p>订单编号：<?=$_item['order_sn']?></p>
                <p>评分：<span class="star" style="width: 200px;" data-score="<?=$_item['score'];?>"></span></p>
                <p>书本：<?=$_item['book_name']?></p>
                <p>评价内容：<?=UtilService::encode( $_item['content'] );?></p>
            </li>
		<?php endforeach;?>
	</ul>
    <?php else:?>
        <p>您还没有过评论！</p>
    <?php endif;?>
</div>