<?php 
use app\assets\WebAsset;
use app\common\services\UrlService;
WebAsset::register($this);
$upload_config = \Yii::$app->params['upload'];
$this->beginPage(); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>管理后台</title>
	<?php $this->head();?>
</head>

<body>
<?php $this->beginBody();?>
<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="profile-element text-center">
                        <img alt="image" class="img-circle" width="50" src="<?php echo UrlService::buildPicUrl('avatar',$this->params['user_info']['avatar']);?>" />
                        <p class="text-muted"><?php echo $this->params['user_info']['nickname'];?></p>
					</div>
					<div class="logo-element">
                        <img alt="image" class="img-circle" width="50" src="<?php echo UrlService::buildPicUrl('avatar',$this->params['user_info']['avatar']);?>" />
					</div>
				</li>
				<li class="dashboard">
					<a href="<?=UrlService::buildWebUrl('/dashboard/index');?>"><i class="fa fa-dashboard fa-lg"></i>
                        <span class="nav-label">仪表盘</span></a>
				</li>
				<li class="account">
					<a href="<?=UrlService::buildWebUrl('/account/index');?>"><i class="fa fa-user fa-lg"></i> <span class="nav-label">账号管理</span></a>
				</li>
                <li class="brand">
                    <a href="<?=UrlService::buildWebUrl('/brand/info');?>"><i class="fa fa-cog fa-lg"></i> <span class="nav-label">品牌设置</span></a>
                </li>
                <li class="book">
                    <a href="<?=UrlService::buildWebUrl('/book/index');?>"><i class="fa fa-book fa-lg"></i> <span class="nav-label">图书管理</span></a>
                </li>
                <li class="member">
                    <a href="<?=UrlService::buildWebUrl('/member/index');?>"><i class="fa fa-group fa-lg"></i> <span class="nav-label">会员列表</span></a>
                </li>
                <li class="finance">
                    <a href="<?=UrlService::buildWebUrl('/finance/index');?>"><i class="fa fa-rmb fa-lg"></i> <span class="nav-label">财务管理</span></a>
                </li>
                <li class="market">
                    <a href="<?=UrlService::buildWebUrl('/qrcode/index');?>"><i class="fa fa-share-alt fa-lg"></i> <span class="nav-label">营销渠道</span></a>
                </li>
                <li class="stat">
                    <a href="<?=UrlService::buildWebUrl('/stat/index');?>"><i class="fa fa-bar-chart fa-lg"></i> <span class="nav-label">统计管理</span></a>
                </li>
			</ul>

		</div>
	</nav>
		<div id="page-wrapper" class="gray-bg" style="background-color: #ffffff;">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<div class="navbar-header">
						<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="<?=UrlService::buildNullUrl();?>"><i class="fa fa-bars"></i> </a>

					</div>
					<ul class="nav navbar-top-links navbar-right">
						<li>
							<span class="m-r-sm text-muted welcome-message">
	                            欢迎使用图书商城管理后台
	                        </span>
						</li>
						<li class="hidden">
							<a class="count-info" href="javascript:void(0);">
								<i class="fa fa-bell"></i>
	                            <span class="label label-primary">8</span>
							</a>
						</li>


						<li class="dropdown user_info">
							<a  class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
	                            <img alt="image" class="img-circle" src="<?php echo UrlService::buildPicUrl('avatar',$this->params['user_info']['avatar']);?>" />
							</a>
	                        <ul class="dropdown-menu dropdown-messages">
	                            <li>
	                                <div class="dropdown-messages-box">
	                                   姓名：<?php echo $this->params['user_info']['nickname'];?>                                  <a href="/web/user/edit" class="pull-right">编辑</a>
	                                </div>
	                            </li> 
	                            <li class="divider"></li>
	                            <li>
	                                <div class="dropdown-messages-box">
	                                   手机号码：<?php echo $this->params['user_info']['mobile'];?>                               </div>
	                            </li>
	                            <li class="divider"></li>
	                            <li>
	                                <div class="link-block text-center">
	                                    <a class="pull-left" href="/web/user/reset-pwd">
	                                        <i class="fa fa-lock"></i> 修改密码
	                                    </a>
	                                    <a class="pull-right" href="/web/user/logout">
	                                        <i class="fa fa-sign-out"></i> 退出
	                                    </a>
	                                </div>
	                            </li>
	                        </ul>
						</li>

					</ul>

				</nav>
			</div>

			<!--不同内容bengin-->
			<?=$content;?>
			<!--不同内容end-->
		</div>
</div>

<div class="hidden_layout_wrap hide">
	<input type="hidden" name="upload_config" value='<?=json_encode($upload_config);?>' > 
</div>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
