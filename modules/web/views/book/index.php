<?php
use app\common\services\UrlService; 
use app\common\services\UtilService;
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/book/tab_book_common.php',['current' => 'index']);?>

<div class="row">
    <div class="col-lg-12">
        <form class="form-inline wrap_search" action="<?=UrlService::buildWebUrl('/book');?>" method="get">
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
                    <select name="catid" class="form-control inline">
                        <option value="<?=ContactService::$status_default;?>">请选择分类</option>
                        <?php foreach($cat as $key => $value):?>
                        <option value="<?=$key;?>" <?php if($key == $search_conditions['catid']):?> selected <?php endif;?> ><?=$value;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="mix_kw" placeholder="请输入书名或标签" class="form-control" value="<?=$search_conditions['mix_kw'];?>">
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
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/book/set">
                        <i class="fa fa-plus"></i>图书
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t"> 
            <thead>
            <tr>
                <th>图书名</th>
                <th>分类</th>
                <th>价格</th>
                <th>库存</th>
                <th>标签</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
				<?php foreach($books as $_item):?>
                    <tr>
                        <td><?=UtilService::encode($_item['name']);?></td>
                        <td><?=UtilService::encode($cat[$_item['cat_id']]);?></td>
                        <td><?=UtilService::encode($_item['price']);?></td>
                        <td><?=UtilService::encode($_item['stock']);?></td>
                        <td><?=UtilService::encode($_item['tags']);?></td>
                        <td>
                            <?php if($_item['status']):?>
                            <a  href="<?=UrlService::buildWebUrl('/book/info',['id' => $_item['id'] ]);?>">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            <a class="m-l" href="<?=UrlService::buildWebUrl('/book/set',['id' => $_item['id'] ]);?>">
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
            <div class="col-lg-12">
                    <span class="pagination_count" style="line-height: 40px;">共<?=$pages['page_count']?>条记录 | 每页<?=$pages['page_size'];?>条</span>
                    <ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
                        <?php for($i = 1;$i <= $pages['page_total'];$i++):?>
                        <li <?php if($pages['p'] == $i):?> class="active" <?php endif;?>>
                            <a href="<?=UrlService::buildWebUrl('/book',[
                                'p' => $i,
                                'status' => $search_conditions['status'],
                                'catid' =>$search_conditions['catid'],
                                'mix_kw' => $search_conditions['mix_kw'],
                            ]);?>"><?=$i;?></a>
                        </li>
                        <?php endfor;?>
                    </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var SCOPE = {
        'ops_url':'<?=UrlService::buildWebUrl('/book/ops');?>'
    }
</script>