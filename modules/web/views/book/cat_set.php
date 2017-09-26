<?php
use app\common\services\UrlService; 
use app\common\services\UtilService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/book/tab_book_common.php',['current' => '']);?>

<div class="row m-t  wrap_cat_set">
	<div class="col-lg-12">
		<h2 class="text-center">分类设置</h2>
		<div class="form-horizontal m-t m-b" id="weixin-form">
			<div class="form-group">
				<label class="col-lg-2 control-label">分类名称:</label>
				<div class="col-lg-10">
					<input type="text" name="name" class="form-control" placeholder="请输入分类名称~~" value="<?=$cat?$cat['name']:'';?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">权重:</label>
				<div class="col-lg-10">
					<input type="text" name="weight" class="form-control" placeholder="请输入分类名称~~" value="<?=$cat?$cat['weight']:'';?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<div class="col-lg-4 col-lg-offset-2">
					<input type="hidden" name="id" value="<?=$cat?$cat['id']:'';?>">
					<button class="btn btn-w-m btn-outline btn-primary" id="button-submit">保存</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var SCOPE = {
		'save_url':'<?=UrlService::buildWebUrl('/book/cat_set');?>',
		'jump_url':'<?=UrlService::buildWebUrl('/book/cat');?>',
	}
</script>