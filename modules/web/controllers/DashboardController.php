<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

class DashboardController extends Controller{
	 public function __construct($id, $module, $config = []){
	    parent::__construct($id, $module, $config = []);
	    $this->layout = 'main';
	}
    public function actionIndex(){
    	//仪表盘展示
        return $this->render('index');
    }
    
}
