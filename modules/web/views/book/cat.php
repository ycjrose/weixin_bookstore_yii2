<?php
use app\common\services\UrlService;  
use app\common\services\UtilService;
use app\common\services\ContactService;
use \app\common\services\StaticService;
StaticService::includeAppJs("/js/web/book/cat.js",\app\assets\WebAsset::className());
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/book/tab_book_common.php',['current' => 'cat']);?>

<div class="row">
	<div class="col-lg-12">
		<form class="form-inline wrap_search" method="get" action="<?=UrlService::buildWebUrl('/book/cat');?>">
			<div class="row  m-t p-w-m">
				<div class="form-group">
					<select name="status" class="form-control inline">
						<option value="<?=ContactService::$status_default;?>">请选择状态</option>
                        <?php foreach($status as $key => $value):?>
                        <option value="<?=$key;?>" <?php if($key == $search_conditions['status']):?>selected<?php endif;?> ><?=$value;?></option>
                        <?php endforeach;?>
                    </select>    
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-lg-12">
					<a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/book/cat_set">
						<i class="fa fa-plus"></i>分类
					</a>
				</div>
			</div>

		</form>
		<table class="table table-bordered m-t">
			<thead>
			<tr>
				<th>序号</th>
				<th>分类名称</th>
				<th>状态</th>
				<th>权重</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
           		<?php foreach($cats as $_item):?>
                    <tr>
                        <td><?=UtilService::encode($_item['id']);?></td>
                        <td><?=UtilService::encode($_item['name']);?></td>
                        <td><?=UtilService::encode($_item['status']);?></td>
                        <td><?=UtilService::encode($_item['weight']);?></td>
                        <td>
                            <?php if($_item['status']):?>
                            <a class="m-l" href="<?=UrlService::buildWebUrl('/book/cat_set',['id' => $_item['id'] ]);?>">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>
                            <a class="m-l button-ops" attr-action="remove" attr-message="是否删除？" href="<?=UrlService::buildNullUrl();?>" data="<?=$_item['id'];?>">
                                    <i class="fa fa-trash fa-lg"></i>
                             </a>
                            <?php else:?>
                                <a class="m-l button-ops" attr-action="recover" attr-message="是否恢复？" href="<?=UrlService::buildNullUrl();?>" data="<?=$_item['id'];?>">
                                        <i class="fa fa-rotate-left fa-lg"></i>
                                 </a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'ops_url':'<?=UrlService::buildWebUrl('/book/cat_ops');?>'
    }
</script>