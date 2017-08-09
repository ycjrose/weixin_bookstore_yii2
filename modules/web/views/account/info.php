<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>管理后台</title>
	<link href="/css/web/bootstrap.min.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="/css/web/style.css?ver=20170401" rel="stylesheet"></head>

<body>
<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="profile-element text-center">
                        <img alt="image" class="img-circle" src="/images/web/logo.png" />
                        <p class="text-muted">编程浪子</p>
					</div>
					<div class="logo-element">
                        <img alt="image" class="img-circle" src="/images/web/logo.png" />
					</div>
				</li>
				<li class="dashboard">
					<a href="/web/dashboard/index"><i class="fa fa-dashboard fa-lg"></i>
                        <span class="nav-label">仪表盘</span></a>
				</li>
				<li class="account">
					<a href="/web/account/index"><i class="fa fa-user fa-lg"></i> <span class="nav-label">账号管理</span></a>
				</li>
                <li class="brand">
                    <a href="/web/brand/info"><i class="fa fa-cog fa-lg"></i> <span class="nav-label">品牌设置</span></a>
                </li>
                <li class="book">
                    <a href="/web/book/index"><i class="fa fa-book fa-lg"></i> <span class="nav-label">图书管理</span></a>
                </li>
                <li class="member">
                    <a href="/web/member/index"><i class="fa fa-group fa-lg"></i> <span class="nav-label">会员列表</span></a>
                </li>
                <li class="finance">
                    <a href="/web/finance/index"><i class="fa fa-rmb fa-lg"></i> <span class="nav-label">财务管理</span></a>
                </li>
                <li class="market">
                    <a href="/web/qrcode/index"><i class="fa fa-share-alt fa-lg"></i> <span class="nav-label">营销渠道</span></a>
                </li>
                <li class="stat">
                    <a href="/web/stat/index"><i class="fa fa-bar-chart fa-lg"></i> <span class="nav-label">统计管理</span></a>
                </li>
			</ul>

		</div>
	</nav>

	<div id="page-wrapper" class="gray-bg" style="background-color: #ffffff;">
		<div class="row border-bottom">
			<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void(0);"><i class="fa fa-bars"></i> </a>

				</div>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<span class="m-r-sm text-muted welcome-message">
                            欢迎使用编程浪子图书商城管理后台
                        </span>
					</li>
					<li class="hidden">
						<a class="count-info" href="javascript:void(0);">
							<i class="fa fa-bell"></i>
                            <span class="label label-primary">8</span>
						</a>
					</li>


					<li class="dropdown user_info">
						<a  class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                            <img alt="image" class="img-circle" src="/images/web/avatar.png" />
						</a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <div class="dropdown-messages-box">
                                   姓名：编程浪子郭大爷                                    <a href="/web/user/edit" class="pull-right">编辑</a>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="dropdown-messages-box">
                                   手机号码：11012345679                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="link-block text-center">
                                    <a class="pull-left" href="/web/user/reset-pwd">
                                        <i class="fa fa-lock"></i> 修改密码
                                    </a>
                                    <a class="pull-right" href="/web/user/logout">
                                        <i class="fa fa-sign-out"></i> 退出
                                    </a>
                                </div>
                            </li>
                        </ul>
					</li>

				</ul>

			</nav>
		</div>
		<div class="row  border-bottom">
	<div class="col-lg-12">
		<div class="tab_title">
			<ul class="nav nav-pills">
								<li  class="current"  >
					<a href="/web/account/index">账户列表</a>
				</li>
							</ul>
		</div>
	</div>
</div><div class="row m-t">
	<div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-b-md">
                                        <a class="btn btn-outline btn-primary pull-right" href="/web/account/set?id=12">
                        <i class="fa fa-pencil"></i>编辑
                    </a>
                                        <h2>账户信息</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 text-center">
                <img class="img-circle circle-border" src="/images/common/qrcode.jpg" width="100px" height="100px"/>
            </div>
            <div class="col-lg-10">
                <p class="m-t">姓名：编程浪子郭大爷</p>
                <p>手机：11012345679</p>
                <p>邮箱：apanly@126.com</p>
            </div>
        </div>
        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="javascript:void(0);" data-toggle="tab" aria-expanded="false">访问记录</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>访问时间</th>
                                            <th>访问Url</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                                                                                        <tr>
                                                <td>
                                                   2017-04-09 13:14:09                                                </td>
                                            <td>
												/web/account/info?id=12                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:14:06                                                </td>
                                            <td>
												/web/account/index                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:12:01                                                </td>
                                            <td>
												/web/account/info?id=13                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:11:59                                                </td>
                                            <td>
												/web/account/index                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:11:54                                                </td>
                                            <td>
												/web/charts/finance                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:11:54                                                </td>
                                            <td>
												/web/charts/dashboard                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:11:54                                                </td>
                                            <td>
												/web/dashboard/index                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-04-09 13:11:53                                                </td>
                                            <td>
												/web/                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-03-26 23:23:20                                                </td>
                                            <td>
												/web/book/images                                            </td>
                                        </tr>
                                                                                    <tr>
                                                <td>
                                                   2017-03-26 23:22:24                                                </td>
                                            <td>
												/web/book/images                                            </td>
                                        </tr>
                                                                                                                </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

	</div>
</div>
</body>
</html>
