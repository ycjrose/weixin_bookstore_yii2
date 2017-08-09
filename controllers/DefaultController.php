<?php
namespace app\controllers;
use yii\web\Controller;

/**
* 默认页面控制类
*/
class DefaultController extends Controller{
	public function actionIndex(){
		$this->layout = false;
		return $this->render('index');
	}
	
}