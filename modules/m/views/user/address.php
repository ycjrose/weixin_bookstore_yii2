<?php
use app\common\services\UrlService;
use app\common\services\UtilService;  
use app\common\services\StaticService;
StaticService::includeAppJs('/js/m/user/address.js',app\assets\MAsset::className());
?>
<div style="min-height: 500px;">
	<div class="page_title clearfix">
    <span>常用收货地址</span>
</div>
<?php if( $list ):?>
<ul class="address_list">
	<?php foreach($list as $_item):?>
    <li>
		<p><span><?=UtilService::encode($_item['nickname']);?></span><?=UtilService::encode($_item['mobile']);?></p>
		<p><?=UtilService::encode($_item['really_address']);?></p>
		<div class="addr_op">
			<em class="del" data="<?=$_item['id'];?>"><i class="del_icon"></i>删除</em>
			<a href="<?=UrlService::buildMUrl('/user/address_set',['id' => $_item['id']]);?>"><i class="edit_icon"></i>编辑</a>
            <?php if( $_item['is_default'] ):?>
			<span class="default_set aon"><i class="check_icon"></i>默认地址</span>
            <?php else:?>
                <span class="set_default" data="<?=$_item['id'];?>">设为默认地址 </span>
            <?php endif;?>
        </div>
	</li>
    <?php endforeach;?>
</ul>
<?php else:?>
    <div class="no-data">
        连个收货地址都没有~~~~
    </div>
<?php endif;?>

<div class="op_box">
    <a href="<?=UrlService::buildMUrl('/user/address_set');?>" class="red_btn" style="color: #ffffff;">添加新地址</a>
</div>
</div>