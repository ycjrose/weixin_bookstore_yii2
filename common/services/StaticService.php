<?php
namespace app\common\services;

//用于加载每个页面自身的资源文件
class StaticService{
	public static function includeAppJs($path,$depend){
		self::includeApp('js',$path,$depend);
	}
	public static function includeAppCss($path,$depend){
		self::includeApp('css',$path,$depend);
	}
	protected static function  includeApp($type,$path,$depend){
		$path = $path.'?ver='.RELEASE_VERSION;
		if($type == 'css'){
			\Yii::$app->getView()->registerCssFile($path,['depends' => $depend]);
		}else{
			\Yii::$app->getView()->registerJsFile($path,['depends' => $depend]);
		}
	}
}