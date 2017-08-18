<?php
namespace app\common\services;
//构建链接
use yii\helpers\Url;

/**
* 
*/
class UrlService {
	
	//构建web模块所有的链接
	public static function buildWebUrl($path,$params = []){
		$path = Url::toRoute(array_merge([$path],$params));
		return '/web'.$path;
	}
	//构建m模块链接
	public static function buildMUrl($path,$params = []){
		$path = Url::toRoute(array_merge([$path],$params));
		return '/m'.$path;
	}
	//构建官网链接
	public static function buildWwwUrl($path,$params = []){
		$path = Url::toRoute(array_merge([$path],$params));
		return $path;
	}
	//空链接
	public static function buildNullUrl(){
		return 'javascript:void(0);';
	}

	//站内图片链接
	public static function buildPicUrl($bucket,$image_key){
		$upload_config = \Yii::$app->params['upload'];
		return $upload_config[$bucket].'/'.$image_key;
	}
}