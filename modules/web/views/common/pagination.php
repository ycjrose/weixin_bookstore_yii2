<?php
use app\common\services\UrlService;
?>

<div class="col-lg-12">
		<span class="pagination_count" style="line-height: 40px;">共<?=$pages['page_count']?>条记录 | 每页<?=$pages['page_size'];?>条</span>
		<ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
            <?php for($i = 1;$i <= $pages['page_total'];$i++):?>
			<li <?php if($pages['p'] == $i):?>class="active"<?php endif;?>>
                <a href="<?=UrlService::buildWebUrl($url,[
                    'p' => $i,
                    'status' => $search_conditions['status'],
                    'mix_kw' => $search_conditions['mix_kw'],
                ]);?>"><?=$i;?></a>
            </li>
            <?php endfor;?>
        </ul>
	</div>