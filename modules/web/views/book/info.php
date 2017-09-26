<?php
use app\common\services\UrlService; 
use app\common\services\UtilService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/book/tab_book_common.php',['current' => '']);?>

<style type="text/css">
	.wrap_info img{
		width: 70%;
	}
</style>
<div class="row m-t wrap_info">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="m-b-md">
					<a class="btn btn-outline btn-primary pull-right" href="<?=UrlService::buildWebUrl('/book/set',['id' => $book_info['id']]);?>">
					   <i class="fa fa-pencil"></i>编辑
					</a>
					<h2>图书信息</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="m-t">图书名称：<?=UtilService::encode($book_info['name']);?></p>
				<p>图书售价：<?=UtilService::encode($book_info['price']);?></p>
				<p>库存总量：<?=UtilService::encode($book_info['stock']);?></p>
				<p>图书标签：<?=UtilService::encode($book_info['tags']);?></p>
				<p>封面图：<img src="<?=UrlService::buildPicUrl('book',$book_info['main_image']);?>" style="width: 100px;height: 100px;"/> </p>
				<p>图书描述：<?=$book_info['summary']?><p>

			</div>
		</div>
        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li >
                                    <a href="#tab-1" data-toggle="tab" aria-expanded="false">销售历史</a>
                                </li>
                                <li>
                                    <a href="#tab-2" data-toggle="tab" aria-expanded="true">库存变更</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="tab-1">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>出售数量</th>
                                        <th>售卖金额</th>
                                        <th>会员名称</th>
                                        <th>时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sale_change_list as $_item):?>
                                            <tr>
                                                <td><?=$_item['quantity'];?></td>
                                                <td><?=$_item['price'];?></td>
                                                <td><?=$_item['member_name'];?></td>
                                                <td><?=$_item['created_time'];?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>变更</th>
                                        <th>备注</th>
                                        <th>时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($stock_change_list as $_item):?>
                                            <tr>
                                                <td><?=$_item['unit'];?></td>
                                                <td><?=$_item['note'];?></td>
                                                <td><?=$_item['created_time'];?></td>
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