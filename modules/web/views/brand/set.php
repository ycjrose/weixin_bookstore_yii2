<?php
use app\common\services\UrlService;  
use app\common\services\StaticService;
StaticService::includeAppJs('/js/web/brand/set.js',app\assets\WebAsset::className());
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/brand/tab_brand_common.php',['current' => '']);?>

<div class="row m-t  wrap_brand_set">
	<div class="col-lg-12">
		<h2 class="text-center">品牌设置</h2>
		<div class="form-horizontal m-t m-b" id="weixin-form">
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌名称:</label>
				<div class="col-lg-10">
					<input type="text" name="name" class="form-control" placeholder="请输入品牌名称~~" value="<?=$brand_info['name'];?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌Logo:</label>
				<div class="col-lg-10">
                    <form class="upload_pic_wrap" target="upload_file" enctype="multipart/form-data" method="POST" action="<?=UrlService::buildWebUrl('/upload/pic');?>">
                        <div class="upload_wrap pull-left">
                            <i class="fa fa-upload fa-2x"></i>
                            <input type="hidden" name="bucket" value="brand" />
                            <input type="file" name="pic" >
                        </div>
						<span class="pic-each">
							<img src="<?=UrlService::buildPicUrl('brand',$brand_info['logo']);?>">
							<input type="hidden" name="logo" value="<?=$brand_info['logo'];?>" />
							<span class="fa fa-times-circle del del_image"><i></i></span>
						</span>
					</form>
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">电话:</label>
				<div class="col-lg-10">
					<input type="text" name="mobile" class="form-control" placeholder="请输入联系电话~~"  value="<?=$brand_info['mobile'];?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">地址:</label>
				<div class="col-lg-10">
					<input type="text" name="address" class="form-control" placeholder="请输入联系地址~~"  value="<?=$brand_info['address'];?>">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌介绍:</label>
				<div class="col-lg-10">
					<textarea  name="description" class="form-control" rows="4"><?=$brand_info['description'];?></textarea>
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<div class="col-lg-4 col-lg-offset-2">
					<button class="btn btn-w-m btn-outline btn-primary" id="button-submit">保存</button>
				</div>
			</div>
		</div>
	</div>
</div>
<iframe class="hide" name="upload_file"></iframe>
<script type="text/javascript">
	var SCOPE = {
		'save_url':'<?=UrlService::buildWebUrl('/brand/set');?>',
		'jump_url':'<?=UrlService::buildWebUrl('/brand/info');?>',
	}
</script>