<?php
use app\common\services\UrlService;
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/account/tab_account_common.php',['current' => 'index']);?>
<div class="row">
	<div class="col-lg-12">
		<form class="form-inline wrap_search" action="<?=UrlService::buildWebUrl('/account');?>" method="get">
			<div class="row m-t p-w-m">
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
						<input type="text" name="mix_kw" placeholder="请输入姓名或者手机号码" class="form-control" value="<?=$search_conditions['mix_kw'];?>">
                        <input type="hidden" name="p" value="1">
						<span class="input-group-btn">
                            <button type="submit" class="btn btn-primary search">
                                <i class="fa fa-search"></i>搜索
                            </button>
                        </span>
					</div>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-lg-12">
					<a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/account/set">
						<i class="fa fa-plus"></i>账号
					</a>
				</div>
			</div>
		</form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>序号</th>
                <th>姓名</th>
                <th>手机</th>
                <th>邮箱</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($list as $_list):?>
                <tr>
                    <td><?=$_list['uid'];?></td>
                    <td><?=$_list['nickname'];?></td>
                    <td><?=$_list['mobile'];?></td>
                    <td><?=$_list['email'];?></td>
                    <td>
                    <?php if($current_uid == $_list['uid']):?>
                    <?php else:?>
                        <?php if($_list['status'] == 1):?>
                            <a  href="<?=UrlService::buildWebUrl('/account/info',['id' => $_list['uid']]);?>">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            <a class="m-l" href="<?=UrlService::buildWebUrl('/account/set',['id' => $_list['uid']]);?>">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>
                            <a class="m-l button-ops" attr-message="是否删除" attr-action="remove" href="<?=UrlService::buildNullUrl();?>" data="<?=$_list['uid'];?>">
                                <i class="fa fa-trash fa-lg"></i>
                            </a>
                        <?php else:?>
                            <a class="m-l button-ops" attr-message="是否恢复" attr-action="recover" href="<?=UrlService::buildNullUrl();?>" data="<?=$_list['uid'];?>">
                                <i class="fa fa-rotate-left fa-lg"></i>
                            </a>
                        <?php endif;?>
                    <?php endif;?>        
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
		<div class="row">
	<div class="col-lg-12">
		<span class="pagination_count" style="line-height: 40px;">共<?=$pages['page_count'];?>条记录 | 每页<?=$pages['page_size'];?>条</span>
		<ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
            <?php for($i=1;$i <= $pages['page_total'];$i++):?>
                <?php if($i == $pages['p']):?>
                    <li class="active"><a href="<?=UrlService::buildNullUrl();?>"><?=$i;?></a></li>
                <?php else:?>
                    <li><a href="<?=UrlService::buildWebUrl('/account',[
                        'p' => $i,
                        'status' => $search_conditions['status'],
                        'mix_kw' => $search_conditions['mix_kw'],
                    ]);?>"><?=$i;?></a></li>
                <?php endif;?>
			 
            <?php endfor;?>
        </ul>
	</div>
</div>	</div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'ops_url':'<?=UrlService::buildWebUrl('/account/ops');?>',
    }
</script>


