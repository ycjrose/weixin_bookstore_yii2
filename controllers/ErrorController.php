<?php
namespace app\controllers;
use yii\web\Controller;
use yii\log\FileTarget;
/**
* 错误页面控制类
*/
class ErrorController extends Controller{
	public function actionError() {
		//记录错误信息写入文件和数据库
		$error = \Yii::$app->errorHandler->exception;
		if($error){
			$file = $error->getFile();
			$line = $error->getLine();
			$message =$error->getMessage();
			$code = $error->getCode();

			$log = new FileTarget();
			$log->logFile = \Yii::$app->getRuntimePath().'/logs/err.log';

			$error_msg = $message . "[file:{$file}][line:{$line}][code:{$code}][url:{$_SERVER[REQUEST_URI]}][postdata:".http_build_query($POST)."]";
			$log->messages[] = [
				$error_msg,
				1,
				'application',
				microtime(true),
			];
			$log->export();
			//错误信息写入数据库
			//todo
		}
		$this->layout = false;
		return $this->render('error',['error_msg' => $error_msg]);
		//return '错误</br>错误信息：' . $error_msg ;
	}

	
}