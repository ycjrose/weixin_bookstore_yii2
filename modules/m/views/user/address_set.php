<?php
use \app\common\services\StaticService;
use \app\common\services\UrlService;
use \app\common\services\UtilService;
StaticService::includeAppJs( "/js/m/user/address_set.js",\app\assets\MAsset::className() );
?>
<div style="min-height: 500px;">
	<div class="page_title clearfix">
    <span>收货地址</span>
</div>
<div class="addr_form_box">
	<div class="addr_input_box">
        <span>收货人</span>
        <input name="nickname" type="text" placeholder="请输入收货人姓名" class="addr_input" value="<?=$info?UtilService::encode( $info['nickname'] ):'';?>" />
    </div>
	<div class="addr_input_box">
        <span>联系电话</span>
        <input name="mobile" type="text" placeholder="请输入收货人联系电话" value="<?=$info?UtilService::encode( $info['mobile'] ):'';?>" class="addr_input" />
    </div>
	<div class="addr_input_box">
		<span>所在地区</span>
		<div class="select_box">
			<select id="province_id">
                <option value="0">请选择省</option>
				<?php foreach($provinces as $_item):?>
					<option value="<?=$_item['id'];?>" <?php if( $info && $info['province_id'] == $_item['id'] ):?> selected <?php endif;?> ><?=$_item['province'];?></option>
				<?php endforeach;?>              
			</select>
		</div>
		<div class="select_box">
			<select id="city_id">
                <option value="0">请选择市</option>
			</select>
		</div>
		<div class="select_box">
			<select id="area_id">
                <option value="0">请选择区</option>
			</select>
		</div>
	</div>
	<div class="addr_input_box">
        <span>详细地址</span>
        <textarea name="address" rows="2" cols="20" placeholder="详细地址" class="addr_textarea"><?=$info?$info['address']:'';?></textarea>
    </div>
</div>
<div class="op_box">
    <input style="width: 100%;"  type="button" value="保存" class="red_btn save" />
</div>

<div class="hidden hide_wrap">
    <input name="id" type="hidden" value="<?=$info?$info['id']:0;?>">
    <input type="hidden" id="area_id_before" value="<?=$info?$info['area_id']:0;?>">
    <input type="hidden" id="city_id_before" value="<?=$info?$info['city_id']:0;?>">
</div>
</div>