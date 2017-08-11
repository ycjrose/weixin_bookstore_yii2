<?php

namespace app\modules\m\controllers;

use yii\web\Controller;

class DefaultController extends Controller{
	public function __construct($id, $module, $config = []){
	    parent::__construct($id, $module, $config = []);
	    $this->layout = 'main';
	}
    public function actionIndex()
    {
    	//品牌首页
        return $this->render('index');
    }
}