<?php
namespace app\common\services\applog;
use app\common\services\UtilService;
use app\models\log\AppLog;
use app\models\log\AppAccessLog;

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

	public static function addAppAccessLog($uid = 0){
		$get_params = \Yii::$app->request->get();
		$post_params = \Yii::$app->request->post();
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		$target_url = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
		$ua = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';

		$access_log = new AppAccessLog();
		$access_log->uid = $uid;
		$access_log->referer_url = $referer;
		$access_log->target_url = $target_url;
		$access_log->query_params = json_encode(array_merge($get_params,$post_params));
		$access_log->ua = $ua;
		$access_log->ip = UtilService::getIP();
		$access_log->created_time = date('Y-m-d H:i:s');

		return $access_log->save(0);
	}
}