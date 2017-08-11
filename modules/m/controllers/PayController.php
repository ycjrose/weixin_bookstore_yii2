<?php

namespace app\modules\m\controllers;

use yii\web\Controller;


class PayController extends Controller{
	public function __construct($id, $module, $config = []){
	    parent::__construct($id, $module, $config = []);
	    $this->layout = 'main';
	}
    public function actionBuy()
    {
    	//购买支付页面
        return $this->render('buy');
    }
    
}
