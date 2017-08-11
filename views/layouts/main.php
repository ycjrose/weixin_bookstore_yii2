<?php
//引入前端资源文件
use app\assets\AppAsset;
use app\common\services\UrlService;
AppAsset::register($this);
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ycj微信图书商城</title>
    <?php $this->head();?>
</head>
<body>
<?php $this->beginBody();?>
<div class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse pull-left">
            <ul class="nav navbar-nav "> 
                <li><a href="<?=UrlService::buildWwwUrl('/');?>">首页</a></li>
                <li><a target="_blank" href="http://47.93.59.20/">博客</a></li>
                <li><a href="<?=UrlService::buildWebUrl('/user/login');?>">管理后台</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="jumbotron body-content">

<!--不同内容bengin-->
<?=$content;?>
<!--不同内容end-->

</div>

<?php $this->endBody();?>
</body></html>
<?php $this->endPage();?>