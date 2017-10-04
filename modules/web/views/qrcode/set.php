<?php
use app\common\services\UrlService;
use app\common\services\UtilService; 
use app\common\services\ContactService; 
use app\common\services\StaticService;
StaticService::includeAppJs( "/js/web/qrcode/set.js",\app\assets\WebAsset::className() );
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/qrcode/tab_common_qrcode.php',['current' => '']);?>
<div class="row m-t  wrap_qrcode_set">
	<div class="col-lg-12">
		<h2 class="text-center">渠道二维码设置</h2>
		<div class="form-horizontal m-t m-b" id="weixin-form">
			<div class="form-group" >
				<label class="col-lg-2 control-label">渠道名称:</label>
				<div class="col-lg-10">
					<input type="text" name="name" class="form-control" placeholder="请输入渠道名称~~" value="<?=$info?$info['name']:'';?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<div class="col-lg-4 col-lg-offset-2">
                    <input type="hidden" name="id" value="<?=$info?$info['id']:0;?>">
					<button class="btn btn-w-m btn-outline btn-primary " id="button-submit">保存</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var SCOPE = {
		'save_url' : '<?=UrlService::buildWebUrl('/qrcode/set');?>' ,
		'jump_url' : '<?=UrlService::buildWebUrl('/qrcode');?>',
	}
</script>