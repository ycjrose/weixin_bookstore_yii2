<?php
use app\common\services\UrlService; 
use app\common\services\StaticService;
StaticService::includeAppJs('/js/web/member/set.js',app\assets\WebAsset::className());
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/member/tab_common_member.php',['current' => '']);?>

<div class="row mg-t20 wrap_member_set">
	<div class="col-lg-12">
		<h2 class="text-center">会员设置</h2>
		<div class="form-horizontal m-t" id="weixin-form">
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">会员名称:</label>
				<div class="col-lg-10">
					<input type="text" class="form-control" placeholder="请输入会员名称" name="nickname" value="<?=$member_info?$member_info['nickname']:'';?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">会员手机:</label>
				<div class="col-lg-10">
					<input type="text" class="form-control" placeholder="请输入会员手机" name="mobile" value="<?=$member_info?$member_info['mobile']:'';?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<div class="col-lg-4 col-lg-offset-2">
					<input type="hidden" name="id" value="<?=$member_info?$member_info['id']:'';?>">
					<button class="btn btn-w-m btn-outline btn-primary" id="button-submit">保存</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var SCOPE = {
		'save_url':'<?=UrlService::buildWebUrl('/member/set');?>',
		'jump_url':'<?=UrlService::buildWebUrl('/member');?>',
	}
</script>
