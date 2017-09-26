<?php 
use \app\common\services\UrlService;
use \app\common\services\UtilService;
use app\assets\MAsset; 
MAsset::register($this);
$upload_config = \Yii::$app->params['upload'];
$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <title>微信图书商城</title>
    <?php $this->head();?>
</head>
<body>
<?php $this->beginBody();?>
<!--不同的部分begin-->
 
<?=$content;?>

<!--不同的部分end-->
<div class="copyright clearfix">
        <?php if( isset( $this->params['current_user'] ) ):?>
            <p class="name">欢迎您，<?=UtilService::encode( $this->params['current_user']["nickname"] );?></p>
        <?php endif;?>
        <p class="copyright">由<a href="/" target="_blank">ycj</a>提供技术支持</p>
</div>
<div class="footer_fixed clearfix">
    <span><a href="<?=UrlService::buildMUrl("/default");?>" class="default"><i class="home_icon"></i><b>首页</b></a></span>
    <span><a href="<?=UrlService::buildMUrl("/product");?>" class="product"><i class="store_icon"></i><b>图书</b></a></span>
    <span><a href="<?=UrlService::buildMUrl("/user");?>" class="user"><i class="member_icon"></i><b>我的</b></a></span>
</div>

<div class="hidden_layout_wrap hide">
    <input type="hidden" name="upload_config" value='<?=json_encode($upload_config);?>' > 
</div>
<div class="hidden_layout_wrap hide">
    <input type="hidden" id="share_info" value='<?=\Yii::$app->getView()->params['share_info'];?>' > 
</div>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>