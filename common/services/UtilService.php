<?php
namespace app\common\services;

use yii\helpers\Html;
//封装通用方法
class UtilService{
	//获取远程ip
	public static function getIP(){
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
	//防止xss注入
	public static function encode($display){
		return Html::encode($display);
	}
	//获取整个项目根路径
	public static function getRootPath(){
		return dirname(\Yii::$app->vendorPath);
	}
	//判断是否是在微信打开
	public static  function isWechat(){
		$ug= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
		if( stripos($ug,'micromessenger') !== false ){
			return true;
		}
		return false;
	}
}