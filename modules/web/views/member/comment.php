<?php
use app\common\services\UrlService; 
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
							                    <tr>
                        <td>
                                                        <img alt="image" class="img-circle" src="/uploads/avatar/20170313/159419a875565b1afddd541fa34c9e65.jpg" style="width: 40px;height: 40px;">
                                                    </td>
                        <td>
							                            郭威							                        </td>
                        <td>
															12312312312							                        </td>
                        <td>浪潮之巅</td>
                        <td>哈哈哈哈或哈哈或</td>
                        <td>8</td>
                    </tr>
				                    <tr>
                        <td>
                                                        <img alt="image" class="img-circle" src="/uploads/avatar/20170313/159419a875565b1afddd541fa34c9e65.jpg" style="width: 40px;height: 40px;">
                                                    </td>
                        <td>
							                            郭威							                        </td>
                        <td>
															12312312312							                        </td>
                        <td>php开发教程</td>
                        <td>书是正品的，非常不错的</td>
                        <td>6</td>
                    </tr>
				                    <tr>
                        <td>
                                                        <img alt="image" class="img-circle" src="/uploads/avatar/20170313/159419a875565b1afddd541fa34c9e65.jpg" style="width: 40px;height: 40px;">
                                                    </td>
                        <td>
							                            郭威							                        </td>
                        <td>
															12312312312							                        </td>
                        <td>php开发教程</td>
                        <td>书是正品的，非常不错的</td>
                        <td>8</td>
                    </tr>
				                    <tr>
                        <td>
                                                        <img alt="image" class="img-circle" src="/uploads/avatar/20170313/159419a875565b1afddd541fa34c9e65.jpg" style="width: 40px;height: 40px;">
                                                    </td>
                        <td>
							                            郭威							                        </td>
                        <td>
															12312312312							                        </td>
                        <td>php开发教程</td>
                        <td>服务非常好</td>
                        <td>10</td>
                    </tr>
							            </tbody>
        </table>
		<div class="row">
	<div class="col-lg-12">
		<span class="pagination_count" style="line-height: 40px;">共4条记录 | 每页50条</span>
		<ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
										                    <li class="active"><a href="javascript:void(0);">1</a></li>
                            					</ul>
	</div>
</div>
    </div>
</div>
