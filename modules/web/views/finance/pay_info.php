<?php
use app\common\services\UrlService;
use app\common\services\UtilService;  
use app\common\services\ContactService;
use \app\common\services\StaticService;
StaticService::includeAppJs( "/js/web/finance/pay_info.js",\app\assets\WebAsset::className() );
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/finance/tab_finance_common.php',['current' => '']);?>

<div class="row m-t wrap_info">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<?php if( $order_info['status'] == 1 && $order_info['express_status'] == -7 ):?>
					<a class="btn btn-outline btn-primary pull-right express_send" href="<?=UrlService::buildNullUrl();?>">确认发货</a>
				<?php endif;?>
				<div class="m-b-md">
					<h2>订单信息</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="m-t">订单编号：<?=UtilService::encode($order_info['id']);?></p>
				<p>会员姓名：<?=UtilService::encode($order_info['member_name']);?></p>
				<p>会员手机：<?=UtilService::encode($order_info['member_mobile']);?></p>
				<p>订单总价：<?=UtilService::encode($order_info['pay_price']);?></p>
				<p>订单状态：<?=UtilService::encode($order_info['status']);?></p>
				<p>创建时间：<?=UtilService::encode($order_info['created_time']);?></p>
                <p>收货信息：<?=UtilService::encode($order_info['member_address']);?></p>
			</div>
		</div>
		<div class="row m-t">
			<div class="col-lg-12">
				<div class="panel blank-panel">
					<div class="panel-heading">
						<div class="panel-options">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab-1" data-toggle="tab" aria-expanded="false">订单商品</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane active" id="tab-1">
								<table class="table table-striped">
									<thead>
									<tr>
										<th>商品</th>
										<th>数量</th>
										<th>金额</th>
									</tr>
									</thead>
									<tbody>
										<?php foreach($order_info['pay_items'] as $_item):?>
										<tr>
											<td><?=$_item['book_name'];?></td>
											<td><?=$_item['quantity'];?></td>
											<td><?=$_item['price'];?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="express_wrap" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">确认发货</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-horizontal m-t m-b">
                            <div class="form-group">
                                <label class="col-lg-2 control-label">发货信息:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?=UtilService::encode($order_info['member_address']);?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">快递信息:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="express_info" class="form-control" placeholder="请输入快递信息，例如圆通快递 VIP123123~~" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <input type="hidden" name="pay_order_id" value="<?=$order_info['id'];?>">
                <button type="button" class="btn btn-primary save">保存</button>
            </div>
        </div>
    </div>
</div>
