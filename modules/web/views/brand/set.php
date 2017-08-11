<div class="row  border-bottom">
	<div class="col-lg-12">
		<div class="tab_title">
			<ul class="nav nav-pills">
									<li  class="current"  >
						<a href="/web/brand/info">品牌信息</a>
					</li>
									<li  >
						<a href="/web/brand/images">品牌相册</a>
					</li>
							</ul>
		</div>
	</div>
</div><div class="row m-t  wrap_brand_set">
	<div class="col-lg-12">
		<h2 class="text-center">品牌设置</h2>
		<div class="form-horizontal m-t m-b">
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌名称:</label>
				<div class="col-lg-10">
					<input type="text" name="name" class="form-control" placeholder="请输入品牌名称~~" value="编程浪子的博客">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌Logo:</label>
				<div class="col-lg-10">
                    <form class="upload_pic_wrap" target="upload_file" enctype="multipart/form-data" method="POST" action="/web/upload/pic">
                        <div class="upload_wrap pull-left">
                            <i class="fa fa-upload fa-2x"></i>
                            <input type="hidden" name="bucket" value="brand" />
                            <input type="file" name="pic" accept="image/png, image/jpeg, image/jpg,image/gif">
                        </div>
						                            <span class="pic-each">
							<img src="/uploads/brand/20170301/a8887738ab1bfd71765dd063fee4ddaa.jpg">
							<span class="fa fa-times-circle del del_image" data="20170301/a8887738ab1bfd71765dd063fee4ddaa.jpg"><i></i></span>
						</span>
						                    </form>
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">电话:</label>
				<div class="col-lg-10">
					<input type="text" name="mobile" class="form-control" placeholder="请输入联系电话~~"  value="12113021774">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">地址:</label>
				<div class="col-lg-10">
					<input type="text" name="address" class="form-control" placeholder="请输入联系地址~~"  value="上海徐汇区宜山路810号8号楼创嘉站201 （贝岭电子大厦院内）">
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<label class="col-lg-2 control-label">品牌介绍:</label>
				<div class="col-lg-10">
					<textarea  name="description" class="form-control" rows="4">我店是知名的综合性网上购物商城，由国内著名出版机构科文公司、美国老虎基金、美国IDG集团、卢森堡剑桥集团、亚洲创业投资基金（原名软银中国创业基金）共同投资成立。</textarea>
				</div>
			</div>
			<div class="hr-line-dashed"></div>
			<div class="form-group">
				<div class="col-lg-4 col-lg-offset-2">
					<button class="btn btn-w-m btn-outline btn-primary save">保存</button>
				</div>
			</div>
		</div>
	</div>
</div>