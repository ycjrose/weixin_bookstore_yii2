<?php
use app\common\services\UrlService;

$tab_list = [
	'index' => [
		'title' => '图书列表',
		'url' => '/book/index',
	],
	'cat' => [
		'title' => '分类列表',
		'url' => '/book/cat',
	],
	'images' => [
		'title' => '图片资源',
		'url' => '/book/images',
	],

];
?>


<div class="row  border-bottom"> 
	<div class="col-lg-12">
		<div class="tab_title">
			<ul class="nav nav-pills">
			<?php foreach($tab_list as $_current => $_item):?>
				<li <?php if($current == $_current):?> class="current"<?php endif;?> >
					<a href="<?=UrlService::buildWebUrl($_item['url']);?>"><?=$_item['title'];?></a>
				</li>
			<?php endforeach;?>
			</ul>
		</div>
	</div>
</div>