<?php
use app\common\services\UrlService;
use app\common\services\UtilService; 
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/member/tab_common_member.php',['current' => 'index']);?>

<div class="row">
    <div class="col-lg-12">
        <form class="form-inline wrap_search" action="<?=UrlService::buildWebUrl('/member');?>" method="get">
            <div class="row  m-t p-w-m">
                <div class="form-group">
                    <select name="status" class="form-control inline">
                        <option value="<?=ContactService::$status_default;?>">请选择状态</option>
                        <?php foreach($status as $key => $value):?>
                        <option value="<?=$key;?>" <?php if($key == $search_conditions['status']):?>selected<?php endif;?> ><?=$value;?></option>
                        <?php endforeach;?>
					</select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="mix_kw" placeholder="请输入姓名或手机号" class="form-control" value="<?=$search_conditions['mix_kw'];?>">
                        <input type="hidden" name="p" value="1">
                        <span class="input-group-btn">
                            <button type="submit" class="btn  btn-primary search">
                                <i class="fa fa-search"></i>搜索
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/member/set">
                        <i class="fa fa-plus"></i>会员
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>头像</th>
                <th>姓名</th>
                <th>手机</th>
                <th>性别</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($members as $_item):?>
					<tr>
                        <td><img alt="image" class="img-circle" src="" style="width: 40px;height: 40px;"></td>
                        <td><?=UtilService::encode($_item['nickname']);?></td>
                        <td><?=UtilService::encode($_item['mobile']);?></td>
                        <td><?=ContactService::$sex[$_item['sex']];?></td>
                        <td><?=ContactService::$status[$_item['status']];?></td>
                        <td>
                            <?php if($_item['status']):?>
                            <a  href="<?=UrlService::buildWebUrl('/member/info',['id' => $_item['id'] ]);?>">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
							<a class="m-l" href="<?=UrlService::buildWebUrl('/member/set',['id' => $_item['id'] ]);?>">
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
		<div class="row">

        <!--分页文件统一封装在其他模板文件中-->
	<?=\Yii::$app->view->renderFile('@app/modules/web/views/common/pagination.php',[
        'pages' => $pages,
        'search_conditions' => $search_conditions,
        'url' => '/member',
    ]);?>
</div>
    </div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'ops_url':'<?=UrlService::buildWebUrl('/member/ops');?>'
    }
</script>