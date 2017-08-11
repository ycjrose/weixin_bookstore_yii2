<div style="min-height: 500px;">
	<div class="page_title clearfix">
    <span>收货地址</span>
</div>
<div class="addr_form_box">
	<div class="addr_input_box">
        <span>收货人</span>
        <input name="nickname" type="text" placeholder="请输入收货人姓名" class="addr_input" value="" />
    </div>
	<div class="addr_input_box">
        <span>联系电话</span>
        <input name="mobile" type="text" placeholder="请输入收货人联系电话" value="" class="addr_input" />
    </div>
	<div class="addr_input_box">
		<span>所在地区</span>
		<div class="select_box">
			<select id="province_id">
                <option value="0">请选择省</option>
				                    <option value="110000" >北京市</option>
				                    <option value="120000" >天津市</option>
				                    <option value="130000" >河北省</option>
				                    <option value="140000" >山西省</option>
				                    <option value="150000" >内蒙古自治区</option>
				                    <option value="210000" >辽宁省</option>
				                    <option value="220000" >吉林省</option>
				                    <option value="230000" >黑龙江省</option>
				                    <option value="310000" >上海市</option>
				                    <option value="320000" >江苏省</option>
				                    <option value="330000" >浙江省</option>
				                    <option value="340000" >安徽省</option>
				                    <option value="350000" >福建省</option>
				                    <option value="360000" >江西省</option>
				                    <option value="370000" >山东省</option>
				                    <option value="410000" >河南省</option>
				                    <option value="420000" >湖北省</option>
				                    <option value="430000" >湖南省</option>
				                    <option value="440000" >广东省</option>
				                    <option value="450000" >广西壮族自治区</option>
				                    <option value="460000" >海南省</option>
				                    <option value="500000" >重庆市</option>
				                    <option value="510000" >四川省</option>
				                    <option value="520000" >贵州省</option>
				                    <option value="530000" >云南省</option>
				                    <option value="540000" >西藏自治区</option>
				                    <option value="610000" >陕西省</option>
				                    <option value="620000" >甘肃省</option>
				                    <option value="630000" >青海省</option>
				                    <option value="640000" >宁夏回族自治区</option>
				                    <option value="650000" >新疆维吾尔自治区</option>
				                    <option value="710000" >台湾省</option>
				                    <option value="810000" >香港特别行政区</option>
				                    <option value="820000" >澳门特别行政区</option>
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
        <textarea name="address" rows="2" cols="20" placeholder="详细地址" class="addr_textarea"></textarea>
    </div>
</div>
<div class="op_box">
    <input style="width: 100%;"  type="button" value="保存" class="red_btn save" />
</div>

<div class="hidden hide_wrap">
    <input name="id" type="hidden" value="0">
    <input type="hidden" id="area_id_before" value="0">
    <input type="hidden" id="city_id_before" value="0">
</div></div>