<?php
use app\common\services\UrlService; 
use app\common\services\UtilService; 
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/member/tab_common_member.php',['current' => '']);?>

<div class="row m-t">
	<div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-b-md">
					<a class="btn btn-outline btn-primary pull-right" href="<?=UrlService::buildWebUrl('/member/set',['id' => $member_info['id']]);?>/web/member/set?id=1">编辑</a>
					   <h2>会员信息</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 text-center">
                <img class="img-circle" src="<?=$member_info['avatar'];?>" width="100px" height="100px"/>
            </div>
            <div class="col-lg-9">
                <dl class="dl-horizontal">
                    <dt>姓名：</dt> <dd><?=UtilService::encode($member_info['nickname']);?></dd>
                    <dt>手机：</dt> <dd><?=UtilService::encode($member_info['mobile']);?></dd>
                    <dt>性别：</dt> <dd><?=ContactService::$sex[$member_info['sex']];?></dd>
                </dl>
            </div>
        </div>
        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab-1" data-toggle="tab" aria-expanded="false">会员订单</a>
                                </li>
                                <li>
                                    <a href="#tab-2" data-toggle="tab" aria-expanded="true">会员评论</a>
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
                                        <th>订单编号</th>
                                        <th>支付时间</th>
                                        <th>支付金额</th>
                                        <th>快递状态</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($orders as $_item):?>
                                        <tr>
                                            <td><?=$_item['order_sn'];?></td>
                                            <td><?=$_item['updated_time'];?></td>
                                            <td><?=$_item['pay_price'];?></td>
                                            <td><?=ContactService::$express_status[$_item['express_status']];?></td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>评论时间</th>
                                        <th>评分</th>
                                        <th>评论内容</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($comments as $_item):?>
                                        <tr>
                                            <td><?=$_item['created_time'];?></td>
                                            <td><?=$_item['score'];?></td>
                                            <td><?=$_item['content'];?></td>
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