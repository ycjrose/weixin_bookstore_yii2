<?php
use app\common\services\UrlService; 

?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/account/tab_common_account.php',['current' => '']);?>

<div class="row m-t">
	<div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-b-md">
                    <a class="btn btn-outline btn-primary pull-right" href="<?=UrlService::buildWebUrl('/account/set',['id' => $user_info['uid']]);?>">
                        <i class="fa fa-pencil"></i>编辑
                    </a>
                                        <h2>账户信息</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 text-center">
                <img class="img-circle circle-border" alt="iamge" src="<?=UrlService::buildPicUrl('avatar',$user_info['avatar']);?>" width="100px" height="100px"/>
            </div>
            <div class="col-lg-10">
                <p class="m-t">姓名：<?=$user_info['nickname'];?></p>
                <p>手机：<?=$user_info['mobile'];?></p>
                <p>邮箱：<?=$user_info['email'];?></p>
            </div>
        </div>
        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="<?=UrlService::buildNullUrl();?>" data-toggle="tab" aria-expanded="false">访问记录</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>访问时间</th>
                                            <th>访问Url</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($access_list as $value):?>
                                            <tr>
                                                <td><?=$value['created_time']?></td>
                                                <td><?=$value['target_url']?></td>
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