<?php
namespace app\common\services;
//封装通用方法
class UtilService{
	public static function getIP(){
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
}