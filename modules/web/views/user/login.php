<?php
use app\common\services\UrlService; 
?>
<div class="loginColumns animated fadeInDown">
	<div class="row">

		<div class="col-md-6 text-center">
			<h2 class="font-bold">ycj图书商城管理后台</h2>
			<p>
				<img src="<?=UrlService::buildWwwUrl('/images/common/qrcode2.jpg');?>" width="300px"/>
			</p>
            <p class="text-danger">
                扫描关注查看Demo
            </p>
		</div>
		<div class="col-md-6">
			<div class="ibox-content">
				<div class="m-t" id="weixin-form">
                    <div class="form-group text-center">
                        <h2 class="font-bold">登录</h2>
                    </div>
					<div class="form-group">
						<input type="text" name="login_name" class="form-control" placeholder="请输入登录用户名">
					</div>
					<div class="form-group">
						<input type="password" name="login_pwd" class="form-control" placeholder="请输入登录密码">
					</div>
					<button id="button-submit" class="btn btn-primary block full-width m-b">登录</button>
                    <h3>账号和密码请关注左侧服务号 回复"<span class="text-danger">商城账号</span>"获取，每日更新一次 </h3>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-6">
            图书商城管理系统 <a href="http://47.93.59.20/" target="_blank"> 技术支持 </a>
		</div>
		<div class="col-md-6 text-right">
			<small>© 2017</small>
		</div>
	</div>
</div>
<script type="text/javascript">
	var SCOPE = {
		'save_url':'<?=UrlService::buildWebUrl("/user/login")?>',
		'jump_url':'<?=UrlService::buildWebUrl("/dashboard")?>'
	}

</script>

