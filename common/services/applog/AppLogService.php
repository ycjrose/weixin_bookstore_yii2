<?php
namespace app\common\services\applog;
use app\common\services\UtilService;
use app\models\log\AppLog;

class AppLogService{
	//记录错误日志
	public static function addErrorLog($appname,$error,$content){
		$app_log = new AppLog();
		$app_log->app_name = $appname;
		$app_log->content = $content;
		//获取ip
		$app_log->ip = UtilService::getIP();
		//获取用户的操作系统
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$app_log->ua = $_SERVER['HTTP_USER_AGENT'];
		}
		if($error){
			$app_log->err_code = $error->getCode();
			if(isset($error->statusCode)){
				$app_log->http_code = $error->statusCode;
			}
			if(method_exists($error, 'getName')){
				$app_log->err_name = $error->getName();
			}
		}
		$app_log->created_time = date('Y-m-d H:i:s');
		$app_log->save(0);
	}
}