<?php
namespace app\common\services;
//存放数据库存的数据和页面的联系
class ContactService{
	public static $status_default = -1;

	public static $status = [
		'1' => '正常',
		'0' => '已删除',
	];
	public static $sex = [
		'1' => '男',
		'2' => '女',
		'0' => '未填写',
	];
	public static $default_pwd = '&&&&&&';

	public static $pay_status = [
		1 => '已支付',
		-8 => '未支付',
		0 => '已关闭',
	];

	public static $express_status = [
		1 => '已签收',
		-6 => '已发货待签收',
		-7 => '已付款待发货',
		-8 => '未支付',
		0 => '已关闭',
	];

	public static $express_status_for_member = [
		1 => '已签收',
		-6 => '已发货待签收',
		-7 => '已付款待发货',
		-8 => '未支付',
		0 => '已关闭',
	];

	public static $default_comment = '系统默认评论';
}