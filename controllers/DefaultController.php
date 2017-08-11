<?php
namespace app\controllers;
use app\common\components\BaseWebController;

/**
* 默认页面控制类
*/
class DefaultController extends BaseWebController{
	public function actionIndex(){
		
		return $this->render('index');
	}
	
}