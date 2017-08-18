<?php
namespace app\common\services;
/**
* 所有服务的基类
*/
class BaseService{
	protected static $err_msg = '';
	protected static $err_code = '';
	public static function _err($code,$msg){
		self::$err_code = $code;
		self::$err_msg = $msg;
		return false;
	}
	public static function getErrCode(){
		return self::$err_code;
	}
	public static function getErrMsg(){
		return self::$err_msg;
	}

}