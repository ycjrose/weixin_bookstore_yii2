<?php
use app\common\services\UrlService; 
use app\common\services\UtilService;
?>
<?=\Yii::$app->view->renderFile('@app/modules/web/views/book/tab_book_common.php',['current' => 'images']);?>

<div class="row">
	<div class="col-lg-12">
		
		<table class="table table-bordered m-t">
			<thead>
			<tr>
				<th>图片</th>
				<th>大图地址</th>
			</tr>
			</thead>
			<tbody>
                <?php foreach($images as $_item):?>
                    <tr>
                        <td>
                            <img src="<?=UrlService::buildPicUrl('book',$_item['file_key']);?>" style="width: 100px;"/>
                        </td>
                        <td>
                            <a target="_blank" href="<?=UrlService::buildPicUrl('book',$_item['file_key']);?>">查看大图</a>
                        </td>
                    </tr>
                <?php endforeach;?>    
            </tbody>
		</table>
        <div class="row">
                <!--分页文件统一封装在其他模板文件中-->
                <?=\Yii::$app->view->renderFile('@app/modules/web/views/common/pagination.php',[
                     'pages' => $pages,
                     'search_conditions' => [
                        'status' => '',
                        'mix_kw' => '',
                     ],
                     'url' => '/book/images',
                ]);?>
        </div>
	</div>
</div>