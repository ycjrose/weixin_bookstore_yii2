<?php
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\StaticService;
StaticService::includeAppJs('/js/m/default/index.js',app\assets\MAsset::className());
?>
<div style="min-height: 500px;">
    <div class="shop_header">
    <i class="shop_icon"></i>
    <strong><?=UtilService::encode($brand_info['name']);?></strong>
</div>


<div id="slideBox" class="slideBox">
    <div class="bd">
        <ul>
            <?php foreach($images as $_item):?>
            <li>
                <img style="height: 200px;" src="<?=UrlService::buildPicUrl('brand',$_item['image_key']);?>" />
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="hd"><ul></ul></div>
</div>
<div class="fastway_list_box">
    <ul class="fastway_list">
        <li><a href="<?=UrlService::buildNullUrl();?>" style="padding-left: 0.1rem;"><span>品牌名称：<?=UtilService::encode($brand_info['name']);?></span></a></li>
        <li><a href="<?=UrlService::buildNullUrl();?>" style="padding-left: 0.1rem;"><span>联系电话：<?=UtilService::encode($brand_info['mobile']);?></span></a></li>
        <li><a href="<?=UrlService::buildNullUrl();?>" style="padding-left: 0.1rem;"><span>联系地址：<?=UtilService::encode($brand_info['address']);?></span></a></li>
        <li><a href="<?=UrlService::buildNullUrl();?>" style="padding-left: 0.1rem;"><span>品牌介绍：<?=UtilService::encode($brand_info['description']);?></span></a></li>
    </ul>
</div></div>

