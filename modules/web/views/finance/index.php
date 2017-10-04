<?php
use app\common\services\UrlService;
use app\common\services\UtilService;  
use app\common\services\ContactService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/web/finance/index.js",\app\assets\WebAsset::className() );
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/finance/tab_finance_common.php',['current' => 'index']);?>

<div class="row">
	<div class="col-lg-12">
		<form class="form-inline wrap_search" action="<?=UrlService::buildWebUrl('/finance');?>" method="get">
			<div class="row  m-t p-w-m">
				<div class="form-group">
					<select name="status" class="form-control inline">
						<option value="-1">请选择状态</option>
						<?php foreach($pay_status as $k => $v):?>
							<option value="<?=$k;?>" <?php if($k == $search_conditions['status']):?>selected<?php endif;?> ><?=$v;?></option>
						<?php endforeach;?>							
					</select>
				</div>
			</div>
		</form>
		<hr/>
		<table class="table table-bordered m-t">
			<thead>
				<tr>
					<th>订单编号</th>
					<th>名称</th>
					<th>价格</th>
					<th>支付时间</th>
					<th>状态</th>
					<th>快递状态</th>
					<th>创建时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($orders as $_item):?>
				<tr>
					<td><?=$_item['id'];?></td>
					<td>
						<?php foreach( $_item['pay_items'] as $_order_item ):?>
							<?=$_order_item["book_name"];?> × <?=$_order_item["quantity"];?><br/>
						<?php endforeach;?>
					</td>
					<td><?=$_item['pay_price'];?></td>
					<td>
						<?php if( $_item['status'] == 1 ):?>
						<?= $_item['pay_time'] ;?>
						<?php endif;?>
					</td>
					<td><?=ContactService::$pay_status[ $_item['status'] ];?></td>
					<td><?=ContactService::$express_status[ $_item['express_status'] ];?></td>
					<td><?=$_item['created_time'];?></td>
					<td>
						<a  href="<?=UrlService::buildWebUrl("/finance/pay_info",[ 'id' => $_item['id'] ] );?>">
						    <i class="fa fa-eye fa-lg"></i>
						</a>
					</td>
				</tr>
				<?php endforeach;?>						
			</tbody>
		</table>
		<div class="row">
		    <!--分页文件统一封装在其他模板文件中-->
			<?=\Yii::$app->view->renderFile('@app/modules/web/views/common/pagination.php',[
		        'pages' => $pages,
		        'search_conditions' => $search_conditions,
		        'url' => '/finance',
		    ]);?>
		</div>
	</div>
</div>