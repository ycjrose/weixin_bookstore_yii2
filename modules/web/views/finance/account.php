<?php
use app\common\services\UrlService;
use app\common\services\UtilService;  
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/finance/tab_finance_common.php',['current' => 'account']);?>

<div class="row">
    <div class="col-lg-12 m-t">
        <p>总收款金额：<?=$total_price;?></p>
    </div>
	<div class="col-lg-12">
		<table class="table table-bordered m-t">
			<thead>
			<tr>
				<th>订单编号</th>
				<th>价格</th>
				<th>支付时间</th>
			</tr>
			</thead>
			<tbody>
				<?php foreach($orders as $_item):?>
				<tr>
					<td><?=$_item['id'];?></td>
					<td><?=$_item['pay_price'];?></td>
					<td><?=$_item['pay_time'];?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<div class="row">
			<!--分页文件统一封装在其他模板文件中-->
			<?=\Yii::$app->view->renderFile('@app/modules/web/views/common/pagination.php',[
			       'pages' => $pages,
			       'search_conditions' => $search_conditions,
			       'url' => '/finance/account',
			]);?>
		</div>
	</div>
</div>