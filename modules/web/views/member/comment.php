<?php
use app\common\services\UrlService;
use app\common\services\UtilService;  
use app\common\services\ContactService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/member/tab_common_member.php',['current' => 'comment']);?>

<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>头像</th>
                <th>姓名</th>
                <th>手机</th>
                <th>书籍名称</th>
                <th>评论内容</th>
                <th>打分</th>
            </tr>
            </thead>
            <tbody>
                <?php if( $comments ):?>
                    <?php foreach($comments as $_item):?>
				    <tr>
                        <td>
                            <img alt="image" class="img-circle" src="<?=$_item['avatar']?>" style="width: 40px;height: 40px;">
                        </td>
                        <td><?=$_item['member_name'];?></td>
                        <td><?=$_item['mobile'];?></td>
                        <td><?=$_item['book_name'];?></td>
                        <td><?=UtilService::encode($_item['content']);?></td>
                        <td><?=$_item['score'];?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endif;?>
			</tbody>
        </table>
		<div class="row">
	               <!--分页文件统一封装在其他模板文件中-->
            <?=\Yii::$app->view->renderFile('@app/modules/web/views/common/pagination.php',[
                   'pages' => $pages,
                   'search_conditions' => [
                        'status' => '',
                        'mix_kw' =>''
                    ],
                   'url' => '/member/comment',
               ]);?>
        </div>
    </div>
</div>
