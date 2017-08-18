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
}